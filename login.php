<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page that allows users to login to the website.
 */
    require('connect.php');

    $errorFlag = false;
    if ($_POST && isset($_POST['login'])){

        if (empty($_POST['user_name']) && empty($_POST['user_password'])){
            $errorMsg = "Both user name & password are Required.";
            $errorFlag = true;

        } else if (empty($_POST['user_name']) && isset($_POST['user_password'])){
            $errorMsg = "A user name is Required.";
            $errorFlag = true;

        } else if (isset($_POST['user_name']) && empty($_POST['user_password'])){
            $errorMsg = "A password is Required.";
            $errorFlag = true;

        } else {
            // If both exists, sanitize user inputs
            $user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $user_password  = filter_input(INPUT_POST, 'user_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Look for user info
            $qry = "SELECT * FROM user";
            $stmt = $db->prepare($qry);
            $stmt->execute();

            while ($row = $stmt->fetch()){
                // Verify password from its hashed form in the database
                if ($user_name == $row['user_name'] && password_verify($user_password, $row['user_password'])){
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['user_name'] = $row['user_name'];
                    $_SESSION['admin_access'] = $row['admin_access'];
                    $_SESSION['active'] = $row['active'];

                    header("Location: index.php");
                    exit();
                }
                else {
                    $errorMsg = "Login Failed. Please, try again.";
                    $errorFlag = true;
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="xstyles.css">
    <title>X-Men CMS: Login</title>
</head>
<body>
<?php include('header.php'); ?>

    <main>
        <form action="login.php" method="POST" id="form-login">
            <fieldset>
                <legend>Login</legend>
                <hr />
<?php if($errorFlag == true): ?>
                <div class="error-box"><?= $errorMsg ?></div>
<?php endif ?>
                <p>
                    <input type="text" name="user_name" placeholder="User Name"><br>
                </p>
                <p>
                    <input type="password" name="user_password" placeholder="Password"><br>
                </p>
                <button type="submit" name="login" value="Login">Login</button>
                <p>Don't have an account yet? <a href="signup.php">Sign Up</a></p>
            </fieldset>
        </form>
<?php include('footer.php'); ?> 
    </main>
</body>
</html>