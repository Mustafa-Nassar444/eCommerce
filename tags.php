<?php
session_start();
$pageTitle = 'Show Items';
include 'init.php';
?>

<div class="container">
    <div class="row">
        <?php
        if (isset($_GET['name'])) {
            $tag = $_GET['name'];
            $allItem = getAllFrom("*", "items", "WHERE Tags LIKE '%$tag%'", "AND Approve =1", "Item_ID", "DESC");
            echo "<h1 class='text-center'>{$tag}</h1>";
            foreach ($allItem as $item) {
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
            }
        } else {
            echo "No such tag";
        }
        ?>
    </div>
</div>
<?php include $tpl . "footer.php"; ?>