<?php

session_start();
$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
$pageTitle = "Comments";
if (isset($_SESSION['Username'])) {

    include 'init.php';
    if ($do == 'Manage') {
        $query = '';
        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'AND RegStatus =0';
        }
        $stmt = $conn->prepare("SELECT comments.*,items.Name AS Item ,users.Username AS Member from comments
        INNER JOIN items ON items.Item_ID=comments.Item_ID
        INNER JOIN users ON users.UserID=comments.Member_ID
        ORDER BY c_id Desc");
        $stmt->execute();
        $rows = $stmt->fetchAll();
?>
        <h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Comment</td>
                        <td>Item</td>
                        <td>Member</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach ($rows as $row) {
                        echo  ' <tr>';
                        echo   ' <td>' . $row["c_id"] . '</td>';
                        echo   ' <td>' . $row["comment"] . '</td>';
                        echo   ' <td>' . $row["Item"] . '</td>';
                        echo   ' <td>' . $row["Member"] . '</td>';
                        echo   ' <td>' . $row["comment_date"] . '</td>';
                        echo     "<td>
                        <a href='?do=Edit&comid=" . $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                        <a href='?do=Delete&comid=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";
                        if ($row['status'] == 0) {
                            echo "<a href='?do=Approve&comid=" . $row['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                        }
                        echo     "</td>";
                        echo  '</tr>';
                    }
                    ?>
                </table>
            </div>
            <!---  <a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>New Member</a> --->
        </div>
        <?php } elseif ($do == 'Edit') {
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        $stmt = $conn->prepare("SELECT * FROM comments WHERE c_id=?");
        $stmt->execute(array($comid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($row > 0) { ?>
            <h1 class="text-center">Edit Comment</h1>
            <div class="container">
                <form action="?do=Update" class="form-horizontal" method="POST">
                    <input type="hidden" name="comid" value="<?php echo $comid; ?>">
                    <!--- Start Comment Edit --->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-laber">Comment</label>
                        <div class="col-sm-10 col-md-4">
                            <textarea class="form-control" name="comment" id=""><?php echo $row['comment'] ?></textarea>
                        </div>
                    </div>
                    <!--- End Comment Edit --->
                    <!--- Start submit  --->
                    <div class=" form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary">
                        </div>
                    </div>
                    <!--- End submit  --->
                </form>
            </div>

<?php } else {
            echo "<div class='container'>";

            $error = '<div class="alert alert-danger"> No Such ID</div>';
            redirectHome($error);
            echo "</div>";
        }
    } elseif ($do == 'Update') {
        echo "<h1 class='text-center'>Update Comment</h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $comid = $_POST['comid'];
            $comment = $_POST['comment'];
            $stmt = $conn->prepare("UPDATE comments SET comment=?  WHERE c_id=? ");
            $stmt->execute(array($comment, $comid));
            echo "<div class='container'>";
            $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated Successfully</div>';
            redirectHome($msg, 'back');
            echo "</div>";
        } else {
            echo "<div class='container'>";
            $msg = "<div class='alert alert-danger'>You can't access this page</div>";
            redirectHome($msg);
        }
        echo "</div>";
        echo "</div>";
    } elseif ($do == 'Delete') {
        echo "<h1 class='text-center'>Delete Comment</h1>";
        echo "<div class='container'>";
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        $count = countItem('c_id', 'comments', $comid);
        if ($count > 0) {
            $stmt = $conn->prepare("DELETE FROM comments Where c_id=:zID");
            $stmt->bindParam(":zID", $comid);
            $stmt->execute();
            $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted Successfully</div>';
            redirectHome($msg, "back");
        } else {
            echo "<div class='container'>";

            $error = '<div class="alert alert-danger">No Such ID</div>';
            redirectHome($error);
            echo "</div>";
        }
        echo '</div>';
    } elseif ($do == 'Approve') {
        echo "<h1 class='text-center'>Approve Comment</h1>";
        echo "<div class='container'>";
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        $count = countItem('c_id', 'comments', $comid);
        if ($count > 0) {
            $stmt = $conn->prepare("UPDATE comments SET status =1 Where c_id=?");

            $stmt->execute(array($comid));
            $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated Successfully</div>';
            redirectHome($msg, "back");
        } else {
            echo "<div class='container'>";

            $error = '<div class="alert alert-danger">No Such ID</div>';
            redirectHome($error);
            echo "</div>";
        }
        echo '</div>';
    }
    include $tpl . "footer.php";
} else {
    header('Location: index.php');
    exit();
}
