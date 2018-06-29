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
          <li class="li"><input type="email" name="" id="email" placeholder="メールを入力してください。" /></li>
          <!--type email - when '@' is not in input, it should be occurred-->
          <li class="li"><input type="password" name="" id="password" placeholder="パスワードを入力してください。" /></li>
          <li id="error" class="error"></li>
          <li class="li" id="small">パスワードを忘れた場合は<a href="../memberInfo/mSerchPws.php" target="_blank" id="this1">こちら</a></li>
          <li class="li" id="button"><input type="button" name="" value="ログイン" class="login" id="login" /></li>
        </ul>
        <!-- </form> -->
      </div>
    </div>
  </div>

  <script>

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
    var idToken;
    var csrfToken;


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
            //console.log("버튼이 눌렀습니다");
            var login = document.getElementById("login");
            var loginText = login.textContent;

            if (loginText == "ログイン.") {
              console.log("修正 ボタン");
            } else {
              login.textContent = "ログイン"
              alert("すでにログインされている状態です。");
              console.log(user.uid);
              //window.location = '/mPunch.php'
              window.location = '../punch.php'
            }
          }
        } //END else
      }); // END firebaseEmailAuth.onAuthStateChanged(function (user)
    } //function userSessionCheck END


    //ログイン成功
    function loginSuccess(firebaseUser) {
      alert("ログイン成功");

      //메인 페이지로 이동
      //window.location.href = "/mPunch.php"
      window.location = '../punch.php'
    }


    // function login() {
    //   //ログインボタン
    //   $(document).on('click', '.login', function() {
    //
    //
    //     email = $('#email').val();
    //     password = $('#password').val();
    //     //  alert("로그인 버튼 눌렸음" + email +":"+ password);
    //
    //
    //     firebaseEmailAuth.signInWithEmailAndPassword(email, password)
    //       .then(function(firebaseUser) {
    //
    //
    //         loginSuccess(firebaseUser);
    //       })
    //       .catch(function(error) {
    //
    //         //alert(error);
    //         alert("ログイン失敗");
    //         //document.getElementById("error").textContent = error;
    //         document.getElementById("error").textContent = "メールまたはパスワードを確認ください";
    //       });
    //   });
    // }

//
//
//     firebase.initializeApp({
//   apiKey: 'user.uid',
//   authDomain: 'http://game-mania.sakura.ne.jp/test/Folder/log/mLogin.php'
// });
// console.log("aaa");
// // As httpOnly cookies are to be used, do not persist any state client side.
// firebase.auth().setPersistence(firebase.auth.Auth.Persistence.NONE);
//
// // When the user signs in with email and password.
// firebase.auth().signInWithEmailAndPassword(email, password).then(user => {
//
//   // Get the user's ID token as it is needed to exchange for a session cookie.
//   return user.getIdToken().then(idToken => {
//     // Session login endpoint is queried and the session cookie is set.
//     // CSRF protection should be taken into account.
//     // ...
//     //loginSuccess(firebaseUser);
//     const csrfToken = getCookie('csrfToken')
//     return postIdTokenToSessionLogin('/ｍLogin11.php', idToken, csrfToken);
//   });
// }).then(() => {
//   // A page redirect would suffice as the persistence is set to NONE.
//   return firebase.auth().signOut();
// }).then(() => {
//   window.location.assign('../punch1.php');
// });
//
//
//
//
// app.post('/ｍLogin11.php', (req, res) => {
//   const idToken = req.body.idToken.toString();
//   const csrfToken = req.body.csrfToken.toString();
//
//   if(csrfToken !== req.cookies.csrfToken) {
//     res.status(401).send('/ｍLogin11.php');
//     return;
//   }
//   const expiresIn = 60 * 60 * 24 * 5 * 1000;
//   firebaseEmailAuth.createSessionCookie(idToken, {expiresIn}).then((sessionCookie)) => {
//     const option = {maxAge: expiresIn, httpOnly : true, secure: true};
//     res.cookie('session', sessionCookie, options);
//     res.end(JSON.stringify({status:'success'}));
//   }, error => {
//     res.status(401).send('UNAUTHORIZED REQUEST!');
//   }
//
// });
//
  </script>
</body>

</html>
