<?php

namespace PublicApi\Controller;

use Middleware\Helper;
use Illuminate\Database\Capsule\Manager as Capsule;

class Customer
{
    public function create($request, $response, $args)
    {
        $post = Helper::clean($request->getParsedBody());

        if (!empty($post["full_name"])) {
            if (!filter_var($post["email"], FILTER_VALIDATE_EMAIL) and !empty($post["email"])) {
                $result = array(
                    "status" => false,
                    "statusCode" => 200,
                    "message" => "Invalid email format",
                );
            } else {
                $data = array(
                    "full_name" => $post["full_name"],
                    "email" => $post["email"],
                    "phone" => $post["phone"],
                    "address" => $post["address"],
                );
                $insert = Capsule::table("customers")->insert($data);
                if ($insert) {
                    $result = array(
                        "status" => true,
                        "statusCode" => 200,
                        "message" => "Success",
                    );
                } else {
                    $result = array(
                        "status" => false,
                        "statusCode" => 200,
                        "message" => "Error",
                    );
                }
            }
        } else {
            $result = array(
                "status" => false,
                "statusCode" => 200,
                "message" => "Can't be empty",
            );
        }
        return $response->withJson($result, $result["statusCode"]);

    }

    public function view($request, $response, $args)
    {
        $post = Helper::clean($request->getQueryParams());
        $page = intval($post["page"]);
        $per_page = intval($post["per_page"]);
        $query = Capsule::table("customers")->skip(($page - 1) * $per_page)->limit($per_page)->get();
        if ($query) {
            $total = Capsule::table("customers")->count();
            $result = array(
                "status" => true,
                "statusCode" => 200,
                "message" => "Success",
                "data" => $query,
                "total" => $total
            );
        } else {
            $result = array(
                "status" => false,
                "statusCode" => 200,
                "message" => $post,


            );
        }

        return $response->withJson($result, $result["statusCode"]);

    }


}