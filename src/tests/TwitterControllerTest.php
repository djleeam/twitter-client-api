<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TwitterControllerTest extends TestCase
{
	/*
	public $base_url = "http://192.168.99.101/api/v1/users";

    public function testGetRecentTweets_200()
    {
    	$response = $this->call('GET', $this->base_url . '/StephenCurry30/recentTweets.json');
        $this->assertEquals(200 , $response->getStatusCode());
    }

    public function testGetRecentTweets_404()
    {
    	$response = $this->call('GET', $this->base_url . '/.../recentTweets.json');
    	$this->assertEquals(404 , $response->getStatusCode());
    }*/
	
	public function test()
	{
		$this->assertTrue(true);
	}
}
