<header>

<div id="header">
    <div id="header_logo">
    <a href="index.php"><img src="images/BuyTheDip_logo.png" alt="logo" id="buythedip_logo"/></a>
    </div>

    <div id="top_header_right">
        <div id="top_greeting_message">
            <span>Hi, <?=$userLoginName?></span>
            <form method="post" action="logout.php" id="logout">
            <?php if ($role == 'member' || $role == 'admin'):?>
                <input type="submit" name="logout" id="logout" value="Logout" />
            <?php endif ?>
            </form>    
        </div>
        <div id="top_function_lists">
            <?php if ($role == 'admin'):?>
                <ul id="top_function_list1">            
                    <li><a href="post_manage.php">Manage Posts</a></li>
                    <li><a href="product_list.php">Product List</a></li>
                    <li><a href="members.php">Manage Members</a></li>            
                </ul>
            <?php endif ?>
            <ul id="top_function_list2">
                <?php if ($role == 'member' || $role == 'admin'):?>
                    <li><a href="post_create.php">Create Post</a></li>
                    <li><a href="product_create.php">Create Product</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="signup.php">Signup</a></li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</div>
</header>