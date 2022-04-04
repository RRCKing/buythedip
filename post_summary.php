<?php
    require('db_connect.php');
    include('authen.php');

    include('nav.php');

    // Only admin role can access this page
    if($role != "admin"){

        // Redirect if not admin
        //header("Location: index.php");       
        //exit;
        
        echo '<h1>Admin only!</h1>';
        exit;

    }

    $sortColumn ="Timestamp";
    $order = "DESC";
    if (isset($_GET['sort_column'])){
        $sortColumn = filter_input(INPUT_GET, 'sort_column', FILTER_SANITIZE_FULL_SPECIAL_CHARS);       
        $order = filter_input(INPUT_GET, 'order', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    // SQL is written as a String .
    $query = "SELECT * FROM posts po JOIN products pr ON pr.Product_ID = po.Product_ID 
                JOIN stores s ON s.Store_ID = po.Store_ID 
                JOIN members m ON m.Member_ID = po.Member_ID
                ORDER BY ".$sortColumn." ".$order;

    // A PDO:: Statement is prepared from the query.
    $statement = $db->prepare($query);

    // Execution on the DB server is delayed until we execute().
    $statement->execute();
    
    
?>

<!DOCTYPE html>
<html>
<head>
    <title>Posts Summary</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <!-- How many database table rows did we SELECT? -->
    <h1>Found <?= $statement->rowCount() ?> Posts</h1>
    <p><a href="post_summary.php?sort_column=Title&order=ASC">Sort by Title in accending order</a></p>
    <p><a href="post_summary.php?sort_column=Post_ID&order=ASC">Sort by Post ID in accending order</a></p>
    <p><a href="post_summary.php?sort_column=Timestamp&order=Desc">Sort by Timestamp in Descending order</a></p>
    <p><a href="post_summary.php?sort_column=Login_Name&order=ASC">Sort by Login Name in accending order</a></p>
    <ol>
        <!-- Fetch each table row in turn. Each $row is a table row hash.
             Fetch returns FALSE when out og rows, halting the loop. -->
        <?php while($row = $statement->fetch()): ?>
            <li>
            <p><?= $row['Timestamp'] ?></p>    
            <p>Title: <?= $row['Title'] ?>
            <a href="post_detail.php?post_id=<?= $row['Post_ID'] ?>" target="_blank"><?= $row['Post_ID'] ?></a>
            </p>
            <p>
            <a href="post_edit.php?post_id=<?= $row['Post_ID'] ?>" target="_blank">Edit <?= $row['Post_ID'] ?></a>
            </p>
            <p><?= $row['Login_Name'] ?><?= $row['Member_ID'] ?></p>
            </li>
        <?php endwhile ?>
    </ol>
</body>
</html>