<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>会員登録</title>
</head>
<body>
<h1>会員登録</h1>
<form action="refer_agasa_confirm.php" method="post">
<table border="1" cellpadding="5" cellspacing="0">
	<tr>
		<th>氏名</th>
		<td><input type="text" name="name"></td>
	</tr>
	<tr>
		<th>フリガナ</th>
		<td><input type="text" name="kana"></td>
	</tr>
	<tr>
		<th>職業</th>
		<td>
			<select name="prof">
				<option>社会人</option>
				<option>学生</option>
				<option>その他</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>好きな登場人物（複数選択可）</th>
		<td>
			<input type="checkbox" name="character[]" value="ポワロ">ポワロ<br>
			<input type="checkbox" name="character[]" value="ヘイスティングス">ヘイスティングス<br>
			<input type="checkbox" name="character[]" value="ジャップ警部">ジャップ警部<br>
			<input type="checkbox" name="character[]" value="ミスレモン">ミスレモン
		</td>
	</tr>
	<tr>
		<th>メールマガジン</th>
		<td>
			<input type="radio" name="magazine" value="yes">希望する<br>
			<input type="radio" name="magazine" value="no">希望しない
		</td>
	</tr>
	<tr>
		<th>備考</th>
		<td><textarea name="memo"></textarea></td>
	</tr>
	<tr>
		<th>個人情報の扱いについて同意する</th>
		<td><input type="checkbox" name="privacy">同意する</td>
	</tr>
	<tr>
		<th colspan="2"><input type="submit" value="登録内容の確認"></th>
	</tr>
</table>
</form>
</body>
</html>