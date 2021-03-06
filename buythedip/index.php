<?php
    require('db_connect.php');
    include('authen.php');

    // SQL is written as a String .
    $query = "SELECT * FROM posts";

    // A PDO:: Statement is prepared from the query.
    $statement = $db->prepare($query);

    // Execution on the DB server is delayed until we execute().
    $statement->execute();
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
    <h1>Home Page: Found <?= $statement->rowCount() ?> Rows</h1>

    <ol>
        <?php while($row = $statement->fetch()): ?>
            <li>
            	<p>Title: <?= $row['Title'] ?> ID: <a href="post_detail.php?post_id=<?= $row['Post_ID'] ?>" target="_blank"><?= $row['Post_ID'] ?></a></p>
            </li>
        <?php endwhile ?>
    </ol>
</body>
</html>