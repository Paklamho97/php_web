<?php
session_start();
include_once ('csrfNonce.php');
include_once ('validate.php');
if(!validate()){
    header('Location: login.php');
    exit();
}
if($_SESSION['auth']['admin']==0){
    echo 'You do not have the permission to access. <br/><a href="login.php">Back to login page.</a>';
    exit();
}
require __DIR__.'/admin/adminpanel_render.php';
$res1 = cat_fetchall();
$res2 = pro_fetchall();
$options1 = '';
$options2 = '';

foreach ($res1 as $value){
    $options1 .= '<option value="'.$value["catid"].'"> '.$value["name"].' </option>';
}
foreach ($res2 as $value){
    $options2 .= '<option value="'.$value["pid"].'"> '.$value["name"].' </option>';
}
?>
<html>
<head>
    <script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
    <link type="text/css" rel="stylesheet" href="./public/css/adminpanel.css"/>
</head>
<body>
<?php echo "<h1>".$_SESSION['auth']['em']."</h1>";?>
<form action="auth.php?action=logout" method="post">
    <button type="submit">Logout</button>
</form>
<fieldset>
    <legend>New Categories</legend>
    <form id="cat_insert" method="POST" action="admin-process.php?action=<?php echo ($action='cat_insert')?>"
          enctype="multipart/form-data">
        <label for="cat_name">Name *</label>
        <div><input id="cat_name" type="text" name="cat_name" required="true"
                    pattern="^[\w\- ]+$" /></div>
        <input type="hidden" name="token" value="<?php echo csrf_getNonce($action);?>">
        <input type="submit" value="Submit" />
    </form>
</fieldset>
<fieldset>
    <legend>Update Categories</legend>
    <form id="cat_insert" method="POST" action="admin-process.php?action=<?php echo $action='cat_update'?>"
          enctype="multipart/form-data">
        <label for="prod_catid">Category *</label>
        <div><select id="prod_catid" name="catid">
                <?php echo $options1;?>
            </select></div>
        <label for="cat_name">New Name *</label>
        <div><input id="cat_name" type="text" name="cat_name" required="true"
                    pattern="^[\w\- ]+$" /></div>
        <input type="hidden" name="token" value="<?php echo csrf_getNonce($action);?>">
        <input type="submit" value="Submit" />
    </form>
</fieldset>
<fieldset>
    <legend>Delete Categories</legend>
    <form id="cat_insert" method="POST" action="admin-process.php?action=<?php echo $action='cat_delete'?>"
          enctype="multipart/form-data">
        <label for="prod_catid">Category *</label>
        <div><select id="prod_catid" name="catid">
                <?php echo $options1;?>
            </select></div>
        <input type="hidden" name="token" value="<?php echo csrf_getNonce($action);?>">
        <input type="submit" value="Submit" />
    </form>
</fieldset>
<fieldset>
    <legend>New Product</legend>
    <form id="prod_insert" method="POST" action="admin-process.php?action=<?php echo $action='pro_insert'?>"
          enctype="multipart/form-data">
        <label for="prod_catid">Category *</label>
        <div><select id="prod_catid" name="catid">
                <?php echo $options1;?>
            </select></div>
        <label for="prod_name">Name *</label>
        <div><input id="prod_name" type="text" name="name" required="true"
                    pattern="^[\w\- ]+$" /></div>
        <label for="prod_price">Price *</label>
        <div><input id="prod_price" type="text" name="price" required="true"
                    pattern="^(0|[1-9][0-9]*)+(.[0-9]{1,2})?$" /></div>
        <label for="prod_description">Description *</label>
        <div><textarea id="prod_description" type="text" name="description" required="true"
                       pattern="^[\w\- ]+$" ></textarea></div>
        …
        <label for="prod_name">Image *</label>
        <div><input id="file0" type="file" name="file" required="true" accept="image/jpeg/png" /></div>
        <div><img src="" id="img0" ></div>
        <input type="hidden" name="token" value="<?php echo csrf_getNonce($action);?>">
        <input type="submit" value="Submit" />
    </form>
</fieldset>

<fieldset>
    <legend>Update Product</legend>
    <form id="prod_update" method="POST" action="admin-process.php?action=<?php echo $action='pro_update'?>"
          enctype="multipart/form-data">
        <label for="prod_catid">Product name *</label>
        <div><select id="prod_id" name="pid">
                <?php echo $options2;?>
            </select></div>
        <label for="prod_catid">Value to update *</label>
        <div><select id="prod_val" name="val">
                <option value="name">name</option>
                <option value="price">price</option>
                <option value="description">description</option>
            </select></div>
        <div><textarea id="prod_description" type="text" name="update_value"
                      required="true" pattern="^[\w\- ]+$" ></textarea></div>
        …
        <label for="prod_name">New Image *</label>
        <div><input id="file1" type="file" name="file" accept="image/jpeg/png" /></div>
        <div><img src="" id="img1" ></div>
        <input type="hidden" name="token" value="<?php echo csrf_getNonce($action);?>">
        <input type="submit" value="Submit" />
    </form>
</fieldset>
<fieldset>
    <legend>Delete Products</legend>
    <form id="del_pro" method="POST" action="admin-process.php?action=<?php echo $action='pro_delete'?>"
          enctype="multipart/form-data">
        <label for="prod_catid">Product name *</label>
        <div><select id="prod_id" name="pid">
                <?php echo $options2;?>
            </select></div>
        <input type="hidden" name="token" value="<?php echo csrf_getNonce($action);?>">
        <input type="submit" value="Submit" />
    </form>
</fieldset>

</body>

</html>

<script src="./public/js/adminpanel.js"></script>

