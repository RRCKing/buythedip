<?php

    // search bar always attached to the pages with connection, so no db connect.

    //assign the search input
    $searchInput = "";
    $searchType = "";
    $categoryFilter = "";
    $searchTypeSelections = array("All"=>"all", "Post Title"=>"title", 
                                    "Post Content"=>"content", "Product Name"=>"product",
                                    "Product Category"=>"product_category", "Store"=>"store",
                                    "Login Name"=>"login_name");

    // Get the category selection for search bar
    $queryCategoriesSearch = "SELECT * FROM Categories";
    $stmtCategoriesSearch = $db->prepare($queryCategoriesSearch);
    $stmtCategoriesSearch->execute();
?>


<form method="post" action="search.php">
        <input id="search" name="search" value="<?=$searchInput?>">
        <select name="search_type" id="search_type">
            <?php foreach($searchTypeSelections as $key => $value): ?>            
                <option value="<?=$value?>">
                    <?=$key?>
                </option>
            <?php endforeach ?>
        </select>
        <label for="category_filter">Filtered by category:</label>
        <select name="category_filter" id="category_filter">
            <option value="-1" selected>All</option>
            <?php while($row = $stmtCategoriesSearch->fetch()): ?>            
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
