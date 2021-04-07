<?php
session_start();
function getdb(){
    $db = new PDO('sqlite:../cart.db');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $db;
}

function validate(){
    if(!empty($_SESSION['auth'])){
        return $_SESSION['auth']['em'];
    }
    if(!empty($_COOKIE['auth'])){
        if($t = json_decode($_COOKIE['auth'],true)){
            if(time() > $t['exp']){
                return false;
            }
            $db = getdb();
            $q = $db->prepare("SELECT salt, password, admin FROM user WHERE email = ?");
            $q->bindParam(1,$t['em']);
            if($q->execute() && ($r = $q->fetch())
                && $t['k'] == hash_hmac('sha256',
                    $t['exp'] . $r['password'], $r['salt'])) {
                $_SESSION['auth'] = $t;
                return $t['em'];  }

            return false;}
    }
    return false;
}

?>