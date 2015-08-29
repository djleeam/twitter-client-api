<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TwitterAPIExchange;

class TwitterController extends Controller
{
	/**
	 * Given a twitter username, return the user's most recent tweets
	 * 
	 * @SWG\Get(
	 *     path="/users/{user_name}/recentTweets.json",
	 *     tags={"users: Operations for users"},
	 *     summary="Get recent tweets",
	 *     description="Given a twitter username, return the user's most recent tweets",
	 *     consumes={"application/json"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         name="user_name",
	 *         in="path",
	 *         type="string",
	 *         required=true,
	 *         description="twitter handle; i.e., @StephenCurry30",
	 *     ),
	 *     @SWG\Parameter(
	 *         name="count",
	 *         in="query",
	 *         type="integer",
	 *         required=false,
	 *         description="number of recent tweets to retrieve",
	 *     ),
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Success"
	 *     ),
	 *     @SWG\Response(
	 *         response=404,
	 *         description="Not found",
	 *     )
	 * )
	 */
    public function getRecentTweets(Request $request, $user_name)
    {
    	$count = $request->input('count');
    	if (is_null($count))
    	{
    		$count = 10;
    	}

    	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
    	$getfield = '?screen_name=' . $user_name . '&count=' . $count;
    	$requestMethod = 'GET';

    	$twitter = new TwitterAPIExchange($this->getOAuthSettings());

    	$result = json_decode($twitter->setGetfield($getfield)
    		->buildOauth($url, $requestMethod)
    		->performRequest());

    	if (empty($result) || isset($result->error) || isset($result->errors)) {
    		return response()->json(array("error" => "Not found."), 404);
    	}
    	else {
	    	return response()->json($result);
    	}
    }

	/**
     * Given two twitter usernames, return the intersection of the users
     * that both given users follow
	 * 
	 * @SWG\Get(
	 *     path="/users/commonFriends.json",
	 *     tags={"users: Operations for users"},
	 *     summary="Get common friends",
	 *     description="Given two twitter usernames, return the intersection of the users that both given users follow",
	 *     consumes={"application/json"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         name="user_name1",
	 *         in="query",
	 *         type="string",
	 *         required=true,
	 *         description="twitter handle; i.e., @StephenCurry30",
	 *     ),
	 *     @SWG\Parameter(
	 *         name="user_name2",
	 *         in="query",
	 *         type="string",
	 *         required=true,
	 *         description="twitter handle; i.e., @KlayThompson",
	 *     ),
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Success"
	 *     ),
	 *     @SWG\Response(
	 *         response=404,
	 *         description="Not found",
	 *     ),
	 *     @SWG\Response(
	 *         response=500,
	 *         description="no content",
	 *     )
	 * )
	 */
    public function getCommonFriends(Request $request)
    {
    	$follow1Array = $this->getFollowingIds($request->input('user_name1'));
    	$follow2Array = $this->getFollowingIds($request->input('user_name2'));
    	$result = array_intersect($follow1Array, $follow2Array);

    	return response()->json(array_values($result));
    }

    private function getFollowingIds($user_name) {
    	$twitter = new TwitterAPIExchange($this->getOAuthSettings());
    	$url = 'https://api.twitter.com/1.1/friends/ids.json';
    	$requestMethod = 'GET';

    	$getfield = '?screen_name=' . $user_name;
    	
    	$followCursor = json_decode($twitter->setGetfield($getfield)
    			->buildOauth($url, $requestMethod)
    			->performRequest());

    	if (isset($followCursor->errors)) {
    		return response()->json(array("error" => "Not found."), 404);
    	}

    	$followArray = array_merge(array(), $followCursor->ids);
    	
    	while ($followCursor->next_cursor != 0) {
    		// get next page
    		$getfield = '?screen_name=' . $user_name . '&cursor=' . $followCursor->next_cursor;
    		$followCursor = json_decode($twitter->setGetfield($getfield)
    				->buildOauth($url, $requestMethod)
    				->performRequest());

    		if (isset($followCursor->errors)) {
    			return response()->json(array("error" => "Not found."), 404);
    		}

    		$followArray = array_merge($followArray, $followCursor->ids);
    	}
    	
    	return $followArray;
    }

    private function getOAuthSettings() {
    	// TODO: externalize tokens
    	return array(
    		'oauth_access_token' => "3327513360-WUAE03Bmr5XVDwDAMvUTsrSOYbIFrCuLAwdG4M0",
    		'oauth_access_token_secret' => "oNrBlYYs1CB4RYMw9bFNkZ0gb95Pe9avA0HUM5YSx58wX",
    		'consumer_key' => "Q9899zRQbLHYq7rMXVub0lkJp",
    		'consumer_secret' => "smG0xvoS4TeNPVwYbDvBtNC3i7Q9FUjADHPc0A5WcT7nIxh8PY"
    	);
    }
}
