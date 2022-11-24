<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page that validates and/or checks any user inputs.
 */
    require('connect.php');

    // ********************************************************** LOGIN VALIDATION    
    if ($_POST && isset($_POST['login'])){
        if (empty($_POST['user_name']) && empty($_POST['user_password'])){
            $errorLogin = "Both user name & password are Required.";
        } else if (empty($_POST['user_name']) && isset($_POST['user_password'])){
            $errorLogin = "A user name is Required.";
        } else if (isset($_POST['user_name']) && empty($_POST['user_password'])){
            $errorLogin = "A password is Required.";
        } else {
            // If both exists, sanitize user inputs
            $user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $user_password  = filter_input(INPUT_POST, 'user_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Look for user info
            $qry = "SELECT * FROM user";
            $stmt = $db->prepare($qry);
            $stmt->execute();

            while ($row = $stmt->fetch()){
                if ($user_name == $row['user_name'] && $user_password == $row['user_password']){
                    $_SESSION['user_name'] = $row['user_name'];
                    $_SESSION['admin_access'] = $row['admin_access'];

                    header("Location: index.php");
                }
            }
        }
    }

    // ****************************************************** UPDATE_MUTANT VALIDATION
    if ($_POST 
        && isset($_POST['update_mutant']) 
        && isset($_POST['x_id'])
        && strlen(trim(($_POST['x_alias'])) > 0)){
            // Sanitize edited fields
            $x_alias = filter_input(INPUT_POST, 'x_alias', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $x_name = filter_input(INPUT_POST, 'x_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $x_power = filter_input(INPUT_POST, 'x_power', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $x_desc = filter_input(INPUT_POST, 'x_desc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Update query
            $qry = "UPDATE xmen SET x_alias = :x_alias, x_name = :x_name, x_power = :x_power, x_desc = :x_desc WHERE x_id = :x_id";
            $stmt = $db->prepare($qry);

            // Bind values & execute query
            $stmt->bindValue(':x_alias', $x_alias);        
            $stmt->bindValue(':x_name', $x_name);
            $stmt->bindValue(':x_power', $x_power);
            $stmt->bindValue(':x_desc', $x_desc);
            $stmt->bindValue(':x_id', $x_id, PDO::PARAM_INT);

            $stmt->execute();
            header("Location: index.php");
    } else {
        $errorUpdate = "An error occurred while processing your edit.";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error❌</title>
</head>
<body>
    <main>
        <div class="error-container">
            <h2><?= $errorLogin ?></h3>
            <a href="login.php">
                <button>Return</button>
            </a>
        </div>
        <!-- <div class="error-container">
            <h2><?= $errorUpdate ?></h3>
            <a href="index.php">
                <button>Return</button>
            </a>
        </div> -->
    </main>
</body>
</html>