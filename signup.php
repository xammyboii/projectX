<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page that allows visiting users to create 
 *              an account to access the website.
 */
    require('connect.php');

    $errorFlag = false;
    if ($_POST && isset($_POST['signup'])){
        // Ensure all fields are filled in
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
            $user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password  = filter_input(INPUT_POST, 'password_1', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Encrypt password
            $user_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user to database
            $qry = "INSERT INTO user (user_name, user_password) 
                    VALUES (:user_name, :user_password)";
            $stmt = $db->prepare($qry);
            $stmt->bindValue(':user_name', $user_name);
            $stmt->bindValue(':user_password', $user_password);
            $stmt->execute();

            header("Location: login.php");
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>X-Men CMS: Sign Up</title>
</head>
<body>
<?php require('header.php') ?>

    <main>
        <form method="post" action="signup.php">
            <fieldset>
                <legend>Sign Up</legend>
<?php if($errorFlag == true): ?>
                <div class="error-box"><?= $errorMsg ?></div>
<?php endif ?>
                <p>
                    <input type="text" name="user_name" value="" class="signup-input" placeholder="User Name"><br>
                </p>
                <p>
                    <input type="password" name="password_1" class="signup-input" placeholder="Password"><br>
                </p>
                <p>
                    <input type="password" name="password_2" class="signup-input" placeholder="Confirm Password"><br>
                </p>
                <button type="submit" name="signup" value="Create User">Sign Up</button>
            </fieldset>
        </form>
    </main>

<?php  require('footer.php') ?>
</body>
</html>