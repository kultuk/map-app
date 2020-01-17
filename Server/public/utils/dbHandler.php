<?php
 
const DB_FILE_LOCATION = 'utils/db.json';
class db {
   private static $data;
    function getJsonData(){
        self::$data = json_decode($this->getFileData());
        return self::$data;
    }
    function saveJsonData($db){
        $this->setFileData(json_encode($db ?? self::$data));
    }

    function getFileData() {
        return file_get_contents(DB_FILE_LOCATION);
    }
    
    function setFileData($content) {
        return file_put_contents(DB_FILE_LOCATION,$content);
    }

    // function addFileData($content) {
    //     $currentData = $this->getFileData();
    //     return file_put_contents(DB_FILE_LOCATION,$currentData.$content);
    // }
}