<?php


class userClass
{
    function __construct()
    {
        define("MONASH_DIR", "ldap.monash.edu.au");
        define("MONASH_FILTER","o=Monash University, c=au");

    }


    public function userLoginCheck(){
        session_start();
        if(isset($_SESSION['userName']) && !empty($_SESSION['userName'])){
            echo "success login";
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
                            $_POST["pword"]);
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
        }
    }

}