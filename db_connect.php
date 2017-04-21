<?php
/**
 * データベース利用の設定
 */
define("HOST", "localhost"); // データベースサーバのアドレス
define("DBNAME", "DBzd1B07"); // データベースの名前
define("DBUSER", "zd1B07"); // データベース利用ユーザの名前
define("DBPASS", "AV3M5E");  // データベース利用ユーザのパスワード

/**
 * データベース利用準備
 */
function db_connect()
{
  //--------------------
  // データベースへ接続する
  //--------------------
  $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME, DBUSER, DBPASS);

  //--------------------
  // エラーモードを例外モードに設定
  //--------------------
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  //--------------------
  // 文字コードをUTF-8に設定する
  //--------------------
  $pdo->exec("SET NAMES utf8");

  return $pdo;
}
