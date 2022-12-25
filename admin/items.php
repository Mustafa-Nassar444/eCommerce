<?php
ob_start();
session_start();
$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
$pageTitle = "Items";
if (isset($_SESSION['Username'])) {

    include 'init.php';
    if ($do == 'Manage') {
        $query = '';
        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'WHERE Approve =0';
        }
        $stmt = $conn->prepare("SELECT items.*,categories.Name AS Category ,users.Username AS Member from items
        INNER JOIN categories ON categories.ID=items.Cat_ID
        INNER JOIN users ON users.UserID=items.Member_ID $query
        ORDER BY Item_ID Desc");
        $stmt->execute();
        $items = $stmt->fetchAll();

?>
        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Country Made</td>
                        <td>Adding Date</td>
                        <td>Status</td>
                        <td>Member</td>
                        <td>Category</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach ($items as $item) {
                        echo  ' <tr>';
                        echo   ' <td>' . $item["Item_ID"] . '</td>';
                        echo   ' <td>' . $item["Name"] . '</td>';
                        echo   ' <td>' . $item["Description"] . '</td>';
                        echo   ' <td>' . $item["Price"] . '</td>';
                        echo   ' <td>' . $item["Country_Made"] . '</td>';
                        echo   ' <td>' . $item["Add_Date"] . '</td>';
                        echo   ' <td>' . $item["Status"] . '</td>';
                        echo   ' <td>' . $item["Member"] . '</td>';
                        echo   ' <td>' . $item["Category"] . '</td>';
                        echo     "<td>
                        <a href='items.php?do=Edit&itemid=" . $item['Item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                        <a href='items.php?do=Delete&itemid=" . $item['Item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";

                        if ($item['Approve'] == 0) {
                            echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                        }
                        echo     "</td>";


                        echo  '</tr>';
                    }
                    ?>
                </table>
            </div>
            <a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>New Item</a>

        </div>

    <?php
    } elseif ($do == 'Add') {
    ?>
        <h1 class="text-center">Add New Item</h1>
        <div class="container">
            <form action="?do=Insert" class="form-horizontal" method="POST">
                <!--- Start Name Edit --->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="name" class="form-control" required="required" autocomplete="off" placeholder="Item Name">
                    </div>
                </div>
                <!--- End Name--->
                <!--- Description--->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="description" class="form-control" required="required" autocomplete="off" placeholder="Description of item">
                    </div>
                </div>
                <!--- End Description--->
                <!--- Price--->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="price" class="form-control" required="required" autocomplete="off" placeholder="Price of item">
                    </div>
                </div>
                <!--- End Price--->
                <!--- Country--->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="country" class="form-control" required="required" autocomplete="off" placeholder="Country of made">
                    </div>
                </div>
                <!--- End Country--->
                <!--- Status--->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-4">
                        <select name="status" id="">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Very Old</option>
                        </select>
                    </div>
                </div>
                <!--- End Status--->
                <!--- Members--->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Members</label>
                    <div class="col-sm-10 col-md-4">
                        <select name="members" id="">
                            <option value="0">...</option>
                            <?php
                            /*       $stmt = $conn->prepare("SELECT * FROM users");
                            $stmt->execute();
                            $users = $stmt->fetchAll(); */
                            $users = getAllFrom("*", 'users', "", "", "UserID", "ASC");
                            foreach ($users as $user) {
                                echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!--- End Members--->
                <!--- Members--->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-4">
                        <select name="category" id="">
                            <option value="0">...</option>
                            <?php
                            /*   $stmt = $conn->prepare("SELECT * FROM categories");
                            $stmt->execute();
                            $cats = $stmt->fetchAll(); */
                            $cats = getAllFrom("*", 'categories', "WHERE parent=0", "", "ID");
                            foreach ($cats as $cat) {
                                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                $sidecats = getAllFrom("*", "categories", "WHERE parent = {$cat['ID']} ", "", "ID");
                                foreach ($sidecats as $scat) {
                                    echo "<option value='" . $scat['ID'] . "'>---" . $scat['Name'] . "</option>";
                                }
                            }

                            ?>
                        </select>
                    </div>
                </div>
                <!--- End Members--->
                <!--- Rating--->
                <!--- <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Rating</label>
                    <div class="col-sm-10 col-md-4">
                        <select class="form-control" name="status" id="">
                            <option value="0">...</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div> --->
                <!--- End Rating--->
                <!--- Country--->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="tags" class="form-control" placeholder="Separate with Comma ,">
                    </div>
                </div>
                <!--- End Country--->
                <!--- Start submit  --->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Items" class="btn btn-primary btn-sm">
                    </div>
                </div>
                <!--- End submit  --->
            </form>
        </div>

        <?php
    } elseif ($do == 'Insert') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Insert Item</h1>";
            echo "<div class='container'>";
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $uid = $_POST['members'];
            $cid = $_POST['category'];
            $tags = $_POST['tags'];
            $formErrors = array();
            /*  if (strlen($user) < 6) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Username</strong> can\'t be less than 6 characters</div>';
            } */
            if (empty($name)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Name</strong> can\'t be empty</div>';
            }
            if (empty($desc)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Description</strong> can\'t be empty</div>';
            }
            if (empty($price)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Price</strong> can\'t be empty</div>';
            }
            if (empty($country)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Country</strong> can\'t be empty</div>';
            }
            if ($status == 0) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Status</strong> Must be selected</div>';
            }
            if ($uid == 0) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Member</strong> Must be selected</div>';
            }
            if ($cid == 0) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Category</strong> Must be selected</div>';
            }
            foreach ($formErrors as $error) {
                echo  $error;
            }
            if (empty($formErrors)) {

                $stmt = $conn->prepare("INSERT INTO items (Name, Description , Price , Add_Date ,Country_Made ,Status,Cat_ID,Member_ID,Tags ) values(:zname,:zdesc,:zprice,now(),:zcountry,:zstat,:zcid,:zuid,:ztags)");
                $stmt->execute(array(
                    'zname' => $name,
                    'zdesc' => $desc,
                    'zprice' => $price,
                    'zcountry' => $country,
                    'zstat' => $status,
                    'zcid' => $cid,
                    'zuid' => $uid,
                    'ztags' => $tags
                ));
                echo "<div class='container'>";

                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Added  Successfully</div>';
                redirectHome($msg, "?do=Manage");
                echo "</div>";
            }
        } else {
            echo "<div class='container'>";
            $error = '<div class="alert alert-danger"> You Cannot Access This Page Directly</div>';

            redirectHome($msg);
            echo "</div>";
        }
        echo "</div>";
    } elseif ($do == 'Edit') {
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $stmt = $conn->prepare("SELECT * FROM items WHERE Item_ID=?");
        $stmt->execute(array($itemid));
        $item = $stmt->fetch();

        $count = $stmt->rowCount();
        if ($count > 0) { ?>
            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form action="?do=Update" class="form-horizontal" method="POST">
                    <input type="hidden" name="itemid" value="<?php echo $itemid; ?>">

                    <!--- Start Name Edit --->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="name" class="form-control" required="required" value="<?php echo $item['Name']; ?>">
                        </div>
                    </div>
                    <!--- End Name--->
                    <!--- Description--->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" class="form-control" required="required" value="<?php echo $item['Description']; ?>">
                        </div>
                    </div>
                    <!--- End Description--->
                    <!--- Price--->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="price" class="form-control" required="required" value="<?php echo $item['Price']; ?>">
                        </div>
                    </div>
                    <!--- End Price--->
                    <!--- Country--->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="country" class="form-control" required="required" value="<?php echo $item['Country_Made']; ?>">
                        </div>
                    </div>
                    <!--- End Country--->
                    <!--- Status--->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="status" id="">
                                <option value="1" <?php if ($item['Status'] == 1) echo 'selected'; ?>>New</option>
                                <option value="2" <?php if ($item['Status'] == 2) echo 'selected'; ?>>Like New</option>
                                <option value="3" <?php if ($item['Status'] == 3) echo 'selected'; ?>>Used</option>
                                <option value="4" <?php if ($item['Status'] == 4) echo 'selected'; ?>>Very Old</option>
                            </select>
                        </div>
                    </div>
                    <!--- End Status--->
                    <!--- Members--->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Members</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="members" id="">
                                <?php
                                $users = getAllFrom("*", 'users', "", "", "UserID", "ASC");
                                foreach ($users as $user) {
                                    echo "<option value='" . $user['UserID'] . "'";
                                    if ($item['Member_ID'] == $user['UserID']) echo 'selected';
                                    echo ">" . $user['Username'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!--- End Members--->
                    <!--- Members--->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="category" id="">
                                <?php
                                $cats = getAllFrom("*", 'categories', "", "", "ID", "ASC");
                                foreach ($cats as $cat) {
                                    echo "<option value='" . $cat['ID'] . "'";

                                    if ($item['Cat_ID'] == $cat['ID']) echo 'selected';
                                    echo ">" . $cat['Name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!--- End Members--->
                    <!--- Rating--->
                    <!--- <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Rating</label>
                    <div class="col-sm-10 col-md-4">
                        <select class="form-control" name="status" id="">
                            <option value="0">...</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div> --->
                    <!--- End Rating--->
                    <!--- Country--->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="tags" class="form-control" placeholder="Separate with Comma ," value="<?php echo $item['Tags'] ?>">
                        </div>
                    </div>
                    <!--- End Country--->
                    <!--- Start submit  --->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save Item" class="btn btn-primary btn-sm">
                        </div>
                    </div>
                    <!--- End submit  --->
                </form>
                <?php
                $stmt = $conn->prepare("SELECT comments.* ,users.Username AS Member from comments
                INNER JOIN users ON users.UserID=comments.Member_ID
                WHERE Item_ID=?");
                $stmt->execute(array($itemid));
                $rows = $stmt->fetchAll();
                if (!empty($rows)) {

                ?>
                    <h1 class="text-center">Manage [<?php echo $item['Name'] ?>] Comments</h1>
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>Comment</td>
                                <td>Member</td>
                                <td>Added Date</td>
                                <td>Control</td>
                            </tr>
                            <?php
                            foreach ($rows as $row) {
                                echo  ' <tr>';
                                echo   ' <td>' . $row["comment"] . '</td>';
                                echo   ' <td>' . $row["Member"] . '</td>';
                                echo   ' <td>' . $row["comment_date"] . '</td>';
                                echo     "<td>
                        <a href='comments.php?do=Edit&comid=" . $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                        <a href='comments.php?do=Delete&comid=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";

                                if ($row['status'] == 0) {
                                    echo "<a href='comments.php?do=Approve&comid=" . $row['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                                }
                                echo     "</td>";


                                echo  '</tr>';
                            }
                            ?>
                        </table>
                        <!---  <a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>New Member</a> --->

                    </div>
                <?php } ?>
            </div>

<?php } else {
            echo "<div class='container'>";

            $error = '<div class="alert alert-danger"> No Such ID</div>';
            redirectHome($error);
            echo "</div>";
        }
    } elseif ($do == 'Update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Item</h1>";
            echo "<div class='container'>";
            $id = $_POST['itemid'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $uid = $_POST['members'];
            $cid = $_POST['category'];
            $tags = $_POST['tags'];
            $formErrors = array();
            /*  if (strlen($user) < 6) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Username</strong> can\'t be less than 6 characters</div>';
            } */
            if (empty($name)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Name</strong> can\'t be empty</div>';
            }
            if (empty($desc)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Description</strong> can\'t be empty</div>';
            }
            if (empty($price)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Price</strong> can\'t be empty</div>';
            }
            if (empty($country)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Country</strong> can\'t be empty</div>';
            }

            foreach ($formErrors as $error) {
                echo  $error;
            }
            if (empty($formErrors)) {

                $stmt = $conn->prepare("UPDATE items SET Name=?, Description =?, Price =? ,Country_Made=? ,Status=?,Cat_ID=?,Member_ID=? ,Tags=? WHERE Item_ID=?");
                $stmt->execute(array($name, $desc, $price, $country, $status, $cid, $uid, $tags, $id));
                echo "<div class='container'>";

                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated  Successfully</div>';
                redirectHome($msg, "back");
                echo "</div>";
            }
        } else {
            echo "<div class='container'>";
            $error = '<div class="alert alert-danger"> You Cannot Access This Page Directly</div>';

            redirectHome($msg);
            echo "</div>";
        }
        echo "</div>";
    } elseif ($do == 'Delete') {
        echo "<h1 class='text-center'>Delete Item</h1>";
        echo "<div class='container'>";
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $count = countItem('Item_ID', 'items', $itemid);
        if ($count > 0) {
            $stmt = $conn->prepare("DELETE FROM items Where Item_ID=:zID");
            $stmt->bindParam(":zID", $itemid);
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
        echo "<h1 class='text-center'>Approve Item</h1>";
        echo "<div class='container'>";
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $count = countItem('Item_ID', 'items', $itemid);
        if ($count > 0) {
            $stmt = $conn->prepare("UPDATE items SET Approve =1 Where Item_ID=?");

            $stmt->execute(array($itemid));
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
ob_end_flush();
