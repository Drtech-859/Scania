<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="navbar.css">
  <style>
    .card {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 80%;
    max-width: 600px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    padding: 32px;
    overflow: hidden;
    border-radius: 10px;
    transition: all 0.5s cubic-bezier(0.23, 1, 0.320, 1);
    margin: 15px auto 10px auto;
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
    #clear-btn{
      padding: 12px 20px;
      border-radius: 25px;
      border:2px red solid;
      font-size: 16px;
      font-weight: bold;
    }
    #clear-btn:hover{
      color: white;
      background-color: #ff0f7b;
      cursor: pointer;
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
        <li>
          <form id="clear-form" method="post">
            <button type="submit" id="clear-btn">CLEAR</button>
            <input type="hidden" name="clear_data" value="1">
          </form>
        </li>
      </ul>
    </nav>
  </header>
  <?php
  session_start(); // Start the session

  $host = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'scaina';

  // Establish database connection
  $conn = new mysqli($host, $username, $password, $database);

  // Check for connection errors
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $user = $_SESSION['username'];

  if (isset($_POST['clear_data'])) {
      // Delete data associated with the current session user from the database
      $query = "DELETE FROM patients WHERE username = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("s", $user);
      $stmt->execute();
      $stmt->close();
  }

  // Fetch data from the database
  $query = "SELECT patient_name, patient_id, patient_age, result FROM patients WHERE username = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $user);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if there are rows returned
  if ($result->num_rows > 0) {
      // Loop through each row
      while ($row = $result->fetch_assoc()) {
          $patientName = $row['patient_name'];
          $patientId = $row['patient_id'];
          $patientAge = $row['patient_age'];
          $resultData = $row['result'];
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
      }
  } else {
      echo "No data available";
  }

  // Close the database connection
  $stmt->close();
  $conn->close();
  ?>

<script>
    hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function() {
      navBar = document.querySelector(".nav-bar");
      navBar.classList.toggle("active");
    }

    let clearBtn = document.getElementById("clear-btn")
    clearBtn.addEventListener("dblclick", function() {
      // Send a request to the server to delete the data of the current user
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "index1.php");
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.onload = function() {
        // Reload the page after successful deletion
        if (xhr.status === 200) {
          window.location.reload();
        } else {
          alert("Failed to clear data.");
        }
      };
      xhr.send("clear_data=1");
    });
  </script>
</body>
</html>
