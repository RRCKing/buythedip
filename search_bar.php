<form method="post" action="search.php">
        <input id="search" name="search" value="<?=$searchInput?>">
        <select name="search_type" id="search_type">
            <option value="all" selected>All</option>
            <option value="title">Post Title</option>
            <option value="content">Post Content</option>            
            <option value="product">Product Name</option>
            <option value="product_category">Product Category</option>
            <option value="login_name">Login Name</option>
        </select>
        <input type="submit" name="command" value="search" />
        <input type="submit" name="command" value="clear" />
</form>   
