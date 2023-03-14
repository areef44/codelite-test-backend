<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 *  * @OA\Info(
 *      version="1.0.0",
 *      title="API Documentation Articles",
 *      description="Codelist Technical Test API Documentation Muhammad Arif",
 *      @OA\Contact(
 *          email="areef.empat.empat@gmail.com"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 * 
 */



class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
