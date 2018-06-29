<!DOCTYPE html>
<html lang="jp" dir="ltr">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="./css/adminStyle.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://use.fontawesome.com/926fe18a63.js"></script>
  <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
     <script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>
  <script src="./js/admin_main.js"></script>
  <script src="./js/db.js"></script>
</head>
<body>

  <div class="admin-all">
    <div class="admin-top">
      <b>管理者ページ</b>
      <form name= "admin" action="adminExcel.php" method="post">
        <div id="loading"><img id="loading-image" src="./image/Loading.gif" alt="Loading..." /></div>
        <input type=hidden name="Attendances_monthly"/>
        <input type=hidden name="Attendances_daily"/>
        <input type=hidden name="traffics"/>
        <input type=hidden name="YMs" value="<?=$_REQUEST['YMs']?>"/>
        <input type=hidden name="Users" style='font-size:16pt'/>
      <?php

      $user_agent = $_SERVER['HTTP_USER_AGENT'];

      date_default_timezone_set('Asia/Tokyo'); //  default地域設定
      $month = date("m"); // 現在の月
   	  $year = date("Y"); // 現在の年
      ?>
      <select name="YMs" id="ym" onchange="select()" style="font-size:18px;">
        <?php for($i=0;$i<12;$i++){
          $r_month = $month-$i;
          if($r_month <1) {
            $r_year = $year - 1;
            $r_month=$r_month+12;
          }else{
            $r_year = $year;
          }
          if($r_month<10){
            $r_month = "0".$r_month;
          }?>

          <option value="<?=$r_year.$r_month?>">
      <?
            echo "&nbsp;".$r_year."年".$r_month."月";
        ?>
          </option>
      <?} ?>
      </select>
      <button type="submit" class="download-ready-btn" onclick="alert('ダウンロード準備を開始します。');">DL準備</button>
      <button type="button" class="download-btn" onclick="getvalue(this.form);">一括DL</button>
    </div>
    <hr>
    <div class="admin-all-input">
      <div class="admin-input">
        <input type="text" name="name" id="user_name">
        <button type="button" name="search" id="search" class="search-btn">検索</button>
      </div>
      <div class="member-div">
        <button type="button" name="memberInsert" class="member-btn" onclick="window.open('./memberInfo/mRegister.php')">登録</button>
      </div>
    </div>
  </div>
  <div class="overlay">
    <header>
      <div class="container">
        <div class="container-small">
          <button type="button" class="headB">
            <span class="fa fa-bars"></span>
          </button>
        </div>
        <nav class="headA">
          <ul id="menu">
            <li><a href="punch.php" class="menu-toggle">出・退勤</a></li>
            <li><a href="memberInfo/mMemInfo.php" class="menu-toggle">個人情報</a></li>
            <li><a href="timeSheet.php" class="menu-toggle">履歴</a></li>
            <li><a href="./admin.php" id="admin" class="menu-toggle">管理者</a></li>
            <li><a href="log/mLogout.php" id="login" class="menu-toggle">ログアウト</a></li>
          </ul>
        </nav>
      </div>
    </header>
  </div>
      <div class="admin-body">
      <div id="refresh">
      </div>
    </div>
  </form>

    <script>
    var auth, database, uid;
    var Attendances_monthlyRef, Attendances_dailyRef, UsersRef, TrafficsRef;
    database = firebase.database();

    auth = firebase.auth();
    var authProvider = new firebase.auth.GoogleAuthProvider();
    auth.onAuthStateChanged(function(user) {
      if (user) {
          database.ref("Users/" + user.uid).once('value').then(function (snapshot) {
            if(snapshot.val()['authority_id'] == 1) {
              //JavaScriptのdom選択を通したナビゲーションメニューのエレメントを変更接触
              document.getElementById("admin").href = "./admin.php";
              document.getElementById("login").textContent = "ログアウト";
              document.getElementById("login").href = "./log/mLogout.php";
              document.getElementById("user_name").textContent =  snapshot.val().user_name + " 様";
              document.getElementById("user_name").href = "./memberInfo/mMemInfo.php";

            } else if(snapshot.val()['authority_id'] == 0){　//管理者　authority_id　が　0なら　出ない
              document.getElementById("login").textContent = "ログアウト";
              document.getElementById("login").href = "./log/mLogout.php";
              document.getElementById("user_name").textContent =  snapshot.val().user_name + " 様";
              document.getElementById("user_name").href = "./memberInfo/mMemInfo.php";
              var menu = document.getElementById('menu');
              console.log(menu);
              var lis = document.getElementsByTagName('li');
              menu.removeChild(lis[3]);
              alert('管理者のみのページです。');
              history.back();

            }else if(!snapshot.val()){
              alert('管理者のみのページです。');
              history.back();
            }

          });
      } else {
        alert('管理者のみのページです。');
        window.location.href="./log/mLogin.php";
      }
    });

    Attendances_monthlyRef = database.ref('Attendances_monthly/');
    Attendances_dailyRef = database.ref('Attendances_daily/');
    UsersRef = database.ref('Users/');
    TrafficsRef = database.ref('Traffics/');
    exportAllData();

    function exportAllData() {
      Attendances_monthlyRef.on('value', function(data){
      document.admin.Attendances_monthly.value = JSON.stringify(data); });

      Attendances_dailyRef.on('value', function(data){
        document.admin.Attendances_daily.value = JSON.stringify(data); });

      UsersRef.on('value', function(data){
        document.admin.Users.value = JSON.stringify(data); });

      TrafficsRef.on('value', function(data){
        document.admin.traffics.value = JSON.stringify(data); });
      }


      var page_value = getQuerystring('page'); // クエリストリングで渡されたpage
      var selectedYM = getQuerystring('select'); // クエリストリングで渡された選択した年月

      if(page_value=="") page_value = 1;
      if(selectedYM==""){
        var today = new Date();
        var mm = today.getMonth()+1;
        var yyyy = today.getFullYear();
        if(mm<10) {
            mm='0'+mm
        }
        selectedYM = yyyy+mm;
      }

      $("#ym").val(selectedYM).prop("selected", true);

      $(window).load(function() {
        Attendances_dailyRef.on('value', function(data){
            dailyAllData = data.val();
          });
        Attendances_monthlyRef.on('value', function(data){
              monthlyAllData = data.val();
            });
        UsersRef.on('value', function(data){
            userAllData = data.val();

            $.ajax({
                type: "POST",
                url: "adminSearch.php?page="+page_value,
                data : {"user_name": $("#user_name").val(),
                        "YMs": $("#ym").val(),
                        "userAllData": [userAllData],
                        "monthlyAllData": [monthlyAllData],
                        "dailyAllData": [dailyAllData]}, //urlに送る Parameter
                success: function(datas){
                  $("#refresh").html(datas); //戻り値 -> テーブルに出力
                },
                error: function(xhr, status, error) {
                  alert(error);
                }
            });
            $('#loading').hide();
          });
      });

      // 検索
      $('#search').click(function(){
          $.ajax({
              type: "POST",
              url: "adminSearch.php",
              data : {"user_name": $("#user_name").val(),
                      "YMs": $("#ym").val(),
                      "userAllData": [userAllData],
                      "monthlyAllData": [monthlyAllData],
                      "dailyAllData": [dailyAllData]},//urlに送る Parameter
              success: function(datas){
                $("#refresh").html(datas); //戻り値 -> テーブルに出力
              },
              error: function(xhr, status, error) {
                alert(error);
              }
          });
      })
    </script>
</body>
</html>
