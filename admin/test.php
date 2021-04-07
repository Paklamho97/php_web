<?php
$db = new PDO('sqlite:../cart.db');
$q = $db->prepare("SELECT categories.name, categories.catid, products.name FROM products INNER JOIN categories ON categories.catid = products.catid WHERE products.pid =".$_GET['pid']);
$q->execute();
$result = $q->fetchAll();
foreach ($result as $el) {
    $pro_catname = $el['0'];
    $pro_catid = $el['catid'];
    $pro_pname = $el['name'];?>
    <a href="./MainPage.php">HOME</a> /
    <a href="./MainPage.php?catid=<?php echo $pro_catid; ?>&name=<?php echo $pro_catname; ?>"><?php echo $pro_catname; ?></a> /
    <a><?php echo $pro_pname; ?></a>
<?php
}
?>
