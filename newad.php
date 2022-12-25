<?php
session_start();
$pageTitle = 'Create New Item';
include 'init.php';
if (isset($_SESSION['user'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $formErrors = [];
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country = $_POST['country'];
        $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
        $imgName = $_FILES['image']['name'];
        $imgSize = $_FILES['image']['size'];
        $imgTmp = $_FILES['image']['tmp_name'];
        $imgType = $_FILES['image']['type'];
        $allowedExtensions = ['jpg', 'png', 'jpeg', 'jfif', 'pjp'];
        $tmp = (explode('.', strtolower($imgName)));
        $imgExtension = end($tmp);
        $tags = str_replace(' ', '', $_POST['tags']);
        if (strlen($name) < 4) {
            $formErrors[] = 'Item Name Must Be At Least 4 Characters';
        }
        if (!empty($imgName) && !in_array($imgExtension, $allowedExtensions)) {
            $formErrors[] = '<div class="alert alert-danger"><strong>Extension</strong> not allowed</div>';
        }
        if ($imgSize > 419304) {
            $formErrors[] = '<div class="alert alert-danger"><strong>Image Size</strong> is larger than 4 MB</div>';
        }
        if (empty($formErrors)) {
            $img = rand(0, 100000) . '_' . $imgName;
            $uploads = 'uploads\imgs';
            if (!is_dir($uploads)) {
                mkdir($uploads, 0557, true);
            } else {
                move_uploaded_file($imgTmp, $uploads . '\\' . $img);
            }
            $stmt = $conn->prepare("INSERT INTO items (Name, Description , Price , Add_Date, Image ,Country_Made ,Status,Cat_ID,Member_ID,Tags ) values(:zname,:zdesc,:zprice,now(),:zimg,:zcountry,:zstat,:zcid,:zuid,:ztags)");
            $stmt->execute(array(
                ':zname' => $name,
                ':zdesc' => $desc,
                ':zprice' => $price,
                ':zimg' => $img,
                ':zcountry' => $country,
                ':zstat' => $status,
                ':zcid' => $category,
                ':zuid' => $_SESSION['uid'],
                ':ztags' => $tags
            ));
            if ($stmt) {
                $successMsg = 'Item Added Successfully';
            }
        }
    }
?>
    <h1 class="text-center"><?php echo $pageTitle ?></h1>
    <div class="information block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $pageTitle ?></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" class="form-horizontal main-form" method="POST" enctype="multipart/form-data">
                                <!--- Start Name Edit --->
                                <div class="form-group form-group-lg">
                                    <label for="" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="name" class="form-control live" data-class=".live-title" required="required" autocomplete="off" placeholder="Item Name">
                                    </div>
                                </div>
                                <!--- End Name--->
                                <!--- Description--->
                                <div class="form-group form-group-lg">
                                    <label for="" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="description" class="form-control live" data-class=".live-desc" required="required" autocomplete="off" placeholder="Description of item">
                                    </div>
                                </div>
                                <!--- End Description--->
                                <!--- Price--->
                                <div class="form-group form-group-lg">
                                    <label for="" class="col-sm-3 control-label">Price</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="price" class="form-control live" data-class=".live-price" required="required" autocomplete="off" placeholder="Price of item">
                                    </div>
                                </div>
                                <!--- End Price--->
                                <!--- Country--->
                                <div class="form-group form-group-lg">
                                    <label for="" class="col-sm-3 control-label">Country</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="country" class="form-control" required="required" autocomplete="off" placeholder="Country of made">
                                    </div>
                                </div>
                                <!--- End Country--->
                                <!--- Status--->
                                <div class="form-group form-group-lg">
                                    <label for="" class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-10 col-md-9">
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
                                    <label for="" class="col-sm-3 control-label">Category</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="category" id="">
                                            <option value="0">...</option>
                                            <?php
                                            foreach (getAllFrom('*', 'categories', 'WHERE Allow_Ads =0', 'AND Visibility=0', 'ID') as $cat) {
                                                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!--- End Country--->
                                <div class="form-group form-group-lg">
                                    <label for="" class="col-sm-3 control-label">Tags</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="tags" class="form-control" placeholder="Separate with Comma ,">
                                    </div>
                                </div>
                                <!--- End Country--->
                                <!--- Start Image--->
                                <div class="form-group form-group-lg">
                                    <label for="" class="col-sm-3 control-label">Image</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="file" name="image" class="form-control" required>
                                    </div>
                                </div>
                                <!--- End Image--->
                                <div class="form-group form-group-lg">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <input type="submit" value="Add Items" class="btn btn-primary btn-sm">
                                    </div>
                                </div>
                                <!--- End submit  --->
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail item-box live-preview">
                                <span class="price-tag">
                                    $<span class="live-price">0</span>
                                </span>
                                <img class="image-responsive" src="d1.png" alt="" />
                                <div class="caption">
                                    <h3 class="live-title">Title</h3>
                                    <p class="live-desc">Description</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?PHP if (!empty($formErrors)) {
                        echo '<div class="alert alert-danger">' . $formErrors[0] . '</div>';
                        if (isset($successMsg)) {
                            echo '<div class="alert alert-success">' . $successMsg . '</div>';
                        }
                    } ?>
                </div>
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