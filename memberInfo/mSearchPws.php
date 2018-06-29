<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title id="titleCheck">Send Email</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>
<script src="../js/db.js"></script>
  <link rel="stylesheet" href="../css/ptsCss/mSearchPws.css">
  </head>
<body align=center>
  <!-- <ul class="messageUl">
    <li id="asd"><input type="text" id="email" value="" placeholder="メールを書いてください。"/></li>
    <li><button id="send0" ><img src=https://www.dropbox.com/s/chxcszvnrdjh1zm/send.png?dl=1 width=50px height=50px></img></button></li>
  </ul> -->
  <div class="logIn">
    <div class="shadow">
      <div class="logInBox">
        <h1>パスワード再設定</h1>
        <!-- <form class="form" action="#" method="post" > -->
        <ul class="ul " >
          <li class="li send" id="infomation"><h3>メールアドレスを入力してください</h3></li>
          <li class="li sent" id="infomation"><h4>パスワード変更のURLを送信しました。</h4></li>
          <li class="li send"><input type="email" name="" id="email" /></li>
          <li class="li send" id="button"><input type="button" name="" value="送信"  id="send" /></li>
          <li class="li sent" id="button"><a href="../log/mLogin.php" ><input type="button"  name="" value="ログイン画面に戻る"  id="backLogin" /></a></li>
        </ul>
      </div>
    </div>
  </div>



  <!--<script src="https://www.gstatic.com/firebasejs/4.13.0/firebase.js"></script>-->
  <script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>
  <script src="../js/db.js"></script><!--firebase-->
  <script>
  var firebaseEmailAuth; //firebase email 認証 モジュール全域の変数
  var firebaseDatabase; //firebase db モジュール全域の変数
  var userInfo; //登録したユーザの情報. object タイプ
  var user;
  var email;//
  var user_id;
  var user_name;
  var name_kana;
  var password;
  var birthday;
  var workplace;
  var start_time;
  var end_time;
  var departure_station;
  var arrival_station;
  var commutation_ticket;
  var department;
  var costs;
  var rest_time;
  var division;
  var occupation;
  var loginUserKey; //roginしたユーザーの key
    //認証モジュールobject読み込む
    firebaseEmailAuth = firebase.auth();
    user = firebase.auth().currentUser;
    //databaseモジュールobject読み込む
    firebaseDatabase = firebase.database();

      Email = {
     Send : function (to,from,subject,body,apikey)
        {
            if (apikey == undefined)
            {
                apikey = Email.apikey;
            }
            var nocache= Math.floor((Math.random() * 1000000) + 1);
            var strUrl = "http://directtomx.azurewebsites.net/mx.asmx/Send?";
            strUrl += "apikey=" + apikey;
            strUrl += "&from=" + from;
            strUrl += "&to=" + to;
            strUrl += "&subject=" + encodeURIComponent(subject);
            strUrl += "&body=" + encodeURIComponent(body);
            strUrl += "&cachebuster=" + nocache;
            Email.addScript(strUrl);
        },
        apikey : "",
        addScript : function(src){
                var s = document.createElement( 'link' );
                s.setAttribute( 'rel', 'stylesheet' );
                s.setAttribute( 'type', 'text/xml' );
                s.setAttribute( 'href', src);
                document.body.appendChild( s );
        }
    };


    //パスワード変更関数
    $(document).on('click', '#send', function () {
      var emailAddress = $('#email').val();
      firebaseEmailAuth.sendPasswordResetEmail(emailAddress).then(function() {
        // Email sent.
        $(".send").hide();
          $(".sent").animate({width:"toggle"}, 0);
      }).catch(function(error) {
        // An error happened.
        //alert(error);
        alert("メール送信失敗");
      });
    });

  </script>
</body>
</html>
