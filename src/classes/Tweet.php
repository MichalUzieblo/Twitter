<?php

class Tweet {
    private $id;
    private $userId;
    private $text;
    private $creationDate;
    
    public function __construct() {
        $this->id = -1;
        $this->userId = 0;
        $this->text = "";
        $this->creationDate = "";
    }
    
    public function setUserId(mysqli $connection, $newUserId) {        
        $sql="SELECT id FROM Users WHERE id=$newUserId"; 
        $result = $connection->query($sql);
        if($result->num_rows == 0) {
            return NULL;
        } else {
            $this->userId = $newUserId;
            return $this;
        }
    }
    
    public function setText($newText) {
        if (!empty($newText) && strlen($newText) > 0 && strlen($newText) <= 140 ) {
            $this->text = $newText;
            return $this;
        } else {
            return NULL;
        }
    }
    
    public function setCreationDate() {
        $this->creationDate = date("Y-m-d H-i-s");
    }    
    
    public function getId() {
        return $this->id;
    }
    
    public function getUserId() {
        return $this->userId;
    }
    
    public function getText() {
        return $this->text;
    }
    
    public function getCreationDate() {
        return $this->creationDate;
    }
    
    static public function loadTweetById(mysqli $connection, $id){
        $sql = "SELECT * FROM Tweet WHERE id=$id";        
        $result = $connection->query($sql);
            if($result == true && $result->num_rows == 1){
            $row = $result->fetch_assoc();            
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['userId'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creationDate'];
            return $loadedTweet;            
        }
        return null;
    }//Dołożyć sprawdzenia czy sesja istnieje
    
    static public function loadAllTweetsByUserId(mysqli $connection, $userId){
        $sql = "SELECT * FROM Tweet WHERE userId=$userId";
        $ret = [];
        $result = $connection->query($sql);
        if($result == true && $result->num_rows != 0){
            foreach($result as $row){
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['userId'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creationDate'];
                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }    
    
    static public function loadAllTweets(mysqli $connection){
        $sql = "SELECT * FROM Tweet ORDER BY creationDate DESC";
        $ret = [];
        $result = $connection->query($sql);
        if($result == true && $result->num_rows != 0){
            foreach($result as $row){
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['userId'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creationDate'];
                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }
    
    public function saveToDB(mysqli $connection){
        if($this->id == -1){
            //Saving new tweet to DB
            $sql = "INSERT INTO Tweet(userId, text, creationDate)
                    VALUES ('$this->userId', '$this->text', '$this->creationDate')";
            $result = $connection->query($sql);
                if($result == true){
                    $this->id = $connection->insert_id;
                    return true;
                }
        } else{
            $sql = "UPDATE Tweet SET userId='$this->userId',
                                    text='$this->text',
                                    creationDate='$this->creationDate'
                                    WHERE id=$this->id";
            $result = $connection->query($sql);
            if($result == true){
                return true;
            }
        }
        return false;
    }
    
    
    
    
}

// Nowa tabela Tweet

//$tableTweet = 'CREATE TABLE Tweet(
//        id int NOT NULL AUTO_INCREMENT,
//        userId int NOT NULL,
//        text varchar(140),
//        creationDate datetime,
//        PRIMARY KEY(id),
//        FOREIGN KEY(userId) REFERENCES Users(id) ON DELETE CASCADE)';