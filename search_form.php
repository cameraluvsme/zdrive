<?php

session_start();



var_dump($_SESSION);
var_dump($_POST);



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
  $search = $_SESSION["search"];
  $pref = $search["pref"];
  $maker = $search["maker"];
  $type = $search["type"];
  $year = $search["year"];
  $price = $search["price"];
  $token2   = $search["token2"];
}

//SEARCH ボタンクリック
if (isset($_POST["search_btn"])){

  $isSearched = TRUE;

    // 入力データの取得
    $pref = $_POST["pref"];  //
    $maker = $_POST["maker"];  //IMPLODE
    $type = $_POST["type"];  //IMPLODE
    $year = $_POST["year"]; //
    $price = $_POST["price"]; //
    $token2 = $_POST["token2"]; //

    if($isSearched == TRUE){
      $search = array(
      "pref" => $pref,
      "maker" => $maker,
      "type" => $type,
      "year" => $year,
      "price" => $price,
      "token2" => $token2
    );

    $_SESSION["search"] = $search;
    header("Location: search_confirm.php");
    exit;
    }

}

//$_SESSION["search"]あるとき
//if (isset($_SESSION["search"])) {
  //$beenSearch = TRUE;
  //$search = $_SESSION["search"];
  //$search = "BEEN SENT";
//}


//$_SESSION["edit"]あるとき
//if (isset($_SESSION["edit"])) {
  //$edit = $_SESSION["edit"];
  //$edit = "EDIT";
//}


?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>


  <section>
    <form action="search_confirm.php" method="post">
      <input type="hidden" name="token2" value="<?php echo getToken(); ?>">
      <table border="1" cellspacing="0" cellpadding="5" bordercolor="#333333">
        <thead>
          <tr>
            <td colspan="2" style="text-align: center;">SELECT YOUR PREFERENCE</td>
          </tr>
        </thead>
        <tfoot>
            <tr>
              <td colspan="2" align="center">
                <input type="submit" value = "SEARCH" name = "search_btn">
              </td>
            </tr>
        </tfoot>
        <tobody>
          <tr>
            <td align="right" nowrap>
              <label>Preference</label>
            </td>
            <td valign="top">
              <label><input type="radio" name="pref" value="NEW">NEW</label><br>
              <label><input type="radio" name="pref" value="USED">USED</label>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap>
              <label>Maker</label>
            </td>
            <td valign="top">
              <label><input type="checkbox" name="maker[]" value="Nissan" <?php if($maker == maker){echo "checked";} ?>>Nissan</label><br>
              <label><input type="checkbox" name="maker[]" value="Toyota" <?php if($maker == maker){echo "checked";} ?>>Toyota</label><br>
              <label><input type="checkbox" name="maker[]" value="Honda" <?php if($maker == maker){echo "checked";} ?>>Honda</label><br>
              <label><input type="checkbox" name="maker[]" value="Mazda" <?php if($maker == maker){echo "checked";} ?>>Mazda</label>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap>
              <label>Type</label>
            </td>
            <td valign="top">
              <select name="type[]" size = "4" multiple>
                <option value="SUV" <?php if($type == SUV){echo "selected";} ?>>SUV</option>
                <option value="Van" <?php if($type == Van){echo "selected";} ?>>Van</option>
                <option value="Compact" <?php if($type == Compact){echo "selected";} ?>>Compact</option>
                <option value="Wagon" <?php if($type == Wagon){echo "selected";} ?>>Wagon</option>
              </select>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap>
              <label>Year</label>
            </td>
            <td valign="top">
              <select name="year" size = "4">
                <option value="2012" <?php if($year == 2012){echo "selected";} ?>>2012</option>
                <option value="2010" <?php if($year == 2010){echo "selected";} ?>>2010</option>
                <option value="2008" <?php if($year == 2008){echo "selected";} ?>>2008</option>
                <option value="2005" <?php if($year == 2005){echo "selected";} ?>>2005</option>
              </select>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap>
              <label>Price</label>
            </td>
            <td valign="top">
              <label><input type="number" name="price"
      value="<?php echo $price; ?>" step = "25" min= "25" max = "900">Man Yen</label>
            </td>
          </tr>
        </tobody>
      </table>
    </form>
  </section>
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