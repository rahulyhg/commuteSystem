<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title id="titleCheck">Register</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://use.fontawesome.com/926fe18a63.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/4.13.0/firebase.js"></script>
<!-- <script src="https://www.gstatic.com/firebasejs/5.0.4/firebase.js"></script> -->
<script src="../js/db.js"></script>
<script src="../js/ptsJs/toggle.js"></script>
<script src="../js/ptsJs/restriction.js"></script>
  <script src="../js/ptsJs/sessionCheck.js"></script>
<link rel="stylesheet" href="../css/ptsCss/mRegister.css">
<link rel="stylesheet" href="../css/ptsCss/menu.css">

  <script> //パスワード一致確認
       function verifynotify(field1, field2, result_id, basic_html, match_html, nomatch_html) {
         this.field1 = field1;
         this.field2 = field2;
         this.result_id = result_id;
         this.basic_html = basic_html;
         this.match_html = match_html;
         this.nomatch_html = nomatch_html;

         this.check = function() {
           // Make sure we don't cause an error
           // for browsers that do not support getElementById
           if (!this.result_id) { return false; }
           if (!document.getElementById){ return false; }
           r = document.getElementById(this.result_id);
           if (!r){ return false; }

           if (this.field1.value != "" && this.field1.value == this.field2.value ) {
             r.innerHTML = this.match_html;
           } else if(this.field1.value != null && this.field2.value != null) {
             r.innerHTML = this.basic_html;

             if(this.field1.value != this.field2.value) r.innerHTML = this.nomatch_html;

           }
         }
       }

       function verifyInput() {
         verify = new verifynotify();
         verify.field1 = document.form.password;
         verify.field2 = document.form.password2;
         verify.result_id = "errorEmail";
         verify.basic_html = "*は必須項目です。";
         verify.match_html = "パスワードOK";
         verify.nomatch_html = "パスワードが一致しません。";
         verify.check();
       }

       function addLoadEvent(func) {
         var oldonload = window.onload;
         if (typeof window.onload != 'function') {
           window.onload = func;
         } else {
           window.onload = function() {
             if (oldonload) {
               oldonload();
             }
             func();
           }
         }
       }

       addLoadEvent(function() {
         verifyInput();
       });

  </script>
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
                      <li><a href="mMemInfo.php" class="menu-toggle">個人情報</a></li>
                      <li><a href="../timeSheet.php" class="menu-toggle">履歴</a></li>
                      <li><a href="admin.php" id="admin" class="menu-toggle">管理者</a></li>
                      <li><a href="../log/mLogin.php" id="login" class="menu-toggle">ログイン</a></li>
                  </ul>
                </nav>
              </div>
        </header>
      </div>
    <div class="register">
      <form class="form" id="form" name="form" >
         <table class="table">
           <tr class="tr "><th class="th" colspan="2"><h3 id="privatePage">新規個人情報</h3></th></tr>
           <tr class="tr" id="error">
             <td class="td" colspan="2" id="errorEmail"></td>
           </tr>
           <tr class="tr nece">
             <th class="th"><label for="email">*E-mail</label></th>
             <td class="td"><input type="email" name="#" id="email" onkeyup="onkeyCheck(this)"/></td>
           </tr>
           <tr class="tr nece">
             <th class="th"><label for="user_id">*社員No.</label></th>
             <td class="td"><input type="text" name="#" id="user_id" value="" onkeyup="onkeyCheck(this)"/></td>
           </tr>
           <tr class="tr nece">
             <th class="th"><label for="user_name">*氏名</label></th>
             <td class="td"><input type="text" name="#" id="user_name" /></td>
           </tr>
           <tr class="tr nece">
             <th class="th"><label for="name_kana">*氏名(カナ)</label></th>
             <td class="td"><input type="text" name="#" id="name_kana"  /></td>
           </tr>
           <tr class="tr nece">
             <th class="th"><label for="password">*パスワード</label></th>
             <td class="td"><input type="password" name="password" id="password"  onkeyup="verify.check()"/></td>
           </tr>
           <tr class="tr nece">
             <th class="th"><label for="password">*再確認</label></th>
             <td class="td"><input type="password" name="password2" id="password2"  onkeyup="verify.check()"/></td>
           </tr>
           <!-- <tr class="tr nece">
             <th class="th"><label for="birthday">生年月日</label></th>
             <td class="td"><input type="text" name="#" id="birthday" onkeyup="onkeyCheck(this)"/></td>
           </tr> -->
           <!-- <tr class="tr nece">
             <th class="th"><button type="button" class="headC" id="headC">
               <span class="fa fa-angle-down"></span>
             </button></th>
             <td class="td" id="llist">追加の情報</td>
           </tr>

           <tr class="tr unnece headD">
             <th class="th"><label for="workplace">勤務先</label></th>
             <td class="td" ><input type="hidden" name="workplace" id="workplace" readOnly/></td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="start_time">勤務時間</label></th>
             <td class="td"><input type="hidden" name="#" id="start_time" readOnly/>&nbsp;~&nbsp;<input type="time" name="#" id="end_time" readOnly/></td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="rest_time">休憩時間</label></th>
             <td class="td"><input type="hidden" name="#" id="rest_time" readOnly/></td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="departure_station">自宅最寄駅</label></th>
             <td class="td"><input type="hidden" name="#" id="departure_station" readOnly/></td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="arrival_station">会社最寄駅</label></th>
             <td class="td"><input type="hidden" name="#" id="arrival_station" readOnly/></td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="costs">電車賃</label></th>
             <td class="td"><input type="hidden" name="#" id="costs" onkeyup="onkeyCheck(this)" readOnly/></td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="commutation_ticket">交通費備考</label></th>
             <td class="td"><input type="hidden" name="#" id="commutation_ticket" readOnly/></td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="department">所属</label></th>
             <td class="td"><input type="hidden" name="#" id="department" value="新規事業開発部" readonly/></td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="division">区分</label></th>
             <td class="td">
               <select id="division" name="division" style="width:100%; height:30px;" readOnly>
               <option value="">選んでください</option>
               <option value="社員">社員</option>
               <option value="スタップ">スタッフ</option>
               </select>
           </td>
           </tr>
           <tr class="tr unnece headD">
             <th class="th"><label for="occupation">業務</label></th>
             <td class="td"><input type="text" name="#" id="occupation" readOnly/></td>
           </tr> -->
             <td class="td" colspan="2"><button type="button" id="join" class="join" >登録 </button></td>
         </table>
        </form>
    </div>
    </div>
  </section>

  <script>

  firebaseEmailAuth = firebase.auth();
  firebaseDatabase = firebase.database();

  $(document).ready(function(){

  userSessionCheck();
//   $(document).on('click','.join', function(){
//   email = $('#email').val();
//   user_id = $('#user_id').val();
//   user_name = $('#user_name').val();
//   var name_kana = $('#name_kana').val();
//   password = $('#password').val();
//   password2 = $('#password2').val();
//   birthday = $('#birthday').val();
//   var workplace = $('#workplace').val();
//   var start_time = $('#start_time').val();
//   var end_time = $('#end_time').val();
//   var rest_time = $('#rest_time').val();
//   var department = $('#department').val();
//   var departure_station = $('#departure_station').val();
//   var arrival_station = $('#arrival_station').val();
//   var commutation_ticket = $('#commutation_ticket').val();
//   var costs = $('#costs').val();
//   var occupation = $('#occupation').val();
//   var division = $('#division').val();
//
//   var secondaryApp = firebase.initializeApp(config, "Secondary");
//
//   secondaryApp.auth().createUserWithEmailAndPassword(email, password).then(function(firebaseUser) {
//       userInfo = firebaseUser;
//       logUser();
//       secondaryApp.auth().signOut();
//   }, function(error) {
//   var errorCode = error.code;
//   var errorMessage = error.message;
//   alert(errorMessage);
//
//   document.getElementById("errorEmail").textContent ="メールには＠が必要です。パスワードは６個以上です。";
//   console.log("ss")
//   });
//
// /////
// function logUser(){
// //var ref = firebaseDatabase.ref("Authority/1/"+userInfo.uid);
//   var Users1 = firebaseDatabase.ref("Users/"+userInfo.uid);
//   var Traffics1 = firebaseDatabase.ref("Traffics/"+userInfo.uid);
//   var Timesheet1 = firebaseDatabase.ref("Timesheet/"+userInfo.uid);
//
//   var Users2 = {
//     authority_id: "0",
//     email: email,
//     user_id: user_id,
//     user_name: user_name,
//     name_kana: name_kana,
//     password: password,
//     password2: password2,
//     birthday: birthday,
//     occupation: occupation,
//     division : division,
//     department : department,
//     workplace: workplace,
//   };
//
//   var Traffics2 = {
//       departure_station: departure_station,
//       arrival_station: arrival_station,
//       commutation_ticket: commutation_ticket,
//       costs: costs,
//     };
//
//     var Timesheet2 = {
//       start_time: start_time,
//       end_time: end_time,
//       rest_time: rest_time,
//     };
// //  ref.set(obj);
// Users1.set(Users2);
// Traffics1.set(Traffics2);
// Timesheet1.set(Timesheet2);
//
// alert("登録成功");
// console.log(" created successfully!");
//  window.close();
// }
// /////
//   }); //END OF $(document).on('click','.join',function()
  }); //END OF $(document).ready(function()


var authProvider = new firebase.auth.GoogleAuthProvider();

document.getElementById("email").placeholder = "例)abc@mercury-group.co.jp";
document.getElementById("user_name").placeholder = "例)田中";
//document.getElementById("birthday").placeholder = "例)19940513";
document.getElementById("name_kana").placeholder = "例)タナカ";
document.getElementById("user_id").placeholder = "例)123456";
document.getElementById("password").placeholder = "入力してください。";
document.getElementById("password2").placeholder = "パスワード再確認";

//
//session check
function userSessionCheck() {
  firebaseEmailAuth.onAuthStateChanged(function(user) {
    if (user) {
      firebaseDatabase.ref("Users/" + user.uid).once('value').then(function(snapshot) {
        authority_id = snapshot.val().authority_id;
        if(authority_id == 1) {
          document.getElementById("login").textContent = "ログアウト";
          document.getElementById("login").href = "../log/mLogout.php";
          document.getElementById("user_name").textContent =  snapshot.val().user_name + " 様";
          document.getElementById("user_name").href = "memberInfo/mRegister.php";
          console.log("1 : "+authority_id);
          document.getElementById("join").textContent = "登録";
        }else {
              alert("管理者のみのページです。");
             window.location = '../punch.php'
        }
      });
    } else {
          alert("管理者でログインしてください。");
          window.location = '../log/mLogin.php'
    }
  }); //firebaseEmailAuth.onAuthStateChanged
} //function userSessionCheck()

  </script>
</body>
</html>
