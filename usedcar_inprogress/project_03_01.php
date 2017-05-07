<?php
$maker = $_POST["maker"];   //配列で人物名を取得??できるかな？
$type = $_POST["type"];
$year = $_POST["year"];
$price = $_POST["price"];
$pref = $_POST["pref"];

if(($price == 1 && $year >= 50000)
	|| ($price == 2 && $year >= 5000)){
	$msg = "一戸建て物件あります";
}
elseif(($price == 1 && $year >= 500) //閉じ括弧忘れによるエラー
		|| ($price == 2 && $year >= 1000)){
	$msg = "マンションあります";
}
else{
	$msg = "紹介できる物件なし";
}

?>
<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>Used Cars</title>
	<style>
	table thead td{
		text-align:center;
	}
	</style>
</head>
<body>
<?php if($_SERVER["REQUEST_METHOD"] === "POST"): ?>
<p>Within your budget and your preference, <br><?php echo $msg; ?></p>
<p><?php echo "<img src = 'images/car_img_0{$imgNum}.jpg' alt ='Image Picture' >"; ?></p>
<?php endif; ?>

<form action="" method="post">
	<table border = "2" cellpadding = "1" cellspacing = "1">
		<thead>
			<tr>
				<td colspan = "2">SELECT YOUR PREFERENCE</td>
			</tr>
		</thead>
		<tobody>
			<tr>
				<th>Preference</th>
				<td>
					<input type="radio" name="pref" value="NEW">NEW<br>
					<input type="radio" name="pref" value="USED">USED
				</td>
			</tr>
			<tr>
				<th>Maker</th>
				<td>
					<input type="checkbox" name="maker[]" value="Nissan" <?php if($maker == maker){echo "checked";} ?>>Nissan<br>
      				<input type="checkbox" name="maker[]" value="Toyota" <?php if($maker == maker){echo "checked";} ?>>Toyota<br>
      				<input type="checkbox" name="maker[]" value="Honda" <?php if($maker == maker){echo "checked";} ?>>Honda<br>
      				<input type="checkbox" name="maker[]" value="Mazda" <?php if($maker == maker){echo "checked";} ?>>Mazda
				</td>
			</tr>
			<tr>
				<th>Type</th>
				<td>
					<select name="type" size = "2">
						<option value="SUV" <?php if($type == SUV){echo "selected";} ?>>SUV</option>
						<option value="Van" <?php if($type == Van){echo "selected";} ?>>Van</option>
						<option value="Compact" <?php if($type == Compact){echo "selected";} ?>>Compact</option>
						<option value="Wagon" <?php if($type == Wagon){echo "selected";} ?>>Wagon</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>Year</th>
				<td>
					<select name="year" size = "2">
						<option value="2012" <?php if($year == 2012){echo "selected";} ?>>2012</option>
						<option value="2010" <?php if($year == 2010){echo "selected";} ?>>2010</option>
						<option value="2008" <?php if($year == 2008){echo "selected";} ?>>2008</option>
						<option value="2005" <?php if($year == 2005){echo "selected";} ?>>2005</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>Price</th>
				<td>
					<input type="number" name="price"
	value="<?php echo $price; ?>" step = "25" min= "50" max = "900">Man Yen
				</td>
			</tr>
		</tobody>
	</table>
	<input type="submit" value = "Confirm"/>
</form>
</body>
</html>