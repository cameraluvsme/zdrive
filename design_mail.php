<?php
session_start();

/*

var_dump($_SESSION);
var_dump($_POST);

*/

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
/*

擬似コード**今後の参考までに
if (empty($_POST) && empty($_SESSION)) {
  $scroll = 0;
}
else {
  $scroll = 1;
}


<input type="hidden" value="<?php echo $scroll ?>">

初回の読み込み時のみの場合
*/


//--------------------
// セッション変数が登録されている場合は読み出す
//--------------------
if (isset($_SESSION["contact"])) {
  $contact = $_SESSION["contact"];
  $name    = $contact["name"];
  $kana    = $contact["kana"];
  $email   = $contact["email"];
  $phone   = $contact["phone"];
  $inquiry = $contact["inquiry"];
  // $contactOnly = $contact["contactOnly"];
}

//--------------------
// セッション変数が登録されている場合は読み出す
//--------------------
if (isset($_SESSION["confirm"])) {
  $headerLocated = TRUE;
  $confirm = $_SESSION["confirm"];
  $confirm = "From Confirm Page";
  $aaa = "";
  //$confirm = "";
  $sent = "";
  $fstvisit = "";
}
else{
  //Scroll DownせずTOP
  $headerLocated = FALSE;
  $fstvisit = "1st Time Visit, Welcome to Page Top!";
  $aaa = "";
  $confirm = "";
  $sent = "";
  //$fstvisit = "";
}

//--------------------
// セッション変数が登録されている場合は読み出す
//--------------------
if (isset($_SESSION["sent"])) {
  $beenSent = TRUE;
  $sent = $_SESSION["sent"];
  $sent = "Mail Has Been Sent, Thank You!";
  $aaa = "";
  $confirm = "";
  //$sent = "";
  $fstvisit = "";
}
/*
else{
  $beenSent = FALSE;
  $fstvisit = "1st Time Visit, Welcome to Page Top!";
  $aaa = "";
  $confirm = "";
  $sent = "";
  //$fstvisit = "";
}
*/

if (isset($_POST["formback"])) {
    // セッション変数を破棄
    unset($_SESSION["sent"]);
    $sent = "Display Contact Form, again!";
    $aaa = "";
    $confirm = "";
    //$sent = "";
    $fstvisit = "";
	//送信後にフォームを再表示
    $_SESSION["confirm"] = "";
    header("Location: design_mail.php");
    exit;
}


//--------------------
// 「確認する」ボタン
//--------------------
if (isset($_POST["confirmbtn"])) {

  $isValidated = TRUE;
  // $contactOnly = TRUE;

  // 入力データの取得
  $name    = $_POST["name"];
  $kana    = $_POST["kana"];
  $email   = $_POST["email"];
  $phone   = $_POST["phone"];
  $inquiry = $_POST["inquiry"];
  $token   = $_POST["token"];
  $aaa = "押したよ";
  //$aaa = "";
  $confirm = "";
  $sent = "";
  $fstvisit = "";

  // 名前のバリデーション
  if ($name === "" || mb_ereg_match("^(\s|　)", $name)){
    $errorName = "※お名前を入力してください";
    $isValidated = FALSE;
    //header("Location: input_error.php");
  }

  // フリガナのバリデーション
  if ($kana === "" || mb_ereg_match("^(\s|　)", $kana)){
    $errorKana = "※カナを入力してください";
    $isValidated = FALSE;
    //header("Location: input_error.php");
  }
  elseif (!preg_match("/^[ァ-ヶー 　]+$/u", $kana)) {
    $errorKana = "カナは全角カタカナで入力してください";
    $isValidated = FALSE;
    //header("Location: input_error.php");
  }

  // メールアドレスのバリデーション
  if ($email === "" || mb_ereg_match("^(\s|　)", $email)){
    $errorEmail = "※メールを入力してください";
    $isValidated = FALSE;
    //header("Location: input_error.php");
  }
  elseif (!preg_match("/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/", $email)){
    $errorEmail = "メールの形式が正しくありません";
    $isValidated = FALSE;
    //header("Location: input_error.php");
  }

  //電話番号のチェック
  if($phone === "" || mb_ereg_match("^(\s|　)", $phone)){
    $errorPhone = "※TELを入力してください";
    $isValidated = FALSE;
    //header("Location: input_error.php");
  }
  elseif(!preg_match("/^\d+$/", $phone)){
    $errorPhone = "TELは数字(ﾊｲﾌﾝなし)を入力してください";
    $isValidated = FALSE;
    //header("Location: input_error.php");
  }


  // 問い合わせ内容のバリデーション
  if ($inquiry === "" || mb_ereg_match("^(\s|　)", $inquiry)){
    $errorInquiry = "※内容を入力してください";
    $isValidated = FALSE;
     //header("Location: input_error.php");
      //exit;
  }

  // エラーが無ければ確認画面へ移動
  if ($isValidated == TRUE) {
    $contact = array(
      "name"    => $name,
      "kana"    => $kana,
      "email"   => $email,
      "phone"   => $phone,
      "inquiry" => $inquiry,
      "token"   => $token,
      // "contactOnly" => FALSE
    );
    $_SESSION["contact"] = $contact;
    // セッション変数を破棄
    unset($_SESSION["confirm"]);
    header("Location: confirm.php");
    //header("Location: confirm.php", FALSE);
    exit;
  }

}

?>
<!DOCTYPE html>

<html lang = "ja">
<head>
    <meta charset = "UTF-8">
    <title>Grad Thesis Site</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <meta name = "description" content = "Site Excercise(Grad Thesis)">
    <meta name = "keywords" content = "PHP, Programming, JavaScript, JQuery, HTML5, CSS3, SQL">
    <link href="https://fonts.googleapis.com/css?family=Rum+Raisin|Barrio|Chewy|Passion+One|Righteous|Risque|Russo+One" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href = "css/bootstrap.css" rel = "stylesheet">
    <link href="https://fonts.googleapis.com/earlyaccess/roundedmplus1c.css" rel="stylesheet" />
    <link href = "css/design_plan.css" rel = "stylesheet">
    <link href = "images/favicon.ico"
            rel = "shortcut icon"
            type="image/vnd.microsoft.icon">
    <style>
    </style>
    <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js">
</script>
<![endif]-->
</head>

<body>
	<!--<?php echo "\$fstvisit={$fstvisit}"; ?>-->

<?php

//var_dump($_SESSION);
//var_dump($_POST);

?>
<div class="container-fluid header_nav" id="page-6">
    <header id = "main_top">
            <img src="images/topMainV04.png" alt="Company Logo Top" id = "logo_top">
            <p>
            中古車の検索は<i class="fa fa-car" aria-hidden="true"></i><br>
            ボディタイプから<i class="fa fa-music" aria-hidden="true"></i><br>
            メーカーから<i class="fa fa-star" aria-hidden="true"></i></p>
            <div id="fade_parent">
                <img src="images/sample_01.jpg"
                        alt="Sea 73 Image Picture"
                        width="500"
                        id="images_fade">
            </div><!-- ./ <div id="fade_parent"> -->
            <p>
            ご予算に合わせて<i class="fa fa-taxi" aria-hidden="true"></i><br>
            ご提案をいたします<i class="fa fa-smile-o" aria-hidden="true"></i>
            </p>
    </header>
    <nav>
        <ul class = "nav nav-pills nav-justified">
            <li data-attribute="top"><a href="#page-6">TOP</a></li>
            <li data-attribute="about"><a href="#page-1">ABOUT</a></li>
            <li data-attribute="new"><a href="#page-2">NEW</a></li>
            <li data-attribute="search"><a href="#page-3">Search</a></li>
            <li data-attribute="contact"><a href="#page-4">Contact</a></li>
        </ul>
    </nav>
</div><!-- ./<div class="container-fluid header_part"> -->


<div class="container-fluid main_part">
    <main>
        <div id="page-1" style="height:600px;">
        <h3>ABOUT</h3>
          <div class="detail">
              <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table_detail">
                  <tr>
                      <th>住所</th>
                      <td>
                        西京都古宿区千人里1-2-3<br>
                        ステップタワー7階
                      </td>
                  </tr>
                  <tr>
                      <th>最寄り駅</th>
                      <td>JR&nbsp;上和田</td>
                  </tr>
                  <tr>
                    <th>道順</th>
                    <td>最寄り駅より&nbsp;徒歩7分<br>
                        <img src="images/map_zdrive.png" alt="Area Map" width="100%">
                    </td>
                  </tr>
                  <tr>
                      <th>電話番号</th>
                      <td>0120-441-222</td>
                  </tr>
                  <tr>
                      <th>営業時間</th>
                      <td>07:00 ～ 17:00</td>
                  </tr>
                  <tr>
                      <th>定休日</th>
                      <td>Sundays</td>
                  </tr>
                  <tr>
                      <th>カード</th>
                      <td>VISA,MASTER,AMEX <br>
                      <span><i class="fa fa-cc-visa" aria-hidden="true"></i></span>
                      <span><i class="fa fa-cc-mastercard" aria-hidden="true"></i></span>
                      <span><i class="fa fa-cc-amex" aria-hidden="true"></i></span>
                      </td>
                  </tr>
              </table>
            </div><!-- ./<div class="detail"> -->
        </div><!-- ./<div id="page-1" style="height:600px;"> -->
        <div id="page-2" style="height:700px;">
            <h3>NEW</h3>
            <div id="parent">

			</div>
        </div><!-- ./<div id="page-2 style="height:600px;"> -->
        <div id="page-3" style="height:800px;">
        <h3>SEARCH</h3>
            <form action="" id="myform">
                    <label>ご予算に合わせたメニューのご提案</label>
                    <br>
                    <span>
                        <i class="fa fa-jpy" aria-hidden="true"></i>
                    </span>
                    <select name="budget">
                        <option value="0000">----</option>
                        <option value="5000">5000</option>
                        <option value="4000">4000</option>
                        <option value="1350">1350</option>
                        <option value="980">980</option>
                        <option value="780">780</option>
                        <option value="600">600</option>
                        <option value="380">380</option>
                    </select>
                    <input type="submit" value = "SEARCH" id = "search_btn">
            </form>
                <section id="rank00" class = "search_hide">
                    <img src="images/original6.jpg" alt="Menu Item">
                </section>
                <section id="rank01" class = "search_hide">
                    <div class="description">
                        <h4>
                        <span>
                            <i class="fa fa-cutlery" aria-hidden="true"></i>
                        </span>
                        WebブラウザでWebサイトを閲覧
                        <span>
                            <i class="fa fa-beer" aria-hidden="true"></i>
                        </span>
                        <br>どういう仕組みになっている?</h4>
                    <h5>oooo 円<span>(Tax Included)</span></h5>
                    <ul>
                        <li>xoxoxox xoxoxo</li>
                        <li>xoxoxxo xoxox</li>
                        <li>xoxoxoxo</li>
                        <li>xoxoxoxoxo</li>
                        <li>xoxoxoxoxoxo</li>
                    </ul>
                    </div>
                <img src="images/original5.jpg"" alt="Menu Item 01">
            </section>
            <section id="rank02" class = "search_hide">
                <div class="description">
                    <h4>
                        <span>
                            <i class="fa fa-star-o" aria-hidden="true"></i>
                        </span>
                        社内研修にスタディラスを導入
                         <span>
                             <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                         </span>
                    <br>全国で同じ内容のトレーニングが可能</h4>
                    <h5>oooo 円<span>(Tax Included)</span></h5>
                    <ul>
                        <li>xoxoxox xoxoxo</li>
                        <li>xoxoxxo xoxox</li>
                        <li>xoxoxoxo</li>
                        <li>xoxoxoxoxo</li>
                        <li>xoxoxoxoxoxo</li>
                    </ul>
                </div>
                <img src="images/original5.jpg"" alt="Menu Item 02">
            </section>
            <section id="rankA" class = "search_hide">
                <div class="description">
                    <h4>ジードライブ</h4>
                    <h5>ooo 円</h5>
                    <p>Web・IT技術教育の<br>
                    プロフェッショナルです</p>
                </div>
                <img src="images/original1.jpg"" alt="Menu Item A">
            </section>
            <section id="rankB" class = "search_hide">
                <div class="description">
                    <h4>講義ビデオ</h4>
                    <h5>ooo 円</h5>
                    <p>講義を視聴することができる<br>
                    効率よく復習</p>
                </div>
                <img src="images/original4.jpg" alt="Menu Item B">
            </section>
            <section id="rankC" class = "search_hide">
                <div class="description">
                    <h4>最新のPC環境</h4>
                    <h5>ooo 円</h5>
                    <p>Web制作に適した<br>
                    最新のPC環境を用意しています。</p>
                </div>
                <img src="images/original3.jpg" alt="Menu Item C">
            </section>
            <section id="rankD" class = "search_hide">
                <div class="description">
                    <h4>スマートフォンで復習</h4>
                    <h5>ooo 円</h5>
                    <p>スマートフォンで<br>
                    好きな時間に復習できる。</p>
                </div>
                <img src="images/original2.jpg" alt="Menu Item D">
            </section>
            <section id="rankE" class = "search_hide">
                <div class="description">
                    <h4>ピンポイントで学習</h4>
                    <h5>ooo 円</h5>
                    <p>苦手なところを何度でも<br>
                    ピンポイントに選んで学習できる。</p>
                </div>
                <img src="images/original1.jpg" alt="Menu Item E">
            </section>
        </div><!-- ENDS<div id="page-2" style="height:500px;"> -->

        <div id="page-4" style="height:700px;">
            <h3>CONTACT</h3>
            <section class="contact">
                  <form action="" method="post"  id="contact" novalidate>
                  <input type="hidden" name="aaa" value="<?php echo $aaa; ?>">
                  <input type="hidden" name="confirm" value="<?php echo $confirm; ?>">
                  <input type="hidden" name="sent" value="<?php echo $sent; ?>">
                  <input type="hidden" name="fstvisit" value="<?php echo $fstvisit; ?>">
                  <input type="hidden" name="token" value="<?php echo getToken(); ?>">
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
                            <input type="submit" value="Confirm" id="send_btn" name = "confirmbtn">
                            <?php if (isset($errorName)): ?>
                                <div class="text-warning"><?php echo h($errorName); ?></div>
                            <?php endif; ?>
                            <?php if (isset($errorKana)): ?>
                                <div class="text-warning"><?php echo h($errorKana); ?></div>
                            <?php endif; ?>
                            <?php if (isset($errorEmail)): ?>
                                <div class="text-warning"><?php echo h($errorEmail); ?></div>
                            <?php endif; ?>
                            <?php if (isset($errorPhone)): ?>
                                <div class="text-warning"><?php echo h($errorPhone); ?></div>
                            <?php endif; ?>
                            <?php if (isset($errorInquiry)): ?>
                                <div class="text-warning"><?php echo h($errorInquiry); ?></div>
                            <?php endif; ?>
                          </td>
                        </tr>
                      </tfoot>
                      <tbody>
                        <tr>
                          <td align="right" nowrap>
                            <label for = "user">お名前<span>*</span></label>
                          </td>
                          <td valign="top">
                            <input type="text" name="name" maxlength="20" required id="user" size="18" value="<?php echo h($name); ?>">
                          </td>
                        </tr>
                        <tr>
                          <td align="right" nowrap>
                            <label for = "pronouce">カナ<span>*</span></label>
                          </td>
                          <td valign="top">
                            <input type="text" name="kana"  maxlength="20" size="18" placeholder="全角カタカナ入力" id = "pronouce" value="<?php echo h($kana); ?>">
                          </td>
                        </tr>
                          <tr>
                          <td align="right" nowrap>
                            <label for = "eaddress">メール<span>*</span></label>
                          </td>
                          <td valign="top">
                            <input type="email" name="email"  maxlength="40" required size="18" id = "eaddress" value="<?php echo h($email); ?>" >
                          </td>
                        </tr>
                          <tr>
                          <td align="right" nowrap>
                            <label for = "phonenum">TEL<span>*</span></label>
                          </td>
                          <td valign="top">
                            <input type="tel" name="phone" maxlength="20" size="18" id = "phonenum" value="<?php echo h($phone); ?>">
                          </td>
                        </tr>
                          <tr>
                          <td align="right" nowrap>
                            <label for = "inquiry_string">内容<span>*</span></label>
                          </td>
                          <td valign="top">
                            <textarea name="inquiry" cols="23" rows="6" maxlength="200" required id = "inquiry_string"><?php echo h($inquiry); ?></textarea>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </form>
                  <div id="shopping_done">
                    <h4>
                    <!--<span></span>様<br>-->お問い合わせありがとうございました
                    <i class="fa fa-smile-o" aria-hidden="true"></i><br><br>
                    <!--<a href="">戻る</a>-->
                    確認メールを送信いたしましたので、<br>
                    ご登録のメールをご覧くださいませ
                    <i class="fa fa-inbox" aria-hidden="true"></i>
                    </h4>
                    <form action="" method = "post">
                      <input type="submit" value="FORM" id="send_btn" class = "form_back" name = "formback">
					         </form>
                </div>
                </section>
        </div><!-- ./<div id="page-4" style="height:600px;"> -->
    </main>
</div><!-- ./<div class="container-fluid main_aside_part"> -->


<footer>
    <address>
    Contact
        <a href = "http://www.zdrv.com/" target = "_blank">
        Z Drive
        </a>
        for any inquiries.
    </address>
    <small>
        &copy;Z Drive All Rights Reserved On Behalf of
        <a href = "http://www.zdrv.com/" target = "_blank">Z Drive K.K.</a>
    </small>
</footer>


<script src="js/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/design_plan.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
    var navHeight = $('nav').height();
    var navHeight =  parseInt(navHeight) - 46;
    // -50 -> padding Top分（凡そ）を調整
    // スクロール
    $('nav a').click(function(){
        var target = $(this).attr('href');
        // スクロールする
        $('html, body').animate(
            {scrollTop: $(target).offset().top - navHeight}
        );
        //.activeを足す
        $(".container-fluid .nav li").click(function(){
            $(".active").removeClass();
            $(this).addClass("active");
        });//$(".container-fluid .nav li").click(function(){
    });//$('nav a').click(function(){
       //.currentを足す
       $(window).scroll(function(){
        var scrollTop = $(window).scrollTop();
        //console.log(scrollTop);
        var navHeight = $('nav').height();
              //現在位置の表示
              $('.nav-pills > li').removeClass("current","synchro");
              if(scrollTop > $('#page-4').offset().top - navHeight){
                 $('li[data-attribute="contact"]').addClass('current');
                 if($(".current").hasClass("active") == true){
                    $('.nav-pills > li').addClass("synchro");
                   }
                 else{
                    $('.nav-pills > li').removeClass(".synchro");
                   }
              }
              else if(scrollTop > $('#page-3').offset().top - navHeight){
                 $('li[data-attribute="search"]').addClass('current');
                 if($('.nav-pills > li').hasClass("active") == true){
                    $(".active").addClass("synchro");
                   }
                 else{
                    $(".active").removeClass(".synchro");
                   }
              }
              else if(scrollTop > $('#page-2').offset().top - navHeight){
                 $('li[data-attribute="new"]').addClass('current');
                 if($('current').hasClass("active") == true){
                    $('.nav-pills > li').addClass("synchro");
                   }
                  else{
                    $('.nav-pills > li').removeClass(".synchro");
                   }
              }
              else if(scrollTop > $('#page-1').offset().top - navHeight){
                  $('li[data-attribute="about"]').addClass('current');
                  if($('.nav-pills > li').hasClass("active") == true){
                     $('.nav-pills > li').addClass("synchro");
                    }
                  else{
                     $('.nav-pills > li').removeClass(".synchro");
                    }
             }
             else {
              $('li[data-attribute="top"]').addClass('current');
             }
        });//$(window).scroll(function(){

    //最初から少しスクロールダウン
    if($("input[name='fstvisit']").val() != ""){
    //if($("input[name='aaa']").val() != "" || $("input[name='confirm']").val() != ""){
    $(window).scrollTop(10);
    }



    //When Error => Reload with Scroll to Contact
    if($("input[name='aaa']").val() != ""){
    //if($("input[name='aaa']").val() != "" || $("input[name='confirm']").val() != ""){
      var scrollDown = parseInt($('#page-4').offset().top);
      //var heightNav = parseInt($('nav').height());

    $(window).scrollTop(scrollDown + 1);
    }

    //From Confirm => Header Location with Scroll to Contact
    if($("input[name='confirm']").val() != ""){
    //if($("input[name='aaa']").val() != "" || $("input[name='confirm']").val() != ""){
      var scrollDown = parseInt($('#page-4').offset().top);
      //var heightNav = parseInt($('nav').height());

    $(window).scrollTop(scrollDown + 1);
    }


    //Mail Sent => Thank You Message
    if($("input[name='sent']").val() != ""){
    //if($("input[name='aaa']").val() != "" || $("input[name='confirm']").val() != ""){
      var scrollDown = parseInt($('#page-4').offset().top);
      //var heightNav = parseInt($('nav').height());

    $(window).scrollTop(scrollDown + 1);
    $("#contact").css({
            display:"none"
            });
        //var userName = $("#user").val();
        //$("#shopping_done span").text(userName);
        $("#shopping_done").css({
            display:"block"
            });
    }

    //フォームを再表示
    $('.form_back').on('click', function() {
      $("#shopping_done").css({
            display:"none"
            });
        //var userName = $("#user").val();
        //$("#shopping_done span").text(userName);
        $("#contact").css({
            display:"block"
            });
    });

});// ./$(document).ready(function(){
</script>

</body>
</html>