<?php
session_start();
include_once ('csrfNonce.php');
include_once ('validate.php');
if(!validate()){
    echo json_encode(array('failed'=>'please login first'));
    exit();
}
if (empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action'])) {
    echo json_encode(array('failed'=>'undefined'));
    exit();
}
if(!csrf_verifyNonce($_REQUEST['action'], $_POST['token'])){
    echo json_encode(array('failed'=>'you have no permission'));
    exit();
}


if($_REQUEST['action'] == 'pro_insert'){
    // TODO: complete the rest of the INSERT command if needed
    if (!preg_match('/^\d*$/', $_POST['catid']))
        throw new Exception("invalid-catid");
    $_POST['catid'] = (int) $_POST['catid'];
    if (!preg_match('/^[\w\- ]+$/', $_POST['name']))
        throw new Exception("invalid-name");
    if (!preg_match('/^[\d\.]+$/', $_POST['price']))
        throw new Exception("invalid-price");
    if (!preg_match('/^[\w\- ]+$/', $_POST['description']))
        throw new Exception("invalid-textt");

    if ($_FILES["file"]["error"] == 0
        && $_FILES["file"]["size"] < 5000000) {
        if($lii=pro_insert($_POST['catid'], $_POST['name'], $_POST['price'], $_POST['description'])){
            $target=$lii;
            $filename = $_FILES["file"]["tmp_name"];
            move_uploaded_file($filename, '/var/www/html/img_small/'.$target);
            echo "insert successfully";
            exit();
        }
        echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
        exit();
    }


}

if($_REQUEST['action'] == 'pro_update'){
    if($_POST['val']=='name'){
        if (!preg_match('/^[\w\- ]+$/', $_POST['update_value'])){
            echo "invalid-name";
            throw new Exception("invalid-name");
        }
        if($target=pro_update($_POST['pid'], $_POST['update_value'], 'name')){
            if ($_FILES["file"]["error"] == 0
                && $_FILES["file"]["size"] < 5000000) {
                if($target){
                    $filename = $_FILES["file"]["tmp_name"];
                    unlink('/var/www/html/img_small/'.$target);
                    move_uploaded_file($filename, '/var/www/html/img_small/'.$target);
                    echo "update successfully";
                    exit();
                }
                exit();
            }
            echo "update successfully";
        }
        else{
            echo "update fail";
        }


    }
    if($_POST['val']=='price'){
        if (!preg_match('/^[\d\.]+$/', $_POST['update_value'])){
            echo "invalid-price";
            throw new Exception("invalid-price");
        }
        if($target=pro_update($_POST['pid'], $_POST['update_value'], 'price')){
            if ($_FILES["file"]["error"] == 0
                && $_FILES["file"]["size"] < 5000000) {
                if($target){
                    $filename = $_FILES["file"]["tmp_name"];
                    unlink('/var/www/html/img_small/'.$target);
                    move_uploaded_file($filename, '/var/www/html/img_small/'.$target);
                    echo "update successfully";
                    exit();
                }
                exit();
            }
            echo "update successfully";
        }
        else{
            echo "update fail";
        }

    }
    if($_POST['val']=='description'){
        if (!preg_match('/^[\w\- ]+$/', $_POST['update_value'])){
            echo "invalid-text";
            throw new Exception("invalid-text");
        }
        if($target=pro_update($_POST['pid'], $_POST['update_value'], 'description')){
            if ($_FILES["file"]["error"] == 0
                && $_FILES["file"]["size"] < 5000000) {
                if($target){
                    $filename = $_FILES["file"]["tmp_name"];
                    unlink('/var/www/html/img_small/'.$target);
                    move_uploaded_file($filename, '/var/www/html/img_small/'.$target);
                    echo "update successfully";
                    exit();
                }
                exit();
            }
            echo "update successfully";
        }
        else{
            echo "update fail";
        }


    }


}
if($_REQUEST['action'] == 'pro_delete'){
    if(pro_delete($_POST['pid'])){
        echo "delete successfully";
    }
}

if($_REQUEST['action'] == 'cat_insert'){
    if (!preg_match('/^[\w\- ]+$/', $_POST['cat_name'])){
        echo "invalid-name";
        throw new Exception("invalid-name");
    }
    if(cat_insert(null, $_POST['cat_name'])){
        echo "insert successfully";
    }
}

if($_REQUEST['action'] == 'cat_update'){
    if (!preg_match('/^[\w\- ]+$/', $_POST['cat_name'])){
        echo "invalid-name";
        throw new Exception("invalid-name");
    }
    if(cat_update($_POST['catid'], $_POST['cat_name'])){
        echo "update successfully";
    }
}

if($_REQUEST['action'] == 'cat_delete'){
    if(cat_delete($_POST['catid'])){
        echo "delete successfully";
    }
}

function pro_insert($catid, $name, $price, $description){
    $db = new PDO('sqlite:../cart.db');
    $db->query("PRAGMA foreign_keys = ON; ");
    $q = $db->prepare("INSERT INTO products (catid, name, price, description) VALUES (?, ?, ?, ?)");
    $q->execute(array($catid, $name, $price, $description));
    return $db->lastInsertId();
}

function pro_update($pid, $val, $action){
    $db = new PDO('sqlite:../cart.db');
    $db->query("PRAGMA foreign_keys = ON; ");

    if($action=='name'){
        $q = $db->prepare("UPDATE products SET name = ? WHERE pid = ?");
        $q->bindValue(1,$val);
        $q->bindValue(2,$pid);
        $q->execute();
        return $pid;
    }
    if($action=='price'){
        $q = $db->prepare("UPDATE products SET price = ? WHERE pid = ?");
        $q->bindValue(1,$val);
        $q->bindValue(2,$pid);
        $q->execute();
        return $pid;
    }
    if($action=='description'){
        $q = $db->prepare("UPDATE products SET description = ? WHERE pid = ?");
        $q->bindValue(1,$val);
        $q->bindValue(2,$pid);
        $q->execute();
        return $pid;
    }

}

function pro_delete($pid){
    $db = new PDO('sqlite:../cart.db');
    $db->query("PRAGMA foreign_keys = ON; ");
    $q = $db->prepare("DELETE FROM products WHERE pid = ?");
    $q->bindValue(1, $pid);
    return $q->execute();
}

function cat_insert($catid, $name){
    $db = new PDO('sqlite:../cart.db');
    $db->query("PRAGMA foreign_keys = ON; ");
    $q = $db->prepare("INSERT INTO categories (catid, name) VALUES (?, ?)");
    return $q->execute(array($catid, $name));
}

function cat_update($catid, $name){
    $db = new PDO('sqlite:../cart.db');
    $db->query("PRAGMA foreign_keys = ON; ");
    $q = $db->prepare("UPDATE categories SET name = ? WHERE catid = ?");
    $q->bindValue(1, $name);
    $q->bindValue(2, $catid);
    return $q->execute();
}

function cat_delete($catid){
    $db = new PDO('sqlite:../cart.db');
    $db->query("PRAGMA foreign_keys = ON; ");
    $q = $db->prepare("DELETE FROM categories WHERE catid = ?");
    $q->bindValue(1, $catid);
    return $q->execute();
}


?>
