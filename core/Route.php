<?php
class Route
{
    function handelRoute($url){
        global $routes;
        unset($routes['default_controller']);

        $url = ltrim($url,'/');
        if(empty($url)){
            $url = "/";
        }

        $handelUrl = $url;
        if(!empty($routes)){
            foreach($routes as $key => $value){
                if(preg_match('~'.$key.'~is',$url)){
                    $handelUrl = preg_replace('~'.$key.'~is',$value,$handelUrl);
                }
            }

        }
        return $handelUrl;
    }
}