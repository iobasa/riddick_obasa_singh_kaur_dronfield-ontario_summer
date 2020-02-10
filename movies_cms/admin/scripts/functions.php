<?php

    function redirect_to($location){
        if($location !=null){
            header("Location: ".$location);
            exit;
        }
    }

    //header: http header;; http request the response is the header location; MUST exit redirect happening server-side