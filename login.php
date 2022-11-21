<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page that allows users to login to the website.
 */
    require('connect.php');


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X-Men: Login</title>
</head>
<body>
<?php include('header.php'); ?>

    <form action="index.php" method="post">
        <h2>Login</h2>
<?php if(isset($_GET['error'])): ?>
        <p class="error">Oops, something went wrong..!</p>
<?php endif ?>
        <label>User Name</label>
        <input type="text" name="username" placeholder="Enter your user name"><br>
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password"><br>
        <button type="submit">Login</button>
    </form>

<?php include('footer.php'); ?> 
</body>
</html>