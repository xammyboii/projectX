<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page that allows logged-in users to add new mutants of
 *      their choice in the website.
 */
    require('connect.php');

    $errorFlag = false;
    if ($_POST && isset($_POST['new_mutant'])){
        if (empty($_POST['x_alias'])){
            $errorMsg = "Character name is required.";
            $errorFlag = true;
        }
        else {
            // Take the user_id from the logged in user
            $user_id = $_SESSION['user_id'];

            // Sanitize edited fields
            $x_alias = filter_input(INPUT_POST, 'x_alias', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $x_name  = filter_input(INPUT_POST, 'x_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $x_power = filter_input(INPUT_POST, 'x_power', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $x_desc  = filter_input(INPUT_POST, 'x_desc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Insert query
            $qry = "INSERT INTO xmen (x_name, x_alias, x_desc, x_power, user_id) VALUES (:x_name, :x_alias, :x_desc, :x_power, :user_id)";
            $stmt = $db->prepare($qry);
            
            // Bind values & execute query
            $stmt->bindValue(':x_alias', $x_alias);        
            $stmt->bindValue(':x_name', $x_name);
            $stmt->bindValue(':x_power', $x_power);
            $stmt->bindValue(':x_desc', $x_desc);
            $stmt->bindValue(':user_id', $user_id);
            $result = $stmt->execute();

            header("Location: index.php");
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>X-Men CMS: New</title>
</head>
<body>
<?php require('header.php') ?>

    <main>
        <form method="post" action="new_mutant.php" id="new_mutant_form">
            <fieldset>
<?php if($errorFlag == true): ?>
                <div class="error-box"><?= $errorMsg ?></div>
<?php endif ?>
                <legend>New Mutant</legend>
                <p>
                    <label for="x_alias">Character</label><br>
                    <input name="x_alias" id="character" autofocus>
                </p>
                <p>
                    <label for="x_name">Name</label><br>
                    <input name="x_name" id="name">
                </p>
                <p>
                    <label for="x_power">Power/Ability</label><br>
                    <input name="x_power" id="power">
                </p>
                <p>
                    <label for="x_desc">Description</label><br>
                    <textarea name="x_desc" id="text_desc"></textarea>
                </p>
                <p>
                    <input type="submit" name="new_mutant" id="add_new_mutant" value="Add New Mutant">
                </p>
            </fieldset>
        </form>
    </main>

<?php require('footer.php') ?>
</body>
</html>