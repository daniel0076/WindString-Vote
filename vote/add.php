<?php
require_once('../admin/auth/db.php');
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: ../admin/login.php");
    die();
}

global $db;
if(check()){
    $query="INSERT INTO `candidates` (`cid`, `performer`, `song`, `type`, `description`, `youtube_id`, `votes`) VALUES (NULL, :performer, :song, :type, :description, :youtube_id, '0')";
    try
    {
        $dbe=$db->prepare($query);
        $dbe->execute(array(':performer'=>$_POST['performer'] , ':song'=>$_POST['song'] , ':type'=>$_POST['type'], ':description'=>$_POST['description'] , 'youtube_id' => $_POST['youtube_id']));
    }
    catch(PDOException $e)
    {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}else die();

function check(){
    $checking=['performer','song','type','description','youtube_id'];
    foreach ($checking as $item) {
        if(!isset($_POST[$item])){
            return false;
        }
    }
    return true;
}

?>
