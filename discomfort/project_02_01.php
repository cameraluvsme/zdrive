<?php
	$hmdt = $_POST["hmdt"];
	if(!is_numeric($hmdt) ){
		echo "Please enter numeric characters<br>";
	}

	elseif($hmdt > 100 or $hmdt < -0){
		 echo "Number you enter is invalid<br>";
	}


	$temp = $_POST["temp"];
	if(!is_numeric($temp) ){
		echo "Please enter numeric characters";
	}

	elseif($temp > 100 or $temp < -50){
		echo "Number you enter is invalid";
	}


	$disIndex = $temp - 0.55 * (1- 0.01 * $hmdt) * ($temp -14.5);
	if($disIndex >= 32){
		$condition = "State of Medical Emergency";
		$imgNum = 3;
	}
	elseif($disIndex >= 29){
		$condition = "Everyone feels severe stress.";
		$imgNum = 2;
	}

	elseif($disIndex >= 27){
		$condition = "Most population feels discomfort.";
		$imgNum = 1;

	}
	elseif($disIndex >= 25){
		$condition = "Most 50% of population feels discomfortable.";
		$imgNum = 6;
	}

	elseif($disIndex >= 21){
		$condition = "Under 50% of population feels discomfortable.";
		$imgNum = 5;
	}

	else{
		$condition = "No discomfort.";
		$imgNum = 4;
	}


	echo "<p>With the condition, <br> {$disIndex} ℃ now is the Discomfort Index. </p>";
	echo "<p>Under {$disIndex}℃ of D.I., <br> {$condition} </p>";
	echo "<img src = 'images/di_img_0{$imgNum}.jpg' alt ='Image Picture' >";
?>

<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>Discomfort Index with PHP</title>
</head>
<body>
	<form action="" method = "post">
		<input type="text" name = "temp" placeholder = "plese enter temperature" value = "<?php echo $temp; ?>"/>℃<br>
		<input type="text" name = "hmdt" placeholder = "plese enter humidity" value = "<?php echo $hmdt; ?>"/>%<br>
		<p><input type="submit" value = "EXECUTE" /></p>
	</form>
</body>
</html>