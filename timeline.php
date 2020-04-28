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
if(isset($_POST['logout'])){
    $operation->Logout();
}
if(isset($_POST['addcomment'])){
    $operation->user_comment = isset($_POST['user_comment']);
    $user_content_id = $_POST['user_content_id'];
    $operation->AddComment($user_content_id);
}
if(isset($_GET['action']) && $_GET['action']== 'add'){
    $operation->LikeDislike($_GET['user_content_id'],$_GET['content_response']);
}
if(isset($_GET['action']) && $_GET['action']== 'update'){
    $stmt = $operation->UpdateDislike($_SESSION['user_id'],$_GET['content_response'],$_GET['user_content_id']); 
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css.css">
        <title>Timeline</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/sagar/task3/froala_editor_3.0.6/css/froala_editor.css">
        <link rel="stylesheet" href="/sagar/task3/froala_editor_3.0.6/css/froala_style.css">
        <link rel="stylesheet" href="/sagar/task3/froala_editor_3.0.6/css/plugins/code_view.css">
        <link rel="stylesheet" href="/sagar/task3/froala_editor_3.0.6/css/plugins/image_manager.css">
        <link rel="stylesheet" href="/sagar/task3/froala_editor_3.0.6/css/plugins/image.css">
        <link rel="stylesheet" href="/sagar/task3/froala_editor_3.0.6/css/plugins/table.css">
        <link rel="stylesheet" href="/sagar/task3/froala_editor_3.0.6/css/plugins/video.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">
        <link href="/sagar/task3/froala_editor_3.0.6/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/froala_editor.pkgd.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/froala-editor@3.0.6/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@3.0.6/js/froala_editor.pkgd.min.js"></script>
        <link href="/sagar/task3/froala_editor_3.0.6/css/froala_style.min.css" rel="stylesheet" type="text/css" />
        <style>
          body {
            text-align: center;
          }

          div#editor {
            width: 81%;
            margin: auto;
            text-align: left;
          }
          .comment-class .form{display:none;}
          input[type=text], select {
                width: 60%;
                padding: 12px 20px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
}
        hr.line {
            border-top: 1px dashed black;
        }
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
         ::placeholder {
        color: red;
        opacity: 0.8;
         }
        </style>
    </head>
    <body style="background-color:#ffffff;">
        <nav class="navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="timeline.php"><?php echo $_SESSION['user_name'];?></a>
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
        </nav> <br>
        <div>
            
        </div>
        <div id="editor">
            <h3><p class="text-primary">Post Your Ideas here...</p></h3>
            <form method="POST" enctype="multipart/formdata">
                <textarea id='edit' name="content" style="margin-top: 30px;" placeholder="Type text">
                </textarea><br>
                <input type="submit" class="btn btn-primary" name="submit" id="submit" class="form-submit" value="Post">
            </form>
            <div class="fr-view">
                <?php
                if(isset($_POST['submit'])){
                    $operation->UserContent($_SESSION['user_id']);
                    $user_content = $operation->user_content = $_POST['content'];
                ?>
            </div><br>
        </div>
        <?php } ?>
        <?php
            $data = $operation->GetUserContent();
//            print_r($data);
            foreach($data as $row): $user_content_id = $row['user_content_id'];
        ?>
        <center>
        <form method="post">
            <div class="jumbotron" style="width: 90rem; background-color:#CFD8DC; border-radius: 25px;">
                <h2><?php echo $row['user_content'];?></h2>
                <p>
                    <?php 
                    if(isset($row['Like_Dislike']) && $row['Like_Dislike']==1){
                        echo '<i class="fa fa-thumbs-up" style="font-size:35px;color:green"></i>'; 
                    ?>
                    <a href="timeline.php?action=update&content_response=2&user_content_id=<?php echo $row['user_content_id'];?>" class="button">Dislike
                        <span class="glyphicon glyphicon-thumbs-down"></span>
                        </a>
                    <?php }elseif(isset($row['Like_Dislike']) && $row['Like_Dislike']==2){
                        echo '<i class="fa fa-thumbs-down" style="font-size:35px;color:red"></i>';
                    ?>
                    <a href="timeline.php?action=update&content_response=1&user_content_id=<?php echo $row['user_content_id']; ?> " class="button">Like
                        <span class="glyphicon glyphicon-thumbs-up"></span>
                        </a>
                    <?php
                    }else{ 
                    ?>
                     <a href="timeline.php?action=add&content_response=1&user_content_id=<?php echo $row['user_content_id']; ?>" class="button">Like
                     <span class="glyphicon glyphicon-thumbs-up"></span>
                     </a>
                    <a href="timeline.php?action=add&content_response=2&user_content_id=<?php echo $row['user_content_id'];?>" class="button">Dislike
                        <span class="glyphicon glyphicon-thumbs-down"></span>
                        </a>
                    <?php } ?>
                    <div class="comment-class">
                        <div class="form1">
                            <input type="text" name="user_comment" placeholder="Add Comment" id="user_comment">
                            <button type="submit" class="btn btn-primary" name="addcomment">Add Comment</button>
                            <input type="hidden" name="user_content_id" value="<?php echo $user_content_id;?>">
                        </div>
                            <?php 
                                $data = $operation->GetComment($user_content_id);
                                foreach($data as $comment){
                                    ?>
                                    <div class="alert" role="alert" ><?php echo $comment['user_name']; echo $comment['created_on']; ?>
                                    </div>
                                    <?php
                                        echo '<h3>' .$comment['user_comment']. '</h3>';
                                    ?>
                                    <hr class="line">
                                <?php
                                }
                            ?>
                    </div>
                    </div>
                </p>
            </div>
        </form>
        </center>
        <?php endforeach; ?>
        <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js">
        </script>
        <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/froala_editor.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/plugins/align.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/plugins/code_beautifier.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/plugins/code_view.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/plugins/draggable.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/plugins/image.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/plugins/image_manager.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/plugins/link.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/plugins/lists.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/plugins/paragraph_format.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/plugins/paragraph_style.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/plugins/table.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/plugins/video.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/plugins/url.min.js"></script>
        <script type="text/javascript" src="/sagar/task3/froala_editor_3.0.6/js/plugins/entities.min.js"></script>
        <script>
            (function () {
              const editorInstance = new FroalaEditor('#edit', {
                enter: FroalaEditor.ENTER_P,
                placeholderText: null,
                events: {
                  initialized: function () {
                    const editor = this;
                    this.el.closest('form').addEventListener('submit', function (e) {
                      console.log(editor.$oel.val());
                    });
                  }
                }
              });
            })();
        </script>
    </body>
</html>