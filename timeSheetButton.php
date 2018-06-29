<?php
  /*
  自分のシフト初期画面設定(Button-form)
  180509
  jhkim
  */
  $UserData = $_POST["Users"];
  $TrafficData = $_POST["Traffics"];  //180621 Traffics追加
?>
    <!-- 修正ボタン  -->
    <?php if($_POST["Auth"] == 1) { ?> <!-- 180621 個別のAuthに修正 -->
      <input type="button" name="modify" id="modify" onclick='DBmodify()' class="modify-btn" value="修正"/>
    <?php } ?>
    <input type=submit name="ExcelExport" class="download-btn" value="DL"></input>
    <input type=hidden name="Users" value=<?=json_encode($UserData) ?>></input>
    <input type=hidden name="Traffics" value=<?=json_encode($TrafficData) ?>></input>
