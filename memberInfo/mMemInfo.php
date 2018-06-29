<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title id="titleCheck">memInfo_Update</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <script src="https://use.fontawesome.com/926fe18a63.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://www.gstatic.com/firebasejs/4.13.0/firebase.js"></script>
  <!--firebase-->
  <script src="../js/db.js"></script>
  <!--firebase-->
  <script src="../js/ptsJs/toggle.js"></script>
  <script src="../js/ptsJs/restriction.js"></script>
  <script src="../js/ptsJs/sessionCheck.js"></script>
  <link rel="stylesheet" href="../css/ptsCss/mMemInfo.css">
  <link rel="stylesheet" href="../css/ptsCss/menu.css">
</head>
<body>
  <section class="sectionB">
    <div class="shadow">
      <div class="overlay" id="overlay">
        <header>
          <div class="container">
            <div class="container-small">
              <button type="button" class="headB">
                    <span class="fa fa-bars"></span>
                  </button>
            </div>
            <nav class="headA">
              <ul id=menu>
                <li><a href="../punch.php" class="menu-toggle">出・退勤</a></li>
                <li><a href="mMemInfo.php" id="privateInfo" class="menu-toggle">個人情報</a></li>
                <li><a href="../timeSheet.php" class="menu-toggle">履歴</a></li>
                <li><a href="../admin.php" id="admin" class="menu-toggle">管理者</a></li>
                <li><a href="../log/mLogout.php" id="login" class="menu-toggle">ログアウト</a></li>
              </ul>
            </nav>
          </div>
        </header>
      </div>
      <div class="register">
        <form class="form" name="form">
          <table class="table">
            <tr class="tr">
              <th class="th" colspan="2">
                <h3 id="privatePage">個人情報</h3></th>
            </tr>
            <tr class="tr" id="error">
              <td class="td" colspan="2" id="notice"></td>
            </tr>
            <tr class="tr">
              <th class="th">E-mail</th>
              <td class="td"><input type="email" name="email" id="email" id="input" class="email" readonly/></td>
            </tr>
            <tr>
              <th class="th">社員No.</th>
              <td class="td"><input type="text" name="#" id="user_id" readonly/></td>
            </tr>
            <tr class="tr">
              <th class="th">氏名</th>
              <td class="td"><input type="text" name="#" id="user_name" id="input" /></td>
            </tr>
            <tr class="tr">
              <th class="th">氏名(カナ)</th>
              <td class="td"><input type="text" name="#" id="name_kana" /></td>
            </tr>
            <tr class="tr">
              <th class="th">パスワード</th>
              <td class="td"><input type="button" name="#" id="password" value="パスワードの変更はこちら" /></td>
            </tr>
            <!--<input type="text" name="#" id="password" onkeyup="onkeyCheck(this)" readonly/>-->
            <tr class="tr">
              <th class="th"><label for="workplace">勤務先</label></th>
              <td class="td" ><input type="text" name="workplace" id="workplace" /></td>
            </tr>
            <tr class="tr">
              <th class="th"><label for="start_time">勤務時間</label></th>
              <td class="td"><input type="time" name="#" id="start_time" />&nbsp;~&nbsp;<input type="time" name="#" id="end_time" /></td>
            </tr>
            <tr class="tr">
              <th class="th"><label for="rest_time">休憩時間</label></th>
              <td class="td"><input type="time" name="#" id="rest_time" /></td>
            </tr>
            <tr class="tr">
             <th class="th"><button type="button" class="headC" id="headC">
               <span class="fa fa-angle-down"></span>
             </button></th>
             <td class="td" id="llist">勤務先の詳細項目</td>
           </tr>
           <!-- <tr class="tr">
             <th class="th">生年月日</th>
             <td class="td"><input type="text" name="#" id="birthday" readonly/></td>
           </tr> -->
           <tr class="tr unnece headD">
             <th class="th"><label for="departure_station">自宅最寄駅</label></th>
             <td class="td"><input type="text" name="#" id="departure_station" /></td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="arrival_station">会社最寄駅</label></th>
             <td class="td"><input type="text" name="#" id="arrival_station" /></td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="costs">電車賃</label></th>
             <td class="td"><input type="text" name="#" id="costs" onkeyup="onkeyCheck(this)" /></td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="commutation_ticket">交通費備考</label></th>
             <td class="td"><input type="text" name="#" id="commutation_ticket" /></td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="department">所属</label></th>
             <td class="td"><input type="text" name="#" id="department" value="新規事業開発部"  readonly/></td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="division">区分</label></th>
             <td class="td">
               <select id="division" name="division" style="width:100%; height:30px;" >
               <option value="">--選んでください--</option>
               <option value="社員">社員</option>
               <option value="スタップ">スタッフ</option>
               </select>
           </td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="occupation">業務</label></th>
             <td class="td"><input type="text" name="#" id="occupation" /></td>
           </tr>
            <td class="td" colspan="2"><span><button type="button" id="update" class="update">完了</button></span><span><button type="reset" onclick='history.go(0); return false;' class="cancle">キャンセル</button></span></td>
          </table>
        </form>
      </div>
    </div>
  </section>
  <script>
    var authProvider = new firebase.auth.GoogleAuthProvider(); //google認証
    $(document).ready(function() {
      //session　check　関数
      userSessionCheck();
    });

    //修正のボタン　修正の関数
    $(document).on('click', '#update', function() {
      imageStateMsgAllSave();
    });
  </script>
</body>
</html>
