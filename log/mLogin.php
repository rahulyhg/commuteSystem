<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>
  <script src="../js/db.js"></script>
  <link rel="stylesheet" href="../css/ptsCss/mLogin.css">
</head>
<body>
  <div class="logIn">
    <div class="shadow">
      <div class="logInBox">
        <h1>MERCURY 勤怠</h1>
        <!-- <form class="form" action="#" method="post" > -->
        <ul class="ul">
          <li class="li"><input type="email" name="" id="email" placeholder="メールアドレスを入力してください。" /></li>
          <!--type email - when '@' is not in input, it should be occurred-->
          <li class="li"><input type="password" name="" id="password" placeholder="パスワードを入力してください。" /></li>
          <li id="error" class="error"></li>
          <li class="li" id="small"><a href="../memberInfo/mSearchPws.php" target="_blank" id="this1">パスワードを忘れた場合</a></li>
          <li class="li" id="button"><input type="button" name="" value="ログイン" class="login" id="login" /></li>
        </ul>
        <!-- </form> -->
      </div>
    </div>
  </div>

  <script>
  //enter key
  $(document).keypress(function(event){
       if ( event.which == 13 ) {
         $('#login').click();
           if($('#email').val().length < 1){
              $('#email').focus();
           }else{
              $('#password').focus();
           }
       }
  });

    var firebaseEmailAuth;
    var firebaseDatabase;
    var userInfo;
    var email;
    var password;


    firebaseEmailAuth = firebase.auth();
    firebaseDatabase = firebase.database();

    $(document).ready(function() {

      userSessionCheck();
    });

    //  ユーザのログインを確認する関数
    function userSessionCheck() {
      //ログインしたらログインしたユーザの情報がある
      firebaseEmailAuth.onAuthStateChanged(function(user) {
        if (user == null) {
          login();
          document.getElementById("login").textContent = "ログイン.";
        } else {
          changeButtonAction();

          function changeButtonAction() {
            var login = document.getElementById("login");
            var loginText = login.textContent;

            if (loginText == "ログイン.") {
              console.log("修正 ボタン");
            } else {
              login.textContent = "ログイン"
              alert("すでにログインされている状態です。");
              console.log(user.uid);
              window.location = '../punch.php'
            }
          }
        } //END else
      }); // END firebaseEmailAuth.onAuthStateChanged(function (user)
    } //function userSessionCheck END

    //ログイン成功
    function loginSuccess(firebaseUser) {
      window.location = '../punch.php'
    }


    function login() {
      //ログインボタン
      $(document).on('click', '.login', function() {
        email = $('#email').val();
        password = $('#password').val();
        firebaseEmailAuth.signInWithEmailAndPassword(email, password)
          .then(function(firebaseUser) {
            loginSuccess(firebaseUser);
          })
          .catch(function(error) {
            //alert(error);
            alert("ログイン失敗");
            //document.getElementById("error").textContent = error;
            document.getElementById("error").textContent = "メールアドレス、パスワードの入力に誤りがあります。";
          });
      });
    }
  </script>
</body>
</html>
