<?php
    require('db_connect.php');
    include('authen.php');

    //assign the search input
    $searchInput = "";
    $categoryId = "";
    if (isset($_POST['command']) && $_POST['command'] == 'filter' && isset($_POST['category_id']) && $_POST['category_id'] != -1){
        
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
        
    }elseif (isset($_POST['command']) && $_POST['command'] == 'search'){

        // according the selection to add the query criteria
        $serachColumn = 'po.title';

        if($_POST['search_category'] == 'all'){
            $serachColumn = 'po.title LIKE :search OR po.content LIKE :search 
                                OR pr.Product_Desc LIKE :search OR c.Category_Name LIKE :search 
                                OR m.Login_Name LIKE :search';
        }

        if($_POST['search_category'] == 'title'){
            $serachColumn = 'po.title LIKE :search';
        }

        if($_POST['search_category'] == 'content'){
            $serachColumn = 'po.content LIKE :search';
        }

        if($_POST['search_category'] == 'product'){
            $serachColumn = 'pr.Product_Desc LIKE :search';
        }

        if($_POST['search_category'] == 'product_category'){
            $serachColumn = 'c.Category_Name LIKE :search';
        }

        if($_POST['search_category'] == 'login_name'){
            $serachColumn = 'm.Login_Name LIKE :search';
        }

        // if filtering form selected, run the query below
        $sortColumn ="Timestamp";
        $order = "DESC";
        $query = "SELECT * FROM posts po 
                    JOIN products pr ON pr.Product_ID = po.Product_ID 
                    JOIN stores s ON s.Store_ID = po.Store_ID 
                    JOIN members m ON m.Member_ID = po.Member_ID
                    JOIN categories c ON c.Category_ID = pr.Category_ID
                    WHERE $serachColumn 
                    ORDER BY ".$sortColumn." ".$order;

        $search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $searchWithPercent = '%'.$search.'%';
        
        $statement = $db->prepare($query);

        $statement ->bindvalue(':search', $searchWithPercent);

        // assign the search input back to the input textbox
        $searchInput = $_POST['search'];

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
        <input id="search" name="search" value="<?=$searchInput?>">
        <select name="search_category" id="search_category">
            <option value="all" selected>All</option>
            <option value="title">Post Title</option>
            <option value="content">Post Content</option>            
            <option value="product">Product Name</option>
            <option value="product_category">Product Category</option>
            <option value="login_name">Login Name</option>
        </select>
        <input type="submit" name="command" value="search" />
        <input type="submit" name="command" value="clear" />
    </form>    
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
        <!-- <button name="filter">Filter</button> -->
        <input type="submit" name="command" value="filter" />
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