<?php
$name = $_POST["name"];  //氏名
$kana = $_POST["kana"];  //フリガナ
$prof = $_POST["prof"];  //職業：社会人、学生、その他
$character = $_POST["character"]; //配列で人物名を取得
$magazine = $_POST["magazine"]; //yesまたはno
$memo = $_POST["memo"];  //備考欄
$privacy = $_POST["privacy"]; //チェックしていればon
?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>確認画面</title>
</head>
<body>
<h1>確認画面</h1>
<table border="1" cellpadding="5" cellspacing="0">
  <tr>
    <th>氏名</th>
    <td><?php echo $name; ?></td>
  </tr>
  <tr>
    <th>フリガナ</th>
    <td><?php echo $kana; ?></td>
  </tr>
  <tr>
    <th>職業</th>
    <td><?php echo $prof; ?></td>
  </tr>
  <tr>
    <th>好きな登場人物（複数選択可）</th>
    <td><?php echo implode("<br>", $character); ?></td>
  </tr>
  <tr>
    <th>メールマガジン</th>
    <td><?php echo $magazine; ?></td>
  </tr>
  <tr>
    <th>備考</th>
    <td><?php echo nl2br($memo); ?></td>
  </tr>
  <tr>
    <th>個人情報の扱いについて同意する</th>
    <td><?php echo $privacy; ?></td>
  </tr>
</table>
</body>
</html>