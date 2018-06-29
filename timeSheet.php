<!DOCTYPE html>
<html lang="jp" dir="ltr">
<!--
  180508
  シフト画面(ログインしたユーザー用)
  Html/css初期作成：ユンへリン
  機能構築：jhkim
-->
<head>
  <!-- ================================== 基礎設定 =====================================-->
  <script src="https://use.fontawesome.com/926fe18a63.js"></script>
  <!-- javascript jquery 宣言 -->
  <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
  <!-- javascript(node.js) Firebase, Auth 宣言-->
  <script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script>
  <script type="text/javascript" src="js/db.js"></script>

  <script>
    var sw = 0;
    $(document).ready(function() {
      $('html').click(function(e) {
        if(sw == 0){
          if($(e.target).hasClass("headB")||$(e.target).hasClass("fa")) {
            $(".headA").animate({
              width: "toggle"
            }, 200);
            sw = 1;
          }
        }else{
         if(!$(e.target).is('.menu-toggle')){
            $(".headA").animate({
              width: "toggle"
            }, 200);
           sw = 0;
          }
        }
      });
    });

    /* Enter Submit Event Remove */
    document.addEventListener('keydown', function(event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        }
    }, true);

    var userInfo = "";
    var LuserInfo, Mcheck;
    var Lauth = "";
  </script>

  <meta charset="UTF-8">
  <!-- 180612 device automatic resize width -->
  <meta name="viewport" content="width=device-width, initial-scale=0.8">
  <link rel="stylesheet" href="css/timeSheet_style.css">
  <link rel="shortcut icon" href=""> <!-- remove favico.ico error -->

  <title>シフト履歴画面</title>

  <?php
    // In My Computer, Turn off all error reporting in php.ini (display_errors)
    //selected YearMonth
    date_default_timezone_set('Asia/Tokyo'); //  default地域設定
    $monthlyDay = date("t"); //一か月の最終日
    $month = date("m"); // 現在の月
  	$year = date("Y"); // 現在の年
    $strtotime = $year."-".$month."-1"; //毎月1日の曜日を求めるための変数
    $dailyInt = date('w', strtotime($strtotime)); // date関数は0~6の数字をreturnする
    $daily = array('日','月','火','水','木','金','土');
    $workarray = array();
  ?>
</head>

<body>
<!-- Loading-image -->
<div id="loading"><img id="loading-image" src="image/Loading.gif" alt="Loading..." /></div>

<div class="overlay">
  <header>
    <div class="container">
      <div class="container-small">
        <button type="button" class="headB">
          <span class="fa fa-bars"></span>
        </button>
      </div>
      <!-- ページ移動メニュー -->
      <nav class="headA">
        <ul id="menu">
            <li><a href="punch.php" class="menu-toggle">出・退勤</a></li>
            <li><a href="memberInfo/mMemInfo.php" class="menu-toggle">個人情報</a></li>
            <li><a href="timeSheet.php" class="menu-toggle">履歴</a></li>
            <li><a href="./admin.php" id="admin" class="menu-toggle">管理者</a></li>
            <li><a href="log/mLogout.php" id="login" class="menu-toggle">ログアウト</a></li> <!-- log/ == ./log/-->
        </ul>
      </nav>
    </div>
  </header>
</div>

<div class="timeSheet-all">
  <div class="timeSheet-top">
    <div class="title">
    <b id="title" >シフト履歴</b>
    </div>
    <!-- 180621 総残業時間 -->
    <div class="alloverwork">
    <h3 id="owa" >総残業時間：</h3>
    </div>
  </div>
  <hr>
  <div class="timeSheet-form">
  <!-- Excel Export Form -->
  <form action="excelExport.php" method="post">
  <!-- Ajax cssの為の基本HTML構造設定 -->
  <div class=buttons-container>
    <div class="select-form" id="SF">
        <select name="YearMonth" id="YearMonth">
          <option selected></option>
        </select>
        <button type="button" name="search" id="search" class="search-btn">検索</button>
    </div>
    <div class="button-form" id="BF">
      <!-- <input type=button name="modify" id="modify" onclick='DBmodify()' class="modify-btn" value=""/> -->
      <input type=submit name="ExcelExport" class="download-btn" value="DL"></input>
    </div>
  </div>
    <!-- Ajax table -->
    <!-- 以下のテーブルの内容(Attendances_dailyだけ)は現在の運営には特にいらないが、開発した時のテスト用テーブルなので残します -->
    <div class="timeSheet-body">
    <table id="refresh">
      <tr>
        <th class="small-cell">日</th>
        <th class="small-cell">曜</th>
        <th>勤務地</th>
        <th>出勤</th>
        <th>退勤</th>
        <th>休憩</th>
        <th>残業</th>
      </tr>
      <!-- <tr>
        <td></td>
        <td></td>
        <td>ローディング...</td>
        <td>ローディング...</td>
        <td>ローディング...</td>
        <td>ローディング...</td>
        <td></td>
      </tr> -->
      <input type=hidden name="workdata" value=<?=json_encode($workarray) ?>></input>
      <input type=hidden name="YM" value=<?=$year.$month ?>></input>
      <tr class="memo-cell">
        <th colspan="2">備考</th>
        <!-- 180612 textArea width auto -->
        <td colspan="4"><textarea style="width:100%;" name="note" id="note" rows="8" cols="80"></textarea></td>
        <td><input type=button value="内容保存" onclick='SaveNote()' class="note-btn"></input></td>
      </tr>
    </table>
    </div>
  </form>
  </div>
</div>

  <!-- ================================== Firebase動作 ================================== -->
  <script>
  var Attendances_dailyRef = "";
  var Attendances_monthlyRef = "";
  var UsersRef = "";
  var SelectYM = "";
  var Notevalue = "";
  var date = ""; //180611 修正

  // Firebase Lodaing....
  $(window).load(function() {
    /* UserAuth Check */
    <?php if(empty($_GET["AuthUser"])) { ?> //$_GET Empty Check(Menuから入った場合)
      auth.onAuthStateChanged(function(user){ //Authentication ChangeCheck(Sessioncheck = Login Check)
        if (user) { //Authentication User is signed in. → don't need Authentication
          userInfo = user.uid; //情報をみたいユーザのuid

          database.ref('Users/' + userInfo + '/authority_id').on('value', function(Auth) {
            Lauth = Auth.val();  //ログインしているユーザーの権限
            Pageloading(Lauth);
          });
        } else { //Authentication No user is signed in. → need Authentication
          alert("正しい接近ではありません! 初期画面に移動します!");
          window.location.href = "log/mLogin.php"; //Login画面(Test:index.php)
        }
      })
    <?php } else { ?> //管理者画面から入った場合
      auth.onAuthStateChanged(function(user){ //Authentication ChangeCheck(Sessioncheck = Login Check)
        if (user) { //Authentication User is signed in. → don't need Authentication
          LuserInfo = user.uid; //ログインしているユーザのuid
          userInfo = "<?=$_GET["AuthUser"]?>";  //情報をみたいユーザのuid

          //180621 jhkim ログインしているユーザの権限を習得
          database.ref('Users/' + LuserInfo + '/authority_id').on('value', function(Auth) {
            Lauth = Auth.val();  //ログインしているユーザーの権限
            Pageloading(Lauth);
          });

        } else { //Authentication No user is signed in. → need Authentication
          alert("正しい接近ではありません! 初期画面に移動します!");
          window.location.href = "log/mLogin.php"; //Login画面(Test:index.php)
        }
      })
    <?php } ?>

    /* Pageloading code */
    function Pageloading(Lauth) {
      //update and refresh
      Attendances_dailyRef = database.ref('Attendances_daily/' + userInfo );
      Attendances_monthlyRef = database.ref('Attendances_monthly/' + userInfo );
      UsersRef = database.ref('Users/' + userInfo );
      SelectYM = <?=$year.$month?>; //SelectYM Save
      Notevalue = "";

      /* 180621 Traffics 追加 */
      TrafficsRef = database.ref('Traffics/' + userInfo );

      /* Firebase on, once Diffrence Practice */
      Attendances_monthlyRef.on('value', function(data){  //onで毎回呼び出す monthly時代のデータ
          Attendances_monthly = data.val();
      })

      Attendances_monthlyRef.once('value', function(data){
        //180611 attendances_memo null Exception(最新のデータがない場合の例外)
        if(Attendances_monthly[<?=$year.$month?>] != null) {
          date = <?=$year.$month?>
        } else {
          date = <?=$year.$month-1?>;
        }

        Mcheck = data.val()[date];  // 180621 jhkim 修正バグException

        Notevalue = Attendances_monthly[date]["attendances_memo"]; //Notevalue save
        $.ajax({
            type: "POST", //データ送信形式
            url: "timeSheetSelect.php", //請求される場所 -> つまり、データを取る場所です
            data : {"YearMonth": date, //洗濯した年月 -> JSON形式 (180611 dateに修正)
                    "Attendances_monthly": Attendances_monthly
                   }, //urlに送る Parameter
            success: function(datas){
              $("#SF").html(datas); //戻り値 -> テーブルに出力
            },
            error: function(xhr, status, error) {
              alert(error);
            }
        })
      })

      //180621 Add Traffics Data(Call back)
      TrafficsRef.on('value', function(data) {
        Traffics = data.val();

        UsersRef.on('value', function(data) {
        Users = data.val();
        WPA = Users["workplace"]; //180629 Add workplace info
        if(Lauth == 0) { //使用者の場合、メニューから管理者が見えないようにする
          var lis = document.getElementsByTagName('li');
          document.getElementById('menu').removeChild(lis[3]);
        }

        $.ajax({
            type: "POST", //データ送信形式
            url: "timeSheetButton.php", //請求される場所 -> つまり、データを取る場所です
            data : {
                    "Users": Users,
                    "Traffics": Traffics,
                    "Auth": Lauth
                   }, //urlに送る Parameter(180621 ログインしているユーザーの権限も追加)
            success: function(datas){
              $("#BF").html(datas); //戻り値 -> テーブルに出力
              $("#title").text("シフト履歴("+data.val()["user_name"]+")");  //180622 titleの横に名前をつける
            },
            error: function(xhr, status, error) {
              alert(error);
            }
          })
        })
      })

      /* Firebase on, once Diffrence Practice */
      Attendances_dailyRef.on('value', function(data){  //onで毎回呼び出す monthly時代のデータ
          Attendances_daily = data.val();
      })

      Attendances_dailyRef.once('value', function(data){ //180611 attendances_memo null Exception
        $.ajax({
          type: "POST", //データ送信形式
          url: "timeSheetSearchTable.php", //請求される場所 -> つまり、データを取る場所です(テーブルの中身が必要)
          data : {"YearMonth": date, //洗濯した年月 -> JSON形式 (180611 dateに修正)
                  "Attendances_daily": Attendances_daily,
                  "Notevalue": Notevalue
                 }, //urlに送る Parameter
          success: function(datas){
            $("#refresh").html(datas); //戻り値 -> テーブルに出力
            $('#owa').text("総残業時間："+$('#overtimealldata').val()); // 180621 総残業時間
          },
          error: function(xhr, status, error) {
            alert(error);
          }
        })
      });
    }
  });
  /* Pageloading code */

  /* Ajax for Search - TimeSheetType */
  $(document).on('click', '.search-btn', function(){
      if(document.getElementById("modify")) { //ユーザーの場合の確認
        document.getElementById("modify").value = "修正";
      }
      SelectYM = document.getElementById("YearMonth").value; //SelectYM Save
      SelectYM = SelectYM.replace(/年/gi, "");
      SelectYM = SelectYM.replace(/月/gi, ""); //SelectYM Save, 180605年月修正に従ってコード修正
      Notevalue = Attendances_monthly[SelectYM]["attendances_memo"]; //Notevalue save
      $.ajax({
          type: "POST", //データ送信形式
          url: "timeSheetSearchTable.php", //請求される場所 -> つまり、データを取る場所です
          data : {"YearMonth": SelectYM, //洗濯した年月 -> JSON形式
                  "Attendances_daily": Attendances_daily,
                  "Notevalue": Notevalue
                 }, //urlに送る Parameter
          success: function(datas){
            $("#refresh").html(datas); //戻り値 -> テーブルに出力
            $('#owa').text("総残業時間："+$('#overtimealldata').val()); // 180621 総残業時間
          },
          error: function(xhr, status, error) {
            alert(error);
          }
      })
  })

  /* Ajax For Modify - TimeSheetType */
  $(document).on('click', '.modify-btn', function(){
    var buttonText = document.getElementById("modify").value; //Onclickより早く作動しますのでLocal変数に作りました。
    SelectYM = document.getElementById("YearMonth").value; //SelectYM Save
    SelectYM = SelectYM.replace(/年/gi, "");
    SelectYM = SelectYM.replace(/月/gi, ""); //SelectYM Save, 180621

    // 180621 年月情報チェック
    if(Mcheck == null) {
      alert("年月の情報がありません!");
    } else {
      if(buttonText == "修正") { //修正の時
        document.getElementById("modify").value = "修正終了";
        $.ajax({
          type: "POST", //データ送信形式
          url: "timeSheetModifyTable.php", //請求される場所 -> つまり、データを取る場所です
          data : {"YearMonth": SelectYM, //選択した年月
                  "Attendances_daily": Attendances_daily,
                  "Notevalue": Notevalue
                 }, //urlに送る Parameter
          success: function(datas){
            $("#refresh").html(datas); //戻り値 -> テーブルに出力
          },
          error: function(xhr, status, error) {
            alert(error);
          }
        })
      } else { //修正完了の時
      document.getElementById("modify").value = "修正";
      $.ajax({
          type: "POST", //データ送信形式
          url: "timeSheetSearchTable.php", //請求される場所 -> つまり、データを取る場所です
          data : {"YearMonth": SelectYM, //選択した年月  -> JSON形式
                  "Attendances_daily": Attendances_daily,
                  "Notevalue": Notevalue
                 }, //urlに送る Parameter
          success: function(datas){
            $("#refresh").html(datas); //戻り値 -> テーブルに出力
            $('#owa').text("総残業時間："+$('#overtimealldata').val()); // 180621 総残業時間
          },
          error: function(xhr, status, error) {
            alert(error);
          }
        })
      }
    }
  })
  </script>

  <!-- ================================== JS関数 ================================== -->
  <script>
    /*
     * Firebase Database update function
     * 180621 jhkim
     * 未入力の場合にも修正して入力できるように使う
     * ただし、その月に一度だけの出勤はやらなきゃ修正できません
     * (一度も出勤しなかったのに修正は理解できません)
     */
    function DBmodify() {
      var buttonText = document.getElementById("modify").value;
      if(buttonText == "修正終了") {
        <?php //print ALL monthlyDays
        for($i=1;$i<$monthlyDay+1;$i++) {
        if($i < 10) $i = "0".$i;
        ?>

        var Pattern = /^([1-9]|[01][0-9]|2[0-9]|3[0-9])[:]([0-5][0-9])$/; //時間形式(元24 -> 39時間までに変更)
        var RTPattern = /^[0-9][:]([0-5][0-9])$/; //時間形式
        var SelectYMD = SelectYM + '<?=$i?>'; //年月日付

        WP = document.getElementById("WPid<?=$i?>").value;
        ST = document.getElementById("STid<?=$i?>").value;
        ET = document.getElementById("ETid<?=$i?>").value;
        RT = document.getElementById("RTid<?=$i?>").value;

        if(WP == "" && ST == "" && ET == "" && RT == "") { //データ一つもないなら修正しない、すでにあるデータ削除
          Attendances_dailyRef.update({  // == Attendances_dailyRef.SelectYMD.remove({ //... })
            [SelectYMD]: {
              workplace: null,
              start_time: null,
              end_time: null,
              rest_time: null
            },
          });
        }
        else { //データが一つだけでもあったら
          if(Pattern.test(ST)) { //修正文字形Check(正規表現式)
            if(Pattern.test(ET)) {
              if(RTPattern.test(RT)) {
                Attendances_dailyRef.update({  // == Attendances_dailyRef.SelectYMD.set({ //... })
                  [SelectYMD]: {
                    workplace: WP,
                    start_time: ST,
                    end_time: ET,
                    rest_time: RT
                  },
                });
              } else { alert(<?=$i?>+"日の休憩時間が正しくないです。"); }
            } else { alert(<?=$i?>+"日の退勤時間が正しくないです。"); }
          } else { alert(<?=$i?>+"日の出勤時間が正しくないです。"); }
        }
        <?php
        } ?>
      }
    }

    /* Note Value Save */
    function SaveNote() {
      if(Mcheck == null) { //180622 add nullcheck
        alert("年月の情報がありません!");
      } else {
        Attendances_monthlyRef.child(SelectYM).once("value", function(snap){ //一つのデータだけに入れる
          snap.ref.update({
              attendances_memo: $("textarea").val()
          })
        })
        alert("保存完了");
      }
    }

    /* Ajax loading function(JQuery) */
    var $loading = $('#loading');
    $(document)
    .ajaxStart(function () {
      $loading.show();
    })
    .ajaxStop(function () {
      $loading.hide();
    });

    /* 180627 時間修正リスト追加 */
    function InsertTimeInfo() {
      //alert(document.getElementsByName("modifycheck")[0].checked);

      SH = document.getElementById("STAllHour").value;
      SM = document.getElementById("STAllMinute").value;
      EH = document.getElementById("ETAllHour").value;
      EM = document.getElementById("ETAllMinute").value;
      RH = document.getElementById("RTAllHour").value;
      RM = document.getElementById("RTAllMinute").value;

      SHM = SH+":"+SM;
      EHM = EH+":"+EM;
      RHM = RH+":"+RM;

      <?php //print ALL monthlyDays
      for($i=1;$i<$monthlyDay+1;$i++) {
        if($i < 10) $i = "0".$i;
      ?>
        if(document.getElementById("modifycheck<?=$i?>").checked) {
          document.getElementById("WPid<?=$i?>").value = WPA; // 180629 Add User Workplace info
          document.getElementById("STid<?=$i?>").value = SHM;
          document.getElementById("ETid<?=$i?>").value = EHM;
          document.getElementById("RTid<?=$i?>").value = RHM;
        }
      <?php
      }
      ?>
    }
  </script>
</body>
</html>
