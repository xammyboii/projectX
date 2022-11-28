<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page that allows logged-in users to add new mutants of
 *      their choice in the website.
 */
    require('connect.php');

    // Start here. If everything's set, then what...? (refer to A3)

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