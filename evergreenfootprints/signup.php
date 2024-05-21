<?php
$showAlert = false;
$showError = false;
$errorMessage = "";

include 'partials/_dbconnect.php';
// Reset alert flags and error message
$showAlert = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Check if any field is empty
    if (empty($username) || empty($password) || empty($email)) {
        $errorMessage = "All fields are required.";
        $showError = true;
    } else {
        // Check if username or email already exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `username` = :username OR `email` = :email");
        $stmt_check->bindParam(':username', $username);
        $stmt_check->bindParam(':email', $email);
        $stmt_check->execute();
        $count = $stmt_check->fetchColumn();

        if ($count > 0) {
            // User already exists
            $errorMessage = "User with the same username or email already exists.";
            $showError = true;
        } else {
            // User does not exist, proceed with insertion
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL statement
            $stmt = $pdo->prepare("INSERT INTO `users` (`username`, `password`, `email`) VALUES (:username, :password, :email)");

            // Bind parameters
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password); // Use hashed password
            $stmt->bindParam(':email', $email);

            // Execute the query
            if ($stmt->execute()) {
                $showAlert = true;
            }
        }
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Registration Page</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background-image: url(https://cdna.artstation.com/p/assets/images/images/025/102/490/large/jorge-jacinto-wisp-red.jpg?1584627212);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
        }

        .alert-container {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: 450px;
        }

        .alert {
            position: relative;
            border-radius: 8px;
        }

        .alert-close {
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
        }

        #content_container,
        .login-container {
            border-radius: 8px;
            padding: 20px;
            width: 300px;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
        }

        #email,
        #password,
        #username,
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #0c0808;
            border-radius: 4px;
        }

        #button_container {
            margin-top: 20px;
        }

        #nep{
            background-color: #226A80;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            width: 100px;
            margin-right: 10px;
            transition: background 0.3s;
        }

        button:hover {
            background: #0C4F60;
        }

        .heading {
            color: black;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #000;
        }

        .form-text {
            color: #000;
            font-size: small;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="alert-container">
        <?php
        if ($showAlert) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="alert-close" onclick="this.parentElement.style.display=\'none\';">&times;</span>
                    <strong>Success!</strong> Your account is now created and you can login.
                </div>';
            $showAlert = false;
        } elseif ($showError) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <span class="alert-close" onclick="this.parentElement.style.display=\'none\';">&times;</span>
        <strong>Error!</strong> ' . $errorMessage . '
    </div>';
            $showError = false;
            $errorMessage = "";
        }
        ?>
    </div>
    <div id="content_container">
        <h1 class="heading">"WE PLEDGE THE GREEN"</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            
            <button id="nep" type="submit" class="btn btn-primary" >SignUp</button>
        </form>
         Existing User?<a href="/evergreenfootprints/login.php">Login</a>
    </div>

   

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>