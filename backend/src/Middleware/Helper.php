<?php


namespace Middleware;

use Illuminate\Database\Capsule\Manager as Capsule;

class Helper
{

    public function clean($data)
    {
        return array_map(function ($item) {
            return self::post($item);
        }, $data);
    }

    public function post($input)
    {
        if (is_array($input)) {
            foreach ($input as $var => $val) {
                $output[$var] = self::post($val);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }
            $input = self::cleanInput($input);
            $output = $input;
        }
        return self::inject($output);
    }

    public function cleanInput($input)
    {

        $search = array(
            '@<script[^>]*?>.*?</script>@si',   // Javascript kodlarını temizleme
            '@<[\/\!]*?[^<>]*?>@si',            // HTML kodlarını temizleme
            '@<style[^>]*?>.*?</style>@siU',    // Stil kodlarını düzenleme
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Çoklu yorum satırlarını temizleme
        );

        $output = preg_replace($search, '', $input);
        return $output;
    }


    #### İnject Engelleme ####

    public function inject($sqlvvv)
    {
        // $sqlvvv = preg_replace( sql_regcase( "/(from|select|insert|delete|where|truncate|truncate table|drop table|show tables|#|\\*|`|&|<|>|\\\\)/" ), "", $sqlvvv );
        $sqlvvv = preg_replace("/(from|select|insert|delete|where|truncate|truncate table|drop table|show tables|#|\\*|`|&|<|>|\\\\)/", "", $sqlvvv);
        $sqlvvv = trim($sqlvvv);
        $sqlvvv = strip_tags($sqlvvv);
        $sqlvvv = addslashes($sqlvvv);
        return self::security($sqlvvv);
    }

    public function security($q)
    {
        $q = str_replace("`", "<xx>", $q);
        $q = str_replace("&", "<xx>", $q);
        $q = str_replace("%", "<xx>", $q);
        $q = str_replace("'", "<xx>", $q);
        $q = str_replace(")", "<xx>", $q);
        $q = str_replace("(", "<xx>", $q);
        $q = str_replace("<", "<xx>", $q);
        $q = str_replace(">", "<xx>", $q);
        $q = str_replace("=", "<xx>", $q);
        $q = str_replace(";", "<xx>", $q);
        $q = str_replace(":", "<xx>", $q);
        $q = trim($q);
        $q = htmlspecialchars(strip_tags(urldecode(addslashes(stripslashes(stripslashes(trim(htmlspecialchars_decode($q))))))));
        return $q;
    }


}