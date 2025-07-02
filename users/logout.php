<?php
session_start();
session_unset();       // Optional but good practice
session_destroy();     // Destroy all session data
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Logging Out...</title>
  <meta http-equiv="refresh" content="2;url=../home.php"> <!-- Redirect after 2 seconds -->

  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f8ff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .message-box {
      background-color: #fff;
      padding: 40px 50px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      text-align: center;
    }

    .message-box h2 {
      color: #28a745;
      margin-bottom: 10px;
    }

    .message-box p {
      color: #555;
      font-size: 16px;
    }
  </style>

  <script>
    // JavaScript fallback redirect after 2 seconds
    setTimeout(() => {
      window.location.href = "../home.php";
    }, 2000);
  </script>
</head>

<body>
  <div class="message-box">
    <h2>✅ Logout Successful</h2>
    <p>Redirecting you to the home page...</p>
  </div>
</body>
</html>
