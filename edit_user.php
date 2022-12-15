<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page that edits username and password.
 */
    require('connect.php');

    $errorFlag = false;

    $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);

    // display all rows from user table
    $query = "SELECT * FROM user WHERE user_id = :user_id";

    $stmt  = $db->prepare($query);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($_POST){
        if (empty($_POST['user_name'])){
            $errorMsg = "User name is required";
            $errorFlag = true;

        } else if (empty($_POST['password_1'])){
            $errorMsg = "Password is required";
            $errorFlag = true;

        } else if ($_POST['password_1'] != $_POST['password_2']){
            $errorMsg = "Passwords do not match";
            $errorFlag = true;

        } else {
            // Sanitize user inputs
            $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
            $user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password  = filter_input(INPUT_POST, 'password_1', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Encrypt password
            $user_password = password_hash($password, PASSWORD_DEFAULT);

            // Update user in the database
            $qry = "UPDATE user SET user_name = :user_name, user_password = :user_password WHERE user_id = :user_id";

            $stmt = $db->prepare($qry);
            $stmt->bindValue(':user_name', $user_name);
            $stmt->bindValue(':user_password', $user_password);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: users.php");
            exit();
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
    <title>X-Men CMS: Edit User</title>
</head>
<body>
<?php require('header.php') ?>

    <main>
        <form method="POST">
            <fieldset>
                <legend>Editing User</legend>
<?php if($errorFlag == true): ?>
                <div class="error-box"><?= $errorMsg ?></div>
<?php endif ?>
                <p>
                    <input type="text" name="user_name" value="<?=((isset($_POST['user_name']))?($_POST['user_name']):($user['user_name']))?>" class="signup-input" autofocus><br>
                </p>
                <p>
                    <input type="password" name="password_1" class="signup-input" placeholder="Password"><br>
                </p>
                <p>
                    <input type="password" name="password_2" class="signup-input" placeholder="Confirm Password"><br>
                </p>
                <input type="hidden" name="user_id" value="<?= $user['user_id']?>">
                <input type="submit" name="edit" value="Edit User">
            </fieldset>
        </form>
<?php  require('footer.php') ?>
    </main>
</body>
</html>