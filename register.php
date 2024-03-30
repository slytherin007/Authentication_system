<?php
session_start();
include 'dbconnect.php';

$email_error = $pwd_error = $message_unsuccess = $message_success = '';

// Function to validate password
function validatePassword($password)
{
    // Validate length minimum 8 characters
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long.";
    }

    // Validate for at least one special character
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        return "Password must contain at least one special character.";
    }

    // Validate for at least one capital letter
    if (!preg_match('/[A-Z]/', $password)) {
        return "Password must contain at least one capital letter.";
    }

    // Validate for at least one number
    if (!preg_match('/[0-9]/', $password)) {
        return "Password must contain at least one number.";
    }

    return true;
}

if (isset($_POST['submit'])) {
    $f_name = $_POST['fname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    // Validate password
    $pwd_validation = validatePassword($password);
   
    if ($pwd_validation !== true) {
        $pwd_error = $pwd_validation;
    } 
    
    elseif ($password != $cpassword) {
        $pwd_error = "Passwords do not match.";
    } 
    
    else {
        //password_hash function for hashing the password to store in the database securely 
        $pass = password_hash($password, PASSWORD_BCRYPT);
        
        // Check if the email already exists
        $sql = "SELECT * FROM register WHERE email='$email'";
        $result = mysqli_query($con, $sql);                         
        if (mysqli_num_rows($result) > 0) {
            $email_error = "Email already exists. Please choose another email Id.";
        } 
        // If email is not already in database continue with registration process
        else {
            $c_pass = password_hash($cpassword, PASSWORD_BCRYPT);
            $insert_sql = "INSERT INTO register (f_name, email, password, c_password) VALUES ('$f_name', '$email', '$pass', '$c_pass')";
            if (mysqli_query($con, $insert_sql)) {
                $message_success = "Data Inserted Successfully.";
            } else {
                $message_unsuccess = "Data Insertion Unsuccessful.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <section>
        <div>
            <h3>Register Panel</h3>
            <div>
                <div>
                    <div>
                        <form action="" method="post">
                            <div>
                                <label>Full Name</label>
                                <input type="text" name="fname" placeholder="Enter your name" required>
                            </div>
                            <div>
                                <label>Email</label>
                                <input type="email" name="email" placeholder="Enter your email" required>
                                <?php echo "<p style='color:red'>$email_error</p>";?>
                            </div>
                            <div>
                                <label>Password</label>
                                <input type="password" name="password" placeholder="Enter your password" required>
                            </div>
                            <div>
                                <label>Confirm Password</label>
                                <input type="password" name="cpassword" placeholder="Confirm your password" required>
                                <?php echo "<p style='color:red'>$pwd_error</p>";?>

                            </div>
                            <input type="submit" name="submit" value="Register" class="btn btn-success mt-3" required>
                            <span class="text-red">
                                <?php echo $message_success;
                                echo $message_unsuccess;
                                ?>
                            </span>
                        </form>
                        <a href="login.php">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
