<?php

require ('./class/dbConn.php');

// place global functions here

define("MONASH_DIR", "ldap.monash.edu.au");
define("MONASH_FILTER","o=Monash University, c=au");

//startup global seesion
session_start();

//connect to the database
global $dataClass;
$dataClass = new dbConn();

function loginStatus(){
    if(isset($_SESSION['userName']) && !empty($_SESSION['userName'])){
        $LDAPresult = 1;
    }elseif(!empty($_POST["uname"]) && !empty($_POST["upassword"])){
        $LDAPconn=@ldap_connect(MONASH_DIR);
        if($LDAPconn)
        {
            $LDAPsearch=@ldap_search($LDAPconn,MONASH_FILTER, "uid=".$_POST["uname"]);
            if($LDAPsearch)
            {
                $LDAPinfo = @ldap_first_entry($LDAPconn,$LDAPsearch);
                if($LDAPinfo)
                {
                    $LDAPresult= @ldap_bind($LDAPconn, ldap_get_dn($LDAPconn, $LDAPinfo),
                        $_POST["upassword"]);
                    $_SESSION['userName'] = $_POST['uname'];
                }
                else
                {
                    $LDAPresult=0;
                }
            }
            else
            {
                $LDAPresult=0;
            }
        }
        else {
            $LDAPresult = 0;
        }
    }else{
        $LDAPresult = 0;
    }

    //Delcare it in case of the warning
    $requestFile = currentFileName()?:'index.php';

    if(!$LDAPresult && $requestFile !== 'index.php' ){
        // if login failed, we need to forward to front page
        header( 'Location: index.php' ) ;
    }
    return $LDAPresult;
}

function currentFileName(){
    $requestFile = false;
    if(!empty($_SERVER['REQUEST_URI'])){
        $uriString = explode('/', $_SERVER['REQUEST_URI']);
        $requestFile = $uriString[sizeof($uriString) - 1];
    }
    return $requestFile;
}

