<?php
$pref = $_POST["pref"];  //
$maker = $_POST["maker"];  //IMPLODE
$type = $_POST["type"];  //IMPLODE
$year = $_POST["year"]; //
$price = $_POST["price"]; //

//配列かどうかの判断
if(is_array($maker)){
$makerResult = implode("<br>", $maker);
}
else{
$makerResult = "選択なし";
}

if(is_array($type)){
$typeResult = implode("<br>", $type);
}
else{
$typeResult = "選択なし";
}




?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>確認画面</title>
</head>
<body>
<table border="1" cellspacing="0" cellpadding="5" bordercolor="#333333">
  <thead>
    <tr>
      <td colspan="2" style="text-align: center;">SELECTED PREFERENCE</td>
    </tr>
  </thead>
  <tfoot>
      <tr>
        <td colspan="2" align="center">
          <input type="submit" value = "SHOW" name = "show_btn">
          <input type="submit" value = "EDIT" name = "edit_btn">
        </td>
      </tr>
  </tfoot>
  <tbody>
      <tr>
          <td align="right" nowrap>
            <label>Preference</label>
          </td>
          <td valign="top">
            <?php echo $pref; ?>
          </td>
      </tr>
      <tr>
          <td align="right" nowrap>
            <label>Maker</label>
          </td>
          <td valign="top">
            <?php echo $makerResult ; ?>
          </td>
      </tr>
      <tr>
          <td align="right" nowrap>
            <label>Type</label>
          </td>
          <td valign="top">
            <?php echo $typeResult; ?>
          </td>
      </tr>
      <tr>
          <td align="right" nowrap>
            <label>Year</label>
          </td>
          <td valign="top">
            <?php echo $year; ?>
          </td>
      </tr>
      <tr>
          <td align="right" nowrap>
            <label>Price</label>
          </td>
          <td valign="top">
            <?php echo $price; ?>
          </td>
      </tr>
  </tbody>
</table>
</body>
</html>