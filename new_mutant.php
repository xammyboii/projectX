<?php 
/*
 * Name: Xaviery Abados
 * WEBD-2008 CMS Project
 * Description: Page that allows admin to add new mutants of their choice in the website.
 */
    require('connect.php');
    require('\xampp\htdocs\a\php-image-resize-master\lib\ImageResize.php');
    require('\xampp\htdocs\a\php-image-resize-master\lib\ImageResizeException.php');

    if (!isset($_SESSION['active']) || ($_SESSION['admin_access'] != 1)) {

        header("Location: index.php");
        exit();
    }

    $errorFlag = false;

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

    // ************************************************************** Creating/Adding New X-Men Character
    if ($_POST && isset($_POST['new_mutant'])) {
        if (strlen($_POST['x_alias']) > 0){
            $user_id = $_SESSION['user_id']; // User Id of current Admin

            // Sanitize Fields
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

                    $qry = "INSERT INTO xmen (x_name, x_alias, x_desc, x_power, x_image, user_id) VALUES (:x_name, :x_alias, :x_desc, :x_power, :x_image, :user_id)";
                    $stmt = $db->prepare($qry);
                    
                    // Bind values & execute query
                    $stmt->bindValue(':x_name', $x_name);
                    $stmt->bindValue(':x_alias', $x_alias);        
                    $stmt->bindValue(':x_desc', $x_desc);
                    $stmt->bindValue(':x_power', $x_power);
                    $stmt->bindValue(':x_image', $x_image);
                    $stmt->bindValue(':user_id', $user_id);
                    $stmt->execute();
                    
                    header("Location: index.php");
                    exit();
                }
            }
            else { // WITHOUT File Image Uploaded
                $qry = "INSERT INTO xmen (x_name, x_alias, x_desc, x_power, user_id) VALUES (:x_name, :x_alias, :x_desc, :x_power, :user_id)";
                $stmt = $db->prepare($qry);
                $stmt->bindValue(':x_name', $x_name);
                $stmt->bindValue(':x_alias', $x_alias);        
                $stmt->bindValue(':x_desc', $x_desc);
                $stmt->bindValue(':x_power', $x_power);
                $stmt->bindValue(':user_id', $user_id);
                $stmt->execute();
                
                header("Location: index.php");
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="xstyles.css">
    <title>X-Men CMS: New</title>
</head>
<body>
<?php include('header.php') ?>
    <main>
        <form method="POST" enctype='multipart/form-data' action="new_mutant.php" id="new_mutant_form">
            <fieldset>
<?php if($errorFlag == true): ?>
                <div class="error-box"><?= $errorMsg ?></div>
<?php endif ?>
                <legend>New Mutant</legend>
                <p>
                    <label for="character">Character</label><br>
                    <input name="x_alias" id="character" autofocus>
                </p>
                <p>
                    <label for="name">Name</label><br>
                    <input name="x_name" id="name">
                </p>
                <p>
                    <label for="power">Power/Ability</label><br>
                    <input name="x_power" id="power">
                </p>
                <p>
                    <label for="text_desc">Description</label><br>
                    <textarea name="x_desc" id="text_desc"></textarea>
                </p>
                <p>
                    <label for="file_image">Image Upload (optional)</label><br>
                    <input type="file" name="file_image" id="file_image">
                </p>
                <p>
                    <input type="submit" name="new_mutant" id="add_new_mutant" value="Add New Mutant">
                </p>
            </fieldset>
        </form>
<?php include('footer.php') ?>
    </main>
</body>
</html>