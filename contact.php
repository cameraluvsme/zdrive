<?php
session_start();

require_once "util.inc.php";

//--------------------
// 変数の初期化
//--------------------
$name    = "";
$kana    = "";
$email   = "";
$phone   = "";
$inquiry = "";

$contactOnly = FALSE;  //地図の表示フラグ

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
  $contactOnly = $contact["contactOnly"];
}

//--------------------
// 「確認する」ボタン
//--------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $isValidated = TRUE;
  $contactOnly = TRUE;

  // 入力データの取得
  $name    = $_POST["name"];
  $kana    = $_POST["kana"];
  $email   = $_POST["email"];
  $phone   = $_POST["phone"];
  $inquiry = $_POST["inquiry"];
  $token   = $_POST["token"];

  // 名前のバリデーション
  if ($name === "") {
    $errorName = "※お名前を入力してください";
    $isValidated = FALSE;
  }

  // フリガナのバリデーション
  if ($kana === "") {
    $errorKana = "※フリガナを入力してください";
    $isValidated = FALSE;
  }
  elseif (!preg_match("/^[ァ-ヶー 　]+$/u", $kana)) {
    $errorKana = "※全角カタカナで入力してください";
    $isValidated = FALSE;
  }

  // メールアドレスのバリデーション
  if ($email === "") {
    $errorEmail = "※メールアドレスを入力してください";
    $isValidated = FALSE;
  }
  elseif (!preg_match("/^[^@]+@[^@]+\.[^@]+$/", $email)) {
    $errorEmail = "※メールアドレスの形式が正しくありません";
    $isValidated = FALSE;
  }

  // 問い合わせ内容のバリデーション
  if ($inquiry === "") {
    $errorInquiry = "※お問い合わせ内容を入力してください";
    $isValidated = FALSE;
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
      "contactOnly" => FALSE
    );
    $_SESSION["contact"] = $contact;
    header("Location: contact_conf_tanma.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="クレセントシューズは靴の素材と履き心地にこだわる方に満足をお届けする東京の靴屋です。後悔しない靴選びはぜひクレセントシューズにお任せください。">
  <meta name="keyword" content="Crescent,shoes,クレセントシューズ,東京,新宿区,メンズシューズ,レディースシューズ,キッズシューズ,ベビーシューズ">

  <title>Contact | Crescent Shoes</title>

  <link rel="shortcut icon" href="images/favicon.ico">
  <link rel="stylesheet" href="css/styles.css">
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
      <!--[if lt IE 9]>
    <script src="http://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv-printshiv.min.js"></script>
    <script src="http://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!--[if lt IE 8]>
    <![endif]-->
  </head>
  <body class="pageTop" id="pageTop">
    <header class="navbar navbar-default navbar-fixed-top" role="banner">
      <div class="container">
        <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <h1 class="navbar-header">
          <a href="index.html" class="navbar-brand"><img src="images/logo01.png" alt="LOGO"></a>
        </h1><!-- /.navbar-header -->
        <nav class="navbar-collapse collapse" id="navigation" role="navigation">
          <ul class="nav navbar-nav">
            <li><a href="index.html">ホーム<span>HOME</span></a></li>
            <li><a href="about.html">会社概要<span>ABOUT</span></a></li>
            <li><a href="news.php">ニュース<span>NEWS</span></a></li>
            <li><a href="shop.html">ショップ<span>ONLINE SHOP</span></a></li>
            <li><a href="contact.php">お問い合わせ<span>CONTACT</span></a></li>
          </ul>
          <form class="navbar-form" role="search">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="keyword">
              <span class="input-group-btn"><button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button></span>
            </div><!-- /.input-group -->
          </form>
        </nav>
      </div>
    </header>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <nav>
            <h1 class="page-header">Contact</h1>
            <ol class="breadcrumb">
              <li><a href="index.html">Home</a></li>
              <li class="active">Contact</li>
            </ol>
          </nav>
        </div>
      </div>
      <?php if(!$contactOnly): ?>
      <div class="row">
        <div class="col-md-8">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6479.926203846024!2d139.7001925!3d35.702525600000016!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188d2f6cba44df%3A0x18b3bc4c95b9f078!2z44CSMTY5LTAwNzMg5p2x5Lqs6YO95paw5a6_5Yy655m-5Lq655S677yS5LiB55uu77yU4oiS77yY!5e0!3m2!1sja!2sjp!4v1439870260921" width="100%" height="400px" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
        <div class="col-md-4">
          <h3>Contact Details</h3>
          <p>
            〒169-0073<br>東京都新宿区百人町2-4-8　グレースビル1階
          </p>
          <p><i class="fa fa-phone"></i> 03-1234-5678</p>
          <p><i class="fa fa-envelope-o"></i> info@crescent.com
          </p>
          <p><i class="fa fa-clock-o"></i>
            月-金曜日: 9:00 AM to 5:00 PM</p>
          </div>
        </div>
        <?php endif; ?>
        <div class="row">
          <div class="col-md-4 hidden-sm hidden-xs contactleft">
            <div class="contact-img">
            <img src="images/contact.jpg">
            </div>
          </div>
          <div class="col-md-8">
            <h3 class="page-header">Send Message</h3>
            <form class="form-horizontal" action="" method="post">
            <input type="hidden" name="token" value="<?php echo getToken(); ?>">
              <div class="form-group">
                <label for="inputname" class="col-sm-3 control-label">お名前<span>(必須)</span></label>
                <div class="col-sm-9">
                <?php if (isset($errorName)): ?>
                <div style="text-warning"><?php echo h($errorName); ?></div>
                <?php endif; ?>
                  <input type="text" class="form-control" id="inputname"  name="name"  value="<?php echo h($name); ?>" required>
                  <p class="help-block">(例)山田　太郎</p>
                </div>
              </div>
              <div class="form-group">
                <label for="inputkana" class="col-sm-3 control-label">フリガナ<span>(必須)</span></label>
                <div class="col-sm-9">
                <?php if (isset($errorKana)): ?>
                <div class="text-warning"><?php echo h($errorKana); ?></div>
                <?php endif; ?>
                  <input type="text" class="form-control" id="inputkana"  name="kana"  value="<?php echo h($kana); ?>" required>
                  <p class="help-block">(例)ヤマダ　タロウ ※全角カタカナ</p>
                </div>
              </div>
              <div class="form-group">
                <label for="inputemail" class="col-sm-3 control-label">メールアドレス<span>(必須)</span></label>
                <div class="col-sm-9">
                <?php if (isset($errorEmail)): ?>
                <div class="text-warning"><?php echo h($errorEmail); ?></div>
                <?php endif; ?>
                  <input type="email" class="form-control" id="inputemail" name="email" value="<?php echo h($email); ?>" required>
                  <p class="help-block">(例)abc@zz.com ※半角英数字</p>
                </div>
              </div>
              <div class="form-group">
                <label for="inputtel" class="col-sm-3 control-label">電話番号</label>
                <div class="col-sm-9">
                  <input type="tel" class="form-control" id="inputtel" name="phone" value="<?php echo h($phone); ?>">
                  <p class="help-block">(例)03-1234-3214　※ハイフンあり　半角数字</p>
                </div>
              </div>
              <div class="form-group">
                <label for="inputmessage" class="col-sm-3 control-label">お問い合わせ内容<span>(必須)</span></label>
                <div class="col-sm-9">
                <?php if (isset($errorInquiry)): ?>
                <div class="text-warning"><?php echo h($errorInquiry); ?></div>
                <?php endif; ?>
                  <textarea rows="10" cols="100" class="form-control" id="message"  name="inquiry" required maxlength="999" style="resize:none"><?php echo h($inquiry); ?></textarea>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-10">
                  <button type="submit" class="btn btn-success btn-lg">内容を確認して送信</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="pagetop margin-top-md">
        <a href="#pageTop" class="center-block text-center" onclick="$('html,body').animate({scrollTop: 0}); return false;"><i class="fa fa-chevron-up center-block "></i>Page Top</a>
      </div>
      <footer class="margin-top-md" role="contentinfo">
        <div class="container">
          <div class="row">
            <div class="text-center">
              <ul class="list-inline">
                <li><a href="index.html">HOME</a></li>
                <li><a href="about.html">ABOUT</a></li>
                <li><a href="news.php">NEWS</a></li>
                <li><a href="shop.html">ONLINE SHOP</a></li>
                <li><a href="contact.php">CONTACT</a></li>
              </ul>
              <small>&copy; Crescent Shoes.All Rights Reserved.</small>
            </div><!-- /.text-center -->
          </div><!-- /.row -->
        </div><!-- /.container -->
      </footer>
      <script src="js/jquery-2.1.4.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <script src="js/main.js"></script>
    </body>
</html>