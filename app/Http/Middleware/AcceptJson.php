<?php

namespace App\Http\Middleware;

use App\ApiCode;
use App\Http\Controllers\ApiResponse\ApiResponse;
use Closure;
use http\Client\Request;

class AcceptJson
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->header('Accept') !=="application/json"){
           $code = ApiCode::BAD_REQUEST_ERROR ;
            return $this->respondWithError($code , $code , 'Accept filed header should be application/json');
        }

        return $next($request);
    }
}
