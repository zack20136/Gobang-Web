<?php session_start(); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php
	$servername = "localhost";
	$username = "root";
	$password = "123";
	$conn = new mysqli($servername, $username, $password, 'gobang');
	
	$name = $_POST['name'];
	$ac = $_POST['ac'];
	$pw = $_POST['pw'];
	$pw2 = $_POST['pw2'];
	
	//判斷帳號密碼是否為空值、確認密碼輸入的正確性
	if($name != null && $ac != null && $pw != null && $pw2 != null && $pw == $pw2)
	{	
		$result = $conn -> query("SELECT * FROM member where account = '$ac'");
		$row = mysqli_fetch_row($result);
		$flag = 0;
		if($row[2] == $ac)//判斷帳號是否被使用過
		{	
			echo '<script>alert("此帳號已被使用!")</script>';
			echo '<meta http-equiv=REFRESH CONTENT=0;url=Register.php>';
			$flag = 1;
		}
		if($flag == 0)
		{
			//讀取最後的id並+1
			$result = $conn -> query("SELECT * FROM member ORDER BY id DESC");
			$row = mysqli_fetch_row($result);
			$id = $row[0] + 1;
			//新增資料進資料庫語法
			if($conn -> query("insert into member(id,name,account,password,win,lose) values ('$id','$name','$ac','$pw','0','0')"))
			{
				echo '<script>alert("註冊成功!")</script>';
				echo '<meta http-equiv=REFRESH CONTENT=0;url=Login.php>';
			}
			else
			{
				echo '<script>alert("註冊失敗!")</script>';
				echo '<meta http-equiv=REFRESH CONTENT=0;url=Register.php>';
			}
		}
	}
	else
	{
		echo '<script>alert("註冊失敗!")</script>';
		echo '<meta http-equiv=REFRESH CONTENT=0;url=Register.php>';
	}
?>