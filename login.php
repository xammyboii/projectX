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
            $qry = "SELECT * FROM user LIMIT 1";
            $stmt = $db->prepare($qry);
            $stmt->execute();

            while ($row = $stmt->fetch()){
                if ($user_name == $row['user_name'] && $user_password == $row['user_password']){
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['user_name'] = $row['user_name'];
                    $_SESSION['user_password'] = $row['user_password'];
                    $_SESSION['admin_access'] = $row['admin_access'];
                    $_SESSION['active'] = $row['active'];

                    header("Location: index.php");
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
    <title>X-Men CMS: Login</title>
</head>
<body>
<?php include('header.php'); ?>

    <main>
        <form action="login.php" method="post">
            <fieldset>
                <legend>Login</legend>
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
            </fieldset>
        </form>
    </main>

<?php include('footer.php'); ?> 
</body>
</html>