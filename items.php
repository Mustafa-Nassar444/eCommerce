<?php
session_start();
$pageTitle = 'Show Item';

include 'init.php';

$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
$stmt = $conn->prepare("SELECT items.* ,categories.Name AS Category, users.Username AS Member,categories.Allow_Comment AS A_C from items
                INNER JOIN categories ON categories.ID=items.Cat_ID
                INNER JOIN users ON users.UserID=items.Member_ID
                WHERE Item_ID=?
                AND Approve=1");
$stmt->execute(array($itemid));
$count = $stmt->rowCount();
if ($count > 0) {
    $item = $stmt->fetch();

?>
    <h1 class="text-center"><?php echo $item['Name'] ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <img class="img-responsive img-thumbnail center-block" src="user.jpg" alt="" />
            </div>
            <div class="col-md-9 item-info">
                <h2><?php echo $item['Name'] ?></h2>
                <p><?php echo $item['Description'] ?></p>
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Added Date</span> : <?php echo $item['Add_Date'] ?>
                    </li>
                    <li>
                        <i class="fa fa-money fa-fw"></i>
                        <span>Price</span> : <?php echo $item['Price'] ?>
                    </li>
                    <li>
                        <i class="fa fa-building fa-fw"></i>
                        <span>Made In</span> : <?php echo $item['Country_Made'] ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Category</span> : <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['Category'] ?></a>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Added By</span> : <a href="#"><?php echo $item['Member'] ?></a>
                    </li>
                    <li class="tags-items">
                        <i class="fa fa-user fa-fw"></i>
                        <span>Tags</span> :
                        <?php
                        $allTags = explode(",", $item['Tags']);
                        if (!empty($allTags)) {
                            foreach ($allTags as $tag) {
                                $lwtag = strtolower($tag);
                                echo "<a href='tags.php?name={$lwtag}'>" . $tag . '</a>';
                            }
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
        <hr class="custom-hr">
        <?php if ($item['A_C'] == 0) {
            if (isset($_SESSION['user'])) { ?>
                <div class="row">
                    <div class="col-md-offset-3">
                        <div class="add-comment">
                            <h3>Add comment</h3>
                            <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="POST">
                                <textarea name="comment" id="" cols="30" rows="0" required></textarea>
                                <input class="btn btn-primary" type="submit" value="Add Comment">
                            </form>
                            <?php
                            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                                $comment = $_POST['comment'];
                                $uid = $_SESSION['uid'];
                                $item_id = $item['Item_ID'];
                                if (!empty($comment)) {
                                    $stmt = $conn->prepare("INSERT INTO comments (comment,status,comment_date,Item_ID,Member_ID) values(:zcomment,0,now(),:zitem,:zmember)");
                                    $stmt->execute(array(
                                        ':zcomment' => $comment,
                                        ":zitem" => $item_id,
                                        ":zmember" => $uid
                                    ));
                                    if ($stmt) {
                                        echo '<div class="alert alert-success">Comment Added</div>';
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php } else {
                echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> to add comment';
            }
            ?>
            <hr class="custom-hr">
            <?php
            $stmt = $conn->prepare("SELECT comments.*,users.Username AS Member,users.avatar AS Avatar from comments
                    INNER JOIN users ON users.UserID=comments.Member_ID
                    WHERE Item_ID=?
                    AND
                    status=1
                    ORDER BY c_id Desc");
            $stmt->execute(array($item['Item_ID']));
            $comments = $stmt->fetchAll();
            //$comments = getAllFrom("comments.*,users.Username AS Member", "comments INNER JOIN users ON users.UserID=comments.Member_ID", "WHERE Item_ID={$item['Item_ID']}", "AND status=1", "cid");

            ?>
            <?php
            foreach ($comments as $comment) { ?>
                <div class="comment-box">
                    <div class="row">
                        <div class="col-sm-2 text-center">
                            <?php echo $comment['Member'];
                            if (!empty($row['avatar'])) {
                                echo   ' <td><img class="img-responsive img-thumbnail img-circle center-block"src="uploads\avatars\\' . $comment["Avatar"] . '" alt="" /></td>';
                            } else {
                                echo   ' <td><img class="img-responsive img-thumbnail img-circle center-block"src="user.jpg" alt="" /></td>';
                            } ?>
                        </div>
                        <div class="col-sm-10">
                            <p class="lead"><?php echo $comment['comment'] ?>
                            </p>
                        </div>
                    </div>
                </div>
                <hr class="custom-hr">
        <?php }
        } else {
            echo 'Comments Not Allowed';
        }
        ?>
    </div>
<?php
} else {
    echo '<div class="container">';
    echo '<div class="alert alert-danger">There\'s No Such ID OR Waiting Approval</div>';
    echo '</div>';
}
include $tpl . "footer.php";
?>