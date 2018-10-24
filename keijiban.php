<?php
header("Content-Type: text/html; charset=UTF-8");

//データベース接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

//テーブル作成
$sql = "CREATE TABLE mission4_3"
."("
."id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."day TEXT,"
."pass TEXT"
.");";
$stmt = $pdo->query($sql);

//編集モード
if(!empty($_POST['hensyu']) && !empty($_POST['hensyupass'])){
	$sql = 'SELECT * FROM mission4_3';
	$results = $pdo -> query($sql);
	foreach($results as $row){
		//ID=編集番号の時、パスワード一致なら変数取得
		if($row['id'] == $_POST['hensyu'] && $row['pass'] == $_POST['hensyupass']){
			$aftername = $row['name'];
			$aftercomment = $row['comment'];
			$afterpass = $row['pass'];
			$afterhide = $row['id'];
		}
	}
}
?>

<form action = 'keijiban.php' method = 'post' accept-charset='UTF-8'>
　　名前　　：<input type = 'text' name = 'name' value = "<?=$aftername?>" placeholder = '名前'><br>
　コメント　：<input type = 'text' name = 'comment' value = "<?=$aftercomment?>" placeholder = 'コメント'><br>
パスワード　：<input type = 'text' name = 'pass' value = "<?=$afterpass?>" placeholder = 'パスワード'>
<input type = 'hidden' name = 'hide' value = "<?=$afterhide?>">
<input type = 'submit' value = '送信'><br><br>
削除対象番号：<input type = 'text' name = 'sakuzyo' placeholder = '削除対象番号'><br>
パスワード　：<input type = 'text' name = 'sakuzyopass' placeholder = 'パスワード'>
<input type = 'submit' value = '削除'><br><br>
編集対象番号：<input type = 'text' name = 'hensyu' placeholder = '編集対象番号'><br>
パスワード　：<input type = 'text' name = 'hensyupass' placeholder = 'パスワード'>
<input type = 'submit' value = '編集'><br>
</form>

<?php
//編集機能
if(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['pass']) && !empty($_POST['hide'])){
	$id = $_POST['hide'];
	$nm = $_POST['name'];
	$kome = $_POST['comment'];
	$ps = $_POST['pass'];
	$sql = "update mission4_3 set name ='$nm', comment = '$kome', pass = '$ps' where id = '$id'";
	$result = $pdo -> query($sql);

//削除機能
}else if(!empty($_POST['sakuzyo']) && !empty($_POST['sakuzyopass'])){
	$id = $_POST['sakuzyo'];
	$pass = $_POST['sakuzyopass'];
	$sql = "delete from mission4_3 where id = '$id' and pass = '$pass'";
	$result = $pdo -> query($sql);

//投稿機能
}else if(!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['pass'])){
	//データ入力
	$sql = $pdo -> prepare("INSERT INTO mission4_3(name,comment,day,pass)VALUES(:name,:comment,:day,:pass)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':day', $day, PDO::PARAM_STR);
	$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$day = date( "Y年m月d日 h:i:s" );
	$pass = $_POST['pass'];
	$sql -> execute();
}

//入力したデータを表示
$sql = 'SELECT * FROM mission4_3 ORDER BY id';
$results = $pdo -> query($sql);
foreach($results as $row){
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['day'].'<br>';
}
?>
