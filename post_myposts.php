<?php
    require('db_connect.php');
    include('authen.php');

    //assign the search input
    $searchInput = "";
    $categoryId = "";
    $selectedSearchColumn = "";
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

        if($_POST['search_type'] == 'all'){
            $serachColumn = 'po.title LIKE :search OR po.content LIKE :search 
                                OR pr.Product_Desc LIKE :search OR c.Category_Name LIKE :search 
                                OR m.Login_Name LIKE :search';
        }

        if($_POST['search_type'] == 'title'){
            $serachColumn = 'po.title LIKE :search';
        }

        if($_POST['search_type'] == 'content'){
            $serachColumn = 'po.content LIKE :search';
        }

        if($_POST['search_type'] == 'product'){
            $serachColumn = 'pr.Product_Desc LIKE :search';
        }

        if($_POST['search_type'] == 'product_category'){
            $serachColumn = 'c.Category_Name LIKE :search';
        }

        if($_POST['search_type'] == 'login_name'){
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
        $searchSearchColumn = $_POST['search_category'];

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
    <div id="top_section">
    <?php include('nav.php')?>
    <div id="search_bar">
        <?php include('search_bar.php')?>
    </div>
    </div>
    <div id="main_section">
        <h1>My Posts: Total <?= $statement->rowCount() ?> Posts</h1>
        <div id="home_post_list">
            <ol>
                <?php while($row = $statement->fetch()): ?>
                    <?php if ($row['Member_ID'] == $userLoginId): ?>
                    <li>                       
                        <p>Post ID: <?= $row['Post_ID'] ?> | Category -  <?=$row['Category_Name']?> | Posted by: <?= $row['Timestamp'] ?>  </p>
                        <h3>
                            <a href="post_detail.php?post_id=<?= $row['Post_ID'] ?>" target="_blank"><?= $row['Title'] ?></a>                        
                        </h3>
                        <p>Product: <?=$row['Product_Desc']?></p>
                            <img src=<?=$row['Img_Link50']?> alt="product_photo" />                                        
                        <p>
                            <a href="post_edit.php?post_id=<?= $row['Post_ID'] ?>" target="_blank">Edit <?= $row['Post_ID'] ?></a>                        
                        </p>                                          
                    </li>
                    <?php endif ?>  
                <?php endwhile ?>
            </ol>
        </div>

    </div>
    
</body>
</html>