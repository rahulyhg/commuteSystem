var firebaseEmailAuth; //firebase email 認証 モジュール全域の変数
var firebaseDatabase; //firebase db モジュール全域の変数
var userInfo; //登録したユーザの情報. object タイプ
var user;
var email; //
var user_id;
var user_name;
var name_kana;
var password;
var password2;
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
var userKey;
var register;
var userInfo2;


firebaseEmailAuth = firebase.auth();
firebaseDatabase = firebase.database();
//情報を呼び出す関数
  if (document.getElementById("titleCheck").textContent == "memInfo_Update") {
function userSessionCheck() {

    firebaseEmailAuth.onAuthStateChanged(function(user) {
        if (user) {
          firebaseDatabase.ref("Users/" + user.uid).once('value').then(function(snapshot) {
            user_name = snapshot.val().user_name;
            loginUserKey = snapshot.key;
            userInfo = snapshot.val();
            email = snapshot.val().email;
            name_kana = snapshot.val().name_kana;
            birthday = snapshot.val().birthday;
            workplace = snapshot.val().workplace;
            division = snapshot.val().division;
            occupation = snapshot.val().occupation;
            department = snapshot.val().department;
            user_id = snapshot.val().user_id;
            password = snapshot.val().password;
            authority_id = snapshot.val().authority_id;

            document.getElementById("login").textContent = "ログアウト";
            document.getElementById("login").href = "../log/mLogout.php";
            document.getElementById("user_name").textContent =  snapshot.val().user_name + " 様";
            document.getElementById("user_name").href = "memberInfo/mRegister.php";
                      if(authority_id == 1) {
                        var menu = document.getElementById('menu');
                        var lis = document.getElementsByTagName('li');
                      }else if(authority_id == 0){　//管理者　authority_id　が　0なら　出ない
                         var menu = document.getElementById('menu');
                         var lis = document.getElementsByTagName('li');
                        menu.removeChild(lis[3]);
                      }

                      //document.getElementById("notice").textContent = "修正が必要な場合は修正ボタン押してください。";
                      //document.getElementById("birthday").value = snapshot.val().birthday;
                      document.getElementById("department").value = snapshot.val().department;

                      //1.eamil
                      if (snapshot.val().email) {
                        document.getElementById("email").value = snapshot.val().email;
                        document.getElementById("email").color='gray';
                        //      console.log("there is email : " + email);
                      } else {
                        document.getElementById("email").placeholder = "入力してください。";
                        //        console.log("there is no email : " + email);
                      }

                      //2.user_name
                      if (snapshot.val().user_name) {
                        document.getElementById("user_name").value = snapshot.val().user_name;
                        ////      console.log("there is user_name : " + user_name);
                      } else {
                        //없으면 더미 데이터 넣어준다.
                        document.getElementById("user_name").placeholder = "入力してください。";
                        /////    console.log("there is no user_name : " + user_name);
                      }

                      //3.name_kana
                      if (snapshot.val().name_kana) {
                        document.getElementById("name_kana").value = snapshot.val().name_kana;

                        //console.log("there is name_kana : " + name_kana);
                      } else {
                        document.getElementById("name_kana").placeholder = "入力してください。";
                        //      console.log("there is no name_kana : " + name_kana);
                      }

                      //4.user_id
                      if (snapshot.val().user_id) {
                        document.getElementById("user_id").value = snapshot.val().user_id;
                        //      console.log("there is user_id : " + user_id);
                      } else {
                        document.getElementById("user_id").placeholder = "入力してください。";
                        //      console.log("there is no user_id : " + user_id);
                      }

                      //5.workplace
                      if (snapshot.val().workplace) {
                        document.getElementById("workplace").value = snapshot.val().workplace;
                        //    console.log("there is workplace : " + workplace);
                      } else {
                        document.getElementById("workplace").placeholder = "入力してください。";
                        //    console.log("there is no workplace : " + workplace);
                      }

                      //6.division
                      if (snapshot.val().division) {
                        document.getElementById("division").value = snapshot.val().division;
                        //    console.log("there is division : " + division);
                      } else {
                        document.getElementById("division").placeholder = "入力してください。";
                        //    console.log("there is no division : " + division);
                      }

                      //7.occupation
                      if (snapshot.val().occupation) {
                        document.getElementById("occupation").value = snapshot.val().occupation;
                        //    console.log("there is occupation : " + occupation);
                      } else {
                        document.getElementById("occupation").placeholder = "入力してください。";
                        //    console.log("there is no occupation : " + occupation);
                      }
          });

          firebaseDatabase.ref("Timesheet/" + user.uid).once('value').then(function(snapshot) {
            start_time = snapshot.val().start_time;
            end_time = snapshot.val().end_time;
            rest_time = snapshot.val().rest_time;

              //8.start_time
              if (snapshot.val().start_time) {
                document.getElementById("start_time").value = snapshot.val().start_time;
                //    console.log("there is start_time : " + start_time);
              } else {
                document.getElementById("start_time").value = "00:00";
                //    console.log("there is no start_time : " + start_time);
              }

              //9.end_time
              if (snapshot.val().end_time) {
                document.getElementById("end_time").value = snapshot.val().end_time;
                //    console.log("there is end_time : " + end_time);
              } else {
                document.getElementById("end_time").value = "00:00";
                //    console.log("there is no end_time : " + end_time);
              }

              //10.rest_time
              if (snapshot.val().rest_time) {
                document.getElementById("rest_time").value = snapshot.val().rest_time;
                //    console.log("there is rest_time : " + rest_time);
              } else {
                document.getElementById("rest_time").value = "00:00";
                //    console.log("there is no rest_time : " + rest_time);
              }
          });

          firebaseDatabase.ref("Traffics/" + user.uid).once('value').then(function(snapshot) {
            arrival_station = snapshot.val().arrival_station;
            departure_station = snapshot.val().departure_station;
            commutation_ticket = snapshot.val().commutation_ticket;
            costs = snapshot.val().costs;
              //11.departure_station
              if (snapshot.val().departure_station) {
                document.getElementById("departure_station").value = snapshot.val().departure_station;
                //  console.log("there is departure_station : " + departure_station);
              } else {
                document.getElementById("departure_station").placeholder = "入力してください。";
                //  console.log("there is no departure_station : " + departure_station);
              }

              //12.arrival_station
              if (snapshot.val().arrival_station) {
                document.getElementById("arrival_station").value = snapshot.val().arrival_station;
                //  console.log("there is arrival_station : " + arrival_station);
              } else {
                document.getElementById("arrival_station").placeholder = "入力してください。";
                //  console.log("there is no arrival_station : " + arrival_station);
              }

              //13.Rate
              if (snapshot.val().commutation_ticket) {
                document.getElementById("commutation_ticket").value = snapshot.val().commutation_ticket;
                //  console.log("there is commutation_ticket : " + commutation_ticket);
              } else {
                document.getElementById("commutation_ticket").placeholder = "定期券 3ヶ月/半年定期は未入力";
                //  console.log("there is no commutation_ticket : " + commutation_ticket);
              }

              //14.costs
              if (snapshot.val().costs) {
                document.getElementById("costs").value = snapshot.val().costs;
                //  console.log("there is costs : " + costs);
              } else {
                document.getElementById("costs").placeholder = "数字で入力してください。";
                //  console.log("there is no costs : " + costs);
              }

          });

        } else {  //if there is no user
          alert("ログインしてください。");
          window.location = '../log/mLogin.php'
          return;
        }
    });
} // END OF userSessionCheck


//データにいれる関数
function imageStateMsgAllSave() {
  email = document.getElementById("email").value;
  user_name = document.getElementById("user_name").value;
  name_kana = document.getElementById("name_kana").value;
  user_id = document.getElementById("user_id").value;
  //birthday = document.getElementById("birthday").value;
  workplace = document.getElementById("workplace").value;
  start_time = document.getElementById("start_time").value;
  end_time = document.getElementById("end_time").value;
  rest_time = document.getElementById("rest_time").value;
  costs = document.getElementById("costs").value;
  departure_station = document.getElementById("departure_station").value;
  arrival_station = document.getElementById("arrival_station").value;
  commutation_ticket = document.getElementById("commutation_ticket").value;
  occupation = document.getElementById("occupation").value;
  division = document.getElementById("division").value;
  department = document.getElementById("department").value;


  logUser();

  function logUser() {
    //var ref = firebaseDatabase.ref("Commute/users/"+loginUserKey);
    var Users1 = firebaseDatabase.ref("Users/" + loginUserKey);
    var Traffics1 = firebaseDatabase.ref("Traffics/" + loginUserKey);
    var Timesheet1 = firebaseDatabase.ref("Timesheet/" + loginUserKey);

    var Users2 = {
      authority_id: authority_id,
      email: email,
      user_id: user_id,
      user_name: user_name,
      name_kana: name_kana,
      //password: password,
      //birthday: birthday,
      occupation: occupation,
      division: division,
      department: department,
      workplace: workplace,
    };

    var Traffics2 = {
      departure_station: departure_station,
      arrival_station: arrival_station,
      commutation_ticket: commutation_ticket,
      costs: costs,
    };

    var Timesheet2 = {
      start_time: start_time,
      end_time: end_time,
      rest_time: rest_time,
    };

    Users1.set(Users2);
    Traffics1.set(Traffics2);
    Timesheet1.set(Timesheet2);

    alert("修正成功");
    window.location.href = "mMemInfo.php"
    window.close();
  }

  // }  //END OF if (fileButton)
  return true;
}


//パスワードを変更する関数
$(document).on('click', '#password', function() {
  var emailAddress = $('#email').val();
  firebaseEmailAuth.sendPasswordResetEmail(emailAddress).then(function() {
    // Email sent.
    alert("パスワード変更のURLを送りました。");
  }).catch(function(error) {
    // An error happened.
    alert("メール送信失敗");
  });

});
}
/////////////////////////////////////////////////////Register//////////////////////////////////////////////////////////////////
else if (document.getElementById("titleCheck").textContent == "Register") {
//新規登録関数
  $(document).on('click','.join', function(){
  email = $('#email').val();
  user_id = $('#user_id').val();
  user_name = $('#user_name').val();
  name_kana = $('#name_kana').val();
  password = $('#password').val();
  password2 = $('#password2').val();
  //birthday = $('#birthday').val();
  // var workplace = $('#workplace').val();
  // var start_time = $('#start_time').val();
  // var end_time = $('#end_time').val();
  // var rest_time = $('#rest_time').val();
  // var department = $('#department').val();
  // var departure_station = $('#departure_station').val();
  // var arrival_station = $('#arrival_station').val();
  // var commutation_ticket = $('#commutation_ticket').val();
  // var costs = $('#costs').val();
  // var occupation = $('#occupation').val();
  // var division = $('#division').val();

  var secondaryApp = firebase.initializeApp(config, "Secondary");

  secondaryApp.auth().createUserWithEmailAndPassword(email, password).then(function(firebaseUser) {
      userInfo = firebaseUser;
      logUser();
      secondaryApp.auth().signOut();
  }, function(error) {
  var errorCode = error.code;
  var errorMessage = error.message;
  alert(errorMessage);

  document.getElementById("errorEmail").textContent ="メールには＠が必要です。パスワードは６個以上です。";
  });

  /////
  function logUser(){
  //var ref = firebaseDatabase.ref("Authority/1/"+userInfo.uid);
  var Users1 = firebaseDatabase.ref("Users/"+userInfo.uid);
  var Traffics1 = firebaseDatabase.ref("Traffics/"+userInfo.uid);
  var Timesheet1 = firebaseDatabase.ref("Timesheet/"+userInfo.uid);

  var Users2 = {
    authority_id: "0",
    email: email,
    user_id: user_id,
    user_name: user_name,
    name_kana: name_kana,
    password: password,
    password2: password2,
    //birthday: birthday,
    occupation: "",
    division : "",
    department : "",
    workplace: "",
  };

  var Traffics2 = {
      departure_station: "",
      arrival_station: "",
      commutation_ticket: "",
      costs: "",
    };

    var Timesheet2 = {
      start_time: "",
      end_time: "",
      rest_time: "",
    };
  //  ref.set(obj);
  Users1.set(Users2);
  Traffics1.set(Traffics2);
  Timesheet1.set(Timesheet2);

  alert("登録成功");
  window.close();
  }
  /////
  }); //END OF $(document).on('click','.join',function()
}
