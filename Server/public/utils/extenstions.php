<?php

function getError($message){
    return json_encode(array(
        "success" => false,
        "error"   => $message
    ));
}