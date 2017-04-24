<?php

require_once '../db_connect.php';
require_once '../function.php';

try{

}


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
                <form action="add_conf_local.php" method="post"  id="contact">
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
                            <input type="submit" value="save" id="send_btn" value = "SAVE">
                            <input type="submit" value="cancel" id="send_btn" value = "CXL">
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
                            <input type="date" name="posted" required size="18">
                          </td>
                        </tr>
                        <tr>
                          <td align="right" nowrap>
                            <label>Maker</label>
                          </td>
                          <td valign="top">
                            <input type="radio" name = "maker" value="Missan">Missan
                            <input type="radio" name = "maker" value="Toyoda">Toyoda
                            <input type="radio" name = "maker" value="Masuda">Masuda
                            <br>
                            <input type="radio" name = "maker" value="Nino">Nino
                            <input type="radio" name = "maker" value="Tsubaru">Tsubaru
                            <input type="radio" name = "maker" value="Ponda">Ponda
                          </td>
                        </tr>
                          <tr>
                          <td align="right" nowrap>
                            <label>Type<span>*</span></label>
                          </td>
                          <td valign="top">
                            <select name="type" size = "4">
                              <option value="SUV">SUV</option>
                              <option value="Van">Van</option>
                              <option value="Compact">Compact</option>
                              <option value="Wagon">Wagon</option>
                              <option value="Sedan">Sedan</option>
                              <option value="Convertible">Convertible</option>
                              <option value="Sports">Sports</option>
                            </select>
                          </td>
                        </tr>
                          <tr>
                          <td align="right" nowrap>
                            <label>Year</label>
                          </td>
                          <td valign="top">
                            <input type="number" name="year" min="1990" max="2020" size="18" value = "year">
                          </td>
                        </tr>
                          <tr>
                          <td align="right" nowrap>
                            <label>Price<span>*</span></label>
                          </td>
                          <td valign="top">
                            <input type="number" name="price" min="10" max="500" size="18" step = "5" value = "price"> Man JPY
                          </td>
                        </tr>
                        </tr>
                          <tr>
                          <td align="right" nowrap>
                            <label>Image<span>*</span></label>
                          </td>
                          <td valign="top">
                          <input type="file" name="image" size="18"><br>
                          <label><input type="checkbox" name="imageflag">
                            No Image</label>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </form>
                </section>
</body>
</html>