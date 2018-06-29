<!DOCTYPE html>
<html>
<head>
  <title>LogOut</title>
  <meta charset="utf-8">
  <meta http-equiv="refresh" content="2; url=./mLogin.php">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>
    <link rel="stylesheet" href="../css/ptsCss/mLogout.css">
</head>

<body>
  <div class="layer">
    <span class="content"><h1>Log Outしました。</h1></span>
    <span class="blank"></span>
  </div>
  <script src="../js/db.js"></script>
  <!--firebase-->
  <script>
    var firebaseEmailAuth;
    firebaseEmailAuth = firebase.auth();
    //logout
    firebaseEmailAuth.signOut().then(function() {
    }).catch(function(error) {
      if (error) {
        alert("ログアウト失敗");
      }
    });
  </script>
</body>
</html>
