<?php
    require('db_connect.php');
    include('authen.php');

    // Only admin role can access this page
    if($role == "admin"){

        // SQL is written as a String .
        $query = "SELECT * FROM posts";

        // A PDO:: Statement is prepared from the query.
        $statement = $db->prepare($query);

        // Execution on the DB server is delayed until we execute().
        $statement->execute();

    }else{
        // Redirect if not admin
        //header("Location: index.php");       
        //exit;
        
        echo '<h1>Admin only!</h1>';
        exit;
    }  

    // SQL is written as a String .
    $queryMembers = "SELECT * FROM members";

    // A PDO:: Statement is prepared from the query.
    $statementMembers = $db->prepare($queryMembers);

    // Execution on the DB server is delayed until we execute().
    $statementMembers->execute();
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
    <div id="search_bar">
        <?php include('search_bar.php')?>
    </div>
    <!-- How many database table rows did we SELECT? -->
    <h1>Home Page: Found <?= $statementMembers->rowCount() ?> Rows</h1>

    <ol>
        <!-- Fetch each table row in turn. Each $row is a table row hash.
             Fetch returns FALSE when out og rows, halting the loop. -->
        <?php while($row = $statementMembers->fetch()): ?>
            <li>
            	<?= $row['Login_Name'] ?> and ID is <?= $row['Member_ID'] ?> and Role is <?= $row['Role'] ?>
                <a href="member_edit.php?member_id=<?= $row['Member_ID'] ?>">Edit Member</a>
            </li>
        <?php endwhile ?>
    </ol>
    <p><a href="member_add.php">Add Member</a></p>
</body>
</html>