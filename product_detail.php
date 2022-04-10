<?php
    require('db_connect.php');
    include('authen.php');

    //Build and prepare SQL String with :id placeholder parameter.
    $queryProduct = "SELECT * FROM products pr
                        JOIN categories c ON c.Category_ID = pr.Category_ID
                        WHERE Product_ID = :product_id LIMIT 1";
    $statementProduct = $db->prepare($queryProduct);

    // Sanitize $_GET['id'] to ensure it's a number.
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    // Bind the :id parameter in the query to the sanitized
    // $id specifying a binding-type of Integer.
    $statementProduct->bindValue(':product_id', $id);
    $statementProduct->execute();

    // Fetch the new row selected by primary key id.
    $rowProduct = $statementProduct->fetch();

    // $queryProduct = "SELECT * FROM products pr JOIN posts po ON pr.Product_ID = po.Product_ID WHERE po.Post_ID = :id LIMIT 1";

    // $statementProduct = $db->prepare($queryProduct);
    // $statementProduct->bindValue('id', $id);
    // $statementProduct->execute();

    // // Fetch the new row selected by primary key id.
    // $rowProduct = $statementProduct->fetch();

    //$img = '<img src="data:image/jpeg;base64,'.base64_encode($rowProduct['Images']).'"/>';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <?php include('nav.php')?>
    <div id="search_bar">
        <?php include('search_bar.php')?>
    </div>
    <h1>Rows Found: <?= $statementProduct->rowCount() ?></h1>
    <h2>Product ID</h2>
    <p><?= $rowProduct['Product_ID']?></p>
    <h2>Product Desc</h2>
    <p><?= $rowProduct['Product_Desc']?></p>
    <h2>Category ID</h2>
    <p><?= $rowProduct['Category_ID']?></p>
    <h2>Category Name</h2>
    <p><?= $rowProduct['Category_Name']?></p>
    <?php if (!empty($rowProduct['Img_Link'])):?>
    <h2>Image</h2>
    <img src=<?=$rowProduct['Img_Link']?> alt="" />
    <h2>Image400</h2>
    <img src=<?=$rowProduct['Img_Link400']?> alt="" />
    <h2>Image50</h2>
    <img src=<?=$rowProduct['Img_Link50']?> alt="" />
    <?php endif ?>
</body>
</html>