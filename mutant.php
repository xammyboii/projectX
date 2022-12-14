<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page showing the full character bio of a specific X-Men character.
 */
    require('connect.php');

    // Prepare query selecting the chosen character from xmen table
    $qry = "SELECT * FROM xmen WHERE x_id = :x_id LIMIT 1"; 
    $stmt = $db->prepare($qry);

    // Sanitize $_GET['x_id']
    $x_id = filter_input(INPUT_GET, 'x_id', FILTER_SANITIZE_NUMBER_INT);

    // Bind the parameter to the query and execute
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
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>X-Men CMS: <?= $mutant['x_alias'] ?></title>
</head>
<body>
<?php include('header.php'); ?>

    <main>
        <div class="mutant-bio">
            <h2>X-Men: <?= $mutant['x_alias'] ?></h2>
<?php if($mutant['x_image'] != null): ?>
            <img src="upload/<?= $mutant['x_image'] ?>" alt="mutant" class="mutant-image"/>
    <?php if($mutant['x_name'] == null): ?>
            <h4>Real Name: (same as alias)</h4>
    <?php else: ?>
            <h4>Real Name: <em><?= $mutant['x_name'] ?></em></h4>
    <?php endif ?>
            <p>Power: <?= $mutant['x_power'] ?></p>
            <p>Description: <?= $mutant['x_desc'] ?></p>

<?php else: ?>
    <?php if($mutant['x_name'] == null): ?>
            <h4>Real Name: (same as alias)</h4>
    <?php else: ?>
            <h4>Real Name: <em><?= $mutant['x_name'] ?></em></h4>
    <?php endif ?>
            <p>Power: <?= $mutant['x_power'] ?></p>
            <p>Description: <?= $mutant['x_desc'] ?></p>
            
<?php endif ?>
<?php if(isset($_SESSION['user_name']) && $_SESSION['admin_access'] == 1): ?>
            <a href="update_mutant.php?x_id=<?= $mutant['x_id'] ?>">
                <button>Edit Bio</button>
            </a>
<?php endif ?>
        </div>
<?php include('footer.php'); ?> 
    </main>
</body>
</html>