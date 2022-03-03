<?php session_start(); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<html>
    <head>
	  <title>五子棋</title>
	  <meta charset="UTF-8">
	  <meta http-equiv="Content-Language" content="zh-TW">
	  <link rel="shortcut icon" href="https://ilearn2.fcu.edu.tw/theme/image.php/essential/theme/1570529392/favicon">
	  <link rel="stylesheet" href="main.css">
    </head>
	
	<?php
		$servername = "localhost";
		$username = "root";
		$password = "123";
		$conn = new mysqli($servername, $username, $password, 'gobang');
	?>
	
    <body class="main">
      <div id="header">
        <h1>
          <p>五子棋</p>
        </h1>
      </div>
	  <div style="text-align:left;height:5px;">
		<?php
			$user = $_SESSION['username'];
			echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 玩家&nbsp&nbsp:&nbsp&nbsp<b>$user</b>";
			echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <a href='logout.php' style='text-decoration: none;'>登出</a>";
		?>
	  </div>
	  <nav style="text-align:center;">
        <ul class="menu">
          <li><a href="./Home.php">首頁</a></li>
          <li><a href="./Robot.php">人機對戰</a></li>
		  <li><a href="./Wait.php">玩家對戰</a></li>
          <li style="background-color: rgb(115, 115, 115);color: white;"><a href="./Info.php">排名</a></li>
          <li><a href="./Rule.php">遊戲規則</a></li>
        </ul>
      </nav>
	  <div style='background:lightgray;margin:20px 100px;border:3px gray solid;padding:30px 0px'>
		  <?php
			$rank = 0;
			$result = $conn -> query("SELECT * FROM member ORDER BY win DESC,lose ASC");
			foreach($result as $row){
				if("$row[id]"!=0){
					$rank++;
					echo "<div style='font-size:20px;padding:30px 50px;margin:0px 100px;'>";
					echo "<nav><li style='list-style: none;'> 排名 : $rank </li> <li style='list-style: none;'> 暱稱 : $row[name] </li> <li style='list-style: none;'> 帳號 : $row[account] </li> <li style='list-style: none;'> 勝場 : $row[win] </li> <li style='list-style: none;'> 敗場 : $row[lose] </li>";
					if("$row[win]"==0 && "$row[lose]"==0){
						$rate = -1;
					}
					elseif("$row[win]"==0){
						$rate = 0;
					}
					elseif("$row[lose]"==0){
						$rate = 100;
					}
					else{
						$rate = "$row[win]"/("$row[win]"+"$row[lose]")*100;
					}
					if($rate==-1){
						echo "<li style='list-style: none;'> 勝率 : - </li>";
					}
					else{
						echo "<li style='list-style: none;'> 勝率 : ";
						echo  number_format($rate,2);
						echo " %</li>";
					}
					echo "</nav></div>";
				}
			}
		  ?>
	  </div>
    </body>
</html>