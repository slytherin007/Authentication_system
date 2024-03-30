<?php
session_start();
include 'dbconnect.php';

$error_msg = '';
// Check if the login form is submitted
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pwd = $_POST['password'];
    $sql = "SELECT * FROM register WHERE email='$email'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    // password_verify: verifies if the password matches the hashed password stored in the database

        if (password_verify($pwd, $row['password'])) {
            $_SESSION['email'] = $email;
            header('Location: menu.php?success=Login Successful');
            exit;
        } else {
            $error_msg = "Username or password is incorrect.";
        }
    } else {
        $error_msg = "Username or password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <section>
        <div>
            <h3>Login Panel</h3>
            <div>
                <div>
                    <div id="div2">
                        <form action="" method="post">
                            <div>
                                <label>Email</label>
                                <input type="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div>
                                <label>Password</label>
                                <input type="password" name="password" placeholder="Enter your password" required>
                            </div>
                            <input type="submit" name="login" value="Login" class="btn btn-success mt-3">
                            <?php if (!empty($error_msg)): ?>
                                <p style="color: red;"><?php echo $error_msg; ?></p>
                            <?php endif; ?>
                        </form>
                        <a href="register.php">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
