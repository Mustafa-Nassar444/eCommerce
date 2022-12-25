<?php
session_start();
$pageTitle = 'Profile';

include 'init.php';

if (isset($_SESSION['user'])) {
    $getUser = $conn->prepare("SELECT * FROM users WHERE Username=?");
    $getUser->execute(array($_SESSION['user']));
    $info = $getUser->fetch();
    // $info = getAllFrom("*", "users", "WHERE Username={$_SESSION['user']}", "", "ID");
?>
    <h1 class="text-center">My Profile</h1>
    <div class="information block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">My information</div>
                <div class="panel-body">
                    <ul class="list-unstyled">
                        <li>
                            <i class="fa fa-unlock-alt fa-fw"></i>
                            <span>Name</span> : <?php echo $info['Username'] ?>
                        </li>
                        <li>
                            <i class="fa fa-envelope-o fa-fw"></i>
                            <span>Email</span> : <?php echo $info['Email'] ?>
                        </li>
                        <li>
                            <i class="fa fa-user fa-fw"></i>
                            <span>Full Name</span> : <?php echo $info['FullName'] ?>
                        </li>
                        <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>Register Date</span> : <?php echo $info['Date'] ?>
                        </li>
                        <li><i class="fa fa-tags fa-fw"></i><span>Fav Category</span> : </li>
                    </ul>
                    <div class="btn btn-default">Edit information
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="my-items" class="my-ads block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">My Items</div>
                <div class="panel-body">
                    <?php
                    $item = getAllFrom("*", "items", "WHERE Member_ID={$info['UserID']}", "", "Item_ID");
                    if (!empty($item)) {
                        echo '<div class="row">';
                        foreach ($item as $item) {
                            echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="thumbnail item-box">';
                            if ($item['Approve'] == 0) echo '<span class="approve-status">Waiting Approval</span>';
                            echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                            echo '<img class="image-responsive" src="user.jpg" alt="" />';
                            echo '<div class="caption">';
                            echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</h3></a>';
                            echo '<p>' . $item['Description'] . '</p>';
                            echo '<div class="date">' . $item['Add_Date'] . '</div>';

                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo 'There\'s No Items To Show, Create <a href="newad.php">New Item</a>';
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="my-comments block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">My Comments</div>
                <div class="panel-body">
                    <?php
                    /* $stmt = $conn->prepare("SELECT * from comments WHERE Member_ID=?");
                    $stmt->execute(array($info['UserID'])); */

                    $comments = getAllFrom("*", "comments", "WHERE Member_ID={$info['UserID']}", "", "c_id");
                    if (!empty($comments)) {
                        foreach ($comments as $comment) {
                            echo '<div class="comment-box">';
                            echo '<p class="member-c">' . $comment['comment'] . '</p>';
                            echo '</div>';
                        }
                    } else {
                        echo 'There\'s No Comment To Show';
                    }
                    ?> </div>
            </div>
        </div>
    </div>
<?php
} else {
    header("Location: index.php");
    exit();
}
include $tpl . "footer.php";
?>