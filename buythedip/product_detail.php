<?php
    require('db_connect.php');
    include('authen.php');

    //Build and prepare SQL String with :id placeholder parameter.
    $queryPost = "SELECT * FROM products WHERE Product_ID = :product_id LIMIT 1";
    $statementPost = $db->prepare($queryPost);

    // Sanitize $_GET['id'] to ensure it's a number.
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    // Bind the :id parameter in the query to the sanitized
    // $id specifying a binding-type of Integer.
    $statementPost->bindValue('id', $id);
    $statementPost->execute();

    // Fetch the new row selected by primary key id.
    $rowPost = $statementPost->fetch();

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
    <title>Post Details</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <?php include('nav.php')?>
    <h1>Rows Found: <?= $statementPost->rowCount() ?></h1>
    <h2>Post ID</h2>
    <p><?= $rowPost['Post_ID']?></p>
    <h2>Title</h2>
    <p><?= $rowPost['Title']?></p>
    <h2>Content</h2>
    <p><?= $rowPost['Content']?></p>
    <h2>Product ID</h2>
    <p><?= $rowPost['Product_ID']?></p>
    <h2>Product Desc</h2>
    <p><?= $rowProduct['Product_Desc']?></p>
    <h2>Product Price</h2>
    <p><?= $rowProduct['Price']?></p>
    <h2>Category ID</h2>
    <p><?= $rowProduct['Category_ID']?></p>
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