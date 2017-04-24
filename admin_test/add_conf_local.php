<?php
$date = $_POST["date"];  //氏名
$maker = $_POST["maker"];  //フリガナ
$type = $_POST["type"];  //職業：社会人、学生、その他
$year = $_POST["year"]; //配列で人物名を取得
$price = $_POST["price"]; //yesまたはno
$image = $_POST["image"];  //備考欄
$imageflag = $_POST["imageflag"]; //チェックしていればon
?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>確認画面</title>
  <style>
    #shopping_done{
    display: none;
}

#contact table thead, #contact table tfoot{
    background-color: #337ab7;
    font-size: 1.6rem;
    font-weight: bold;
}


#page-4 #contact table,#page-4 #contact form#page-4 contact section{
    width: 90%;
    margin: 0 auto;
}


#contact table thead{
    color: white;
}

#contact table tbody{
    background-color: rgba(102,153,255,0.65);
    font-size: 1.4rem;
}

#contact table tfoot a{
    color: white;
    text-decoration: underline;
    font-weight: bold;
}


  </style>
</head>
<body>
<section id = "contact">
  <table border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr>
        <th colspan="2" style="text-align: center;">Confirm Your Inputs</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td colspan="2" align="center">
          <a href="add_local.php">Back</a>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <tr>
        <td align="right" nowrap>
            <label>Date</label>
        </td>
        <td valign="top"><?php echo $date; ?></td>
      </tr>
      <tr>
        <td align="right" nowrap>
            <label>Maker</label>
        </td>
        <td valign="top"><?php echo $maker; ?></td>
      </tr>
      <tr>
        <td align="right" nowrap>
          <label>Type</label>
        </td>
        <td valign="top"><?php echo $type; ?></td>
      </tr>
      <tr>
        <td align="right" nowrap>
            <label>Year</label>
        </td>
        <td valign="top"><?php echo $year; ?></td>
      </tr>
      <tr>
        <td align="right" nowrap>
            <label>Price</label>
        </td>
        <td valign="top"><?php echo $price; ?></td>
      </tr>
      <tr>
        <td align="right" nowrap>
            <label>Image</label>
        </td>
        <td valign="top"><?php echo "PENDING"; ?></td>
      </tr>
    </tbody>
  </table>
</section>

</body>
</html>