<?php

session_start();



var_dump($_SESSION);
var_dump($_POST);
var_dump($_SESSION["search"]);
var_dump($_SESSION["show"]);



require_once "util.inc.php";

//--------------------
// 変数の初期化
//--------------------
$name    = "";
$kana    = "";
$email   = "";
$phone   = "";
$inquiry = "";
$aaa = "";
$confirm = "";
$sent = "";
$fstvisit = "";
$pref = "";
$maker = "";
$type = "";
$year = "";
$price = "";
$makerResult = "";
$search = "";
$show = "";
$edit = "";
$condition = "";
$imgNum= "";


//--------------------
// セッション変数が登録されている場合は読み出す
//--------------------
if (isset($_SESSION["search"])) {
  $isSearched = TRUE;
  $search = $_SESSION["search"];
  $pref = $search["pref"];
  $maker = $search["maker"];
  $type = $search["type"];
  $year = $search["year"];
  $price = $search["price"];
  //$token2   = $search["token2"];
  // CSRF対策
  //if($token2 !== getToken()){
    //$reLocated = TRUE;
    //$_SESSION["movepage"] = $movepage;
    //header("Location: search_form.php");
    //exit();
  //}
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

    if(is_numeric($price)){
      $noprice = FALSE;
    }
    else{
      $noprice = TRUE;
      $choose = "CHOOSE";
      $errorPrice = "値段を選択してください";
    }
}
//else {
    // 不正なアクセス
    // 入力ページへ戻る
    //$reLocated = TRUE;
    //$_SESSION["movepage"] = $movepage;
    //header("Location: search_form.php");
    //exit();
//}

//$_SESSION["search"]あるとき
//if (isset($_SESSION["search"])) {
  //$beenSearch = TRUE;
  //$search = $_SESSION["search"];
  //$search = "BEEN SENT";
//}



//SHOW ボタンクリック
if (isset($_POST["show_btn"])){

  $_SESSION["show"] = $show;
  //unset($_SESSION["search"]);
  //unset($_SESSION["edit"]);
  header("Location: search_confirm.php");
  exit;
}

//$_SESSION["show"]あるとき
if (is_null($_SESSION["show"])) {
  $showClick = FALSE;
}
else{
  $showClick = TRUE;
  //$show = $_SESSION["show"];
  $show = "SHOW RESULT";
}

if($showClick == TRUE){
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
}





//EDIT ボタンクリック
if (isset($_POST["edit_btn"])){
    $_SESSION["edit"] = $edit;
    //unset($_SESSION["search"]);
    unset($_SESSION["show"]);
    header("Location: search_form.php");
    exit;
}

//AGAIN ボタンクリック
if (isset($_POST["again_btn"])){
    $_SESSION["again"] = $again;
    unset($_SESSION["search"]);
    unset($_SESSION["show"]);
    header("Location: search_form.php");
    exit;
}

?>

<!doctype html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>確認画面</title>
</head>
<body>
<section class = "search_page">
  <form action="" method="post" id = "search_click">
    <input type="hidden" name="noprice" value="<?php echo $choose; ?>">
    <table border="1" cellspacing="0" cellpadding="5" bordercolor="#333333">
      <thead>
        <tr>
          <td colspan="2" style="text-align: center;">SELECTED PREFERENCE</td>
        </tr>
      </thead>
      <tfoot>
          <tr>
            <td colspan="2" align="center">
              <input type="submit" value = "SHOW" name = "show_btn" id = "show">
              <input type="submit" value = "EDIT" name = "edit_btn">
              <?php if (isset($errorPrice)): ?>
                  <div class="text-warning"><?php echo h($errorPrice); ?></div>
              <?php endif; ?>
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
  </form>
</section>
<div id = "result_show" style="display: none;">
  <input type="hidden" name="show" value="<?php echo $show; ?>">
  <h4>
  <?php echo h($condition); ?>
  <i class="fa fa-car" aria-hidden="true"></i>
  </h4>
  <section>
    <?php echo "<img src = 'images/di_img_0{$imgNum}.jpg' alt ='Image Picture' >";?>
  </section>
  <form action="" method = "post">
    <input type="submit" value="AGAIN" id="send_btn" class = "search_back" name = "again_btn">
  </form>
</div>
<script src="js/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/design_plan.js" type="text/javascript"></script>
<script>
$(document).ready(function(){

    //SEARCH CLICK
    //if($("input[name='search']").val() != ""){
      //var scrollDown = parseInt($('#page-3').offset().top);

      //$(window).scrollTop(scrollDown + 1);
      //$("#result_show").css({
              //display:"none"
              //});
      //$("#search_form").css({
              //display:"none"
             // });
      //$("#search_click").css({
              //display:"block"
              //});
      //}

      //EDIT CLICK
      //if($("input[name='edit']").val() != ""){
        //var scrollDown = parseInt($('#page-3').offset().top);

        //$(window).scrollTop(scrollDown + 1);
        //$("#result_show").css({
                //display:"none"
                //});
        //$("#search_click").css({
                //display:"none"
                //});
        //$("#search_form").css({
               //display:"block"
                //});
     // }

      //SHOW CLICK
      if($("input[name='show']").val() != ""){
        //var scrollDown = parseInt($('#page-3').offset().top);

        //$(window).scrollTop(scrollDown + 1);
        $("#search_click").css({
                display:"none"
                });
        $("#result_show").css({
                display:"block"
                });
      }

      //NO PRICE
      if($("input[name='noprice']").val() != ""){
        //var scrollDown = parseInt($('#page-3').offset().top);

        //$(window).scrollTop(scrollDown + 1);
        $("#show").css({
                display:"none"
                });
      }


});// ./$(document).ready(function(){
</script>
</body>
</html>