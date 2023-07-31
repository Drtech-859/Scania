<?php
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

    // Check if email already exists
    $email = $_POST['email'];
    $check_email_query = "SELECT * FROM members WHERE email = ?";
    $stmt = $conn->prepare($check_email_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script> window.alert('Email Already Exists');
              setTimeout(function() {window.location.href = 'index1.php';}, 1000);</script>";
        exit;
    }

    $sql = "INSERT INTO `members` (`username`, `email`, `password`)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $_POST['username'], $_POST['email'], $_POST['password']);

    if ($stmt->execute()) {
        header('Location: login.php');
        exit;
    } else {
        echo "<script> window.alert('Registration failed');
              setTimeout(function() {window.location.href = 'login.html';}, 1000);</script>";
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
