<?php session_start();
include_once ('validate.php')?>
<html>
<head>
    <!--using bootstrap for Asgn 1-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="./public/css/main.css"/>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
    <script src="./public/js/main.js"></script>
</head>
<body>
<div id="main">
    <div id="content">
        <div id="Navigationbar">
        </div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <?php if($u = validate()){
                $username = $u;
            }
            else{
                $username = "GUEST";
            }?>
            <a class="navbar-brand" href="#"><?php echo $username?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <?php if($u = validate()){
                            echo "<a class=\"nav-link\" href=\"auth.php?action=logout\">logout<span class=\"sr-only\">(current)</span></a>";
                            echo "<a class=\"nav-link\" href=\"login.php\">account management<span class=\"sr-only\">(current)</span></a>";
                        }
                        else{
                            echo "<a class=\"nav-link\" href=\"login.php\">login<span class=\"sr-only\">(current)</span></a>";
                         }?>

                    </li>

                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </nav>

        <nav id="nav">
            <a href="./index.php">HOME</a>
        </nav>

        <div id="categories">
            <span id="categories_title">Categories</span>
            <div id="categories_items">
                <?php
                $db = new PDO('sqlite:../cart.db');
                $q = $db->prepare("SELECT * FROM categories");
                $q->execute();
                $result = $q->fetchAll();
                foreach ($result as $cat){
                    $cat_name = $cat['name'];
                    $cat_catid = $cat['catid'];
                ?>
                    <a id="<?php echo $cat_name; ?>" href="index.php?catid=<?php echo $cat_catid; ?>&name=<?php echo $cat_name; ?>"  method="get"><?php echo htmlspecialchars($cat_name); ?></a>
                <?php
                } ?>
            </div>
        </div>

        <div id="cart">
            <img src="img/cart.png"/>
            <span id="cart_label">CART</span>
            <div id="cart_view">
                <div>Cart is empty</div>
            </div>
        </div>

            <div id="items">
                <?php
                if($_GET["catid"]) {
                    $db = new PDO('sqlite:../cart.db');
                    $q = $db->prepare("SELECT * FROM products WHERE catid = ?");
                    $q->execute(array($_GET["catid"]));
                    $result = $q->fetchAll();
                    foreach ($result as $pro) {
                        $pro_id = $pro['pid'];
                        $pro_name = $pro['name'];
                        $pro_price = $pro['price'];
                        ?>
                        <div class="card" onclick="window.open('ProductPage.php?pid=<?php echo $pro_id; ?>','_self')">
                            <img src="/img_small/<?php echo $pro_id; ?>" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title" id="item_name"><?php echo htmlspecialchars($pro_name); ?></h5>
                                <p class="card-text"><b>HK$<?php echo htmlspecialchars($pro_price); ?></b></p>
                                <a onclick="add_to_cart(<?php echo $pro_id; ?>, event)" class="btn btn-primary">Add to Cart</a>
                            </div>
                        </div>

                    <?php } ?>
                    <script>
                        let id = "#"+"<?php echo $_GET["name"]; ?>"
                        console.log(typeof id)
                        let str = document.querySelector(id).innerText
                        document.querySelector('#nav').innerHTML="<a href='/index.php'>HOME</a> /"+"<a>"+str+"</a>"
                    </script>
                <?php }
                else {
                    $db = new PDO('sqlite:../cart.db');
                    $q = $db->prepare("SELECT * FROM products");
                    $q->execute();
                    $result = $q->fetchAll();
                    foreach ($result as $pro) {
                        $pro_id = $pro['pid'];
                        $pro_name = $pro['name'];
                        $pro_price = $pro['price'];
                        ?>
                        <div class="card" onclick="window.open('ProductPage.php?pid=<?php echo $pro_id; ?>','_self')">
                            <img src="/img_small/<?php echo $pro_id; ?>" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title" id="item_name"><?php echo htmlspecialchars($pro_name); ?></h5>
                                <p class="card-text"><b>HK$<?php echo htmlspecialchars($pro_price); ?></b></p>
                                <a onclick="add_to_cart('<?php echo $pro_id; ?>', event)" class="btn btn-primary">Add to Cart</a>
                            </div>
                        </div>
                        <?php
                    } }?>

            </div>


        <footer class="footer">
            <p>He Bailin</p>
        </footer>
    </div>


</div>


</body>
</html>

