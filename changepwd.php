<?php
session_start();
if(!isset($_SESSION['auth'])){
    echo "<script>alert('please login first')</script> <a href='login.php'>Back to login page.</a>";
    exit;
}
?>

<html>
<head></head>
<body>
<form action="auth.php?action=changepwd" method="post">
    <label>password</label><input type="password" name="pwd"/>
    <br/>
    <label>new password</label><input type="password" name="new_pwd"/>
    <input type="submit">
</form>
</body>
</html>
