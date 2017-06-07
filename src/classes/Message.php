<?php

class Message {
    private $id;
    private $messageReceiverId;
    private $messageSenderId;
    private $text;
    private $creationDate;
    private $isRead;
    
    public function __construct() {
        $this->id = -1;
        $this->messageReceiverId = 0;
        $this->messageSenderId = 0;
        $this->text = "";
        $this->creationDate = "";
        $this->isRead = 0;
    }
    
    public function setMessageReceiverId(mysqli $connection, $messageReceiverId) {        
        $sql="SELECT id FROM Users WHERE id=$messageReceiverId"; 
        $result = $connection->query($sql);
        if($result->num_rows == 0) {
            return NULL;
        } else {
            $this->messageReceiverId = $messageReceiverId;
            return $this;
        }
    }
    
    public function setMessageSenderId(mysqli $connection, $messageSenderId) {        
        $sql="SELECT id FROM Users WHERE id=$messageSenderId"; 
        $result = $connection->query($sql);
        if($result->num_rows == 0) {
            return NULL;
        } else {
            $this->messageSenderId = $messageSenderId;
            return $this;
        }
    }
    
    public function setText($newText) {
        if (!empty($newText) && strlen($newText) > 0) {
            $this->text = $newText;
            return $this;
        } else {
            return NULL;
        }
    }
    
    public function setCreationDate() {
        $this->creationDate = date("Y-m-d H-i-s");
    }    
    
    public function setIsRead(mysqli $connection, $isRead) {        
        if($isRead == 0 || $isRead == 1) {
            $this->isRead = $isRead;
            return $this;
        } else {
            
            return NULL;
        }
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getMessageReceiverId() {
        return $this->messageReceiverId;
    }
    
    public function getMessageSenderId() {
        return $this->messageSenderId;
    }
    
    public function getText() {
        return $this->text;
    }
    
    public function getCreationDate() {
        return $this->creationDate;
    }
    
    public function getIsRead() {
        return $this->isRead;
    }
    
    static public function loadMessageById(mysqli $connection, $id){
        $sql = "SELECT * FROM Message WHERE id=$id";        
        $result = $connection->query($sql);
            if($result == true && $result->num_rows == 1){
            $row = $result->fetch_assoc();            
            $loadedMessage = new Message();
            $loadedMessage->id = $row['id'];
            $loadedMessage->messageReceiverId = $row['messageReceiverId'];
            $loadedMessage->messageSenderId = $row['messageSenderId'];
            $loadedMessage->text = $row['text'];
            $loadedMessage->creationDate = $row['creationDate'];
            $loadedMessage->isRead = $row['isRead'];
            return $loadedMessage;            
        }
        return null;
    }
    
    static public function loadAllSendMessageByUserId(mysqli $connection, $messageSenderId){
        $sql = "SELECT * FROM Message WHERE messageSenderId=$messageSenderId";
        $ret = [];
        $result = $connection->query($sql);
        if($result == true && $result->num_rows != 0){
            foreach($result as $row){
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->messageReceiverId = $row['messageReceiverId'];
                $loadedMessage->messageSenderId = $row['messageSenderId'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->creationDate = $row['creationDate'];
                $loadedMessage->isRead = $row['isRead'];
                
                $ret[] = $loadedMessage;
            }
        }
        return $ret;
    }    
    
    static public function loadAllReceiveMessageByUserId(mysqli $connection, $messageReceiverId){
        $sql = "SELECT * FROM Message WHERE messageReceiverId=$messageReceiverId";
        $ret = [];
        $result = $connection->query($sql);
        if($result == true && $result->num_rows != 0){
            foreach($result as $row){
                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->messageReceiverId = $row['messageReceiverId'];
                $loadedMessage->messageSenderId = $row['messageSenderId'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->creationDate = $row['creationDate'];
                $loadedMessage->isRead = $row['isRead'];
                
                $ret[] = $loadedMessage;
            }
        }
        return $ret;
    } 
    
    public function saveToDB(mysqli $connection){
        if($this->id == -1){
            //Saving new message to DB
            $sql = "INSERT INTO Message(messageReceiverId, messageSenderId, text, creationDate, isRead)
                    VALUES ('$this->messageReceiverId', '$this->messageSenderId', '$this->text', '$this->creationDate', $this->isRead)";
            $result = $connection->query($sql);
                if($result == true){
                    $this->id = $connection->insert_id;
                    return true;
                }
        } else{
            $sql = "UPDATE Message SET messageReceiverId='$this->messageReceiverId',
                                    messageSenderId='$this->messageSenderId',
                                    text='$this->text',
                                    creationDate='$this->creationDate',
                                    isRead=$this->isRead
                                    WHERE id=$this->id";
            $result = $connection->query($sql);
            if($result == true){
                return true;
            } else {
                $connection->error;
            }
        }
       return $connection->error;
//        return false;
    }
}


//$tableMessage = 'CREATE TABLE Message(
//        id int NOT NULL AUTO_INCREMENT,
//        messageReceiverId int NOT NULL,
//        messageSenderId int NOT NULL,
//        text varchar(140),
//        creationDate datetime,
//        isRead tinyint default 0,
//        PRIMARY KEY(id),
//        FOREIGN KEY(messageReceiverId) REFERENCES Users(id),
//        FOREIGN KEY(messageSenderId) REFERENCES Users(id)
//        )';

