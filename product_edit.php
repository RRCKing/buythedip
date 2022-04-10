<?php
    require('db_connect.php');
    include('authen.php');

    // Only admin role can access this page
    if($role != "admin"){

        // Redirect if not admin
        //header("Location: index.php");       
        //exit;
        
        echo '<h1>Admin only!</h1>';
        exit;

    }

    if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])){
        
        //Build and prepare SQL String with :id placeholder parameter.
        $queryProduct = "SELECT * FROM products WHERE Product_ID = :product_id LIMIT 1";
        $statementProduct = $db->prepare($queryProduct);

        // Sanitize $_GET['id'] to ensure it's a number.
        $productId = filter_input(INPUT_GET, 'product_id', FILTER_SANITIZE_NUMBER_INT);
        // Bind the :id parameter in the query to the sanitized
        // $id specifying a binding-type of Integer.
        $statementProduct->bindValue('product_id', $productId);
        $statementProduct->execute();

        // Fetch the new row selected by primary key id.
        $rowProduct = $statementProduct ->fetch();

        //$img = '<img src="data:image/jpeg;base64,'.base64_encode($rowProduct['Images']).'"/>';

        // Query Categories for selection
        $queryCategories = "SELECT * FROM categories";
        $statementCategories = $db->prepare($queryCategories);
        $statementCategories->execute();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Product Edit</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <?php include('nav.php')?>
    <div id="search_bar">
        <?php include('search_bar.php')?>
    </div>
    <form method="post" action="product_process.php">
        <legend>Product Edit</legend>
        <input type="hidden" name="product_id" value="<?= $rowProduct['Product_ID']?>">
        <ul>
            <li>
                <label for="product_desc">Product Description</label>
                <input id="product_desc" name="product_desc" value=<?=$rowProduct['Product_Desc']?>>
            </li>
            <li>
                <label for="category_id">Choose a Category:</label>
                <select name="category_id" id="category_id">
                    <?php while($rowCat = $statementCategories->fetch()): ?>
                        <option value="<?=$rowCat['Category_ID']?>">
                            <?=$rowCat['Category_Name']?>
                        </option>
                    <?php endwhile ?>
                </select>
            </li>
            <li>
                <?php if ($rowProduct['Img_Link']):?>
                    <div>
                    <p>Remove the image?</p>
                    <input type="checkbox" id="img_link" name="img_link">
                    <label for="img_link"><?=$rowProduct['Img_Link']?></label>
                    <img src=<?=$rowProduct['Img_Link']?> alt="<?=$rowProduct['Img_Link']?>" />
                    </div>
                <?php endif ?>
                <?php if ($rowProduct['Img_Link400']):?>
                    <div>                        
                    <input type="checkbox" id="img_link400" name="img_link400">
                    <label for="img_link400"><?=$rowProduct['Img_Link400']?></label>
                    <img src=<?=$rowProduct['Img_Link400']?> alt="<?=$rowProduct['Img_Link400']?>" />
                    </div>                        
                <?php endif ?>
                <?php if ($rowProduct['Img_Link50']):?>
                    <input type="checkbox" id="img_link50" name="img_link50">
                    <label for="img_link50"><?=$rowProduct['Img_Link50']?></label>
                    <img src=<?=$rowProduct['Img_Link50']?> alt="<?=$rowProduct['Img_Link50']?>" />
                <?php endif ?>
            </li>      
            <li>
                <input type="submit" name="command" value="Edit" />
                <input type="submit" name="command" value="Delete" 
                    onclick="return confirm('Are you sure you wish to delete this product?')" />
            </li>
        </ul>
    </form>

</body>
</html>