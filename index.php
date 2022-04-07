<?php
    require('db_connect.php');
    include('authen.php');

    $categoryId = "";
    if (isset($_POST['filter']) && isset($_POST['category_id']) && $_POST['category_id'] != -1){
        
        // if filtering form selected, run the query below
        $sortColumn ="Timestamp";
        $order = "DESC";
        $query = "SELECT * FROM posts po 
                    JOIN products pr ON pr.Product_ID = po.Product_ID 
                    JOIN stores s ON s.Store_ID = po.Store_ID 
                    JOIN members m ON m.Member_ID = po.Member_ID
                    JOIN categories c ON c.Category_ID = pr.Category_ID
                    WHERE c.Category_ID = :category_id
                    ORDER BY ".$sortColumn." ".$order;

        $categoryId = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
        
        $statement = $db->prepare($query);

        $statement ->bindvalue(':category_id', $categoryId);
        
    }else{
        // if no query selected, run the query below
        $categoryId = "";
        $sortColumn ="Timestamp";
        $order = "DESC";
        $query = "SELECT * FROM posts po 
                    JOIN products pr ON pr.Product_ID = po.Product_ID 
                    JOIN stores s ON s.Store_ID = po.Store_ID 
                    JOIN members m ON m.Member_ID = po.Member_ID
                    JOIN categories c ON c.Category_ID = pr.Category_ID
                    ORDER BY ".$sortColumn." ".$order;

        $statement = $db->prepare($query);
    }

    //$statement->bindValue(':sort_column', 'Title');
    //$statement->bindValue(':order', $order);
    // Execution on the DB server is delayed until we execute().
    $statement->execute();

    // Get the category selection
    $queryCategories = "SELECT * FROM Categories";
    $stmtCategories = $db->prepare($queryCategories);
    $stmtCategories->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <?php include('nav.php')?>
    <h1>Home Page: Found <?= $statement->rowCount() ?> Posts</h1>
    <form method="post">
    <label for="category_id">Choose a category:</label>
    <select name="category_id" id="category_id">
        <option value="-1" selected>All</option>
        <?php while($row = $stmtCategories->fetch()): ?>            
            <?php if ($categoryId == $row['Category_ID']): ?>
            <option value="<?=$row['Category_ID']?>" selected>
                <?=$row['Category_Name']?>
            </option>
            <?php else: ?>
            <option value="<?=$row['Category_ID']?>">
                <?=$row['Category_Name']?>
            </option>
            <?php endif ?>
        <?php endwhile ?>
    </select>
    <button name="filter">Filter</button>
    </form>
    <ol>
        <?php while($row = $statement->fetch()): ?>
            <li>
                <p><?= $row['Timestamp'] ?></p>
            	<p>
                    Category <?=$row['Category_Name']?> - 
                    Title: <?= $row['Title'] ?> ID: <a href="post_detail.php?post_id=<?= $row['Post_ID'] ?>" target="_blank"><?= $row['Post_ID'] ?></a>
                    <?php if ($row['Member_ID'] == $userLoginId): ?>
                    <a href="post_edit.php?post_id=<?= $row['Post_ID'] ?>" target="_blank">Edit <?= $row['Post_ID'] ?></a>
                    <?php endif ?>
                </p>                        
            </li>
        <?php endwhile ?>
    </ol>
</body>
</html>