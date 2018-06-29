<?php
  /*
    自分のシフト初期画面設定(Select-form)
    180509
    jhkim
  */
  $YMs = $_POST["Attendances_monthly"]; //配列送信
  $selectYM = $_POST["YearMonth"];
?>
      <select name="YearMonth" id="YearMonth">
        <?php //print Year and Month
        foreach($YMs as $key=>$value) {
          $yearkey = substr($key, 0, 4); // 180605年月修正
          $monthkey = substr($key, 4, 2);
          if($key == $selectYM) { ?>
            <option selected><?=$yearkey."年".$monthkey."月"?></option>
          <?php } else { ?>
            <option><?=$yearkey."年".$monthkey."月"?></option>
          <?php }
        } ?>
      </select>
      <button type="button" name="search" id="search" class="search-btn">検索</button>
