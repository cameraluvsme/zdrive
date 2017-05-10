<?php

session_start();



var_dump($_SESSION);
var_dump($_POST);



require_once "util.inc.php";


//--------------------
// セッション変数が登録されている場合は読み出す
//--------------------
if (isset($_SESSION["search"])) {
  $search = $_SESSION["search"];
  $pref = $contact["pref"];
  $makerResult = $contact["makerResult"];
  $typeResult = $contact["typeResult"];
  $year = $contact["year"];
  $price = $contact["price"];
}

//SEARCH ボタンクリック
if (isset($_POST["search_btn"])){
    // 入力データの取得
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

    $search = array(
      "pref"    => $pref,
      "makerResult"    => $makerResult,
      "typeResult"   => $typeResult,
      "year"   => $year,
      "price"   => $price
    );

    $_SESSION["search"] = $search;
    unset($_SESSION["show"]);
    unset($_SESSION["edit"]);
    header("Location: search_confirm.php");
    exit;
}
//$_SESSION["search"]あるとき
if (isset($_SESSION["search"])) {
  $beenSearch = TRUE;
  $search = $_SESSION["search"];
  $search = "BEEN SENT";
}


//SHOW ボタンクリック
if (isset($_POST["show_btn"])){

  if($price >= 350){
    $condition = "新車のご提案が可能です";
    $imgNum = 4;
  }

  elseif($price >= 200){
    $condition = "幅広いご提案ができます";
    $imgNum = 5;

  }
  elseif($price >= 100){
    $condition = "複数のご提案が可能です";
    $imgNum = 3;
  }

  elseif($price >= 50){
    $condition = "お買い得な車です";
    $imgNum = 2;
  }

  else{
    $condition = "紹介できる車なし";
    $imgNum = 1;
  }

  $_SESSION["show"] = $show;
  unset($_SESSION["search"]);
  unset($_SESSION["edit"]);
  header("Location: search_form.php");
  exit;
}
//$_SESSION["show"]あるとき
if (isset($_SESSION["show"])) {
  $show = $_SESSION["show"];
  $search = "SHOW RESULT";
}

//EDIT ボタンクリック
if (isset($_POST["edit"])){
    $_SESSION["edit"] = $edit;
    //unset($_SESSION["search"]);
    unset($_SESSION["show"]);
    header("Location: search_form.php");
    exit;
}

//$_SESSION["edit"]あるとき
if (isset($_SESSION["edit"])) {
  $edit = $_SESSION["edit"];
  $edit = "EDIT";
}


?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>確認画面</title>
</head>
<body>

<table border="1" cellspacing="0" cellpadding="5" bordercolor="#333333" id = "search_click">
  <input type="hidden" name="edit" value="<?php echo $edit; ?>">
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
<div id = "result_show">
  <input type="hidden" name="show" value="<?php echo $show; ?>">
  <h4>
  <?php echo h($condition); ?>
  <i class="fa fa-car" aria-hidden="true"></i>
  </h4>
  <?php echo "<img src = 'images/di_img_0{$imgNum}.jpg' alt ='Image Picture' >";?>
  <form action="" method = "post">
    <input type="submit" value="SEARCH" id="send_btn" class = "search_back" name = "srchback">
  </form>
</div>
<script src="js/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/design_plan.js" type="text/javascript"></script>
<script>
$(document).ready(function(){

    //SEARCH CLICK
    if($("input[name='search']").val() != ""){
      var scrollDown = parseInt($('#page-3').offset().top);

      $(window).scrollTop(scrollDown + 1);
      $("#result_show").css({
              display:"none"
              });
      $("#search_form").css({
              display:"none"
              });
      $("#search_click").css({
              display:"block"
              });
      }

      //EDIT CLICK
      if($("input[name='edit']").val() != ""){
        var scrollDown = parseInt($('#page-3').offset().top);

        $(window).scrollTop(scrollDown + 1);
        $("#result_show").css({
                display:"none"
                });
        $("#search_click").css({
                display:"none"
                });
        $("#search_form").css({
                display:"block"
                });
      }

      //SHOW CLICK
      if($("input[name='show']").val() != ""){
        var scrollDown = parseInt($('#page-3').offset().top);

        $(window).scrollTop(scrollDown + 1);
        $("#search_form").css({
                display:"none"
                });
        $("#search_click").css({
                display:"none"
                });
        $("#result_show").css({
                display:"block"
                });
      }


});// ./$(document).ready(function(){
</script>
</body>
</html>