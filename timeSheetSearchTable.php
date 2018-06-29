<?php
/*
  180427
  POSTデータを呼び出してテーブルを更新(検索)します
  jhkim1
*/
date_default_timezone_set('Asia/Tokyo'); //  default地域設定
if(empty($_POST["Attendances_daily"])) {   // データがない時foreachのエラーメッセージが見えないようにする 180601
  $Days = array();
} else {
  $Days = $_POST["Attendances_daily"]; //Database Days Data
}
$SelectYear = substr($_POST["YearMonth"], 0, 4);
$SelectMonth = substr($_POST["YearMonth"], 4, 6);
$note = $_POST["Notevalue"];
//selected YearMonth in Timesheet
$SelectYM = $SelectYear.$SelectMonth;
$time = mktime(0, 0, 0, $SelectMonth, 1, $SelectYear);
$monthlyDay = date("t", $time); //一か月の最終日
$month = date("m", $time); // 現在の月
$year = date("Y", $time); // 現在の年
$strtotime = $year."-".$month."-1"; //毎月1日の曜日を求めるための変数
$dailyInt = date('w', strtotime($strtotime)); // date関数は0~6の数字をreturnする
$daily = array('日','月','火','水','木','金','土');
$workarray = array();
$weekcount = array(0,0,0,0,0,0,0);
$OTA = 0;

/* 春分の日 */
function Syunbun($y) {
 $check = $y % 4;
 $d = 21;

 if($y >= 1992 && year <= 2023) {
   if($check < 2) $d = 20;
 } else if($y >= 2024 && year <= 2055) {
   if($check < 3) $d = 20;
 } else if($y >= 2056 && year <= 2091) {
   $d = 20;
 }

 return $d;
}

/* 秋分の日 */
function Syuubun($y) {
 $check = $y % 4;
 $d = 23;

 if($y >= 2012 && year <= 2043) {
   if($check < 1) $d = 22;
 } else if($y >= 2044 && year <= 2075) {
   if($check < 2) $d = 22;
 } else if($y >= 2076 && year <= 2099) {
   if($check < 3) $d = 22;
 }

 return $d;
}

?>
    <table>
      <tr>
        <th class="small-cell">日</th>
        <th class="small-cell">曜</th>
        <th>勤務地</th>
        <th>出勤</th>
        <th>退勤</th>
        <th>休憩</th>
        <th>残業</th>
      </tr>
      <?php //print Days
      for($i=1;$i<$monthlyDay+1;$i++) {
      if($i < 10) $i = "0".$i; //日が 1~9の場合
      $day = $daily[$dailyInt]; //曜日を表すための変数
      $weekcount[$dailyInt] += 1; //その月のWeekCount
      foreach($Days as $key=>$value) { //日出力して比較
        $Daycheck = true;
        $WP = "";
        $ET = "";
        $ST = "";
        $RT = "";
        $OT = 0;

        /* 180621 jhkim アルゴリズム修正 */
        $STCal = 0;
        $ETCal = 0;

        if($year.$month.$i == $key) {
          $WP = $value["workplace"];
          $ST = $value["start_time"];
          $ET = $value["end_time"];
          $RT = $value["rest_time"];

          if(strlen($ST) == 4) { // 0:00
            $STCal = (substr($ST, 0, 1)*60+substr($ST, 2, 4));
          } else { // 00:00
            $STCal = (substr($ST, 0, 2)*60+substr($ST, 3, 5));
          }
          if(strlen($ET) == 4) { // 0:00
            $ETCal = (substr($ET, 0, 1)*60+substr($ET, 2, 4));
          } else { // 00:00
            $ETCal = (substr($ET, 0, 2)*60+substr($ET, 3, 5));
          }
          $OTCal = $ETCal - $STCal - (substr($RT, 0, 1)*60+substr($RT, 2, 4)) - 480;

          if($OTCal <= 0) {
            $OT = "";
            break;
          } else {
            $OTA += $OTCal; //総残業時間計算

            if(($OTCal/60) < 0) {
              $OTT = ceil($OTCal/60);
            } else{
              $OTT = floor($OTCal/60);
            }
            if($OTT == -0) $OTT = 0;  //-0 -> 0
            $OTM = $OTCal%60;
            if(strlen($OTM) == 1) $OTM = "0".$OTM; //180621

            //$OT = $OTT.'時間 '.$OTM.'分';//残業時間
            $OT = $OTT.':'.$OTM;//180618残業時間コード修正
            break;
          }
        } else {
          $Daycheck = false;
        }
      }
      ?>
      <tr>
        <?php
        /*
         * 180621 jhkim 土日祝日の情報
         *
         * 特定の日
         * 元日、建国記念の日、昭和の日、憲法記念日、緑の日、子供の日、山の日、文化の日、勤労感謝の日、天皇誕生日
         *
         * 特定週の日曜日
         * 成人の日、海の日、敬老のひ、体育の日
         *
         * 年によって違う
         * 春分の日、秋分の日
         */


        if($day == "日"
           || $month.$i == "0101" || $month.$i == "0211" || $month.$i == "0429"
           || $month.$i == "0503" || $month.$i == "0504" || $month.$i == "0505"
           || $month.$i == "0811" || $month.$i == "1103" || $month.$i == "1123"
           || $month.$i == "1223"
           || ($month == "01" && $weekcount[1] == "2" && $day == "月") /* 成人の日 */
           || ($month == "07" && $weekcount[1] == "3" && $day == "月") /* 海の日 */
           || ($month == "09" && $weekcount[1] == "3" && $day == "月") /* 敬老の日 */
           || ($month == "10" && $weekcount[1] == "2" && $day == "月") /* 体育の日 */
           || $month.$i == "03".Syunbun($year) /* 春分の日 */
           || $month.$i == "09".Syuubun($year) /* 秋分の日 */ ){ ?>
            <td class="small-cell" style="color: red;"><?=$i?></td>
            <td class="small-cell" style="color: red;"><?=$day?></td>
        <?php } else if($day == "土") { ?>
              <td class="small-cell" style="color: blue;"><?=$i?></td>
              <td class="small-cell" style="color: blue;"><?=$day?></td>
        <?php } else { ?>
            <td class="small-cell"><?=$i?></td>
            <td class="small-cell"><?=$day?></td>
        <?php } ?>

        <?php
        $dayarray = array();
        array_push($dayarray, $i, $day, $WP, $ST, $ET, $RT); //dayarray
        array_push($workarray, $dayarray); //workarray

        if($Daycheck == true) { ?>
          <td><?=$WP?></td>
          <td><?=$ST?></td>
          <td><?=$ET?></td>
          <td><?=$RT?></td><!-- popup and GPS MAP Add(C Rank) -->
          <td><?=$OT?></td>
        <?php } else { ?>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        <?php } ?>
      </tr>
      <?php if($dailyInt<6) {$dailyInt++;} else {$dailyInt=0;}
      } ?>

      <input type=hidden name="workdata" value=<?=json_encode($workarray) ?>></input>
      <input type=hidden name="YM" value=<?=$SelectYM ?>></input>
      <tr class="memo-cell">
        <!-- colspan = cols weight -->
        <th colspan="2">備考</th>
        <!-- 180612 textArea width auto -->
        <td colspan="4"><textarea style="width:100%;" name="note" rows="8" cols="80"><?=$note?></textarea></td>
        <td><input type=button value="内容保存" onclick='SaveNote()' class="note-btn"></input></td>
      </tr>

    </table>

    <?php
    /* 180621 jhkim 総残業時間追加*/
    $OTTA = 0;
    if(($OTA/60) < 0) {
      $OTTA = ceil($OTA/60);
    } else{
      $OTTA = floor($OTA/60);
    }
    if($OTTA == -0) $OTTA = 0;  //-0 -> 0
    $OTMA = $OTA%60;
    if(strlen($OTMA) == 1) $OTMA = "0".$OTMA; //180621

    //$OT = $OTT.'時間 '.$OTM.'分';//残業時間
    $OTA = $OTTA.':'.$OTMA;//180618残業時間コード修正
    ?>
    <input type=hidden value=<?=$OTA?> id="overtimealldata"></input>
