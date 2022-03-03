<?php session_start(); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<html>
    <head>
	  <title>五子棋</title>
	  <meta charset="UTF-8">
	  <meta http-equiv="Content-Language" content="zh-TW">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <link rel="shortcut icon" href="https://ilearn2.fcu.edu.tw/theme/image.php/essential/theme/1570529392/favicon">
	  <link rel="stylesheet" href="main.css">
    </head>
	
	<?php
		$servername = "localhost";
		$username = "root";
		$password = "123";
		$conn = new mysqli($servername, $username, $password, 'gobang');
		
		$ac = $_SESSION['username'];
		if(mysqli_fetch_row($conn -> query("SELECT * FROM wait where player1 = 'player1'"))){
			$sql = $conn -> query("UPDATE wait SET player1='$ac' WHERE num='0'");
		}
		elseif(mysqli_fetch_row($conn -> query("SELECT * FROM wait where player2 = 'player2'"))&& !mysqli_fetch_row($conn -> query("SELECT * FROM wait where player1 = '$ac'"))){
			$sql = $conn -> query("UPDATE wait SET player2='$ac' WHERE num='0'");
		}
		
		while(mysqli_fetch_row($conn -> query("SELECT * FROM wait where player1 = 'player1'"))||mysqli_fetch_row($conn -> query("SELECT * FROM wait where player2 = 'player2'"))){}
		
		$row = mysqli_fetch_row($conn -> query("SELECT * FROM wait WHERE num='0'"));
		$p1 = $row[1];
		$p2 = $row[2];
		$_SESSION['p1'] = $p1;
		$_SESSION['p2'] = $p2;
		
		$result = $conn -> query("SELECT * FROM game ORDER BY room DESC");
		$row = mysqli_fetch_row($result);
		$_SESSION['room'] = $row[0] + 1;
		$room = $_SESSION['room'];
		
		if($ac == $p2){
			$sql = $conn -> query("insert into game(room,player1,player2) values ('$room','$p1','$p2')");
			$sql = $conn -> query("UPDATE wait SET player1='player1',player2='player2' WHERE num='0'");
		}
	?>
	
    <body class="main">
      <div id="header">
        <h1>
          <p>五子棋</p>
        </h1>
      </div>
	  <div style="text-align:left;height:5px;">
		<?php
			if($ac == $p1){
				echo "<span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 玩家&nbsp&nbsp:&nbsp&nbsp<b>$ac</b>(黑)</span>";
			}
			elseif($ac == $p2){
				echo "<span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 玩家&nbsp&nbsp:&nbsp&nbsp<b>$ac</b>(白)</span>";
			}
			echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <a href='Login.php' style='text-decoration: none;'>登出</a>";
		?>
	  </div>
	  <nav style="text-align:center;">
        <ul class="menu">
          <li><a href="./Home.php">首頁</a></li>
          <li><a href="./Robot.php">人機對戰</a></li>
		  <li style="background-color: rgb(115, 115, 115);color: white;"><a href="./Wait.php">玩家對戰</a></li>
          <li><a href="./Info.php">排名</a></li>
          <li><a href="./Rule.php">遊戲規則</a></li>
        </ul>
      </nav>
	  <div style="text-align:left;height:20px;">
		<span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspPOS(</span>
		<span id="posx">0</span>
		<span>,</span>
		<span id="posy">0</span>
		<span>)&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>
		<span>Turn:</span>
		<span id="Turn">0</span>
		
	  </div>
	  <div style="text-align:right;height:5px;">
		<?php
			if($ac == $p1){
				echo "<p>對手&nbsp&nbsp:&nbsp&nbsp<b>$p2</b>(白)&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</p>";
			}
			elseif($ac == $p2){
				echo "<p>對手&nbsp&nbsp:&nbsp&nbsp<b>$p1</b>(黑)&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</p>";
			}
		?>
	  </div>
	  <div>
		<canvas id="chess" name="chess" width="450px" height="450px"></canvas>
	  </div>
	  
	  <div id="chat-messages" style="overflow-y:scroll;height:100px;width=400px;margin:20px 10px;border:2px gray solid;"></div>
	  <span>&nbsp&nbsp</span>
	  <input type="text" class="message"></input>
	  
    </body>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="socket.io-client/socket.io.js"></script>
	
	<script>
        var socket = io.connect("ws://59.126.121.67:3000");
		
        $('.message').on('change', function(){
            socket.emit('send message', $(this).val());
            $(this).val('');
        });
        socket.on('new message', function(data){
            $('#chat-messages').append('<p>' + data +'</p>');
        });
    </script>
	
	<script>
		//畫布畫筆
		var chess = document.getElementById("chess");
		var context = chess.getContext("2d"); //context可以看作畫筆
		context.strokeStyle="#000000";              //畫筆的顏色
		//載入棋盤
		window.onload = function(){               //頁面載入完成事件
			for(var i=0;i<15;i++){
				context.moveTo(15,15+30*i);          //橫線（x，y）起始點
				context.lineTo(435,15+30*i);         //橫線（x，y）終止點
				context.stroke();                    //畫一條線
				context.moveTo(15+30*i,15);          //豎線
				context.lineTo(15+30*i,435);
				context.stroke();
			}
		}
		context.save();
	</script>
	
	<script>
		//贏法陣列
		var wins = [];
		for(var i=0;i<15;i++){
			wins[i]=[];
			for(var j=0;j<15;j++){
				wins[i][j]=[];
			}
		}
		var count = 0;
		for(var x=0;x<11;x++){                            
			for(var y=0;y<15;y++){
				for(var z=0;z<5;z++){
					wins[x+z][y][count]=true;
				}
				count++;
			}
		}
		for(var x=0;x<15;x++){
			for(var y=0;y<11;y++){
				for(var z=0;z<5;z++){
					wins[x][y+z][count]=true;
				}
				count++;
			}
		}
		for(var x=0;x<11;x++){
			for(var y=0;y<11;y++){
				for(var z=0;z<5;z++){
					wins[x+z][y+z][count]=true;
				}
				count++;
			}
		}
		for(var x=0;x<11;x++){
			for(var y=4;y<15;y++){
				for(var z=0;z<5;z++){
					wins[x+z][y-z][count]=true;
				}
				count++;
			}
		}
		//遍歷棋盤，是否有棋子，預設為0沒有
		var isChess = [];
		for(var i=0;i<15;i++){
			isChess[i]=[];
			for(var j=0;j<15;j++){
				isChess[i][j]=0;
			}
		}
		//人和電腦贏的子佔贏法的情況
		var p1Win=[];
		var p2Win=[];
		for(var i=0;i<count;i++){
			p1Win[i]=0;
			p2Win[i]=0;
		}
		
		var turn = 0;
		//判斷輸贏
		function checkWin(x,y,turn){
			if(turn == 0){
				for(var i=0;i<count;i++){
					if(wins[x][y][i]){
						p1Win[i]++;
					}
					if(p1Win[i]==5){
						setTimeout(function(){
							if(ac==p1){
								alert("你贏了");
								window.location.href="./battle_win.php"
							}
							else{
								alert("你輸了");
								window.location.href="./battle_lose.php"
							}
						},20);
					}
				}
			}
			else{
				for(var i=0;i<count;i++){
					if(wins[x][y][i]){
						p2Win[i]++;
					}
					if(p2Win[i]==5){
						setTimeout(function(){
							if(ac==p2){
								alert("你贏了");
								window.location.href="./battle_win.php"
							}
							else{
								alert("你輸了");
								window.location.href="./battle_lose.php"
							}
						},20);
					}
				}
			}
		}
		
		chess.onmousemove=function(e){
			var x =(e.offsetX/30)|0;   //得到點選的x座標
			var y = (e.offsetY/30)|0;  //得到點選的y座標
			document.getElementById("posx").innerHTML = x;
			document.getElementById("posy").innerHTML = y;
		}
		
		var ac = "<?php echo $ac?>";
		var p1 = "<?php echo $p1?>";
		var p2 = "<?php echo $p2?>";
		var room = "<?php echo $room?>";
		
		chess.onclick=function(e){
			var x =(e.offsetX/30)|0;   //得到點選的x座標
			var y = (e.offsetY/30)|0;  //得到點選的y座標
			if(isChess[x][y]==0){
				if(turn==0){
					if(ac==p1){
						isChess[x][y]=1;
						oneStep(x,y,turn);
						checkWin(x,y,turn);
						socket.emit('chessboard',room,turn,x,y);
						turn=1;
					}
				}
				else{
					if(ac==p2){
						isChess[x][y]=1;
						oneStep(x,y,turn);
						checkWin(x,y,turn);
						socket.emit('chessboard',room,turn,x,y);
						turn=0;
					}
				}
				document.getElementById("Turn").innerHTML = turn;
			}
		}
		
		socket.on('new chessboard', function(R,T,X,Y){
			if(R==room){
				var x=X;
				var y=Y;
				if(isChess[x][y]==0){
					isChess[x][y]=1;
					if(T==0){
						oneStep(x,y,turn);
						checkWin(x,y,turn);
						turn=1;
					}
					else{
						oneStep(x,y,turn);
						checkWin(x,y,turn);
						turn=0;
					}
					document.getElementById("Turn").innerHTML = turn;
				}
			}
        });
		
		function oneStep(x,y,turn){
			var color;
			context.beginPath();
		    context.arc(15+30*x,15+30*y,13,0,2*Math.PI)
		    context.closePath();
		    
		    if(turn == 0){
		    	color = "black";
		    }
			else{
		    	color = "white";
		    }
		    context.fillStyle=color;
		    context.fill();
		}
	</script>
</html>