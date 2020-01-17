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

// Define app routes
    $app->get('/test', function (Request $request, Response $response, $args) {

        // $test = array('data'=>56);
        // $token = JWT::encode($test,'JWT_KEY');
        // $data  = JWT::decode($token,'JWT_KEY', array('HS256'));
        // $response->getBody()->write(json_encode($data));
        return $response;
    });
    $app->post('/register', function (Request $request, Response $response, $args) {
    $body = json_decode($request->getBody(), TRUE);
    // echo json_encode($body);
    $users = new users();
    $password = $body['password'];
    $username = $body['username'];
    if($users->checkIfExists($username)){

        $response->getBody()->write(getError('User already exists'));
        return $response;
    }
    $newUserID = $users->register($username,$password);
    $response->getBody()->write(json_encode(array(
        'success'  => true,
        'accessToken' => $newUserID
    )));
    return $response;
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
    return $response;
});

$app->get('/locations/lan/{lan:[0-9]+}/lon/{lon:[0-9]+}', function (Request $request, Response $response, $args) {
    $users = new users();
    $userData = $users->checkUserToken($request);
    $userID = $userData->id;
    $lan = $args['lan'];
    $lon = $args['lon'];
    if(is_nan($userID) || is_nan($lon) || is_nan($lan)){
        $response->getBody()->write(getError('invalid data'));
        return $response;
    }
    $wasSaved = $users->addLocations($userID,$lon,$lan);
    $response->getBody()->write(json_encode(array(
        'success' => $wasSaved
    )));
    return $response;
});
// Run app
$app->run();