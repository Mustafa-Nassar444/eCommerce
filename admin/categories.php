<?php
ob_start();
session_start();
$pageTitle = 'Categories';
$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
$pageTitle = "Categories";
if (isset($_SESSION['Username'])) {

    include 'init.php';
    if ($do == 'Manage') {
        $sort = 'ASC';
        $sort_array = ['ASC', 'DESC'];
        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
            $sort = $_GET['sort'];
        }
        $cats = getAllFrom("*", "categories", "WHERE parent=0", "", "Ordering", $sort);
?>
        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-edit"></i> Manage Categories
                    <div class="option pull-right">
                        <i class="fa fa-sort"></i> Ordering: [
                        <a href="?sort=ASC" class="<?php if ($sort == 'ASC') echo 'active'; ?>">ASC</a> |
                        <a href="?sort=DESC" class="<?php if ($sort == 'DESC') echo 'active'; ?>">DESC</a> ]
                        <i class="fa fa-eye"></i> View: [
                        <span class="active" data-view="full">Full</span> |
                        <span data-view='classic'>Classic</span> ]
                    </div>
                </div>
                <div class="panel-body">
                    <?php
                    foreach ($cats as $cat) {
                        echo "<div class='cat'>";
                        echo "<div class='hidden-buttons'>";
                        echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i>Edit</a>";
                        echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i>Delete</a>";
                        echo "</div>";
                        echo "<h3>" . $cat['Name'] . "</h3>";
                        echo "<div class='full-view'>";
                        echo "<p>";
                        if ($cat['Description'] == '') {
                            echo " This Category has no Description";
                        } else {
                            echo $cat['Description'];
                        }
                        echo "</p>";
                        if ($cat['Visibility'] == 1) echo '<span class="visibility"><i class="fa fa-eye"></i>Hidden</span>';
                        if ($cat['Allow_Comment'] == 1) echo '<span class="comment"><i class="fa fa-close"></i>Comment Disabled</span>';
                        if ($cat['Allow_Ads'] == 1) echo '<span class="advs"><i class="fa fa-close"></i>Ads Disabled</span>';

                        $childCats = getAllFrom("*", "categories", "WHERE parent={$cat['ID']}", "", "ID", "ASC");

                        if (!empty($childCats)) {
                            echo "<h4 class='child-head'>Side Categories</h4>";
                            echo "<ul class='list-unstyled child-cats'>";
                            foreach ($childCats as $cate) {
                                echo '<li class="child-edit">
                                <a href="categories.php?do=Edit&catid=' . $cate['ID'] . '">' . $cate['Name'] . '</a>
                                <a href="categories.php?do=Delete&catid=' . $cate['ID'] . '" class="show-delete confirm"> Delete</a>
                                </li>';
                            }
                            echo "</ul>";
                        }
                        echo "</div>";

                        echo "<hr>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
            <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add Category</a>
        </div>
    <?php

    } elseif ($do == 'Add') {
    ?>
        <h1 class="text-center">Add New Category</h1>
        <div class="container">
            <form action="?do=Insert" class="form-horizontal" method="POST">
                <!--- Start Name Edit --->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Category Name">
                    </div>
                </div>
                <!--- End Name Edit --->
                <!--- Start Description Edit --->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="description" class="form-control" placeholder="Category Description">
                        <!---<i class="show-pass fa fa-eye fa-2x"></i>-->
                    </div>
                </div>
                <!--- End Description Edit --->
                <!--- Start Ordering Edit --->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Ordering</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="ordering" class="form-control" placeholder="Order of the category">
                    </div>
                </div>
                <!--- End Ordering Edit --->
                <!--- Start category type --->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Category Type</label>
                    <div class="col-sm-10 col-md-4">
                        <select name="parent" id="">
                            <option value="0">None</option>
                            <?php
                            $allCats = getAllFrom("*", "categories", "WHERE parent=0", "", "ID", "ASC");
                            foreach ($allCats as $cat) {
                                echo '<option value="' . $cat['ID'] . '">' . $cat['Name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!--- End category type --->
                <!--- Start Visibility Edit --->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Visibility</label>
                    <div class="col-sm-10 col-md-4">
                        <div>
                            <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                            <label for="vis-yes">Yes</label>
                        </div>
                        <div>
                            <input id="vis-no" type="radio" name="visibility" value="1" />
                            <label for="vis-no">No</label>
                        </div>
                    </div>
                </div>
                <!--- End Visibility Edit --->
                <!--- Start Allow_Comment Edit --->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Comments</label>
                    <div class="col-sm-10 col-md-4">
                        <div>
                            <input id="com-yes" type="radio" name="comments" value="0" checked />
                            <label for="com-yes">Yes</label>
                        </div>
                        <div>
                            <input id="com-no" type="radio" name="comments" value="1" />
                            <label for="com-no">No</label>
                        </div>
                    </div>
                </div>
                <!--- End Allow_Comment Edit --->
                <!--- Start Ads Edit --->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-label">Ads</label>
                    <div class="col-sm-10 col-md-4">
                        <div>
                            <input id="ad-yes" type="radio" name="ads" value="0" checked />
                            <label for="ad-yes">Yes</label>
                        </div>
                        <div>
                            <input id="ad-no" type="radio" name="ads" value="1" />
                            <label for="ad-no">No</label>
                        </div>
                    </div>
                </div>
                <!--- End Ads Edit --->
                <!--- Start submit  --->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Category" class="btn btn-primary">
                    </div>
                </div>
                <!--- End submit  --->
            </form>
        </div>

        <?php
    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container'>";
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $parent = $_POST['parent'];
            $order = $_POST['ordering'];
            $visible = $_POST['visibility'];
            $comments = $_POST['comments'];
            $ads = $_POST['ads'];
            if (!empty($name)) {
                $check = countItem("Name", "categories", $name);
                if ($check == 1) {
                    $error = '<div class="alert alert-danger">Category already <strong>exists</strong></div>';
                    redirectHome($error, 'back');
                } else {
                    $stmt = $conn->prepare("INSERT INTO categories (Name, Description , parent ,Ordering , Visibility ,Allow_Comment ,Allow_Ads) values(:zname,:zdesc,:zparent,:zorder,:zvis,:zcom,:zad)");
                    $stmt->execute(array(
                        'zname' => $name,
                        'zdesc' => $desc,
                        'zparent' => $parent,
                        'zorder' => $order,
                        'zvis' => $visible,
                        'zcom' => $comments,
                        'zad' => $ads,

                    ));
                    echo "<div class='container'>";

                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Added  Successfully</div>';
                    redirectHome($msg, "back");
                    echo "</div>";
                }
            } else {
                echo "<div class='container'>";

                $msg = '<div class="alert alert-danger"> Name is Required</div>';
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
    } elseif ($do == 'Edit') {
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        $stmt = $conn->prepare("SELECT * FROM categories WHERE ID=? LIMIT 1");
        $stmt->execute(array($catid));
        $cat = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) { ?>
            <h1 class="text-center">Edit Category</h1>
            <div class="container">
                <form action="?do=Update" class="form-horizontal" method="POST">
                    <input type="hidden" name="catid" value="<?php echo $catid ?>">
                    <!--- Start Name Edit --->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="name" class="form-control" required="required" placeholder="Category Name" value="<?php echo $cat['Name']; ?>">
                        </div>
                    </div>
                    <!--- End Name Edit --->
                    <!--- Start Description Edit --->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="description" class="form-control" placeholder="Category Description" value="<?php echo $cat['Description']; ?>">
                            <!---<i class="show-pass fa fa-eye fa-2x"></i>-->
                        </div>
                    </div>
                    <!--- End Description Edit --->
                    <!--- Start Ordering Edit --->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="ordering" class="form-control" placeholder="Order of the category" value="<?php echo $cat['Ordering']; ?>">
                        </div>
                    </div>
                    <!--- End Ordering Edit --->
                    <!--- Start category type --->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Category Type</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="parent" id="">
                                <option value="0">None</option>
                                <?php
                                $allCats = getAllFrom("*", "categories", "WHERE parent=0", "", "ID", "ASC");
                                foreach ($allCats as $cate) {
                                    echo '<option value="' . $cate['ID'] . '"';
                                    if ($cat['parent'] == $cate['ID']) echo 'selected';
                                    echo '>' . $cate['Name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!--- End category type --->
                    <!--- Start Visibility Edit --->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Visibility</label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['Visibility'] == 0) echo 'checked'; ?> />
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visibility'] == 1) echo 'checked'; ?> />
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!--- End Visibility Edit --->
                    <!--- Start Allow_Comment Edit --->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Comments</label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="com-yes" type="radio" name="comments" value="0" <?php if ($cat['Allow_Comment'] == 0) echo 'checked'; ?> />
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="comments" value="1" <?php if ($cat['Allow_Comment'] == 1) echo 'checked'; ?> />
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!--- End Allow_Comment Edit --->
                    <!--- Start Ads Edit --->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-label">Ads</label>
                        <div class="col-sm-10 col-md-4">
                            <div>
                                <input id="ad-yes" type="radio" name="ads" value="0" <?php if ($cat['Allow_Ads'] == 0) echo 'checked'; ?> />
                                <label for="ad-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ad-no" type="radio" name="ads" value="1" <?php if ($cat['Allow_Ads'] == 1) echo 'checked'; ?> />
                                <label for="ad-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!--- End Ads Edit --->
                    <!--- Start submit  --->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Edit Category" class="btn btn-primary">
                        </div>
                    </div>
                    <!--- End submit  --->
                </form>
            </div>

<?php } else {
            echo "<div class='container'>";
            $error = '<div class="alert alert-danger"> No Such ID</div>';
            redirectHome($error, 'categories.php');
            echo "</div>";
        }
    } elseif ($do == 'Update') {
        echo "<h1 class='text-center'>Update Categories</h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['catid'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $order = $_POST['ordering'];
            $parent = $_POST['parent'];
            $visible = $_POST['visibility'];
            $comments = $_POST['comments'];
            $ads = $_POST['ads'];
            if (!empty($name)) {
                $stmt = $conn->prepare("UPDATE categories SET Name=?, Description=?, Ordering=?,parent=?, Visibility=? ,Allow_Comment=?, Allow_Ads=? WHERE ID=? LIMIT 1");
                $stmt->execute(array($name, $desc, $order, $parent, $visible, $comments, $ads, $id));
                echo "<div class='container'>";
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated Successfully</div>';
                redirectHome($msg, 'back');
                echo "</div>";
            } else {
                echo "<div class='container'>";
                $msg = '<div class="alert alert-danger"> Name is Required</div>';
                redirectHome($msg, "back");
                echo "</div>";
            }
        } else {
            echo "<div class='container'>";
            $msg = "<div class='alert alert-danger'>You can't access this page</div>";
            redirectHome($msg);
        }
        echo "</div>";
        echo "</div>";
    } elseif ($do == 'Delete') {
        echo "<h1 class='text-center'>Delete Category</h1>";
        echo "<div class='container'>";
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        $count = countItem('ID', 'categories', $catid);
        if ($count > 0) {
            $stmt = $conn->prepare("DELETE FROM categories Where ID=:zID");
            $stmt->bindParam(":zID", $catid);
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
    }
    include $tpl . "footer.php";
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();
?>