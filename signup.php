<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page that allows visiting users to create 
 *              an account to access the website.
 */
    require('connect.php');


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X-Men CMS: Sign Up</title>
</head>
<body>
<?php require('header.php') ?>

    <main>
        <form method="post" action="signup.php">
            <fieldset>
                <legend>Sign Up</legend>
                <p>
                    <input type="text" name="new_user_name" placeholder="User Name"><br>
                </p>
                <p>
                    <input type="password" name="new-user_password" placeholder="Password"><br>
                </p>
                <p>
                    <input type="password" name="new_user_password" placeholder="Confirm Password"><br>
                </p>
                <button type="submit" name="signup" value="Create User">Sign Up</button>
            </fieldset>
        </form>
    </main>

<?php  require('footer.php') ?>
</body>
</html>