<!DOCTYPE html>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <table>
        <tr class="list-name">
          <td class="small-cell">出勤中</td>
          <td>社員No.</td>
          <td>名前</td>
          <td>遅刻</td>
          <td>総残業時間</td>
          <td>総勤務時間</td>
          <td>権限</td>
        </tr>

        <?php
          $LIST_SIZE = 25; // 一画面に表示する社員の数
          $BLOCK_SIZE = 5; // <<前へ [ ][ ][ ] 次へ>> の間に表示するページの数
          $pageArray = array();

          $userAllData = $_POST["userAllData"]; // Usersのすべてのデータ
          $monthlyAllData = $_POST["monthlyAllData"]; // Attendances_dailyのすべてのデータ
          $dailyAllData = $_POST["dailyAllData"]; // Attendances_monthlyのすべてのデータ
          $search_name = htmlspecialchars($_POST["user_name"]); // scriptなどの入力を防ぐ
          if(preg_match('/[^一-龠]/u',$search_name)) $search_name = mb_convert_kana( $search_name, "C", "UTF-8" );


          date_default_timezone_set('Asia/Tokyo'); //  default地域設定
          $ym = $_POST["YMs"]; // 選択されている年月(default: 現在の年月)

          $name = array(); // sortの為の配列
          $sort_user = array(); // sortされた配列
          $sort_user_r = array(); // 出力に利用する配列
          $sort_daily = array(); // sortしたAttendances_daily
          $sort_monthly = array(); // sortしたAttendances_monthly

          /////////////////// name_kanaでソート ////////////////////
          foreach ($userAllData as $key => $value) {
            foreach ($value as $key2 => $value2) {
              array_push($name, $value2['name_kana']);
            }
          }

          sort($name); // 名前順ソート

          for($i=0;$i<count($name);$i++){
            foreach ($userAllData as $key => $value) {
              foreach ($value as $key2 => $value2) {
                if($name[$i]==$value2['name_kana']) $sort_user[$key2] = $value2;
              }
            }
          }

          foreach ($sort_user as $key => $value) {
            foreach ($monthlyAllData as $key2 => $value2) {
              foreach ($value2 as $key3 => $value3) {
                if($key==$key3) $sort_monthly[$key] = $value3;
              }
            }
          }

          foreach ($sort_user as $key => $value) {
            foreach ($dailyAllData as $key2 => $value2) {
              foreach ($value2 as $key3 => $value3) {
                if($key==$key3) $sort_daily[$key] = $value3;
              }
            }
          }

          ///////////////////////////////検索結果////////////////////////////////////////////
          if(!empty($search_name)){
            foreach ($sort_user as $key => $value) {
              if(strpos($value["user_name"], $search_name)!== false){
                $sort_user_r[$key] = $value;
                array_push($pageArray, $key);
              }
              if($value["user_name"]!=$value["name_kana"]){
                if(strpos($value["name_kana"], $search_name)!== false){
                  $sort_user_r[$key] = $value;
                  array_push($pageArray, $key);
                }
              }
            }

            if(empty($sort_user_r)){
              $pageArray = null;
              echo "<td colspan='7'>検索結果がございません。</td>";
            }
          }else{
            foreach ($sort_user as $key => $value) {
              array_push($pageArray, $key); // ソートしたユーザーの順番を配列に入れる
            }
            $sort_user_r = $sort_user;
          }

          //////////////////////////////ページング///////////////////////////
          if(!empty($_GET['page'])){ // QueryStringにページナンバーがない場合
            $page = $_GET['page'] ? intval($_GET['page']) : 1;
          }else{
            $page = 1;
          }

          $page_count = count($pageArray); // 社員の人数をカウントする
          if(ceil($page_count/$LIST_SIZE)<$_GET['page']){ // ページ数よりGETの数字が大きい場合
            $page = 1;
          }

          // 今のページで何ページから何ページまで表示されるか
          if($page%$BLOCK_SIZE != 0) $block_start = floor($page/$BLOCK_SIZE)*$BLOCK_SIZE+1;
          else $block_start = floor($page/$BLOCK_SIZE)*$BLOCK_SIZE-($BLOCK_SIZE-1);

          $block_end = min($block_start+$BLOCK_SIZE-1, ceil($page_count/$LIST_SIZE));
      	  if(!empty($search_name)) $block_end = 1;

          // 現在ページに表示される配列の範囲
          $start = max($page*$LIST_SIZE-($LIST_SIZE-1), 1);
          $end = min($page*$LIST_SIZE, $page_count);
          // 前へ、次へを押すとき移動するページ
          $prev_page = max((ceil($page/$BLOCK_SIZE)-2)*$BLOCK_SIZE+1, 1);
          $next_page = min(floor(($page+$BLOCK_SIZE)/$BLOCK_SIZE)*$BLOCK_SIZE+1, ceil($page_count/$LIST_SIZE));

          if(is_int($page/$BLOCK_SIZE)) $cur_block = floor($page/$BLOCK_SIZE)-1;
          else $cur_block = floor($page/$BLOCK_SIZE);

          if($page_count>count($pageArray)) $page_count = count($pageArray); // 社員の数

          /////////////////////////////// ループの開始 ////////////////////////////////////////
          for($i=$start-1;$i<$end;$i++){ // start~endの人まで表示
            foreach ($sort_user_r as $key => $value) { // key-uid
              if($pageArray[$i] == $key){
                $user_id ="";
                $user_name="";
                $authority_id="";

                foreach ($value as $key2 => $value2) { // key2-Usersの下の項目(user_id, user_nameなど)
                  if($key2=="user_id") $user_id = $value2;
                  else if($key2=="user_name") $user_name = $value2;
                  else if($key2=="authority_id"){
                    if($value2 == 1) $authority_id="管理者";
                    else  $authority_id="";
                  }
                }

                $late_count=0;
                foreach ($sort_monthly as $key2 => $value2) {
                  if($key==$key2){
                    foreach ($value2 as $key3 => $value3) {
                      if($key3==$ym){
                        foreach ($value3 as $key4 => $value4) {
                          if($key4=="late_count") {
                            if($value4==0) $late_count = 0;
                            else $late_count = $value4;
                          }
                        }
                      }
                    }
                  }else continue;
                }

                $one_hour=0; // 一人の今月の働いた時間
                $one_min=0; // 一人の今月の働いた分
                $end_time="";
                $rest_time="";
                $start_time="";
                $start = ""; // 出勤状況表示用
                $daily_time="00:00"; // 総勤務時間
                $overtime_hour=0;
                $overtime_min=0;
                $overtime = "00:00"; // 総残業時間

                foreach ($sort_daily as $key2 => $value2) { // key2-uid
                  if($key==$key2){
                    foreach ($value2 as $key3 => $value3) { // key3-日付
                      $end_time="";
                      $rest_time="";
                      $start_time="";

                      ////////////////////////////[総勤務時間]/////////////////////////////////////
                      if(strpos($key3, $ym)!== false){
                        foreach ($value3 as $key4 => $value4) { // key4-start, rest, end_time
                          if($key4=="end_time") $end_time=explode(":", $value4);
                          else if($key4=="rest_time") $rest_time=explode(":", $value4);
                          else if($key4=="start_time") $start_time = explode(":", $value4);
                        }
                        if($end_time!=0){
                          if(empty($rest_time)){
                            $rest_time[0] = 0;
                            $rest_time[1] = 0;
                          }
                          $hour = $end_time[0]-$start_time[0]-$rest_time[0];
                          $min = $end_time[1]-$start_time[1]-$rest_time[1];
                          if($min<0){
                            if($min>-59){
                              $hour--;
                              $min = $min+60;
                            }else{
                              $hour--;
                              $min = $min+120;
                            }
                          }

                          $one_hour+=$hour;
                          $one_min+=$min;

                          if($one_min>59){
                            $one_hour=$one_hour+floor($one_min/60);
                            $one_min= $one_min-floor($one_min/60)*60;
                          }

                          if($one_min==0) $one_min = "00";
                          if(strlen($one_hour)==1) $one_hour = "0".$one_hour;

                          if($one_hour<0||$one_min<0){
                            $one_hour="00";
                            $one_min="00";
                          }


                          $daily_time = $one_hour.":".$one_min;

                          ///////////////////////// 総残業時間 //////////////////////
                          if($hour>=8){
                            if($hour==8&&$min>0){
                              $overtime_min+=$min;
                            }else if($hour!=8||$min!=0){
                              $overtime_hour = $overtime_hour+($hour-8);
                              $overtime_min +=  $min;
                            }
                            if($overtime_min>59){
                              $overtime_hour=$overtime_hour+floor($overtime_min/60);
                              $overtime_min= $overtime_min-floor($overtime_min/60)*60;
                            }
                            if($overtime_hour==0) $overtime_hour = "00";
                            if($overtime_min==0) $overtime_min = "00";

                            $overtime=$overtime_hour.":".$overtime_min;
                          }
                        }
                        //////////////////////////[出勤状況]/////////////////////////////////
                        if($key3==date("Ymd")){
                          if($end_time=="") $start="●";
                          else $start="";
                        }
                      }
                    }
                  }else continue;
                }
                ?>
                <tr>
                  <td style="width: 3%;"><?=$start?></td>
                  <td><a href="timeSheet.php?AuthUser=<?=$key?>"><?=$user_id?></a></td>     <!-- $key: uid -->
                  <td><?=$user_name?></td>
                  <td><?=$late_count?></td>
                  <td><?=$overtime?></td>
                  <td><?=$daily_time?></td>
                  <td><?=$authority_id?></td>
                </tr>

                <?php
              }
            }
          }
?>
      </table>

<?php
// ページング
if(count($sort_user_r)/$LIST_SIZE>1){
?>
      <div class='paging_area'>
      	<?php if( $page > $BLOCK_SIZE ):
          ?>
      	<a class='move_btn' href="<?= "./admin.php?page=$prev_page&&select=$ym" ?>">前へ</a>
      	<?php else: ?>
      	<span class='move_btn disabled'>前へ</span>
      	<?php endif ?>

      	<?php for( $p = $block_start; $p <= $block_end; $p++ ):  ?>
      	<a class='pagenum <?= ( $p == $page )?"current":"" ?>' href="<?= "./admin.php?page=$p&&select=$ym" ?>">
      		<?= $p ?>
      	</a>
      	<?php endfor ?>

      	<?php
        if(  floor($page_count/$LIST_SIZE/$BLOCK_SIZE) > $cur_block ):?>     <!-- 全体ブロック＜今見ているブロック -->
      	<a class='move_btn' href="<?= "./admin.php?page=$next_page&&select=$ym" ?>">次へ</a>
      	<?php else: ?>
      	<span class='move_btn disabled'>次へ</span>
      	<?php endif ?>
      </div>

<?php
}
?>
