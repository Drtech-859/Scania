<?php
session_start(); // Start the session

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
    
    $user = $_SESSION['username']; // Retrieve username from session if available

    // Check if a file was uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileType = $_FILES['file']['type'];

        // Move uploaded file to a desired location
        $destinationPath = 'uploads/' . $fileName;
        move_uploaded_file($fileTmpPath, $destinationPath);

        // Call the predict.py script passing the uploaded file
        $command = "python predict.py " . $destinationPath . " 2>&1";
        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            // Extract the prediction result from the output
            $prediction = end($output);

            $sql = "INSERT INTO `patients` (`patient_name`, `patient_id`, `patient_age`, `result`, `username`)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssiss", $_POST['patient_name'], $_POST['patient_id'], $_POST['patient_age'], $prediction, $user );

            if ($stmt->execute()) {
                echo "Record updated successfully";

                // Create temporary session variables
                $_SESSION['patient_name'] = $_POST['patient_name'];
                $_SESSION['patient_id'] = $_POST['patient_id'];
                $_SESSION['patient_age'] = $_POST['patient_age'];
                $_SESSION['result'] = $prediction;

                // Delete the temporary image file
                unlink($destinationPath);
                header("Location: index1.php"); // Redirect to index1.php
                exit();
            } else {
                echo "<script> window.alert('Error updating record');
                      setTimeout(function() {window.location.href = 'upload_img.php';}, 1000);</script>";
                exit;
            }
        } else {
            echo "Error executing prediction script";
            exit;
        }

        $stmt->close();
        $conn->close();
    }
}
?>






<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload CXR</title>
  <link rel="stylesheet" href="index.css">
  <style>
    h1 {
      font-family: 'Poppins', sans-serif, 'arial';
      font-weight: 600;
      font-size: 72px;
      color: white;
      text-align: center;
    }

    h4 {
      font-family: 'Roboto', sans-serif, 'arial';
      font-weight: 400;
      font-size: 20px;
      color: #9b9b9b;
      line-height: 1.5;
    }

    /* ///// inputs /////*/

    input:focus~label,
    textarea:focus~label,
    input:valid~label,
    textarea:valid~label {
      font-size: 0.75em;
      color: #999;
      top: -5px;
      -webkit-transition: all 0.225s ease;
      transition: all 0.225s ease;
    }

    .styled-input {
      float: left;
      width: 293px;
      margin: 1rem 0;
      position: relative;
      border-radius: 4px;
    }

    @media only screen and (max-width: 768px) {
      .styled-input {
        width: 100%;
      }
    }

    .styled-input label {
      color: #999;
      padding: 1.3rem 30px 1rem 30px;
      position: absolute;
      top: 10px;
      left: 0;
      -webkit-transition: all 0.25s ease;
      transition: all 0.25s ease;
      pointer-events: none;
    }

    .styled-input.wide {
      width: 650px;
      max-width: 100%;
    }

    input,
    textarea {
      padding: 30px;
      border: 0;
      width: 100%;
      font-size: 1rem;
      background-color: #2d2d2d;
      color: white;
      border-radius: 4px;
    }

    input:focus,
    textarea:focus {
      outline: 0;
    }

    input:focus~span,
    textarea:focus~span {
      width: 100%;
      -webkit-transition: all 0.075s ease;
      transition: all 0.075s ease;
    }

    textarea {
      width: 100%;
      min-height: 15em;
    }

    .input-container {
      width: 650px;
      max-width: 100%;
      margin: 20px auto 25px auto;
    }

    .submit-btn {
        width:300px;
      padding: 7px 35px;
      border-radius: 60px;
      display: inline-block;
      background-color: #4b8cfb;
      color: white;
      font-size: 18px;
      cursor: pointer;
      box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.06), 0 2px 10px 0 rgba(0, 0, 0, 0.07);
      -webkit-transition: all 300ms ease;
      transition: all 300ms ease;
    }

    .submit-btn:hover {
      transform: translateY(1px);
      box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.10), 0 1px 1px 0 rgba(0, 0, 0, 0.09);
    }

    @media (max-width: 768px) {
      .submit-btn {
        text-align: center;
      }
    }

    input[type=checkbox]+label {
      color: #ccc;
      font-style: italic;
    }

    input[type=checkbox]:checked+label {
      color: #f00;
      font-style: normal;
    }

    /*style for image upload card*/
    .custum-file-upload {
      height: fit-content;
      width: 300px;
      display: flex;
      flex-direction: column;
      align-items: space-between;
      gap: 20px;
      cursor: pointer;
      align-items: center;
      justify-content: center;
      border: 2px dashed #cacaca;
      background-color: rgba(255, 255, 255, 1);
      padding: 1.5rem;
      border-radius: 10px;
      box-shadow: 0px 48px 35px -48px rgba(0, 0, 0, 0.1);
      margin-left: auto;
      margin-right: auto;
    }

    .custum-file-upload .icon {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .custum-file-upload .icon svg {
      height: 80px;
      fill: rgba(75, 85, 99, 1);
    }

    .custum-file-upload .text {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .custum-file-upload .text span {
      font-weight: 400;
      color: rgba(75, 85, 99, 1);
    }

    .custum-file-upload input {
      display: none;
    }

    /* Smaller file preview */
    .preview-image {
      max-width: 200px;
      max-height: 200px;
    }

    /* Center alignment for file preview */
    #preview {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 1rem;
    }
  </style>
  <link rel="stylesheet" href="navbar.css">

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
  <div class="container" style="margin:2rem 1rem;z-index:-1;">
    <div class="row">
      <h1 style="text-align:center;color:#2d2d2d;font-size: 2rem;">Upload Patient Details</h1>
    </div>
    <div class="row input-container">
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
      <div class="col-xs-12">
        <div class="styled-input wide">
          <input type="text" name="patient_name" required />
          <label>Patient Name</label>
        </div>
      </div>
      <div class="col-md-6 col-sm-12">
        <div class="styled-input">
          <input type="text" name="patient_id" required />
          <label>Patient ID</label>
        </div>
      </div>
      <div class="col-md-6 col-sm-12">
        <div class="styled-input" style="float:right;">
          <input type="text" name="patient_age" required />
          <label>Patient age</label>
        </div>
      </div>
      <div class="col-xs-12">
        <label class="custum-file-upload" for="file">
          <div id="icon-container" class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="" viewBox="0 0 24 24">
              <g stroke-width="0" id="SVGRepo_bgCarrier"></g>
              <g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g>
              <g id="SVGRepo_iconCarrier">
                <path
                  fill=""
                  d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V9C19 9.55228 19.4477 10 20 10C20.5523 10 21 9.55228 21 9V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM14 15.5C14 14.1193 15.1193 13 16.5 13C17.8807 13 19 14.1193 19 15.5V16V17H20C21.1046 17 22 17.8954 22 19C22 20.1046 21.1046 21 20 21H13C11.8954 21 11 20.1046 11 19C11 17.8954 11.8954 17 13 17H14V16V15.5ZM16.5 11C14.142 11 12.2076 12.8136 12.0156 15.122C10.2825 15.5606 9 17.1305 9 19C9 21.2091 10.7909 23 13 23H20C22.2091 23 24 21.2091 24 19C24 17.1305 22.7175 15.5606 20.9844 15.122C20.7924 12.8136 18.858 11 16.5 11Z"
                  clip-rule="evenodd"
                  fill-rule="evenodd"
                ></path>
              </g>
            </svg>
          </div>
          <div class="text">
            <span>Select File</span>
          </div>
          <input type="file" id="file" name="file" style="display:none;" />
          <div id="preview"></div> 
        </label>
      </div>


    </div>
    <div class="row" style="margin:2rem 0;text-align:center;">
      <input type="submit" value="Submit" name="submit" class="submit-btn" />
    </div>
  </form>
  </div>
  <div class="footer">
        <p>&copy; 2023 Scania - Pneumonia Detection</p>
    </div>
  <script>
    const fileInput = document.getElementById("file");
    const previewContainer = document.getElementById("preview");
    const iconContainer = document.getElementById("icon-container");
  
    fileInput.addEventListener("change", function () {
      const file = this.files[0];
      const reader = new FileReader();
  
      reader.addEventListener("load", function () {
        const previewImage = document.createElement("img");
        previewImage.setAttribute("src", this.result);
        previewImage.setAttribute("class", "preview-image");
        previewContainer.innerHTML = "";
        previewContainer.appendChild(previewImage);
        iconContainer.style.display = "none"; // Hide the SVG icon container
      });
  
      if (file) {
        reader.readAsDataURL(file);
      }
    });
  </script>
    <script>
    hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function() {
      navBar = document.querySelector(".nav-bar");
      navBar.classList.toggle("active");
    }
  </script>
</body>
</html>
