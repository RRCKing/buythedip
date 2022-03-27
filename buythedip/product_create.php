<?php
    require('db_connect.php');
    include('authen.php');

    //***The library of resize image ***//
    include 'ImageResize.php';
    include 'ImageResizeException.php';
    use \Gumlet\ImageResize;
    use \Gumlet\ImageResizeException;
    $image_upload_detected = false;
    $upload_error_detected = false;


    // Only logged in can access this page, otherwise redirect to index.php.
    $sessMemberId = "";
    if (!isset($_SESSION['sess_role'])){
        header("Location: index.php"); 
        exit;
    }else{
        $sessMemberId = $_SESSION['sess_member_id'];
    }

    // Get the category selection
    $queryCategories = "SELECT * FROM Categories";
    $stmtCategories = $db->prepare($queryCategories);
    $stmtCategories->execute();


    if ($_POST && !empty($_POST['product_desc'])) {
        
        // Santitize input
        $productDesc = filter_input(INPUT_POST, 'product_desc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $categoryId = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);

        // Add Product query
        $addProduct = "INSERT INTO products (Product_Desc, Category_ID, Img_Link, Img_Link400, Img_Link50) 
                    VALUES (:product_desc, :category_id, :imglink, :imglink400, :imglink50)";
        $productStatement = $db->prepare($addProduct);

        // ******** File Upload ********//
        $invalid_file = false;
        $imgLink = "";
        $imgLink400 = "";
        $imgLink50 = "";
        // file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
        // Default upload path is an 'uploads' sub-folder in the current folder.
        function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
            $current_folder = dirname(__FILE__);
            
            // Build an array of paths segment names to be joins using OS specific slashes.
            $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
            
            // The DIRECTORY_SEPARATOR constant is OS specific.
            return join(DIRECTORY_SEPARATOR, $path_segments);
        }

        // file_is_an_image() - Checks the mime-type & extension of the uploaded file for "image-ness".
        function file_is_an_image($temporary_path, $new_path) {

            $images_mine_types       = ['image/gif', 'image/jpeg', 'image/png'];
            $image_file_extensions   = ['gif', 'jpg', 'jpeg', 'png'];
            
            $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
            
            // minme is from tmp folder, while actual file in the path set in file_upload_path, in this case 'uploads' folder in the current folder.
            $actual_mime_type        = mime_content_type($temporary_path);
            
            $file_extension_is_img = in_array($actual_file_extension, $image_file_extensions);

            $mime_type_is_img      = in_array($actual_mime_type, $images_mine_types);
            
            return $file_extension_is_img && $mime_type_is_img;
        }
        
        $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
        $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);

        if ($image_upload_detected) { 

            $image_filename        = $_FILES['image']['name'];
            $image_filename_lowercase = strtolower($image_filename);        
            $temporary_image_path  = $_FILES['image']['tmp_name'];
            $new_image_path        = file_upload_path($image_filename_lowercase);
            
            // This is only for checking images
            if (file_is_an_image($temporary_image_path, $new_image_path)){

                // has to check allowed file types and image types before move, otherwise the $temporary_image_path will be moved and error.
                move_uploaded_file($temporary_image_path, $new_image_path);

                $new_resized_filename = str_replace(array('.jpg', 'jpeg', '.png', '.gif', '.PNG', '.JPG', '.JPEG', '.GIF'), '', $_FILES['image']['name']);
                $actual_resized_file_extension   = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $lowercase_ext = strtolower($actual_resized_file_extension);

                $resize_image = new ImageResize('uploads'.DIRECTORY_SEPARATOR.$_FILES['image']['name']);
                $resize_image
                    ->resizeToWidth(400)
                    ->save('uploads'.DIRECTORY_SEPARATOR.$new_resized_filename."_medium.".$lowercase_ext)

                    ->resizeToWidth(50)
                    ->save('uploads'.DIRECTORY_SEPARATOR.$new_resized_filename."_thumbnail.".$lowercase_ext)
                ;
                
                $imglink = 'uploads'.DIRECTORY_SEPARATOR.$_FILES['image']['name'];
                $imglink400 = 'uploads'.DIRECTORY_SEPARATOR.$new_resized_filename."_medium.".$lowercase_ext;
                $imglink50 = 'uploads'.DIRECTORY_SEPARATOR.$new_resized_filename."_thumbnail.".$lowercase_ext;
                
                
            }else{
                $invalid_file = true;    
            }
        }
        // ******** File Upload End ********//
        
        // Build values to the parameters
        $productStatement->bindvalue(':product_desc', $productDesc);
        $productStatement->bindvalue(':category_id', $categoryId);
        $productStatement->bindvalue(':imglink', $imglink);
        $productStatement->bindvalue(':imglink400', $imglink400);
        $productStatement->bindvalue(':imglink50', $imglink50);

        if($productStatement->execute()){

            
            header("Location: post_create.php");
        }

        
    }



    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Product Add</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <?php include('nav.php')?>
    <form method="post" action="product_create.php" enctype='multipart/form-data'>
        <legend>Create Product</legend>
        <ul>
            <li>
                <label for="product_desc">Product Description</label>
                <input id="product_desc" name="product_desc">
            </li>
            <li>
                <label for="category_id">Choose a category:</label>
                <select name="category_id" id="category_id">
                    <?php while($row = $stmtCategories->fetch()): ?>
                        <option value="<?=$row['Category_ID']?>">
                            <?=$row['Category_Name']?>
                        </option>
                    <?php endwhile ?>
                </select>
            </li>
            <li>
                <label for='image'>Image Filename:</label>
                <input type='file' name='image' id='image'>
            </li>
            <li>
                <input type="submit" name="command" value="submit" />
            </li>
        </ul>
    </form>
    <?php if ($upload_error_detected): ?>
        <p>Error Number: <?= $_FILES['image']['error'] ?></p>
    <?php elseif ($image_upload_detected): ?>
    <?php if ($invalid_file): ?>
        <p>File type invalid, only '.jpg', 'jpeg', '.png', and '.gif' allowed.</p>
    <?php endif ?>
        <p>Client-Side Filename: <?= $_FILES['image']['name'] ?></p>
        <p>Apparent Mime Type:   <?= $_FILES['image']['type'] ?></p>
        <p>Size in Bytes:        <?= $_FILES['image']['size'] ?></p>
        <p>Temporary Path:       <?= $_FILES['image']['tmp_name'] ?></p>
    <?php endif ?>
</body>
</html>