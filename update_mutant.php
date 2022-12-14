<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page editing the character bio of a specific X-Men character.
 */
    require('connect.php');
    require('\xampp\htdocs\a\php-image-resize-master\lib\ImageResize.php');
    require('\xampp\htdocs\a\php-image-resize-master\lib\ImageResizeException.php');

    // ********************************************************************* GETTING THE XMEN CHARACTER
    $qry = "SELECT * FROM xmen WHERE x_id = :x_id LIMIT 1";
    $stmt = $db->prepare($qry);

    $x_id = filter_input(INPUT_GET, 'x_id', FILTER_SANITIZE_NUMBER_INT);

    $stmt->bindValue('x_id', $x_id, PDO::PARAM_INT);
    $stmt->execute();
    $mutant = $stmt->fetch();

    // ************************************************************************** Image Upload Functions
    function file_upload_path($original_filename, $upload_subfolder_name = 'upload') {
        $current_folder = dirname(__FILE__);
        $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
        
        return join(DIRECTORY_SEPARATOR, $path_segments);
    }

    function file_is_an_image($temporary_path, $new_path) {
        $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
        $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
        
        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
        $actual_mime_type        = getimagesize($temporary_path)['mime'];
        
        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
        
        return $file_extension_is_valid && $mime_type_is_valid;
    }

    $file_image_detected = isset($_FILES['file_image']) && ($_FILES['file_image']['error'] === 0);
    $file_error_detected = isset($_FILES['file_image']) && ($_FILES['file_image']['error'] > 0);

    // ********************************************************************** UPDATE_MUTANT VALIDATION
    $errorFlag = false;
    
    if ($_POST && isset($_POST['update_mutant']) && isset($_POST['x_id'])) {
        if (strlen($_POST['x_alias']) > 0) {
            $user_id = $_SESSION['user_id']; // User Id of current Admin

            // Sanitize Edited Fields
            $x_id    = filter_input(INPUT_POST, 'x_id', FILTER_SANITIZE_NUMBER_INT);
            $x_name  = filter_input(INPUT_POST, 'x_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $x_alias = filter_input(INPUT_POST, 'x_alias', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $x_desc  = filter_input(INPUT_POST, 'x_desc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $x_power = filter_input(INPUT_POST, 'x_power', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            filter_input(INPUT_POST, 'file_image',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            // WITH File Image Uploaded
            if ($file_image_detected) {
                $image_filename 	= $_FILES['file_image']['name'];
                $temp_image_path 	= $_FILES['file_image']['tmp_name'];
                $new_image_path 	= file_upload_path($image_filename);

                if (file_is_an_image($temp_image_path, $new_image_path)) {
                    $img = new \Gumlet\ImageResize($temp_image_path);
                    $img->resizeToWidth(400);
                    $img->save($temp_image_path);

                    move_uploaded_file($temp_image_path, $new_image_path);
                    $x_image = $_FILES['file_image']['name'];

                    $qry = "UPDATE xmen SET x_name = :x_name, x_alias = :x_alias, x_desc = :x_desc, x_power = :x_power,  x_image = :x_image, user_id = :user_id WHERE x_id = :x_id";
                    $stmt = $db->prepare($qry);

                    // Bind values & execute query
                    $stmt->bindValue(':x_name', $x_name);
                    $stmt->bindValue(':x_alias', $x_alias);        
                    $stmt->bindValue(':x_desc', $x_desc);
                    $stmt->bindValue(':x_power', $x_power);
                    $stmt->bindValue(':x_image', $x_image);
                    $stmt->bindValue(':user_id', $user_id);
                    $stmt->bindValue(':x_id', $x_id, PDO::PARAM_INT);
                    $stmt->execute();

                    header("Location: mutant.php?x_id={$x_id}");
                    exit();
                }
            } 
            elseif (isset($_POST['remove_image'])) { // REMOVE IMAGE Checked
                $x_id = $_POST['x_id'];
                $image_path = 'upload/' . $_POST['file_image'];

                unlink($image_path);

                // Unlinked/Removed image variable
                $x_image = '';

                $qry = "UPDATE xmen SET x_name = :x_name, x_alias = :x_alias, x_desc = :x_desc, x_power = :x_power,  x_image = :x_image, user_id = :user_id WHERE x_id = :x_id";
                $stmt = $db->prepare($qry);

                $stmt->bindValue(':x_name', $x_name);
                $stmt->bindValue(':x_alias', $x_alias);        
                $stmt->bindValue(':x_desc', $x_desc);
                $stmt->bindValue(':x_power', $x_power);
                $stmt->bindValue(':x_image', $x_image);
                $stmt->bindValue(':user_id', $user_id);
                $stmt->bindValue(':x_id', $x_id, PDO::PARAM_INT);
                $stmt->execute();

                header("Location: mutant.php?x_id={$x_id}");
                exit();
            }
            else { // WITHOUT File Image Uploaded
                $qry = "UPDATE xmen SET x_name = :x_name, x_alias = :x_alias, x_desc = :x_desc, x_power = :x_power,  user_id = :user_id WHERE x_id = :x_id";
                $stmt = $db->prepare($qry);

                $stmt->bindValue(':x_name', $x_name);
                $stmt->bindValue(':x_alias', $x_alias);        
                $stmt->bindValue(':x_desc', $x_desc);
                $stmt->bindValue(':x_power', $x_power);
                $stmt->bindValue(':user_id', $user_id);
                $stmt->bindValue(':x_id', $x_id, PDO::PARAM_INT);
                $stmt->execute();

                header("Location: mutant.php?x_id={$x_id}");
                exit();
            }

        } elseif (empty($_POST['x_alias'])) {
            $errorFlag = true;
            $errorMsg = "Character name is required.";

        } elseif ($file_error_detected) {
            $errorFlag = true;
            $errorMsg = $_FILES['file_image']['error'];
    
        }
    }

    // ************************************************************************ DELETE_MUTANT VALIDATION
    if ($_POST && isset($_POST['delete_mutant']) && isset($_POST['x_id'])) {
        $x_id    = filter_input(INPUT_POST, 'x_id', FILTER_SANITIZE_NUMBER_INT);

        $qry = "DELETE FROM xmen WHERE x_id = :x_id LIMIT 1";
        $stmt = $db->prepare($qry);
        $stmt->bindValue(':x_id', $x_id, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: index.php");
        exit();
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
        <form method="POST" enctype='multipart/form-data' action="update_mutant.php" id="update_mutant_form">
            <fieldset>
<?php if($errorFlag == true): ?>
                <div class="error-box"><?= $errorMsg ?></div>
<?php endif ?>
                <legend>Editing <?= $mutant['x_alias'] ?></legend>
                <p>
                    <label for="x_alias">Character</label><br>
                    <input name="x_alias" value="<?= $mutant['x_alias'] ?>" autofocus>
                </p>
                <p>
                    <label for="x_name">Name</label><br>
                    <input name="x_name" value="<?= $mutant['x_name'] ?>">
                </p>
                <p>
                    <label for="x_power">Power/Ability</label><br>
                    <input name="x_power" value="<?= $mutant['x_power'] ?>">
                </p>
                <p>
                    <label for="x_desc">Description</label><br>
                    <textarea name="x_desc"><?= $mutant['x_desc'] ?></textarea>
                </p>
                <p>
<?php if($mutant['x_image'] != null): ?>
                    <label for="remove_image">Remove Image? (<?= $mutant['x_image'] ?>) </label>
                    <input type="checkbox" name="remove_image">
                    <input type="hidden" name="file_image" value="<?= $mutant['x_image'] ?>">
<?php else: ?>
                    <label for="file_image">Image Upload (optional)</label><br>
                    <input type="file" name="file_image"><br>
<?php endif ?>
                </p>
                <p>
                    <input type="hidden" name="x_id" value="<?= $mutant['x_id'] ?>">
                    <input type="submit" name="update_mutant" value="Update Mutant">
                    <input type="submit" name="delete_mutant" value="Delete Mutant" onclick="return confirm('Are you sure you sure you want to delete this mutant?')">
                </p>
            </fieldset>
        </form>
<?php include('footer.php'); ?>
    </main>
</body>
</html>