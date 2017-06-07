<?php

class Comment {
    private $id;
    private $userId;
    private $postId;
    private $text;
    private $creationDate;
    
    public function __construct() {
        $this->id = -1;
        $this->userId = 0;
        $this->postId = 0;
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
    
    public function setPostId(mysqli $connection, $newPostId) {        
        $sql="SELECT id FROM Tweet WHERE id=$newPostId"; 
        $result = $connection->query($sql);
        if($result->num_rows == 0) {
            return NULL;
        } else {
            $this->postId = $newPostId;
            return $this;
        }
    }
    
    public function setText($newText) {
        if (!empty($newText) && strlen($newText) > 0 && strlen($newText) <= 60) {
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
    
    public function getPostId() {
        return $this->postId;
    }
    
    public function getText() {
        return $this->text;
    }
    
    public function getCreationDate() {
        return $this->creationDate;
    }
    
    static public function loadCommentById(mysqli $connection, $id){
        $sql = "SELECT * FROM Comment WHERE id=$id";        
        $result = $connection->query($sql);
            if($result == true && $result->num_rows == 1){
            $row = $result->fetch_assoc();            
            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->userId = $row['userId'];
            $loadedComment->postId = $row['postId'];
            $loadedComment->text = $row['text'];
            $loadedComment->creationDate = $row['creationDate'];
            return $loadedComment;            
        }
        return null;
    }
    
    static public function loadAllCommentByPostId(mysqli $connection, $postId){
        $sql = "SELECT * FROM Comment WHERE postId=$postId";
        $ret = [];
        $result = $connection->query($sql);
        if($result == true && $result->num_rows != 0){
            foreach($result as $row){
                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['userId'];
                $loadedComment->postId = $row['postId'];
                $loadedComment->text = $row['text'];
                $loadedComment->creationDate = $row['creationDate'];
                $ret[] = $loadedComment;
            }
        }
        return $ret;
    }    
    
    public function saveToDB(mysqli $connection){
        if($this->id == -1){
            //Saving new tweet to DB
            $sql = "INSERT INTO Comment(userId, postId, text, creationDate)
                    VALUES ('$this->userId', '$this->postId', '$this->text', '$this->creationDate')";
            $result = $connection->query($sql);
                if($result == true){
                    $this->id = $connection->insert_id;
                    return true;
                }
        } else{
            $sql = "UPDATE Comment SET userId='$this->userId',
                                    postId='$this->postId',
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


//$tableComment = 'CREATE TABLE Comment(
//        id int NOT NULL AUTO_INCREMENT,
//        userId int NOT NULL,
//        postId int NOT NULL,
//        text varchar(60),
//        creationDate datetime,
//        PRIMARY KEY(id),
//        FOREIGN KEY(userId) REFERENCES Users(id),
//        FOREIGN KEY(postId) REFERENCES Tweet(id)
//        )';

