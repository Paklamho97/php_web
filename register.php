<html>
<head></head>
<body>
<fieldset>
    <legend>Register</legend>
    <form method="post" action="auth.php?action=register">
        <input name="email" type="email" required="true" pattern="^[\w\-\/][\w\'\-\/\.]*@[\w\-]+(\.[\w\-]+)*(\.[\w]{2,6})$">
        <input name="password" type="password" required="true">
        <input name="admin" type="number">
        <input type="submit">
    </form>
</fieldset>
</body>
</html>