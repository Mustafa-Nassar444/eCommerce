<?php
ob_start();
session_start();
$pageTitle = 'Login';
if (isset($_SESSION['user'])) {

    header('Location: index.php');
}
include 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $user = $_POST['username'];
        $pass = $_POST['pass'];
        $hashedPassword = sha1($pass);
        $stmt = $conn->prepare("SELECT UserID,Username, Password FROM users WHERE Username=? AND Password =?");
        $stmt->execute(array($user, $hashedPassword));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) {
            $_SESSION['user'] = $user;
            $_SESSION['uid'] = $row['UserID'];
            header('Location: index.php');
            exit();
        }
    } else {
        $formErrors = array();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $email = $_POST['email'];
        if (isset($email)) {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (filter_var($email, FILTER_VALIDATE_EMAIL) != true) {
                $formErrors[] = 'Email isn\'t valid';
            }
            if (empty($email)) {
                $formErrors[] = 'Email can\'t be empty';
            }
        }
        if (empty($username)) {
            $formErrors[] = 'Username can\'t be empty';
        }
        if (strlen($password) < 6) {
            $formErrors[] = 'Password can\'t be less than 6 characters';
        }
        if (empty($password)) {
            $formErrors[] = 'Password can\'t be empty';
        }

        if ($password !== $password2) {
            $formErrors[] = 'Passwords aren\'t match';
        }
        if (empty($formErrors)) {
            $hashedPassword = sha1($password);
            $check = countItem('Username', 'users', $username);
            if ($check == 1) {
                $formErrors[] = 'Username already exists';
            } else {
                $stmt = $conn->prepare("INSERT INTO users (Username, Password , Email,RegStatus ,Date ) values(:zuser,:zpass,:zemail,0,now())");
                $stmt->execute(array(
                    ':zuser' => $username,
                    ':zpass' => $hashedPassword,
                    ':zemail' => $email

                ));
                $successMsg = 'Register Completed';
            }
        }
    }
}
?>
<div class="container login-page">
    <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span></h1>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Username" required='required'>
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="pass" autocomplete="new-password" placeholder="Password" required='required'>
        </div>
        <input class="btn btn-primary btn-block" name="login" type="submit" value="Login">
    </form>
    <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Username" required='required'>
        </div>
        <div class="input-container">
            <input class="form-control" type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Password" required='required'>
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Confirm" required='required'>
        </div>
        <input class="btn btn-success btn-block" name="signup" type="submit" value="Signup">
    </form>
    <div class="the-errors text-center">
        <?php
        if (!empty($formErrors)) {
            foreach ($formErrors as $error) {
                echo '<div class="msg-error">' . $error . '</div>';
            }
        }
        if (isset($successMsg)) {
            echo '<div class="msg-success">' . $successMsg . '</div>';
        }
        ?>

    </div>
</div>

<?php include $tpl . 'footer.php';
ob_end_flush(); ?>