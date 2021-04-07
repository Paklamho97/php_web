<?php
session_start();
function getdb(){
    $db = new PDO('sqlite:../cart.db');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $db;
}

if (empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action'])) {
    echo json_encode(array('failed'=>'undefined'));
    exit();
}

if($_REQUEST['action'] == "register"){
    $db = getdb();
    $q = $db->prepare("INSERT INTO user (email, salt, password, admin) VALUES (?, ?, ?, ?)");
    $salt = mt_rand();
    $hash = hash_hmac('sha256', $_POST['password'], $salt);
    return $q->execute(array($_POST['email'], $salt, $hash, $_POST['admin']));
}

if($_REQUEST['action'] == "login"){
    $db = getdb();
    $q = $db->prepare("SELECT salt, password, admin FROM user WHERE email = ?");
    if ($q->execute(array($_POST['email']))&&($r=$q->fetch())
        && $r['password']==hash_hmac('sha256',
            $_POST['password'], $r['salt'])){
        // if successfully authenticated
        $exp = time() + 3600 * 24 * 3; // 3days
        $token = array('em'=>$_POST['email'], 'exp'=>$exp, 'admin'=>$r['admin'], 'k'=> hash_hmac('sha256', $exp . $r['password'], $r['salt']));
        setcookie('auth', json_encode($token), $exp, null, null, true, true);
        $_SESSION['auth'] = $token;
        session_regenerate_id();
        if($r['admin']==1){
            header('Location: admin.php');
            exit();
        }
        else{
            header('Location: index.php');
            exit();
        }

    } else {
        #throw new Exception('auth-error');
        header('Content-Type: text/html; charset=utf-8');
        echo "<script>alert('email or password is incorrect')</script> <br/><a href='javascript:history.back();'>Back to login page.</a>";
        exit();
    }
}

if($_REQUEST['action'] == "logout"){
    clearSessionAndCookie();
    header("Location: login.php");
    exit();
}

if($_REQUEST['action'] == "changepwd"){
    $db = getdb();
    $q = $db->prepare("SELECT salt, password, admin FROM user WHERE email = ?");
    if ($q->execute(array($_SESSION['auth']['em']))&&($r=$q->fetch())
        && $r['password']==hash_hmac('sha256',
            $_POST['pwd'], $r['salt'])){

        $q = $db->prepare("UPDATE user SET salt = ?, password = ? WHERE email = ?");
        $salt = mt_rand();
        if($q->execute(array($salt, hash_hmac('sha256',$_POST[new_pwd],$salt), $_SESSION['auth']['em']))){
            clearSessionAndCookie();
            header("Location: login.php");
            exit();
        }
        else{
            echo "error";
            exit();
        }
    }
    else{
        echo "<script>alert('password is wrong')</script>";
        exit();
    }



}

function clearSessionAndCookie(){
    if (isset($_COOKIE['auth'])) {
        unset($_COOKIE['auth']);
        setcookie('auth', '', time() - 3600, '/'); // empty value and old timestamp
    }
    if (isset($_COOKIE['username'])) {
        unset($_COOKIE['username']);
        setcookie('username', '', time() - 3600, '/'); // empty value and old timestamp
    }
    session_destroy();
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
                $_SESSION['auth'] = $_COOKIE['auth'];
                return $t['em'];  }

            return false;}
    }
    return false;
}

?>