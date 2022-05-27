<?php

namespace Middleware;

class Authentication
{
    public function __invoke($request, $response, $next)
    {
        $keys =[""];
        $auth = $request->getHeader('ApiKey');
        if (!in_array($auth[0], $keys)){
            $result = array(
                "message" => "No Access Permission.",
                "status" => "error",
                "statusCode" => 401
            );
            return $response->withJson($result, $result["statusCode"]);
        }else{
            $response = $next($request, $response);
            return $response;
        }

    }
}
