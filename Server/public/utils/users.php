<?php
use \Firebase\JWT\JWT;
include('utils\dbHandler.php');
include('utils\creds.php');

class users {

    //checks if the auth token recieved is valid
    function checkUserToken($request)
    {
        $token = $request->getHeader('auth-token')[0];
        
        try{
            $userData = JWT::decode($token, JWT_KEY,array('HS256'));
            return $userData;
        }catch(\Exception $e){
            return array();
        }
    }
    //creating a jwt for the user
    function getUserToken($user)
    {
        $user = (array) $user;
        $creds = array(
            'id'=> $user['id'] ,
            'un'=> $user['username']
        );
        echo $user->id;
        return JWT::encode($creds, JWT_KEY);
    }
    //adding a new user
    function register($username,$password){
        $users = $this->getAll();
        $newUserID = count($users) +1;
        $newUser = array(
            'id' => $newUserID, 
            'username' => $username,
            'password' => password_hash($password,PASSWORD_DEFAULT),
            'locations'=> array(),
        );
        $authToken = $this->getUserToken($newUser);
        array_push($users,$newUser);
        $this->save($users);
        
        return $authToken;
    }
    //search for a user with the same username
    function checkIfExists($username){
        $user = $this->getByUsername($username);
        return $user != null; 
    }

    //returns the user with the same username
    function getByUsername($username)
    {
        $users = $this->getAll();
        foreach ($users as $user) {
            if($user->username == $username){
                return $user;
            }
        }
        return null;
    }

    //returns the user with the same id
    function getUser($userID){
        $users = $this->getAll();
        foreach ($users as $user) {
            if($user->id == $userID){
                return $user;
            }
        }
        return null;
    }

    //returs all the users form the db
    function getAll()
    {
        $db = new db();
        return $db->getJsonData(); 
    }

    //updates the db with the current list of users
    function save($users = null)
    {
        $db = new db();
        $db->saveJsonData($users);
    }

    //returns the locations list of the given user
    function getLocations($userID){
        $user = $this->getUser($userID);
        if($user != null){
            return $user->locations;
        }
        
        return [];
    }

    //adds a locations for the user
    function addLocations($userID,$lng,$lat){
        $users = $this->getAll();
        foreach ($users as $user) {
            if($user->id == $userID){
                // $locations = $user->locations;
                array_push($user->locations, array(
                    'lng' => $lng,
                    'lat' => $lat
                ));
                $this->save();
                return true;
            }
        }
        return false;
    }
}