<?php
session_start();
require_once 'class.php';
$operation = new Operations();
if(empty($_SESSION['user_name']) && empty($_SESSION['user_email'])){
    header("location:login.php");
}else{
    $_SESSION['user_name'];
    $_SESSION['user_id'];
}
//$operation = new Operations();
if(isset($_POST['logout'])){
    $operation->Logout();
} 
if(isset($_GET['user_request_id'])){
    $result = $operation->ActionRequest($_GET['status_id'],$_GET['user_request_id'],$_SESSION['user_id'],isset($_GET['sender_id']));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Requests</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="/sagar/task3/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        .button {
         background-color: black;
         border-radius: 8px;
         border: none;
         color: white;
         padding: 10px 10px;
         text-align: center;
         text-decoration: none;
         display: inline-block;
         font-size: 20px;
         margin: 4px 2px;
         cursor: pointer;
         -webkit-transition-duration: 0.4s; /* Safari */
          transition-duration: 0.2s;
         }
    </style>
</head>
<body>
    <nav class="navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand " href="timeline.php"><?php echo $_SESSION['user_name']; ?></a>
            </div>
            <ul class=" nav navbar-nav">
                <li class="nav-item nav-link active"><a href="request.php">Request<span class="badge badge-light"></span></a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="nav-item nav-link active"><a href="add_friends.php">Add Friends</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <form class="forms" method="post">
                    <button type="submit" class="button" name="logout">Logout</button>
                </form>
            </ul>
        </div>
    </nav>
    <h3 style="color: green"><center>View Request...</center></h3>
    <div class="table">
        <table class="table table-striped text-center col-sm-6 table-hover">
            <tr>
                <th><h3><center>Name</center></h3></th>
                <th><h3><center>Photo</center></h3></th>
                <th><h3><center>Action</center></h3></th>
            </tr>
            <?php $result = $operation->ReceiveRequest($_SESSION['user_name']);
            foreach($result as $row): ?>
            <tr>
                <style>
                    img {
                    border-radius: 50%;
                    }
                </style>
                <td>
                    <h3><center><?php print ($row['user_name']); ?></center></h3>
                </td>
                <td>
                    <h3><center><img src="web_image/<?php echo $row['user_photo']; ?>" height="60" width="60"></center></h3>
                </td>
                <td>
                    <?php if($row['is_friend']==1){
                        echo '<h3 style="color:green">You are Now Friends<h3>';
                    }elseif($row['status_id']==3){
                        echo '<h3 style="color:red">You Ignored Request<h3>';
                    }
                    elseif($row['is_friend']==0 && $row['sender']==1){
                        echo '<h3 style="color:red">Request is pending!!<h3>';
                    }
                    else{
                    ?>
                    <h3>
                    <center>
                        <a href="request.php?user_request_id=<?php echo $row['user_request_id']; ?>&status_id=2&sender_id=<?php echo $row['user_id'];?>"><button class="button">Accept</button></a>
                        <a href="request.php?user_request_id=<?php echo $row['user_request_id']; ?>&status_id=3"><button class="button">Ignore</button></a>
                    </center>
                    </h3>
                    <?php } ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>