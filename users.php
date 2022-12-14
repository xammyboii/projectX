<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page where admins can view and edits users.
 */
    require('connect.php');

    // display all rows from user table
    $query = "SELECT * FROM user";
    $stmt  = $db->prepare($query);
    $stmt->execute();

    // fetch and display to the page
    $users = $stmt->fetchAll(); 
    
    // DEMOTE User Access
    if ($_POST && isset($_POST['demote'])){
        // Demote user by Updating their access
        $demote_qry = "UPDATE user SET admin_access = 0 WHERE user_id = :user_id";
        $demote_stmt = $db->prepare($demote_qry);
        $demote_stmt->bindValue(':user_id', $_POST['user_id-1']);
        $demote_stmt->execute();

        header("Location: users.php");
        exit;
    }

    // PROMOTE User Access
    if ($_POST && isset($_POST['promote'])){
        // Promote user by Updating their access
        $promote_qry = "UPDATE user SET admin_access = 1 WHERE user_id = :user_id";
        $promote_stmt = $db->prepare($promote_qry);
        $promote_stmt->bindValue(':user_id', $_POST['user_id-0']);
        $promote_stmt->execute();

        header("Location: users.php");
        exit;
    }

    // DELETE User
    if ($_POST && isset($_POST['delete'])){
        if (isset($_POST['user_id-0'])){
            // Delete user by their user_id
            $id = filter_input(INPUT_POST, 'user_id-0', FILTER_SANITIZE_NUMBER_INT);

            $delete_qry = "DELETE FROM user WHERE user_id = :id LIMIT 1";
            $delete_stmt = $db->prepare($delete_qry);
            $delete_stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $delete_stmt->execute();

            header("Location: users.php");
            exit;

        } elseif(isset($_POST['user_id-1'])){
            $id = filter_input(INPUT_POST, 'user_id-1', FILTER_SANITIZE_NUMBER_INT);

            $delete_qry = "DELETE FROM user WHERE user_id = :id LIMIT 1";
            $delete_stmt = $db->prepare($delete_qry);
            $delete_stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $delete_stmt->execute();

            header("Location: users.php");
            exit;
            
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
    <title>X-Men CMS: Users</title>
</head>
<body>
<?php include('header.php'); ?>

    <main>
<?php foreach($users as $user): ?>
        <form method="post" action="users.php">
            <div class="user-box">
                <label><?= $user['user_name'] ?></label>
                
<?php if($user['admin_access'] == 1): ?>
                <p>Access: Administrator</p>
                <input type="hidden" name="user_id-1" value="<?= $user['user_id'] ?>">
                <button type="submit" name="demote">Demote Access</button>
                <button type="submit" name="delete" onclick="return confirm('Do you wish to delete this user?')">Delete User</button><br>
                
<?php elseif($user['admin_access'] == 0): ?>
                <p>Access: Regular User</p>
                <input type="hidden" name="user_id-0" value="<?= $user['user_id'] ?>">
                <button type="submit" name="promote">Promote Access</button>
                <button type="submit" name="delete" onclick="return confirm('Do you wish to delete this user?')">Delete User</button><br>
        </form>
<?php endif ?>
            </div>
<?php endforeach ?>
    </main>

<?php include('footer.php'); ?> 
</body>
</html>