<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
// use \Firebase\JWT\JWT;

include('utils\users.php');
include('utils\extenstions.php');

require __DIR__ . '/../vendor/autoload.php';

/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
$app = AppFactory::create();

// Add Routing Middleware
$app->addRoutingMiddleware();

/**
 * Add Error Handling Middleware
 *
 * @param bool $displayErrorDetails -> Should be set to false in production
 * @param bool $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool $logErrorDetails -> Display error details in error log
 * which can be replaced by a callable of your choice.
 
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);


$app->options('/{routes:.+}', function ($request, $response, $args) {
    return enableCORS($response);
});

// Define app routes
$app->post('/login', function (Request $request, Response $response, $args) {
    $body = json_decode($request->getBody(), TRUE);
    // echo json_encode($body);
    $users = new users();
    $password = $body['password'];
    $username = $body['username'];
    $user = $users->getByUsername($username);
    // echo $user->password.' '.password_hash($password,PASSWORD_DEFAULT);
    if($user ==  null){
        $response->getBody()->write(getError('Invalid username'));
    }else if(password_verify ($password,$user->password)){
        $authToken = $users->getUserToken($user);
        
        $response->getBody()->write(json_encode(array(
            'success'  => true,
            'accessToken' => $authToken
        )));
    }else{
        $response->getBody()->write(getError('Wrong password'));
    }
    return enableCORS($response);
});
$app->post('/register', function (Request $request, Response $response, $args) {
    $body = json_decode($request->getBody(), TRUE);
    // echo json_encode($body);
    $users = new users();
    $password = $body['password'];
    $username = $body['username'];
    if($users->checkIfExists($username)){

        $response->getBody()->write(getError('User already exists'));
        return enableCORS($response);
    }
    $authToken = $users->register($username,$password);
    $response->getBody()->write(json_encode(array(
        'success'  => true,
        'accessToken' => $authToken
    )));
    return enableCORS($response);
});

// Define app routes
$app->get('/locations', function (Request $request, Response $response, $args) {
    $users = new users();
    $userData = $users->checkUserToken($request);
    // echo json_encode($userData);
    $id = $userData->id;
    if(is_numeric($id)){
        // echo $id;
        $locations = $users->getLocations($id);
        $response->getBody()->write(json_encode($locations));
    }else{
        
        $response->getBody()->write(getError('invalid id: '.$id));
    }
    return enableCORS($response);
});

$app->get('/locations/lat/{lat:[0-9]+}/lon/{lon:[0-9]+}', function (Request $request, Response $response, $args) {
    $users = new users();
    $userData = $users->checkUserToken($request);
    $userID = $userData->id;
    $lat = $args['lat'];
    $lon = $args['lon'];
    if(is_nan($userID) || is_nan($lon) || is_nan($lat)){
        $response->getBody()->write(getError('invalid data'));
        return enableCORS($response);
    }
    $wasSaved = $users->addLocations($userID,$lon,$lat);
    $response->getBody()->write(json_encode(array(
        'success' => $wasSaved
    )));
    return enableCORS($response);
});
// Run app
$app->run();