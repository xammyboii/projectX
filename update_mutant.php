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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Updating <?= $mutant['x_alias'] ?></title>
</head>
<body>
<?php include('header.php'); ?>

    <main>
        <form method="post" action="validation.php" id="update_mutant_form">
            <fieldset>
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