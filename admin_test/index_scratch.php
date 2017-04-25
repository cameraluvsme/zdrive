<?php
session_start();

require_once "../db_connect.php";
require_once "../function.php";
// require_once "auth.inc.php";

//------------------------------------------------
// ログイン認証済みでなければログインページへ移動
//------------------------------------------------
// auth_confirm();

//-------------
// 画像のパス
//-------------
// define("IMAGE_PATH", "../images/press/");

//----------------------
// 1ページに表示する件数
//----------------------
 define("NUM_PER_PAGE", 5);

try {
  //--------------------
  // データベースの準備
  //--------------------
  $pdo = db_connect();

  //--------------------------------
  // ページ分割機能 >> 実件数の取得
  //--------------------------------
  $sql = "SELECT COUNT(*) AS hits FROM used_cars";
  $stmt = $pdo->query($sql);
  $res = $stmt->fetch();
  $hits = $res["hits"];
/*
  //----------------------------------
  // ページ分割機能 >> ページ数の計算
  //----------------------------------
  // $numPages = ceil($hits / NUM_PER_PAGE);
/
  //------------------------------------
  // ページ分割機能 >> ページ番号の取得
  //------------------------------------
  if (isset($_GET["p"])) {
  	$currentPage = $_GET["p"];
  }
  else {
  	$currentPage = 1;
  }
  $prevPage = $currentPage - 1;
  $nextPage = $currentPage + 1;

  //----------------------------------------------
  // ページ分割機能 >> SQLのLIMITオプションの生成
  //----------------------------------------------
  $offset = ($currentPage - 1) * NUM_PER_PAGE;
*/
  //----------------------
  // お知らせリストの取得
  //----------------------
  $sql = "SELECT * FROM used_cars ORDER BY posted DESC
  		  LIMIT " . $offset . "," . NUM_PER_PAGE;
  $stmt = $pdo->query($sql);
  $news = $stmt->fetchAll();
/*
  //----------------------------------------
  // データベースに未登録の画像データを削除
  //----------------------------------------
  $sql = "SELECT image FROM news";
  $stmt = $pdo->query($sql);
  $imageNames = $stmt->fetchAll();
  foreach ($imageNames as $imageName){
  	$imagedata[] = IMAGE_PATH . $imageName[image];
  }
  $imagefiles = glob(IMAGE_PATH . "*");
  foreach ($imagefiles as $imagefile){
  	if(!in_array($imagefile, $imagedata)){
  		unlink($imagefile);
  	}
  }
*/
}
catch (PDOException $e) {
  echo $e->getMessage();
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>お知らせ一覧 | Crescent Shoes 管理</title>
<link rel="stylesheet" href="css/admin.css">
</head>
<body id="admin_index">
<header>
  <div class="inner">
    <span><a href="index.php">Crescent Shoes 管理</a></span>
    <div id="account">
      admin
      [ <a href="logout.php">ログアウト</a> ]
    </div>
  </div>
</header>
<div id="container">
  <main>
    <h1>お知らせ一覧</h1>
    <p><a href="news_add.php">お知らせの追加</a></p>
    <div id="pages">
    <?php
    if ($numPages > 1) {
      //--------------------
      // 前のページへのリンク
      //--------------------
      if ($currentPage == 1) {
        echo "前のページ | ";
      }
      else {
        echo '<a href="?p='.h($prevPage).'">前のページ</a> | ';
      }
      //--------------------
      // ページ番号のリンク
      //--------------------
      for ($p = 1; $p <= $numPages; $p++) {
        if ($p == $currentPage) {
          echo " ".h($p)." |";
        }
        else {
          echo ' <a href="?p='.h($p).'">'.h($p).'</a> |';
        }
      }
      //--------------------
      // 次のページへのリンク
      //--------------------
      if ($currentPage == $numPages) {
        echo " 次のページ";
      }
      else {
        echo ' <a href="?p='.h($nextPage).'">次のページ</a>';
      }
    }
    ?>
    </div>
    <table>
      <tr>
        <th>日付</th>
        <th>タイトル／お知らせ内容</th>
        <th>画像(64x64)</th>
        <th>編集</th>
        <th>削除</th>
      </tr>
      <?php foreach ($news as $item): ?>
      <tr>
        <td class="center"><?php echo h($item["posted"]); ?></td>
        <td>
        <span class="title"><?php echo h($item[""]); ?></span>
        <?php echo h($item["message"]); ?>
        </td>
        <td class="center">
        <?php if($item["image"]):?>
        <img src="<?php echo IMAGE_PATH . h($item["image"]); ?>" width="64" height="64" alt="">
        <?php else:?>
        <img src="../images/press.png" width="64" height="64" alt="">
        <?php endif;?>
        </td>
        <td class="center"><a href="news_edit.php?id=<?php echo h($item["id"]); ?>">編集</a></td>
        <td class="center"><a href="news_delete.php?id=<?php echo h($item["id"]); ?>">削除</a></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </main>
  <footer>
    <p>&copy; Crescent Shoes All rights reserved.</p>
  </footer>
</div>
</body>
</html>
