<?php
use \Firebase\JWT\JWT;
include('utils\dbHandler.php');
include('utils\creds.php');

class users {
    function checkUserToken($request)
    {
        $token = $request->getHeader('auth-token')[0];
        // echo gettype($token);
        $userData = JWT::decode($token, JWT_KEY,array('HS256'));
        return $userData;
    }
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
    function checkIfExists($username){
        $user = $this->getByUsername($username);
        return $user != null; 
    }
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
    function getUser($userID){
        $users = $this->getAll();
        foreach ($users as $user) {
            if($user->id == $userID){
                return $user;
            }
        }
        return null;
    }
    function getAll()
    {
        $db = new db();
        return $db->getJsonData(); 
    }
    function save($users = null)
    {
        $db = new db();
        $db->saveJsonData($users);
    }
    function getLocations($userID){
        $user = $this->getUser($userID);
        if($user != null){
            return $user->locations;
        }
        
        return [];
    }
    function addLocations($userID,$lon,$lan){
        $users = $this->getAll();
        foreach ($users as $user) {
            if($user->id == $userID){
                // $locations = $user->locations;
                array_push($user->locations, array(
                    'lon' => $lon,
                    'lan' => $lan
                ));
                $this->save();
                return true;
            }
        }
        return false;
    }
    function findUser($userID){

    }
}