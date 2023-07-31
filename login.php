<?php
session_start(); // Start the session

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'scaina';
    $conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

    // Check connection
    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if username is set in the $_POST array
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM members WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Store the username in a session variable
            $_SESSION['username'] = $username;

            // Redirect to index.html
            header('Location: index1.php');
            exit;
        } else {
            $error = "Invalid Username or Password";
        }

        $stmt->close();
    } else {
        $error = "Username not available";
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCANIA</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="form-wrapper sign-up">
            <form action="reg.php" method="post">
                <h2>Sign Up</h2>
                <div class="input-group">
                    <input type="text" name="username" required>
                    <label for="username">Username</label>
                </div>
                <div class="input-group">
                    <input type="email" name="email" required>
                    <label for="email">Email</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" required>
                    <label for="password">Password</label>
                </div>
                <button type="submit" class="btn">Sign Up</button>
                <div class="sign-link">
                    <p>Already have an account? <a href="#" class="signIn-link">Sign In</a></p>
                </div>
            </form>
        </div>
        <div class="form-wrapper sign-in">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h2>Login</h2>
                <div class="input-group">
                    <input type="text" name="username" required>
                    <label for="">Username</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" required>
                    <label for="">Password</label>
                </div>
                <button type="submit" class="btn">Login</button>
                <div class="sign-link">
                    <p>Don't have an account? <a href="#" class="signUp-link">Sign Up</a></p>
                </div>
                <?php if ($error != "") : ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script>
        const wrapper = document.querySelector('.wrapper');
        const signUpLink = document.querySelector('.signUp-link');
        const signInLink = document.querySelector('.signIn-link');

        signUpLink.addEventListener('click', () => {
            wrapper.classList.add('animate-signIn');
            wrapper.classList.remove('animate-signUp');
        });

        signInLink.addEventListener('click', () => {
            wrapper.classList.add('animate-signUp');
            wrapper.classList.remove('animate-signIn');
        });
    </script>
</body>

</html>
