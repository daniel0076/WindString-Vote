<?php
session_start();

if(isset($_GET['cid'])){
    $cid=$_GET['cid'];
    if(!is_numeric($cid))die();
}else die();

require('vendor/autoload.php');
require_once('admin/auth/fb.php');
require_once('admin/auth/db.php');
require_once('static/xajax-0.6-beta1/xajax_core/xajax.inc.php');
date_default_timezone_set('Asia/Taipei');

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;

FacebookSession::setDefaultApplication($app_id,$app_secret);
$redirect_url="http://guitar.nctucs.net/windstring/vote.php?cid=$cid";
$helper = new FacebookRedirectLoginHelper($redirect_url);
$session = $helper->getSessionFromRedirect();
$user_profile=fb_auth($session,$helper);
vote($user_profile);

function vote($user_profile){
    global $db;
    if($user_profile){
        $query="SELECT * FROM `voters` WHERE `fb_id` = :fb_id";
        try{
            $dbe=$db->prepare($query);
            $query_array=array(':fb_id'=>$user_profile->getId());
            $dbe->execute($query_array);
            $result=$dbe->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            echo "DB Error".$e;
            die();
        }

        if($result){
            echo "<script>alert('你已經投過了ㄛQQ');location.href='/windstring/'</script>";
            die();
        }else{
            $cid=$_GET['cid'];
            $ip=get_ip();
            $_date=date('Y-m-d H:i:s');
            $query="INSERT INTO `voters` (`vid`, `name`, `gender`, `email`, `fb_id`, `cid`, `from_ip`, `date`) VALUES (NULL, :name, :gender, :email, :fb_id, :cid, :from_ip, :_date)";
            try{
                $dbe=$db->prepare($query);
                $query_array=array(':name'=>$user_profile->getName(),':gender'=>$user_profile->getGender(),':email'=>$user_profile->getEmail(),':fb_id'=>$user_profile->getId(),':cid'=>$cid,':from_ip'=>$ip,':_date'=>$_date);
                $dbe->execute($query_array);
            }
            catch(PDOException $e){
                echo "<script>alert('資料庫錯誤，請再試一次');location.href='/windstring/'</script>";
                die();
            }
            echo "<script>alert('感謝你的投票');location.href='/windstring/'</script>";
        }

    }else die();
}
function get_ip(){
    if(getenv('HTTP_X_FORWARDED_FOR')){
        $ip=getenv('HTTP_X_FORWARDED_FOR');
    }else if(getenv('REMOTE_ADDR')){
        $ip=getenv('REMOTE_ADDR');
    }else $ip="UNKNOWN";

    return $ip;
}
function fb_auth($session,$helper){

    if ($session) {
        // Logged in
        try {
            $user_profile = (new FacebookRequest(
                $session, 'GET', '/me'
            ))->execute()->getGraphObject(GraphUser::className());
            return $user_profile;

        } catch(FacebookRequestException $e) {
            echo "Exception occured, code: " . $e->getCode();
            echo " with message: " . $e->getMessage();
        }
        return true;
    } else{
        $req_permissions=array('req_perms'=>'email');
        $fb_auth_url=$helper->getLoginUrl($req_permissions);
        header("Location: $fb_auth_url");
        try {
            $session = $helper->getSessionFromRedirect();
        } catch(FacebookRequestException $ex) {
            // When Facebook returns an error
            echo "authencaton error";
        } catch(\Exception $ex) {
            echo "authencaton error";
            // When validation fails or other local issues
        }
    }
}
?>
