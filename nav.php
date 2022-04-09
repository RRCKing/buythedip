
<nav id="top_bar">
    <ul class="nav">
        <li><a href="index.php">Home</a></li>
        <li><a href="login.php">Login/Logout</a></li>
        <li><a href="signup.php">Signup</a></li>
        <?php if ($role == 'member' || $role == 'admin'):?>
            <li><a href="post_create.php">Create Post</a></li>
            <li><a href="product_create.php">Create Product</a></li>
        <?php endif ?>
        <?php if ($role == 'admin'):?>
            <li><a href="post_manage.php">Posts Manage</a></li>
            <li><a href="product_list.php">Product List</a></li>
            <li><a href="members.php">Manage Members</a></li>
        <?php endif ?>
    </ul>
</nav>

<?php include('search_bar.php')?>