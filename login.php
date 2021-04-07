<?php session_start();
include_once ('validate.php');?>
<html>
<head></head>
<body>
<?php
if($username = validate()){
    echo "<h1>Hello ".$username."</h1>
    <form action='auth.php?action=logout' method='post'>
        <button type='submit'>Logout</button>
    </form>
    
    <a href='changepwd.php'>Change password</a>
    ";

}
else{
    echo "<fieldset> 
        <legend>Login</legend> 
        <form method='post' action='auth.php?action=login'> 
            <input name='email' type='email' required='true' pattern=\"^[\w\-\/][\w\'\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$\"> 
            <input name='password' type='password' required='true'> 
            <input type='submit'> 
        </form> 
    </fieldset>";

} ?>
</body>
</html>