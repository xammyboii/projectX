<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Home page of the CMS website.
 */
    require('connect.php');

    // display all rows from xmen table
    $query = "SELECT * FROM xmen";
    $stmt  = $db->prepare($query);
    $stmt->execute();

    // fetch and display to the page
    $mutants = $stmt->fetchAll(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="xstyles.css">
    <title>X-Men CMS: Home</title>
</head>
<body>
<?php include('header.php'); ?>
<!-- <?= $_SESSION['user_id'] ?> -->

    <main>
<?php foreach($mutants as $mutant): ?>
        <div class="mutant-box">
            <h2>
                <a href="mutant.php?x_id=<?= $mutant['x_id'] ?>">
                    <?= $mutant['x_alias'] ?>
                </a>
            </h2>
            
<?php if($mutant['x_name'] == null): ?>
            <h4>Name: (same as alias)</h4>
<?php else: ?>
            <h4>Name: <em><?= $mutant['x_name'] ?></em></h4>
<?php endif ?>
        </div>
<?php endforeach ?>
<?php include('footer.php'); ?> 
    </main>
</body>
</html>