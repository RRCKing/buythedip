<?php
    require('db_connect.php');
    include('authen.php');

    //assign the search input
    $searchInput = "";
    $searchType = "";
    $searchTypeSelections = array("All"=>"all", "Post Title"=>"title", 
                                    "Post Content"=>"content", "Product Name"=>"product",
                                    "Product Category"=>"product_category", "Store"=>"store",
                                    "Login Name"=>"login_name");
    // for initialize the category filter dropdown box and set the memorized selection for the rest of the code.
    $categoryFilter = "";
    if (isset($_POST['command']) && $_POST['command'] == 'search'){

        // according the selection to add the query criteria
        $serachColumn = 'po.title';

        if($_POST['search_type'] == 'all'){
            $serachColumn = '(po.title LIKE :search OR po.content LIKE :search 
                                OR pr.Product_Desc LIKE :search OR c.Category_Name LIKE :search 
                                OR m.Login_Name LIKE :search
                                OR s.Store_Name LIKE :search)';
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

        if($_POST['search_type'] == 'store'){
            $serachColumn = 's.Store_Name LIKE :search';
        }

        // if category filter is not all(-1), add filter requirement.
        if ($_POST['category_filter'] != -1){
            $serachColumn =  $serachColumn.' AND c.Category_ID = :category_filter';
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
        $searchWithPercent = '%'.strtolower($search).'%';
        $categoryFilter = filter_input(INPUT_POST, 'category_filter', FILTER_SANITIZE_NUMBER_INT);
        
        $statement = $db->prepare($query);

        $statement ->bindvalue(':search', $searchWithPercent);

        // if filter is not all (-1), bind category filter to query.
        if ($_POST['category_filter'] != -1){
            $statement ->bindvalue(':category_filter', $categoryFilter);
        }       

        // assign the search input back to the input textbox
        $searchInput = $_POST['search'];
        $searchType = $_POST['search_type'];

    }else{
        // if no query selected, run the query below       
        
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <title>Search</title>
</head>
<body>
    <?php include('nav.php')?>    
    <form method="post" action="search.php">
            <input id="search" name="search" value="<?=$searchInput?>">
            <select name="search_type" id="search_type">
                <?php foreach($searchTypeSelections as $key => $value): ?>            
                    <?php if ($searchType == $value): ?>
                    <option value="<?=$value?>" selected>
                        <?=$key?>
                    </option>
                    <?php else: ?>
                    <option value="<?=$value?>">
                        <?=$key?>
                    </option>
                    <?php endif ?>
                <?php endforeach ?>
            </select>
            <label for="category_filter">Filtered by category:</label>
            <select name="category_filter" id="category_filter">
                <option value="-1" selected>All</option>
                <?php while($row = $stmtCategories->fetch()): ?>            
                    <?php if ($categoryFilter == $row['Category_ID']): ?>
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
            <input type="submit" name="command" value="search" />
            <input type="submit" name="command" value="clear" />
    </form>
    <h1>Search Result</h1>
    <div id="home_post_list">
        <ol>
            <?php while($row = $statement->fetch()): ?>
                <li>
                    <p>Post ID: <?= $row['Post_ID'] ?> | Category -  <?=$row['Category_Name']?> | Posted by: <?= $row['Timestamp'] ?>  </p>
                    <h3>
                        <a href="post_detail.php?post_id=<?= $row['Post_ID'] ?>" target="_blank"><?= $row['Title'] ?></a>                        
                    </h3>
                    <p>Product: <?=$row['Product_Desc']?></p>
                    <img src=<?=$row['Img_Link50']?> alt="product_photo" />
                    <?php if ($row['Member_ID'] == $userLoginId): ?>                    
                    <p>
                        <a href="post_edit.php?post_id=<?= $row['Post_ID'] ?>" target="_blank">Edit <?= $row['Post_ID'] ?></a>                        
                    </p>
                    <?php endif ?>                        
                </li>
            <?php endwhile ?>
        </ol>
    </div>
</body>
</html>