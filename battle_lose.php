<?php session_start(); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php
	$servername = "localhost";
	$username = "root";
	$password = "123";
	$conn = new mysqli($servername, $username, $password, 'gobang');
	
	$ac = $_SESSION['username'];
	
	$result = $conn -> query("SELECT * FROM member WHERE account='$ac'");
	$row = mysqli_fetch_row($result);
	$lose = $row[5]+1;
	$sql = $conn -> query("UPDATE member SET lose='$lose' WHERE account='$ac'");
	
	echo '<meta http-equiv=REFRESH CONTENT=0;url=Home.php>';
?>