<!DOCTYPE html>
<html lang="jp" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://use.fontawesome.com/926fe18a63.js"></script>
<script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
<script type = "text/javascript" src="js/time.js"></script>
<link rel="stylesheet" href="css/punch_style.css">

<script>

var sw = 0;
var filter = "win16|win32|win64|mac"
$(document).ready(function() {
  $('html').click(function(e) {
    if(sw == 0){
      if($(e.target).hasClass("headB")||$(e.target).hasClass("fa")) {
        $(".headA").animate({
          width: "toggle"
        }, 200);
        if(navigator.platform){
          if(0 > filter.indexOf(navigator.platform.toLowerCase())){
            $("#button-state").prop("disabled", true);
          }
          }
        sw = 1;
      }
    }else{
     if(!$(e.target).is('.menu-toggle')){
        $(".headA").animate({
          width: "toggle"
        }, 200);
        if(navigator.platform){
         if(0 > filter.indexOf(navigator.platform.toLowerCase())){
           $("#button-state").prop("disabled", false);
           }
         }
       sw = 0;
      }
    }
  });
});

  //  var sw2 = 0;
//    var sw = true;
  //  var filter = "win16|win32|win64|mac";
//    $(document).ready(function(){

    //   $(".headB").on('click', function(){
    //     $(".headA").animate({width:"toggle"}, 200);
    //     if(navigator.platform){
    //       if(0 > filter.indexOf(navigator.platform.toLowerCase())){
    //         if(sw){
    //           $("#button-state").prop("disabled", sw);
    //           sw = false;
    //         }else{
    //           $("#button-state").prop("disabled", sw);
    //           sw = true;
    //         }
    //       }
    //     }
    //   });
    // });

</script>
<style>
.time:nth-of-type(1) {
  margin-bottom: 10px;
}
.time{
  font-size: 35px;
  border:0 none;
  margin-top: 30%;
  background-color: transparent;
  color: white;
  width: 100%;
  margin: 0;
  padding: 0;
  text-align: center;
}
</style>
</head>
<body onLoad = "clock()" >
  <div class="container">
    <!-- Add Loading-image 180619 jhkim -->
    <div id="loading"><img id="loading-image" src="image/Loading.gif" alt="Loading..." /></div>

    <div class="Login"><a href="./log/mLogin" id="user_name"><h2></h2></a></div>

    <button class="start-circle" onclick="StateChange() ;  " id="button-state"> <!--updatetime()-->
        <form name="clock">
          <br>
          <br>
          <input type="text" class="time" name="date2" value="" readonly><br>
          <input type="text" class="time" name="time" value="" readonly>
          <input type="hidden" class="time" name="date" value="" readonly>
        </form>
    <p class="check" id="check">出勤</p>
    </button>
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
        <li><a href="./memberInfo/mMemInfo.php" class="menu-toggle"> 個人情報</a></li>
        <li><a href="timeSheet.php" class="menu-toggle">履歴</a></li>
        <li><a href="admin.php" id="admin" class="menu-toggle">管理者</a></li>
        <li><a href="./log/mLogout.php" id="login" class="menu-toggle">ログアウト</a></li>
    </ul>
    </nav>
    </div>
    </header>
    </div>
<script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>
<script type="text/javascript" src="js/db.js"></script>
<script language="javascript" type="text/javascript">

  // 180619 jhkim 出勤退勤修正
  inoutcheck = false;
	function StateChange() {
    // 180619 jhkim 15分の間退勤できないように修正
    // 180619 jhkim 出勤退勤の入力修正
    var Attendances_daily1 = firebaseDatabase.ref("Attendances_daily/" + loginUserKey + "/" + dateStr); //データベース構造分け紐付け
    var rested_time = restin;

    workstart = {
      start_time: checkintime,
      workplace: workplace,
    };
    workend = {
      end_time: checkouttime
    };
    workrest ={
      rest_time: rested_time,
    };

    var echeck = 0;
    var stateoff = 0;
    //180619 jhkim1 退勤して1時間過ぎないと出勤できません
    Attendances_daily1.once('value').then(function (snap) {
      if(snap.val() != null) {
        if(snap.val().end_time != null) {
          e = snap.val().end_time; //今日のエンドタイムのデータ
          stemp = e.substring(0,2)*1;
          mtemp = e.substring(3,5)*1;
          echeck = (stemp*60)+mtemp+60;
        }
      }

  		if(!inoutcheck && ((hours*60)+minutes) > echeck){ //出勤 → 退勤
        var element = document.getElementById("button-state").className="end-circle";
  			document.getElementById("check").textContent = "退勤";
        alert("おはようございます！あなたの出勤時間は" + checkintime + "です！");
        inoutcheck = true;
        reloadCheck = false;
        scheck = (hours*60)+minutes+15; //出勤して15分以後
        Attendances_daily1.set(workstart);
        stateoff = 0;
      } else if(inoutcheck && ((hours*60)+minutes) < scheck){ //15分の間は出勤ができません
        alert("出勤して15分までは退勤ができません!");
  		} else if(inoutcheck && ((hours*60)+minutes) >= scheck){ //出勤 → 退勤
        var element = document.getElementById("button-state").className="start-circle";
  			document.getElementById("check").textContent = "出勤";
        alert("お疲れ様です！あなたの退勤時間は" + checkouttime + "です！");
        Attendances_daily1.update(workend);
        inoutcheck = false;




        eplus = checkouttime[checkouttime.length - 5] + checkouttime[checkouttime.length - 4];
        splus = checkintime[checkintime.length - 5] + checkintime[checkintime.length - 4];
        esplus = eplus - splus;
        if(esplus < 6){
          restin = "0:00";
        }
        else if(esplus >= 6 && esplus < 9){
          restin = "0:45";
        }
        else if(esplus >= 9){
          restin = "1:00";
        }
        rested_time = restin;
        console.log(workrest);
        console.log(restin);

        Attendances_daily1.update(workrest);


      } else {
        stateoff = 1;
        if(stateoff == 1){
          document.getElementById("button-state").style.visibility = "hidden";
          alert("本日の業務は終了しました！お疲れ様でした！");
        }
      }
    });
  }

  var firebaseEmailAuth; //ファイアベースemail認証モジュールのグローバル変数
  var firebaseDatabase; //ファイアベースdbモジュールのグローバル変数
  var user_name; //ユーザ名
  var loginUserKey; //ログインしたユーザーの親key
  var userInfo; //ログインしたユーザーの情報object type
  var comment; //ユーザが書いた文章を保存
  var timeStr;
  var dateStr;
  var rest_time;
  var rested_time;
  var restin;
  var work_place;
  var origin_start_time;
  var origin_end_time;
  var Attendances_month;
  var late_counted;
  var Attendances_monthly;
  var Attendances_mounth_date;
  var late_count;
  var already_Checked_Late;
  var splus;
  var eplus;
  var esplus;

  //認証モジュールオブジェクトの読み込み
  firebaseEmailAuth = firebase.auth();
  //データベースモジュールオブジェクトの読み込み
  firebaseDatabase = firebase.database();

  //セッションチェック関数
  userSessionCheck();

  //ユーザがログインしているならいることを確認してくれる関数
  function userSessionCheck() {


    //ログインしている場合、 - ユーザがあれば、userを引数の値に渡してくれる。
    firebaseEmailAuth.onAuthStateChanged(function (user) {

      if (user) {
        //180618 jhkim, 出勤退勤ボタンの状態コード
        var data = new Date();
        months = data.getMonth() + 1;
        days = data.getDate();
        years = data.getFullYear();
        dateStr = years;
        dateStr += ((months < 10) ? "0" : "") + months;
        dateStr += ((days < 10) ? "0" : "") + days;

        firebaseDatabase.ref("Attendances_daily/" + user.uid + "/" + dateStr).once('value').then(function (snapshot) {
          if(snapshot.val() != null) {
              s = snapshot.val().start_time; //今日のスタートタイムのデータ
              e = snapshot.val().end_time; //今日のエンドタイムのデータ
              if(s && !e) { //スタートタイムがあってエンドタイムがない場合→退勤ボタンを見せる
                element = document.getElementById("button-state").className="end-circle";
        				document.getElementById("check").textContent = "退勤";
                inoutcheck = true;
                stemp = s.substring(0,2)*1;
                mtemp = s.substring(3,5)*1;
                scheck = (stemp*60)+mtemp+15;  //出勤して15分以後
              }


          }
          $('#loading').hide(); //Loading end
        });

		firebaseDatabase.ref("Attendances_daily/" + user.uid + "/" + dateStr).once('value').then(function (snapshot) {
          if(snapshot.val() == null){
            already_Checked_Late = 0;
          }
          else {
            already_Checked_Late = 1;
          }
        });

        //ルック - データベースに保存されたニックネームを現在接続されているuserのpk key値を利用して、インポート
        firebaseDatabase.ref("Users/" + user.uid).once('value').then(function (snapshot) {

          user_name = snapshot.val().user_name;   //ユーザニックネームは書き続けるそこからグローバル変数に割り当て
          loginUserKey = snapshot.key;  //ログインしたユーザのkeyも書き続けるので、グローバル変数に割り当て
          userInfo = snapshot.val(); //snapshot.val（）にuserテーブルに対応するオブジェクト情報が超えています。userInfoに代入！
          workplace = snapshot.val().workplace;

     var  authority_id = snapshot.val().authority_id;

          if(authority_id == 1) {
            document.getElementById("admin").href = "./admin.php";
            document.getElementById("user_name").textContent =  snapshot.val().user_name + " 様";
            document.getElementById("user_name").href = "./memberInfo/mMemInfo.php";
          }else {　//管理者　authority_id　が　0なら　出ない
            document.getElementById("user_name").textContent =  snapshot.val().user_name + " 様";
            document.getElementById("user_name").href = "./memberInfo/mMemInfo.php";
            var lis = document.getElementsByTagName('li');
            document.getElementById('menu').removeChild(lis[3]);
          }
          return true
        });

        firebaseDatabase.ref("Timesheet/" + user.uid).once('value').then(function (snapshot) {
          if(snapshot.val()){ // yun: timesheet check
            rest_time = snapshot.val()['rest_time'];
            origin_start_time = snapshot.val()['start_time'];
            origin_end_time = snapshot.val()['end_time'];
            if(!snapshot.val()['rest_time']||!snapshot.val()['start_time']||!snapshot.val()['end_time']){
              alert("勤務時間を入力してください。");
              window.location.href = "./memberInfo/mMemInfo.php";
            }
          }else{
            alert("勤務時間を入力してください。");
            window.location.href = "./memberInfo/mMemInfo.php";
          } // yun: timesheet check 尾
        });

		firebaseDatabase.ref("Attendances_monthly/" + user.uid + "/" + Attendances_mounth_date).once('value').then(function (snapshot) {
          if(snapshot.val()==null) {
            late_counted = 0;
          } else {
            late_count = snapshot.val().late_count
            late_counted = late_count;
          }
        });
      } else {
              alert("ログインしてください。");
              window.location = 'log/mLogin.php'
              return false;
      }

      // firebaseに登録する年月に関してのフォルダ構造
    firebaseDatabase.ref("Attendances_monthly/" + user.uid).once('value').then(function (snapshot) {
          temp = Object.keys(snapshot.val());
          Attendances_month = temp[temp.length - 1];
      });
    });
  }
  //修正のボタン　修正の関数
  $(document).on('click', '#button-state', function() {
    if(already_Checked_Late == 0 ){
     saveTime();
     already_Checked_Late++;
   } else {
   }
  });

  //データにいれる関数
  //180619 jhkim 中にあった入力関数上に移動
  function saveTime() {
    late_counted = 0;
    /* 180627 jhkim 出勤して４時間後に退勤すると遅刻カウントしないコード */
    cstemp = checkintime.substring(0,2)*1;
    cetemp = checkintime.substring(3,5)*1;
    oststemp = origin_start_time.substring(0,2)*1;
    ostetemp = origin_start_time.substring(3,5)*1;

    scheck = cstemp - oststemp;
    echeck = cetemp - ostetemp;

    if(scheck == 4 && echeck == 0) {} //4時間の場合、late_countedは上がらない
    else if(origin_start_time <= checkintime) {
        late_counted++;
    }
    Save_Attendanth_month(Attendances_monthly,loginUserKey,Attendances_mounth_date);
  }

  function Save_Attendanth_month(Attendances_monthly,loginUserKey,Attendances_mounth_date) {

    Attendances_monthly = firebaseDatabase.ref("Attendances_monthly/");
    Attendances_mounthly1 = firebaseDatabase.ref("Attendances_monthly/" + loginUserKey + "/" + Attendances_mounth_date); //データベース構造紐付け

    var Attendances_mounthly2 = {
          attendances_memo: "",
          late_count: late_counted,
        };

    if(Attendances_month == Attendances_mounth_date){ //Database上の年月と実際の年月が同じなら
    var newPostKey = firebase.database().ref().child("Attendances_monthly").push().key; //キーの取得
      var updates = {};
         updates["/loginUserKey/" + Attendances_month + "/" +  newPostKey] = Attendances_mounthly2; //Attendances_mounthly2を1にアップデート
		 Attendances_mounthly1.update(Attendances_mounthly2);
      } else{ //違うなら
          Attendances_mounthly1.set(Attendances_mounthly2); //Attendances_mounthly2を1にセット
      }
  }
  </script>
  </body>
</html>
