//menu
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
//dropDown
num = 0;
$(document).ready(function(){
 $(".headC").on('click', function(){
    $(".headD").animate({width:"toggle"}, 200);

num++;
  if(num % 2 == 0){
          //jQuery('#llist').show();
          //$("#llist").text("勤務先の詳細項目")
          if (document.getElementById("titleCheck").textContent == "memInfo_Update")     $("#llist").text("勤務先の詳細項目")
          else if(document.getElementById("titleCheck").textContent == "Register")       $("#llist").text("追加の情報")
          document.getElementById("headC").innerHTML = '<span class="fa fa-angle-down"></span>';
      } else {
          //jQuery('#llist').hide();
          $("#llist").text("リストを閉じる")
          document.getElementById("headC").innerHTML = '<span class="fa fa-angle-up"></span>';
   //        var btn = document.createElement("BUTTON");
   // document.body.appendChild(btn);
}

  });
  });
