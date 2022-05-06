<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Routing\Controller as BaseController;


class OpenApiController extends BaseController
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="OpenApi Documentation",
     *      description="Use `X-API-KEY: YOUR_API_TOKEN` in header of request to API access
    <br><br>
    Use command `php artisan generate:token` to create a new token",
     *      @OA\Contact(
     *          email="oleksiivelychko@icloud.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="https://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST_HTTPS,
     *      description="HTTPS API Server"
     * )
     *
     * @OA\SecurityScheme(
     *      securityScheme="ApiKeyAuth",
     *      in="header",
     *      name="X-API-KEY",
     *      type="apiKey",
     * )
     *
     */
}
