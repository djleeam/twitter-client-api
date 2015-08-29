<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * @SWG\Swagger(
 *     basePath="/api/v1",
 *     produces={"application/json"},
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Twitter Client API",
 *         description="Twitter Client API Documentation",
 *         @SWG\Contact(name="ltruong0968@gmail.com"),
 *     )
 * )
 */
abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;
}
