<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="index.css">
  <link rel="stylesheet" href="navbar.css">
  <style>
    .main{
      min-height: 100%;
      padding: 0%;
    }
  </style>
  <title>SCANIA</title>
</head>
<body>
  <div class="main">
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
          <a href="index.html" class="active">Home</a>
        </li>
        <li>
          <a href="health.html">Health</a>
        </li>
        <li>
          <a href="about.html">About</a>
        </li>
        <li>
          <a href="contact.html">Contact</a>
        </li>
        <li>
          <a href="signout.php">Signout</a>
        </li>
      </ul>
    </nav>
  </header>
    <div class="card-container">
      <div class="card">
        <div class="content">
          <img src="images/upload.png" alt="upload">
          <p class="heading">Upload CXR Image</p>
          <p class="para">Upload the images to be examined for Pneumonia</p>
          <a href="upload_img.php" type="submit" class="btn">Upload</a>
        </div>
      </div>
      <div class="card">
        <div class="content">
          <img src="images/result.png" alt="result">
          <p class="heading">Check Result</p>
          <p class="para">Click the result button to view the result of latest uploaded image </p>
          <a href="result.php" type="submit" class="btn">Result</a>
        </div>
      </div>
      <div class="card">
        <div class="content">
          <img src="images/history.png" alt="history">
          <p class="heading">History</p>
          <p class="para">Click the histoy button to view the history</p>
          <a href="history.php" type="submit" class="btn">History</a>
        </div>
      </div>
    </div>
    <div class="footer">
        <p>&copy; 2023 Scania - Pneumonia Detection</p>
    </div>
  </div>
  <script>
    hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function() {
      navBar = document.querySelector(".nav-bar");
      navBar.classList.toggle("active");
    }
  </script>
</body>
</html>