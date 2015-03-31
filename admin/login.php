<?php
require_once('auth/db.php');
session_start();
if(isset($_GET['logout'])){
    unset($_SESSION['admin']);
    unset($_SESSION['name']);
}
global $db;
if(isset($_POST['login']) && isset($_POST['password'])){
    $login=$_POST['login'];
    $pw=$_POST['password'];
    $login_query="SELECT * FROM `accounts` WHERE `login` = :login AND `password` = :password";
    try {
        $check= $db->prepare($login_query);
        $check->execute(array(':login'=> $login,':password'=> hash('sha256',$pw)));
        $result=$check->fetch(PDO::FETCH_ASSOC);
        if($result>0){
            $_SESSION['admin']=true;
            $_SESSION['name']=$result['login'];
            header("Location: ../vote/index.php");
            die();
        }

    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>登入 | 2015交大風弦盃</title>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js?ver=2.0.3"></script>
  <link rel="stylesheet" href="../static/semantic-ui/dist/semantic.css"/>
  <script src="../static/semantic-ui/dist/semantic.js"></script>
</head>
<body>
  <div class="container" id="main">
    <div class="ui large attached message"> 登入 </div>
    <div class="ui blue attached fluid message" id="response"> </div>
    <form class="ui form attached fluid segment" name="loginForm" id="loginForm" action="" method="post" >
      <div class="field">
        <label>帳號</label>
        <div class="ui left labeled icon input">
          <i class="user icon"></i>
          <input name="login" type="text"  autocomplete="off">
          <div class="ui corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div class="field">
        <label>密碼</label>
        <div class="ui left labeled icon input">
          <i class="lock icon"></i>
          <input name="password" type="password" >
          <div class="ui corner label"> <i class="icon asterisk"></i> </div>
        </div>
      </div>
      <div id="button_div">
        <input type="submit" value="Login" class="ui blue submit button" />
      </div>
    </form>
  </div>

</body>

</html>
