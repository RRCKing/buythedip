<?php
    require('db_connect.php');
    include('authen.php');

    // Only logged in can access this page, otherwise redirect to index.php.
    if (!isset($_SESSION['sess_role'])){
        header("Location: index.php"); 
        exit;
    }

    if($_POST['captcha'] == $_SESSION['code']){
        echo "correct captcha";
    }else{
        echo "Invalid captcha";
        exit;
    }

    if (isset($_POST['command']) && $_POST['command'] == 'submit' && $_POST && !empty($_POST['post_id']) && !empty($_POST['member_id']) && !empty($_POST['comment'])) {

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

    if (isset($_POST['command']) && $_POST['command'] == 'Edit' && $_POST && !empty($_POST['comment_id']) && !empty($_POST['member_id']) && !empty($_POST['comment'])) {

        // Santitize user input to escape HTML entitles and filter out dangerous characters.
        $postId = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
        $commentId = filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);        
        $memberId = filter_input(INPUT_POST, 'member_id', FILTER_SANITIZE_NUMBER_INT);

        // Build the parameterized SQL query and bind to the above sanitized values.
        $updateComment = "UPDATE comments SET Comment = :comment, 
                            Member_ID = :member_id WHERE Comment_ID = :comment_id";
        $commentStatement = $db->prepare($updateComment);
        
        // Build values to the parameters
        //$statement->bindParm(':')
        $commentStatement->bindvalue(':comment_id', $commentId);
        $commentStatement->bindvalue(':comment', $comment);
        $commentStatement->bindvalue(':member_id', $memberId);

        if($commentStatement->execute()){
            header("Location: post_detail.php?post_id={$postId}"); 

        }
    }

    if (isset($_POST['command']) && $_POST['command'] == 'Delete' && $_POST && !empty($_POST['comment_id']) && !empty($_POST['member_id']) && !empty($_POST['comment'])) {

        // Santitize user input to escape HTML entitles and filter out dangerous characters.
        $postId = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
        $commentId = filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);

        // Build the parameterized SQL query and bind to the above sanitized values.
        $updateComment = "DELETE FROM comments WHERE Comment_ID = :comment_id";
        $commentStatement = $db->prepare($updateComment);
        
        // Build values to the parameters
        //$statement->bindParm(':')
        $commentStatement->bindvalue(':comment_id', $commentId);

        if($commentStatement->execute()){
            header("Location: post_detail.php?post_id={$postId}"); 

        }
    }
?>