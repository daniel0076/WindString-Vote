<?php
session_start();
require('../vendor/autoload.php');
require_once('admin/auth/fb.php');

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;

FacebookSession::setDefaultApplication($app_id,$app_secret);
$redirect_url="http://guitar.nctucs.net/windstring/admin/fb_auth.php";
$helper = new FacebookRedirectLoginHelper($redirect_url);
$session = $helper->getSessionFromRedirect();

function fb_auth($session){

    if ($session) {
        // Logged in
        try {
            $user_profile = (new FacebookRequest(
                $session, 'GET', '/me'
            ))->execute()->getGraphObject(GraphUser::className());

            echo "Name: " . $user_profile->getName();
            echo "Name: " . $user_profile->getEmail();
            echo "Name: " . $user_profile->getId();
            echo "Name: " . $user_profile->getGender();

        } catch(FacebookRequestException $e) {
            echo "Exception occured, code: " . $e->getCode();
            echo " with message: " . $e->getMessage();
        }
    } else{
        $req_permissions=array('req_perms'=>'email');
        echo '<a href="' . $helper->getLoginUrl($req_permissions) . '">Login with Facebook</a>';
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
