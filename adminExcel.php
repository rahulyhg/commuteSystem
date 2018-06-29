<?php
  date_default_timezone_set('Asia/Tokyo');
  $user_agent = $_SERVER['HTTP_USER_AGENT'];

  //OSの判別(文字化け対策)
  function getOS() {
    global $user_agent;
    $os_platform  = "Unknown OS Platform";
    $os_array = array(
      '/windows nt 10/i'      =>  'Windows 10',
      '/windows nt 6.3/i'     =>  'Windows 8.1',
      '/windows nt 6.2/i'     =>  'Windows 8',
      '/windows nt 6.1/i'     =>  'Windows 7',
      '/windows nt 6.0/i'     =>  'Windows Vista',
      '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
      '/windows nt 5.1/i'     =>  'Windows XP',
      '/windows xp/i'         =>  'Windows XP',
      '/windows nt 5.0/i'     =>  'Windows 2000',
      '/windows me/i'         =>  'Windows ME',
      '/win98/i'              =>  'Windows 98',
      '/win95/i'              =>  'Windows 95',
      '/win16/i'              =>  'Windows 3.11',
      '/macintosh|mac os x/i' =>  'Mac OS X',
      '/mac_powerpc/i'        =>  'Mac OS 9',
      '/linux/i'              =>  'Linux',
      '/ubuntu/i'             =>  'Ubuntu',
      '/iphone/i'             =>  'iPhone',
      '/ipod/i'               =>  'iPod',
      '/ipad/i'               =>  'iPad',
      '/android/i'            =>  'Android',
      '/blackberry/i'         =>  'BlackBerry',
      '/webos/i'              =>  'Mobile'
    );

    foreach ($os_array as $regex => $value){
      if (preg_match($regex, $user_agent)) $os_platform = $value;
    }
    return $os_platform;
  }

  $user_os = getOS();


	if(!is_dir("./upload")){ // uploadディレクトリーがない場合生成
 	 mkdir("./upload");
	}

        require_once "Classes/PHPExcel.php";
        $objPHPExcel = new PHPExcel();
        require_once "Classes/PHPExcel/IOFactory.php";
        $filename = "workSystemForm.xlsx";

        /* $_POSTで値を持つ */
        $UserData = json_decode($_POST["Users"], true); //User Data
        $workdata = json_decode($_POST['Attendances_daily'] ,true); //User Dayly Data
        $monthlyWorkdata = json_decode($_POST['Attendances_monthly'] ,true);
        $traffics = json_decode($_POST['traffics'], true); // 定期の情報
        $YMs = $_POST['YMs']; //選択した年月
        $SelectedYear = substr($YMs, 0, 4); // 選択されて年
        $SelectedMonth = substr($YMs, 4, 6); // 選択された月

        $today = mktime(0,0,0,$SelectedMonth,1,$SelectedYear); // 選択された月の日付を得るため

        $monthlyDay = date("t", $today); //選択した月の最終日
        $strtotime = $SelectedYear."-".$SelectedMonth."-1"; //毎月1日の曜日を求めるための変数
        $dailyInt = date('w', strtotime($strtotime)); // date関数は0~6の数字をreturnする
        $daily = array('日','月','火','水','木','金','土'); // daily[dailyInt]

        $dailyWork = array(); // 一人一か月のAttendances_daily

        foreach ($workdata as $key => $value) { // $key-uid
          for($j=1;$j<=$monthlyDay;$j++){
            if($j<10){
              $i = "0".$j; // データベース上の形が201806のため
            }else{
              $i = $j;
            }
            if(empty($workdata[$key][$YMs.$i])){ // Attendances_dailyのデータがない場合
              $dailyWork[$key][$YMs.$i]["start_time"] = "";
              $dailyWork[$key][$YMs.$i]["rest_time"] = "";
              $dailyWork[$key][$YMs.$i]["end_time"] = "";
              $dailyWork[$key][$YMs.$i]["workplace"] = "";
            }else{ // 一部分存在する場合
              if(empty($workdata[$key][$YMs.$i]["start_time"])) $workdata[$key][$YMs.$i]["start_time"] = "";
              if(empty($workdata[$key][$YMs.$i]["rest_time"])) $workdata[$key][$YMs.$i]["rest_time"] = "";
              if(empty($workdata[$key][$YMs.$i]["end_time"])) $workdata[$key][$YMs.$i]["end_time"] = "";
              if(empty($workdata[$key][$YMs.$i]["workplace"])) $workdata[$key][$YMs.$i]["workplace"] = "";

              $dailyWork[$key][$YMs.$i]["start_time"] = $workdata[$key][$YMs.$i]["start_time"];
              $dailyWork[$key][$YMs.$i]["rest_time"] = $workdata[$key][$YMs.$i]["rest_time"];
              $dailyWork[$key][$YMs.$i]["end_time"] = $workdata[$key][$YMs.$i]["end_time"];
              $dailyWork[$key][$YMs.$i]["workplace"] = $workdata[$key][$YMs.$i]["workplace"];
            }
          }
        }

        try {
          foreach ($UserData as $key => $value) { // $key-uid, $value-Users
             $objReader=PHPExcel_IOFactory::createReaderForFile($filename);
             //$objReader->setReadDataOnly(true); // Read only
             $objPHPExcel = $objReader->load($filename);
             $objPHPExcel->setActiveSheetIndex(0);
             $sheet = $objPHPExcel->getActiveSheet();

             /* User Data Input */
             $sheet -> setCellValue('C3', PHPExcel_Shared_Date::FormattedPHPToExcel($SelectedYear, $SelectedMonth, 1));
             $sheet -> setCellValue('C4', $value['department']);
             $sheet -> setCellValue('C5', $value['name_kana']);
             $sheet -> setCellValue('C6', $value['user_name']);
             $sheet -> setCellValue('C7', $value['user_id']);
             $sheet -> setCellValue('C8', $value['division']);
             $sheet -> setCellValue('C9', $value['workplace']);
             $sheet -> setCellValue('C10', $value['workplace']);
          /* User Daily Data Input */
          foreach ($dailyWork as $key2 => $value2) { // key2-uid, value2-uidのAttendances_daily
            if($key==$key2){
              $cellint = 13;
              $dailyInt = date('w', strtotime($strtotime)); // date関数は0~6の数字をreturnする
              foreach ($value2 as $key4 => $value4) {
                 $sheet -> setCellValue('A13', "1");
                 $sheet -> setCellValue('B'.$cellint, $daily[$dailyInt]); // day of the week
                 if(empty($value4['start_time'])){
                  $sheet -> setCellValue('C'.$cellint, "");
                 }else{
                   $sheet -> setCellValue('C'.$cellint, $value4['workplace']); // workplace
                 }
                 $sheet -> setCellValue('E'.$cellint, CorrectTime($value4['start_time'], $SelectedYear, $SelectedMonth)); // starttime
                 $sheet -> setCellValue('F'.$cellint, CorrectTime($value4['end_time'], $SelectedYear, $SelectedMonth)); // endtime
                 $sheet -> setCellValue('G'.$cellint, $value4['rest_time']); // resttime
                 $sheet -> getStyle('F'.$cellint) -> getNumberFormat() -> setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME3);// View Style

                 $cellint++;

                 // 曜日を回すため
                 if($dailyInt<6){
                   $dailyInt++;
                 }else{
                   $dailyInt=0;
                 }
               }
            }

            // 月の最終日まで表示
            if($monthlyDay<31){
              $sheet -> setCellValue('A43', ' ');
              if($monthlyDay<30){
                $sheet -> setCellValue('A42', ' ');
                if($monthlyDay<29){
                  $sheet -> setCellValue('A41', ' ');
                }
              }
            }
          }

          foreach ($monthlyWorkdata as $key3 => $value3) { // key3-uid
            if($key==$key3){
              foreach ($value3 as $key5 => $value5) {
                if($key5==$YMs){
                  if(empty($value5['attendances_memo'])) $value5['attendances_memo']="";
                  $sheet -> setCellValue('A46', $value5['attendances_memo']); //note
     		          $sheet -> mergeCells('A46:O48'); //A46~O48まで
        	        $sheet->getStyle('A46:O48')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); //Horizontal_left
                }
              }
            }
          }

          foreach ($traffics as $key4 => $value4) { // key4-uid, value4-uidの交通費
            if($key==$key4){
              if(empty($value4['departure_station'])) $value4['departure_station']="";
              if(empty($value4['arrival_station'])) $value4['arrival_station']="";
              if(empty($value4['costs'])) $value4['costs']="";
              $sheet->setCellValue('F5', $value4['departure_station']); // 自宅最寄り駅
              $sheet->setCellValue('H5', $value4['arrival_station']); // 会社最寄り駅
              $sheet->setCellValue('j5', $value4['costs']);
            }
          }

          $sheet -> setTitle($YMs); //sheet name
          $excelName = $value['user_name'];
	        $excelNo = $value['user_id'];

          if(strpos($user_os, 'Mac')!== false){ // タイトル文字化け対策
            if (class_exists('Normalizer')) {
              if (Normalizer::isNormalized($excelName, Normalizer::FORM_D)) {
                $excelName = Normalizer::normalize($excelName, Normalizer::FORM_C);
              }
            }
          }else{
              $excelName = mb_convert_encoding($value['user_name'], 'SJIS-win', 'UTF-8');
          }

          /* Export Excel File */
          $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	        header('Cache-Control: max-age=0');
         // print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">");

          $objWriter->save("upload/".$YMs."_".$excelNo."_".$excelName.".xlsx");

        }
      }catch (exception $e) {
        echo '<script>alert("エクセルファイルの読み書き途中エラーが発生しました。"); history.back();';
        echo 'console.log('.$e->getMessage().')</script>';
      }

      function CorrectTime($TV, $SY, $SM) {
        if($TV) {
          $SelectedDate = PHPExcel_Shared_Date::FormattedPHPToExcel($SY, $SM, 1);
          $SelectedTime = PHPExcel_Shared_Date::FormattedPHPToExcel($SY, $SM, 1, substr($TV, 0, 2), substr($TV, 3, 5));
          $correctTime = $SelectedTime - $SelectedDate;
        } else {
          $correctTime = "";
        }
        return $correctTime;
      }

      echo "<script>alert('ダウンロード準備完了'); history.back(); </script>";
      ?>
