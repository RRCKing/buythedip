<?php
    require('db_connect.php');
    include('authen.php');

    // Only logged in can access this page, otherwise redirect to index.php.
    $sessMemberId = "";
    if (!isset($_SESSION['sess_role'])){
        header("Location: index.php"); 
        exit;
    }else{
        $sessMemberId = $_SESSION['sess_member_id'];
    }

    $queryCategories = "SELECT * FROM categories";

    $statementCategories = $db->prepare($queryCategories);
    
    $statementCategories->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Product List</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <div id="top_section">
        <?php include('nav.php')?>
        <div id="search_bar">
            <?php include('search_bar.php')?>
        </div>
    </div>
    <div id="main_section">
        <h1>Category Edit: Found <?= $statementCategories->rowCount() ?> Categories</h1>
        <form method="post" action="category_process.php">
            <legend>Category Edit</legend>
            <input type="hidden" name="product_id" value="<?= $rowProduct['Product_ID']?>">
            <ul>
                <li>
                    <label for="category_id">Choose a Category to edit:</label>
                    <select name="category_id" id="category_id">
                        <?php while($rowCat = $statementCategories->fetch()): ?>
                            <option value="<?=$rowCat['Category_ID']?>">
                                <?=$rowCat['Category_Name']?>
                            </option>
                        <?php endwhile ?>
                    </select>
                </li>            
                <li>
                    <label for="category_name">New Category Name:</label>
                    <input id="category_name" name="category_name">
                </li>   
                <li>
                    <input type="submit" name="command" value="Edit" />
                </li>
            </ul>
        </form>

        <ol>        
            <?php while($rowCat = $statementCategories->fetch()): ?>
                <li>
                    <?= $rowCat['Category_Name'] ?> and ID is <?= $rowCat['Category_ID'] ?>
                    <a href="category_edit.php?category_id=<?= $rowCat['Category_ID'] ?>" target="_blank">Edit</a>
                </li>
            <?php endwhile ?>
        </ol>
    </div>
    
</body>
</html>