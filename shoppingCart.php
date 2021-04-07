<?php
$pid = $_POST['pid'];
$db = new PDO('sqlite:../cart.db');
$q = $db->prepare("SELECT * FROM products WHERE pid = ?");
$q->execute(array($pid));
$result = $q->fetchAll();
echo json_encode($result);

?>