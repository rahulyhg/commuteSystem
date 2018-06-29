$(document).ready(function() {
  $("#costs").keyup(function() {
    $(this).val($(this).val().replace(/[^0-9]/gi, ""));
  }); //数字だけ
  $("#datetime").keyup(function() {
    $(this).val($(this).val().replace(/[^0-9:\-]/gi, ""));
  }); //数字とー
  $("#eng").keyup(function() {
    $(this).val($(this).val().replace(/[^a-z0-9:\-_]/gi, ''));
  }); //数字,　－、 英語, ＿,　韓国語(X)
});


//password　check
function onkeyCheck(obj) {
  password = $('#password').val();
  for (var i = 0; i < obj.value.length; i++) {
    if (escape(obj.value.charAt(i)).length > 4) {
      if (password) {
        document.getElementById("password").placeholder = "半角でご入力ください。";
      }
      document.getElementById("notice").textContent = "パスワードは半角でご入力ください。";
      obj.value = obj.value.substr(0, i);
      obj.focus();
    }
  }
}


//特殊キー
$(document).ready(function() {
  $("#birthday").keyup(function(){ $(this).val($(this).val().replace(/[^0-9]/gi,"") ); }); //数字だけ
   //$("#user_name").keyup(function(){ $(this).val($(this).val().replace(/[^0-9:\-]/gi,"") ); }); //数字、(-)
   $("#name_kana").keyup(function(){ $(this).val($(this).val().replace(/[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi,'') ); }); //数字、, (-), 英語, (_),韓国語(X)
   $("#user_name").keyup(function(){ $(this).val($(this).val().replace(/[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]/gi,'') ); }); //数字、, (-), 英語, (_
    $("#user_id").keyup(function(){ $(this).val($(this).val().replace(/[^0-9]/gi,"") ); }); //数字だけ
 });


//全角を半角
function onkeyCheck(obj){
  email = $('#email').val();
  user_id = $('#user_id').val();
  password = $('#password').val();
  password2 = $('#password2').val();
  birthday = $('#birthday').val();

  for(var i=0; i< obj.value.length; i++) {
  if (escape(obj.value.charAt(i)).length > 4){
  if(email){
    document.getElementById("email").placeholder ="半角でご入力ください。";
} if(password){
document.getElementById("password").placeholder ="半角でご入力ください。";
}if(password2){
document.getElementById("password2").placeholder ="半角でご入力ください。";
} if(birthday){
document.getElementById("birthday").placeholder ="半角でご入力ください。";
} if(user_id){
document.getElementById("user_id").placeholder ="半角でご入力ください。";
}
document.getElementById("errorEmail").textContent ="メール, パスワード, 社員No., 生年月日は半角でご入力ください。";
  obj.value = obj.value.substr(0, i);
  obj.focus();
   }
   }
}
