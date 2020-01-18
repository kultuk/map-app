<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

include('utils\users.php');
include('utils\extenstions.php');

require __DIR__ . '/../vendor/autoload.php';
$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);


$app->options('/{routes:.+}', function ($request, $response, $args) {
    return addGeneralHeaders($response);
});

$app->post('/login', function (Request $request, Response $response, $args) {
    $body = json_decode($request->getBody(), TRUE);
    
    $users = new users();
    $password = $body['password'];
    $username = $body['username'];
    $user = $users->getByUsername($username);
    
    if($user ==  null){
        $response->getBody()->write(getError('Invalid username',true));
    }else if(password_verify ($password,$user->password)){
        $authToken = $users->getUserToken($user);
        
        $response->getBody()->write(json_encode(array(
            'success'  => true,
            'accessToken' => $authToken
        )));
    }else{
        $response->getBody()->write(getError('Wrong password',true));
    }
    return addGeneralHeaders($response);
});
$app->post('/register', function (Request $request, Response $response, $args) {
    $body = json_decode($request->getBody(), TRUE);
    
    $users = new users();
    $password = $body['password'];
    $username = $body['username'];
    if($users->checkIfExists($username)){
        
        $response->getBody()->write(getError('User already exists',true));
        return addGeneralHeaders($response);
    }
    $authToken = $users->register($username,$password);
    $response->getBody()->write(json_encode(array(
        'success'  => true,
        'accessToken' => $authToken
    )));
    return addGeneralHeaders($response);
});

//get all the locations of the user
$app->get('/locations', function (Request $request, Response $response, $args) {
    $users = new users();
    $userData = $users->checkUserToken($request);
    
    $id = $userData->id;
    if($id != null && is_numeric($id) || $users->checkIfExists($userData->username)){
        $locations = $users->getLocations($id);
        $response->getBody()->write(json_encode($locations));
    }else{
        
        $response->getBody()->write(getError('invalid id: '.$id, true));
    }
    return addGeneralHeaders($response);
});

//adds a location to the user's list
$app->post('/locations/add', function (Request $request, Response $response, $args) {
    $body = json_decode($request->getBody(), TRUE);
    $users = new users();
    $userData = $users->checkUserToken($request);
    $userID = $userData->id;
    $lat = $body['lat'];
    $lng = $body['lng'];
    $userExists = $users->checkIfExists($userData->un);
    if($userID == null || $lng == null || $lat == null 
        || is_nan($userID) || is_nan($lng) || is_nan($lat) 
        || !$userExists){
        
        $response->getBody()->write(getError('invalid data',!$userExists));
        return addGeneralHeaders($response);
    }
    $wasSaved = $users->addLocations($userID,$lng,$lat);
    $response->getBody()->write(json_encode(array(
        'success' => $wasSaved
    )));
    return addGeneralHeaders($response);
});
// Run app
$app->run();