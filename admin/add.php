<?php
require_once '../db_connect.php';
require_once '../function.php';

$pdo = db_connect();

$sql = "SELECT * FROM used_cars";
$posts = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
// var_dump($posts);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
  <link rel="stylesheet" href="css/sanitize.css">
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

#contact table tfoot #shopping_done a{
    color: white;
    text-decoration: underline;
    font-weight: bold;
}


  </style>
</head>
<body>

<section class="contact">
                <form action="" method="post"  id="contact">
                <table border="1" cellspacing="0" cellpadding="5" bordercolor="#333333">
                    <thead>
                        <tr>
                            <th colspan="2" style="text-align: center;">
                                下記ご入力ください<br>
                                <span style="margin-left: 3%">*入力必須項目</span>
                            </th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <td colspan="2" align="center">
                            <input type="submit" value="SEND" id="send_btn">
                            <p id="shopping_done">
                              <span></span>様<br>お問い合わせありがとうございました
                              <i class="fa fa-smile-o" aria-hidden="true"></i><br>
                              <a href="">戻る</a>
                            </p>
                          </td>
                        </tr>
                      </tfoot>
                      <tbody>
                        <tr>
                          <td align="right" nowrap>
                            <label>Date<span>*</span></label>
                          </td>
                          <td valign="top">
                            <input type="date" name="posted" required size="18" value = "posted">
                          </td>
                        </tr>
                        <tr>
                          <td align="right" nowrap>
                            <label>Maker</label>
                          </td>
                          <td valign="top">
                            <input type="text" name="maker"  maxlength="20" size="18" value ="maker">
                          </td>
                        </tr>
                          <tr>
                          <td align="right" nowrap>
                            <label>Type<span>*</span></label>
                          </td>
                          <td valign="top">
                            <input type="text" name="type"  maxlength="20" size="18" value ="type">
                          </td>
                        </tr>
                          <tr>
                          <td align="right" nowrap>
                            <label>Year</label>
                          </td>
                          <td valign="top">
                            <input type="number" name="year" min="1980" max="2030" size="18" value = "year">
                          </td>
                        </tr>
                          <tr>
                          <td align="right" nowrap>
                            <label>Price<span>*</span></label>
                          </td>
                          <td valign="top">
                            <input type="number" name="year" min="5" max="5000" size="18" step = "5" value = "year"> Man JPY
                          </td>
                        </tr>
                        </tr>
                          <tr>
                          <td align="right" nowrap>
                            <label>Image<span>*</span></label>
                          </td>
                          <td valign="top">
                          <input type="file" name="image" size="18">
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </form>
                </section>
</body>
</html>