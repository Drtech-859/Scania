<!DOCTYPE html>
<html>
<head>
   <link rel="stylesheet" href="navbar.css">
   <link rel="stylesheet" href="index.css">
  <style>
    .card {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 70%;
    max-width: 600px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    padding: 32px;
    overflow: hidden;
    border-radius: 10px;
    transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
    margin: 10% auto;
    }
    .content {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 20px;
    color: black;
    transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
    }

    .content .heading {
    font-weight: 700;
    font-size: 32px;
    }

    .content .para {
    line-height: 1.5;
    font-size: 24px;
    }
    .card::before {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(-45deg, #f89b29 0%, #ff0f7b 100% );
    z-index: -1;
    transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
    }
    .card:hover::before {
    height: 100%;
    }
    .card:hover {
    box-shadow: none;
    }
  </style>
</head>
<body>

<header>
    <div class="logo">SCANIA</div>
    <div class="hamburger">
      <div class="line"></div>
      <div class="line"></div>
      <div class="line"></div>
    </div>
    <nav class="nav-bar">
      <ul>
        <li>
          <a href="index1.php" class="active">Home</a>
        </li>
      </ul>
    </nav>
  </header>

<?php
session_start(); // Start the session
if (isset($_SESSION['patient_name']) && isset($_SESSION['patient_id']) && isset($_SESSION['patient_age']) && isset($_SESSION['result'])) {
    $patientName = $_SESSION['patient_name'];
    $patientId = $_SESSION['patient_id'];
    $patientAge = $_SESSION['patient_age'];
    $resultData = $_SESSION['result'];
    ?>

    <div class="card">
        <div class="content">
            <h2 class="heading"><?php echo $patientName; ?></h2>
            <p class="para">ID: <?php echo $patientId; ?></p>
            <p class="para">Age: <?php echo $patientAge; ?></p>
            <p class="para">Result: <?php echo $resultData; ?></p>
        </div>
    </div>
    
        
    <?php
} else {
    echo "No data available";
}
?>
<script>
    hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function() {
      navBar = document.querySelector(".nav-bar");
      navBar.classList.toggle("active");
    }
  </script>
</body>
</html>
