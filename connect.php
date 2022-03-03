<?php session_start(); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php
	$servername = "localhost";
	$username = "root";
	$password = "123";
	$conn = new mysqli($servername, $username, $password, 'gobang');
	
	$ac = $_POST['ac'];
	$pw = $_POST['pw'];
	//搜尋資料庫資料
	$result = $conn -> query("SELECT * FROM member where account = '$ac'");
	$row = mysqli_fetch_row($result);
	
	//判斷帳號與密碼是否為空白
	//以及MySQL資料庫裡是否有這個會員
	if($ac != null && $pw != null && $row[2] == $ac && $row[3] == $pw)
	{
		//將帳號寫入session，方便驗證使用者身份
		$_SESSION['username'] = $ac;
		echo '<script>alert("登入成功!")</script>';
		echo '<meta http-equiv=REFRESH CONTENT=0;url=Home.php>';
	}
	else
	{
		echo '<script>alert("登入失敗!")</script>';
		echo '<meta http-equiv=REFRESH CONTENT=0;url=Login.php>';
	}
?>