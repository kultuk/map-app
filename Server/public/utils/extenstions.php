<?php

function getError($message){
    return json_encode(array(
        "success" => false,
        "error"   => $message
    ));
}
function enableCORS($response){
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Content-Type', 'application/json');
}