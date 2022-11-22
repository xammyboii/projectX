<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page that allows users to login to the website.
 */
    require('connect.php');

    // Validation happens in validation.php file
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X-Men CMS: Login</title>
</head>
<body>
<?php include('header.php'); ?>

    <main>
        <form action="validation.php" method="post">
            <fieldset>
                <legend>Login</legend>
                <p>
                    <input type="text" name="user_name" placeholder="User Name"><br>
                </p>
                <p>
                    <input type="password" name="user_password" placeholder="Password"><br>
                </p>
                <button type="submit" name="login" value="Login">Login</button>
            </fieldset>
        </form>
    </main>

<?php include('footer.php'); ?> 
</body>
</html>