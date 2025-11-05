
<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["SignupBtn"])) {
        $errors = [];

        $min_lenght = 6;
        $max_lenght = 20;


        if (empty($_POST['fullname'])) {
            $errors['fullname'] = "Name cannot be empty";
        } else {
            $name = validate_input($_POST["fullname"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $errors['fullname'] = "Only letters and white space allowed";
            } else {
                $name = validate_input($_POST['fullname']);
            }
        }

        if (empty($_POST['email'])) {
            $errors['email'] = "Email cannot be empty";
        } else {
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Invalid email syntax";
            } else {
                $email = validate_input($_POST['email']);
            }
        }

        if (empty($_POST['password'])) {
            $errors['password'] = "Password cannot be empty";
        } else {
            if (strlen($_POST['password']) < $min_lenght) {
                $errors['password'] = "Password must be 6-20 char";
            } elseif (strlen($_POST['password']) > $max_lenght) {
                $errors['password'] = "Password must be 6-20 chars";
            } else {
                $password = validate_input($_POST['password']);
                $hashed_Password = password_hash($password, PASSWORD_DEFAULT);
            }
        if (empty($_POST['confirmPassword'])) {
         $errors['confirmPassword'] = "Please confirm your password";
         } else {
         $confirm_password = validate_input($_POST['confirmPassword']);
         }

    // Only compare if there are no password errors yet
         if (empty($errors['password'])) {
            if ($password !== $confirm_password) {
            $errors['confirmPassword'] = "Passwords do not match";
            }
        }
    }
        $verify_email = $conn->prepare('SELECT * FROM users WHERE email = ?');
        $verify_email->execute([$email]);

        if ($verify_email->rowCount() > 0) {
            $errors['email_exist'] = "Please use another email,it already exists";
        } else {
            $create_user = $conn->prepare('INSERT INTO users (name, email,password) VALUES(?,?,?)');
            $success = $create_user->execute([$name, $email, $hashed_Password]);

            if ($success) {
                $message[] = "user created successfully";
            } else {
                $errors["error_creating"] = "An error occur please try again";
            }
        }
        if (!empty($errors)) {
            $query = http_build_query($errors); //error creating=
            header("Location: sign-up.php?$query");
            exit();
        } else {
            $query = http_build_query($message);
            header("Location: Login.html?$query");
            exit();
        }
    }

    if (isset($_POST['LoginBtn'])) {
        $error = [];

        $min_lenght = 6;
        $max_lenght = 20;

        if (empty($_POST['email'])) {
            $error['email'] = 'Email cannot be empty';
        } else {
            if (!filter_var(validate_input($_POST['email']), FILTER_VALIDATE_EMAIL)) {
                $error['email'] = 'PLease enter a valid type of email';
            } else {
                $email = validate_input($_POST['email']);
            }
        }

        if (empty($_POST['password'])) {
            $error['password'] = 'Password cannot be empty';
        } else {
            if (strlen($_POST['password']) < $min_lenght) {
                $error['password'] = "Password must be 6-20 chars";
            } elseif (strlen($_POST["password"]) > $max_lenght) {
                $error['password'] = "Password must be 6-20 chars";
            } else {
                $password = validate_input($_POST['password']);
            }
        }

        if (empty($error)) {
            $check_email = $conn->prepare('SELECT * FROM users WHERE email = ?');
            $check_email->execute([$email]);
                    } else {
                        $error['password'] = "Invalid credentials";
                    }
                }
            } else {
                $error['email'] = "The entered email does not exist";
            }
    
        if (!empty($error)) {
            $query = http_build_query($error);
            header("Location: dashboard.html?$query");
            exit();
        }
    
function validate_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>