<?php
    require('db_connect.php');
    include('authen.php');

    // Only logged in can access this page, otherwise redirect to index.php.
    $sessMemberId = "";
    if (!isset($_SESSION['sess_role'])){
        header("Location: index.php"); 
        exit;
    }else{
        $sessMemberId = $_SESSION['sess_member_id'];
    }

    if ($_POST && !empty($_POST['post_id']) && !empty($_POST['member_id']) && !empty($_POST['comment'])) {

        // Santitize user input to escape HTML entitles and filter out dangerous characters.
        $postId = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);        
        $memberId = filter_input(INPUT_POST, 'member_id', FILTER_SANITIZE_NUMBER_INT);

        // Build the parameterized SQL query and bind to the above sanitized values.
        $addComment = "INSERT INTO comments (Post_ID, Comment, Member_ID) 
                    VALUES (:post_id, :comment, :member_id)";
        $commentStatement = $db->prepare($addComment);
        
        // Build values to the parameters
        //$statement->bindParm(':')
        $commentStatement->bindvalue(':post_id', $postId);
        $commentStatement->bindvalue(':comment', $comment);
        $commentStatement->bindvalue(':member_id', $memberId);

        if($commentStatement->execute()){
            header("Location: post_detail.php?post_id={$postId}"); 

        }
    }
?>