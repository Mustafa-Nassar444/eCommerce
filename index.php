<?php
ob_start();
session_start();
$pageTitle = 'Homepage';

include 'init.php';
?>
<div class="container">
  <h1 class="text-center">Show Items</h1>
  <div class="row">
    <?php
    $stmt = $conn->prepare("SELECT items.*,categories.Visibility As VS FROM items INNER JOIN categories ON items.Cat_ID=categories.ID WHERE Approve=1 AND categories.Visibility=0 ORDER BY Cat_ID DESC ");
    $stmt->execute();
    $items = $stmt->fetchAll();
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
<?php
include $tpl . "footer.php";
ob_end_flush();
?>