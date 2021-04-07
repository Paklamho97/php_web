<html>
<head>
    <!--using bootstrap for Asgn 1-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="./public/css/main.css"/>
    <link rel="stylesheet" href="./public/css/product.css"/>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
    <script src="./public/js/main.js"></script>
</head>
<body>
<div id="main">
    <div id="Navigationbar">
    </div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Asgn1</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="./index.php">Home <span class="sr-only">(current)</span></a>
                </li>

            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <nav id="nav">
        <?php
        $db = new PDO('sqlite:../cart.db');
        $q = $db->prepare("SELECT categories.name, categories.catid, products.name FROM products INNER JOIN categories ON categories.catid = products.catid WHERE products.pid =".$_GET['pid']);
        $q->execute();
        $result = $q->fetchAll();
        foreach ($result as $el) {
            $pro_catname = $el['0'];
            $pro_catid = $el['catid'];
            $pro_pname = $el['name'];?>
            <a href="./index.php">HOME</a> /
            <a href="./index.php?catid=<?php echo $pro_catid; ?>&name=<?php echo $pro_catname; ?>"><?php echo $pro_catname; ?></a> /
            <a><?php echo $pro_pname; ?></a>
            <?php
        }
        ?>

    </nav>


    <div id="cart">
        <img src="img/cart.png"/>
        <span id="cart_label">CART</span>
        <div id="cart_view">
            <div>Cart is empty</div>
        </div>
    </div>


    <div class="product_view">
        <?php
        $db = new PDO('sqlite:../cart.db');
        $q = $db->prepare("SELECT * FROM products WHERE pid = ?");
        $q->execute(array($_GET['pid']));
        $result = $q->fetchAll();
        foreach ($result as $p){
            $p_name = $p['name'];
            $p_price = $p['price'];
            $p_description = $p['description'];
        }

        ?>
        <img src="./img_small/<?php echo $_GET['pid']; ?>"/>
        <div id="productpage_messages">
            <span id="productpage_name"><?php echo $p_name; ?></span>
            <span id="productpage_description"><?php echo $p_description; ?></span>
            <span id="productpage_price">HK$ <?php echo $p_price; ?></span>
            <div>
                <input id="propage_quan" type="text" placeholder="1" value="1" disabled="disabled">
                <button class="opr_btn" onclick="opr_plus()">+</button><button class="opr_btn" onclick="opr_minus()">-</button>
            </div>
            <button id="submit_btn" name="submit" onclick="add_to_cart(<?php echo $_GET['pid']; ?>, event)">Add to Cart</button>
        </div>

    </div>

</div>
<footer class="footer">
    <p>Asgn_1</p>
    <p>1155152374 He Bailin</p>
</footer>

</body>
</html>
<script src="./public/js/productpage.js"></script>
