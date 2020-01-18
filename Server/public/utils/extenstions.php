<?php

//setting a default error message object
function getError($message, $invalidToken){
    return json_encode(array(
        "success" => false,
        "invalidToken" => !!$invalidToken,
        "error"   => $message
    ));
}

//setting all the relavent header - for CORS and json body response
function addGeneralHeaders($response){
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'auth-token')
            ->withHeader('Content-Type', 'application/json');
}