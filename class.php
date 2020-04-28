<?php
require_once 'dbConfig.php';

/*Class for All the operations done in this task*/
class Operations{
    //object properties
    public $user_name;
    public $user_email;
    public $user_password;
    public $user_mobile;
    public $user_photo;
    public $user_id;
    public $conn;
    public $requested_user_id;
    public $user_content;
    public $user_comment;


    public function __construct() {
        $this->conn = new dbConfig();
    }
    
    /*E-mail Check Function*/
    public function check_email($user_email){
        $this->conn;
        $check_query = $this->conn->prepare("SELECT user_email FROM user WHERE user_email=?");
        $check_query->bindParam(1,$user_email);
        $check_query->execute();
        $num_of_rows = $check_query->fetchColumn();
        if($num_of_rows){
            return false;
        } else {
            return true;
        }
    }

    /*Insert Function*/
    public function Insert(){
        $this->conn;
        $hashedPassword = password_hash($this->user_password, PASSWORD_BCRYPT);
        $sql = $this->conn->prepare("INSERT INTO `user` (`user_name`, `user_email`, `user_password`, "
        . "`user_mobile`, `user_photo`) VALUES (?, ?, ?, ?, ?)");
        $sql->execute([$this->user_name, $this->user_email, $hashedPassword, $this->user_mobile,
        $this->user_photo]);
        $user_id = $this->user_id = $this->conn->lastInsertId();
        $_SESSION['user_id'] = $user_id;
        $user_id = $_SESSION['user_id'];
    }
    
    /*Login Function*/
    public function Login($user_email,$user_password,$user_id){
        $this->conn;
        if(!empty($user_email) && !empty($user_password)){
            $stmt = $this->conn->prepare("SELECT user_id,user_name,user_password,user_email FROM user WHERE user_email=?");
            $stmt->bindParam(1, $user_email);
            $stmt->execute();
            $database_data = $stmt->fetch();
            $_SESSION['user_email'] = $user_email;
            $_SESSION['user_name'] = $database_data['user_name'];
            $user_id= $this->user_id = $_SESSION['user_id'] = $database_data['user_id'];
            if($stmt->rowCount()==1){
                if(password_verify($this->user_password, $database_data['user_password'])){
                   header("location:timeline.php");
                }else{
                    return false; 
                }
            }else{
                return false;
            }
        }
    }
    
    /*Fetch Username From Database*/
    public function Select($user_id,$user_name) {
        $this->conn;
        $stmt = $this->conn->prepare("SELECT user.user_name,user.user_id,user.user_photo, (SELECT status_id FROM user_request WHERE user_id = ? AND requested_user_id = user.user_id LIMIT 1) AS status_id FROM user WHERE user.user_id != ? AND user_id NOT IN (SELECT requested_user_id FROM user_friend WHERE user_id =?)");
        $stmt->bindParam(1, $_SESSION['user_id']);
        $stmt->bindParam(2, $_SESSION['user_id']);
        $stmt->bindParam(3, $_SESSION['user_id']);
        $stmt->execute();
        //$stmt->debugDumpParams();
        $result = $stmt->fetchAll();
        return $result;
    }
    
    /*Logout Function*/
    public function Logout(){
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_password']);
        header("location:login.php");
    }
    
    /*Request Send Function(Inserting into user_request)*/
    public function SendRequest($user_id,$requested_user_id){
        $this->conn;
        $sql = $this->conn->prepare("INSERT INTO user_request (user_id, requested_user_id) VALUES (?,?);");   
        $sql->execute([$user_id,$requested_user_id]);
        header("location:add_friends.php");
    }
    
    /*Receive Request Function(Data Fetching From user_request Table)*/
    public function ReceiveRequest(){
        $this->conn;
//        $stmt = $this->conn->prepare("SELECT user.user_name, user.user_photo, user_request.user_id,user_request.user_request_id,user_request.requested_user_id,
//            user_request.status_id 
//            FROM user
//            INNER JOIN user_request ON user.user_id=user_request.user_id WHERE requested_user_id =?;");
        
//        $stmt = $this->conn->prepare("select ur.status_id, ur.user_request_id, ur.user_id, u.user_photo, u.user_name, IF( EXISTS (select user_friend_id from user_friend where user_id = ?), 1,0) as is_friend from user_request ur join user u on u.user_id = ur.requested_user_id  where ur.user_id = ?");
        $stmt = $this->conn->prepare("select  u.user_id, ur.status_id, ur.user_request_id, ur.user_id, u.user_photo, u.user_name, 1 as sender, IF( EXISTS(select user_friend_id from user_friend uf1 where uf1.user_id = ?  and uf1.requested_user_id = u.user_id),1,0) as is_friend from user_request ur join user u on u.user_id = ur.requested_user_id where (ur.user_id = ?) union all select  u.user_id, ur.status_id, ur.user_request_id, ur.user_id, u.user_photo, u.user_name,  0 as sender,IF( EXISTS(select user_friend_id from user_friend uf1 where uf1.user_id = ?  and uf1.requested_user_id = u.user_id),1,0) as is_friend from user_request ur join user u on u.user_id = ur.user_id where (ur.requested_user_id = ?)");
        $stmt->bindParam(1,$_SESSION['user_id']);
        $stmt->bindParam(2,$_SESSION['user_id']);
        $stmt->bindParam(3,$_SESSION['user_id']);
        $stmt->bindParam(4,$_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
    
    /*Accept or Ignore Request(Action happening in user_request Table)*/
    public function ActionRequest($status_id,$user_request_id,$user_id,$requested_user_id){
        $this->conn;
        $stmt = $this->conn->prepare("UPDATE user_request SET status_id = ? WHERE user_request_id = ?;  INSERT INTO user_friend (user_id, requested_user_id) VALUES (?,?); INSERT INTO user_friend (user_id, requested_user_id) VALUES (?,?); ");
        $stmt->bindParam(1,$status_id);
        $stmt->bindParam(2,$user_request_id);
        $stmt->bindParam(3,$_GET['sender_id']);
        $stmt->bindParam(4,$_SESSION['user_id']);
        $stmt->bindParam(5,$user_id);
        $stmt->bindParam(6,$_GET['sender_id']);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
        header("location:request.php");
    }
    
    /*Save User Content into user_content Table*/
    public function UserContent($user_id){
        $this->conn;
        $stmt = $this->conn->prepare("INSERT INTO `user_content` (`user_id`, `user_content`) VALUES (?, ?);");
        $stmt->execute([$_SESSION['user_id'], $_POST['content']]);
    }
    
    /*Fetch User Content From User Content Table*/
    public function GetUserContent(){
        $this->conn;
        $stmt = $this->conn->prepare("SELECT user_content.*, (SELECT user_content_response.content_response FROM user_content_response WHERE user_content.user_content_id = user_content_response.user_content_id AND user_content_response.user_id = ? ) AS Like_Dislike FROM `user_content` WHERE user_id = ? OR user_id IN (SELECT requested_user_id FROM user_friend WHERE user_id = ?) ORDER BY user_content DESC");   
        $stmt->bindParam(1,$_SESSION['user_id']);
        $stmt->bindParam(2,$_SESSION['user_id']);
        $stmt->bindParam(3,$_SESSION['user_id']);
        $stmt->execute();
        $data = $stmt->fetchAll();
        return $data;
    }
    
    /*Comment Function*/
    public function AddComment($user_content_id){
        $this->conn;
        $stmt = $this->conn->prepare("INSERT INTO `user_comment` (`user_id`, `user_comment`, `user_content_id`) VALUES (?, ?, ?); "
                . "SELECT user_comment,user_content_id FROM user_comment WHERE user_content_id = ?");
        $stmt->bindParam(1,$_SESSION['user_id']);
        $stmt->bindParam(2,$_POST['user_comment']);
        $stmt->bindParam(3,$_POST['user_content_id']);
        $stmt->bindParam(4,$_POST['user_content_id']);
        $stmt->execute();
    }
    
    /*Get Comment Function*/
    public function GetComment($user_content_id){
        $this->conn;
        $stmt = $this->conn->prepare("SELECT user_comment.user_comment,user_comment.user_id,user_comment.user_content_id,user_comment.created_on, user.user_name
FROM user_comment
JOIN user ON user_comment.user_id=user.user_id WHERE user_content_id =?");
        $stmt->execute([$user_content_id]);
        $data = $stmt->fetchAll();
        return $data;
    }
    
    /*For Like And Dislike*/
    public function LikeDislike($user_content_id,$content_response){
        $this->conn;
        $stmt = $this->conn->prepare("INSERT INTO `user_content_response` (`user_id`, `user_content_id`, `content_response`) VALUES (?, ?, ? ); ");
        $stmt->bindParam(1,$_SESSION['user_id']);
        $stmt->bindParam(2,$user_content_id);
        $stmt->bindParam(3,$content_response);
        $stmt->execute();
        return $stmt;
    }
    
    /*For Updating Dislike status = 2 */
    public function UpdateDislike($user_id,$content_response,$user_content_id){
        $this->conn;
        $stmt = $this->conn->prepare("UPDATE user_content_response SET content_response=? WHERE user_id = ? AND user_content_id=?");
        $stmt->bindParam(1,$content_response);
        $stmt->bindParam(2,$user_id);
        $stmt->bindParam(3,$user_content_id);
        $stmt->execute();
        return $stmt;
    }
}