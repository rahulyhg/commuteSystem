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

// 選択された月のデータをダウンロード準備
function getvalue(frm) {
  var val = frm.ym.options[frm.ym.selectedIndex].value;
  window.location.href = 'adminDownload.php?YMs='+val;
}

// エンターキー防止
document.addEventListener('keydown', function(event) {
  if (event.keyCode === 13) {
      event.preventDefault();
  }
}, true);

// クエリストリングを取る為
function getQuerystring(key, default_){
  if (default_==null) default_="";
  key = key.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regex = new RegExp("[\\?&]"+key+"=([^&#]*)");
  var qs = regex.exec(window.location.href);
  if(qs == null)
    return default_;
  else
    return qs[1];
}

// 年月を選択した場合のデータチェンジ
function select(){
  var page_value = getQuerystring('page');

  if(page_value==""){
    page_value = 1;
  }
    $.ajax({
        type: "POST",
        url: "adminSearch.php?page="+page_value,
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
}
