<?php
session_start();

// ✅ Clear all session data
session_unset();
session_destroy();

// ✅ Prevent cache so back button can’t reopen secure pages
header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// ✅ Redirect to home page immediately
header("Location: /sehat-guardian/home.php");
exit();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Logging Out...</title>
  <meta http-equiv="refresh" content="2;url=home.php">
  
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
    // Prevent back button access after logout
    window.history.pushState(null, null, window.location.href);
    window.onpopstate = function() {
      window.history.pushState(null, null, window.location.href);
    };
    
    // Clear browser cache
    if (window.history && window.history.pushState) {
      window.history.replaceState(null, null, window.location.href);
    }
    
    setTimeout(() => {
      window.location.href = "home.php";
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