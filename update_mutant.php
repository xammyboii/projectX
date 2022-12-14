<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page editing the character bio of a specific X-Men character.
 */
    require('connect.php');

    $qry = "SELECT * FROM xmen WHERE x_id = :x_id LIMIT 1";
    $stmt = $db->prepare($qry);

    $x_id = filter_input(INPUT_GET, 'x_id', FILTER_SANITIZE_NUMBER_INT);

    $stmt->bindValue('x_id', $x_id, PDO::PARAM_INT);
    $stmt->execute();
    $mutant = $stmt->fetch();

    // ****************************************************** UPDATE_MUTANT VALIDATION
    $errorMsg = "An error occurred while processing your edit.";
    $errorFlag = false;
    
    if ($_POST 
        && isset($_POST['update_mutant']) 
        && (isset($_POST['x_name']) && !empty(strlen($_POST['x_name'])))
        && (isset($_POST['x_alias']) && !empty(strlen($_POST['x_alias'])))
        && (isset($_POST['x_power']) && !empty(strlen($_POST['x_power'])))
        && (isset($_POST['x_desc']) && !empty(strlen($_POST['x_desc'])))
        && isset($_POST['x_id'])){

            // Sanitize edited fields
            $x_alias = filter_input(INPUT_POST, 'x_alias', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $x_name  = filter_input(INPUT_POST, 'x_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $x_power = filter_input(INPUT_POST, 'x_power', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $x_desc  = filter_input(INPUT_POST, 'x_desc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $x_id    = filter_input(INPUT_POST, 'x_id', FILTER_SANITIZE_NUMBER_INT);

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
    } 
    else {
        $errorFlag = true;

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>X-Men CMS: Updating <?= $mutant['x_alias'] ?></title>
</head>
<body>
<?php include('header.php'); ?>

    <main>
        <form method="post" action="update_mutant.php" id="update_mutant_form">
            <fieldset>
<?php if($errorFlag == true): ?>
                <div class="error-box"><?= $errorMsg ?></div>
<?php endif ?>
                <legend>Editing <?= $mutant['x_alias'] ?></legend>
                <p>
                    <label>Character</label><br>
                    <input name="x_alias" value="<?= $mutant['x_alias'] ?>" autofocus>
                </p>
                <p>
                    <label>Name</label>
                    <input name="x_name" value="<?= $mutant['x_name'] ?>">
                </p>
                <p>
                    <label>Power/Ability</label>
                    <input name="x_power" value="<?= $mutant['x_power'] ?>">
                </p>
                <p>
                    <label>Description</label>
                    <textarea name="x_desc"><?= $mutant['x_desc'] ?></textarea>
                </p>
                <p>
                    <input type="hidden" name="x_id" value="<?= $mutant['x_id'] ?>">
                    <input type="submit" name="update_mutant" value="Update Mutant">
                </p>
            </fieldset>
        </form>
    </main>

<?php include('footer.php'); ?>
</body>
</html>