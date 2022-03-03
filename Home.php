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
          <li style="background-color: rgb(115, 115, 115);color: white;"><a href="./Home.php">首頁</a></li>
          <li><a href="./Robot.php">人機對戰</a></li>
		  <li><a href="./Wait.php">玩家對戰</a></li>
          <li><a href="./Info.php">排名</a></li>
          <li><a href="./Rule.php">遊戲規則</a></li>
        </ul>
      </nav>
	  
	  <div>
		<h2>
		  <p>歡迎來到</P>
		  <p>連線版五子棋</P>
		  <p>!!!</P>
		  <p>!!!</P>
		</h2>
	  </div>
    </body>
</html>