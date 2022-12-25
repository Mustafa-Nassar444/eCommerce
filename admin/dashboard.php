<?php
ob_start();
session_start();
$pageTitle = 'Dashboard';
if (isset($_SESSION['Username'])) {
    include 'init.php';
    $noUsers = 5;
    $latestUsers = getLatest('*', 'users', 'UserID', $noUsers);
    $noItems = 3;
    $latestItems = getLatest('*', 'items', 'Item_ID', $noItems);
?>
    <div class="home-stats">
        <div class="container text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                            Total Members
                            <span><a href="members.php"><?php echo countItem('UserID', 'users'); ?></a></span>

                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Pending Members
                            <span><a href="members.php?do=Manage&page=Pending"><?php echo countItem('RegStatus', 'users', 0); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Items
                            <span><a href="items.php"><?php echo countItem('Item_ID', 'items'); ?></a></span>
                        </div>

                    </div>
                </div>
                <div class="col-md-3">

                    <div class="stat st-comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">Total Comments
                            <span><a href="comments.php"><?php echo countItem('c_id', 'comments'); ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="latest">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i> Latest <?php echo $noUsers; ?> Registered Users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php foreach ($latestUsers as $Users) {
                                    echo '<li>';
                                    echo $Users['Username'];
                                    echo '<a href="members.php?do=Edit&userid=' . $Users['UserID'] . '">';
                                    echo '<span class="btn btn-success pull-right">';
                                    echo ' <i class="fa fa-edit"></i>Edit';
                                    if ($Users['RegStatus'] == 0) {
                                        echo "<a href='members.php?do=Activate&userid=" . $Users['UserID'] . "' class='btn btn-info pull-right activate'><i class='fa fa-close'></i>Activate</a>";
                                    }
                                    echo '</span>';
                                    echo '</a>';
                                    echo '</li>';
                                } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i> Latest <?php echo $noItems; ?> Items
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php foreach ($latestItems as $Items) {
                                    echo '<li>';
                                    echo $Items['Name'];
                                    echo '<a href="items.php?do=Edit&itemid=' . $Items['Item_ID'] . '">';
                                    echo '<span class="btn btn-success pull-right">';
                                    echo ' <i class="fa fa-edit"></i>Edit';
                                    if ($Items['Approve'] == 0) {
                                        echo "<a href='items.php?do=Approve&itemid=" . $Items['Item_ID'] . "' class='btn btn-info pull-right activate'><i class='fa fa-check'></i>Approve</a>";
                                    }
                                    echo '</span>';
                                    echo '</a>';
                                    echo '</li>';
                                } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!---Start latest Comment -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comment-o"></i> Latest Comments
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <?php
                            $stmt = $conn->prepare("SELECT comments.* ,users.Username AS Member from comments
                            INNER JOIN users ON users.UserID=comments.Member_ID ORDER BY c_id Desc");
                            $stmt->execute();
                            $comments = $stmt->fetchAll();
                            foreach ($comments as $comment) {
                                echo '<div class="comment-box">';
                                echo '<span class="member-n">' . $comment['Member'] . '</span>';
                                echo '<p class="member-c">' . $comment['comment'] . '</p>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    include $tpl . "footer.php";
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();
?>