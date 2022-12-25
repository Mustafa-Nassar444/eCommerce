<?php
session_start();
$pageTitle = 'Category';
include 'init.php';
?>

<div class="container">
    <h1 class="text-center">Show Category Items</h1>
    <div class="row">
        <?php
        $category = isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;
        $items = getAllFrom("*", "items", "WHERE Cat_ID={$category}", "AND Approve=1", "Cat_ID");

        foreach ($items as $item) {
            echo '<div class="col-sm-6 col-md-3">';
            echo '<div class="thumbnail item-box">';
            echo '<span class="price-tag">$' . $item['Price'] . '</span>';
            echo '<img class="image-responsive" src="phone.jpg" alt="" />';
            echo '<div class="caption">';
            echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</h3></a>';
            echo '<p>' . $item['Description'] . '</p>';
            echo '<div class="date">' . $item['Add_Date'] . '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        } ?>
    </div>
</div>
<?php include $tpl . "footer.php"; ?>