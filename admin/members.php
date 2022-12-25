<?php

session_start();
$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
$pageTitle = "Members";
if (isset($_SESSION['Username'])) {

    include 'init.php';
    if ($do == 'Manage') {
        $query = '';
        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'AND RegStatus =0';
        }
        $rows = getAllFrom("*", "users", "where GroupID != 1", $query, "UserID", "DESC");
?>
        <h1 class="text-center">Manage Members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table manage-members text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Avatar</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Registered Date</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach ($rows as $row) {
                        echo  ' <tr>';
                        echo   ' <td>' . $row["UserID"] . '</td>';
                        if (!empty($row['avatar'])) {
                            echo   ' <td><img src="uploads\avatars\\' . $row["avatar"] . '" alt="" /></td>';
                        } else {
                            echo   ' <td><img src="..\user.jpg" alt="" /></td>';
                        }
                        echo   ' <td>' . $row["Username"] . '</td>';
                        echo   ' <td>' . $row["Email"] . '</td>';
                        echo   ' <td>' . $row["FullName"] . '</td>';
                        echo   ' <td>' . $row["Date"] . '</td>';
                        echo     "<td>
                        <a href='?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>
                        <a href='?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";
                        if ($row['RegStatus'] == 0) {
                            echo "<a href='?do=Activate&userid=" . $row['UserID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Activate</a>";
                        }
                        echo     "</td>";
                        echo  '</tr>';
                    }
                    ?>
                </table>
            </div>
            <a href="?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>New Member</a>
        </div>
    <?php } elseif ($do == 'Add') { ?>
        <h1 class="text-center">Add New Member</h1>
        <div class="container">
            <form action="?do=Insert" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <!--- Start Username Edit --->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-laber">Username</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="Username">
                    </div>
                </div>
                <!--- End Username Edit --->
                <!--- Start Password Edit --->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-laber">Password</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="password" name="password" class="password form-control" autocomplete="new-password" required="required" placeholder="Password">
                        <!---<i class="show-pass fa fa-eye fa-2x"></i>-->
                    </div>
                </div>
                <!--- End Password Edit --->
                <!--- Start Email Edit --->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-laber">Email</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="email" name="email" class="form-control" required="required" placeholder="E-mail">
                    </div>
                </div>
                <!--- End Email Edit --->
                <!--- Start Full Name Edit --->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-laber">Full Name</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="text" name="full" class="form-control" required="required" placeholder="Full Name">
                    </div>
                </div>
                <!--- End Full Name Edit --->
                <!--- Start Full Name Edit --->
                <div class="form-group form-group-lg">
                    <label for="" class="col-sm-2 control-laber">Avatar</label>
                    <div class="col-sm-10 col-md-4">
                        <input type="file" name="avatar" class="form-control">
                    </div>
                </div>
                <!--- End Full Name Edit --->
                <!--- Start submit  --->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add" class="btn btn-primary">
                    </div>
                </div>
                <!--- End submit  --->
            </form>
        </div>

        <?php
    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Insert Member</h1>";
            echo "<div class='container'>";
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['full'];
            $pass = $_POST['password'];
            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];
            $allowedExtensions = array('jpg', 'png', 'jpeg', 'jfif', 'pjp');
            $tmp = explode('.', strtolower($avatarName));
            $avatarExtension = end($tmp);
            $hashedPass = sha1($pass);
            $formErrors = array();
            /*  if (strlen($user) < 6) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Username</strong> can\'t be less than 6 characters</div>';
            } */
            if (empty($user)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Username</strong> can\'t be empty</div>';
            }
            if (strlen($pass) < 6) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Password</strong> can\'t be less than 6 characters</div>';
            }
            if (empty($pass)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Password</strong> can\'t be empty</div>';
            }
            if (empty($email)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Email</strong> can\'t be empty</div>';
            }
            if (empty($name)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Full Name</strong> can\'t be empty</div>';
            }
            if (!empty($avatarName) && !in_array($avatarExtension, $allowedExtensions)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Extension</strong> not allowed</div>';
            }
            if ($avatarSize > 419304) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Image Size</strong> is larger than 4 MB</div>';
            }
            foreach ($formErrors as $error) {
                redirectHome($error, 'back');
            }
            if (empty($formErrors)) {
                $avatar = rand(0, 100000) . '_' . $avatarName;
                $uploads = 'uploads\avatars';
                if (!is_dir($uploads)) {
                    mkdir($uploads, 0557, true);
                } else {
                    move_uploaded_file($avatarTmp, $uploads . '\\' . $avatar);
                }
                $check = countItem("Username", "users", $user);
                if ($check == 1) {
                    $error = '<div class="alert alert-danger">Username already <strong>exists</strong></div>';
                    redirectHome($error, 'back');
                } else {
                    $stmt = $conn->prepare("INSERT INTO users (Username, Password , Email , FullName ,RegStatus ,Date, avatar ) values(:zuser,:zpass,:zemail,:zname,1,now(),:zavatar)");
                    $stmt->execute(array(
                        'zuser' => $user,
                        'zpass' => $hashedPass,
                        'zemail' => $email,
                        'zname' => $name,
                        'zavatar' => $avatar
                    ));
                    echo "<div class='container'>";

                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Added  Successfully</div>';
                    redirectHome($msg, "?do=Manage");
                    echo "</div>";
                }
            }
        } else {
            echo "<div class='container'>";
            $error = '<div class="alert alert-danger"> You Cannot Access This Page Directly</div>';

            redirectHome($msg);
            echo "</div>";
        }
        echo "</div>";
    } elseif ($do == 'Edit') {
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stmt = $conn->prepare("SELECT * FROM users WHERE UserID=? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) { ?>
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form action="?do=Update" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <!--- Start Username Edit --->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-laber">Username</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="username" class="form-control" value="<?php echo $row['Username']; ?>" autocomplete="off" required="required">
                        </div>
                    </div>
                    <!--- End Username Edit --->
                    <!--- Start Password Edit --->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-laber">Password</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>">
                            <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Change Your Password">
                        </div>
                    </div>
                    <!--- End Password Edit --->
                    <!--- Start Email Edit --->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-laber">Email</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="email" name="email" class="form-control" value="<?php echo $row['Email']; ?>" required="required">
                        </div>
                    </div>
                    <!--- End Email Edit --->
                    <!--- Start Full Name Edit --->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-laber">Full Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text" name="full" class="form-control" value="<?php echo $row['FullName']; ?>" required="required">
                        </div>
                    </div>
                    <!--- End Full Name Edit --->
                    <!--- Start Avatar Edit --->
                    <div class="form-group form-group-lg">
                        <label for="" class="col-sm-2 control-laber">Avatar</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="file" name="avatar" class="form-control">
                        </div>
                    </div>
                    <!--- End avatar Edit --->
                    <!--- Start submit  --->
                    <div class="form-group form-group-lg">
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
        echo "<h1 class='text-center'>Update Member</h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['userid'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['full'];
            $avatarName = $_FILES["avatar"]["name"];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];
            $allowedExtensions = ['jpg', 'png', 'jpeg', 'jfif', 'pjp'];
            $tmp = (explode('.', strtolower($avatarName)));
            $avatarExtension = end($tmp);
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
            $formErrors = array();
            if (empty($user)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Username</strong> can\'t be empty</div>';
            }
            if (empty($email)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Email</strong> can\'t be empty</div>';
            }
            if (empty($name)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Full Name</strong> can\'t be empty</div>';
            }
            if (!empty($avatarName) && !in_array($avatarExtension, $allowedExtensions)) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Extension</strong> not allowed</div>';
            }
            if ($avatarSize > 419304) {
                $formErrors[] = '<div class="alert alert-danger"><strong>Image Size</strong> is larger than 4 MB</div>';
            }
            foreach ($formErrors as $error) {
                redirectHome($error, 'back');
            }
            if (empty($formErrors)) {
                $avatar = rand(0, 100000) . '_' . $avatarName;
                $uploads = 'uploads\avatars';
                if (!is_dir($uploads)) {
                    mkdir($uploads, 0557, true);
                } else {
                    move_uploaded_file($avatarTmp, $uploads . '\\' . $avatar);
                }
                $stmt2 = $conn->prepare("SELECT * FROM users WHERE Username=? AND UserID !=?");
                $stmt2->execute(array($user, $id));
                $count = $stmt2->rowCount();
                if ($count == 1) {
                    echo "<div class='container'>";
                    $msg = "<div class='alert alert-danger'><strong>Username</strong> Already Exist</div>";
                    redirectHome($msg, 'back');
                } else {
                    $stmt = $conn->prepare("UPDATE users SET Username=?, Email=?, FullName=?, Password=?, avatar=? WHERE UserID=? LIMIT 1");
                    $stmt->execute(array($user, $email, $name, $pass, $avatar, $id));
                    echo "<div class='container'>";
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated Successfully</div>';
                    redirectHome($msg, 'back');
                    echo "</div>";
                }
            }
        } else {
            echo "<div class='container'>";
            $msg = "<div class='alert alert-danger'>You can't access this page</div>";
            redirectHome($msg);
        }
        echo "</div>";

        echo "</div>";
    } elseif ($do == 'Delete') {
        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $count = countItem('UserID', 'users', $userid);
        if ($count > 0) {
            $stmt = $conn->prepare("DELETE FROM users Where UserID=:zID");
            $stmt->bindParam("zID", $userid);
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
    } elseif ($do == 'Activate') {
        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $count = countItem('UserID', 'users', $userid);
        if ($count > 0) {
            $stmt = $conn->prepare("UPDATE users SET RegStatus =1 Where UserID=?");
            $stmt->execute(array($userid));
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
