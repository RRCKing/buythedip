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

    // SQL is written as a String .
    $query = "SELECT * FROM posts";

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
    <h1>Found <?= $statement->rowCount() ?> Rows</h1>

    <ul>
        <!-- Fetch each table row in turn. Each $row is a table row hash.
             Fetch returns FALSE when out og rows, halting the loop. -->
        <?php while($row = $statement->fetch()): ?>
            <li><?= $row['Title'] ?> By <?= $row['Content'] ?> 
            and ID is 
            <a href="post_detail.php?post_id=<?= $row['Post_ID'] ?>" target="_blank"><?= $row['Post_ID'] ?></a>
            <a href="post_edit.php?post_id=<?= $row['Post_ID'] ?>" target="_blank">Edit <?= $row['Post_ID'] ?></a>
            </li>
        <?php endwhile ?>
    </ul>
</body>
</html>