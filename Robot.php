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
			echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 玩家&nbsp&nbsp:&nbsp&nbsp<b>$user</b>(黑)";
			echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <a href='logout.php' style='text-decoration: none;'>登出</a>";
		?>
	  </div>
	  <nav style="text-align:center;">
        <ul class="menu">
          <li><a href="./Home.php">首頁</a></li>
          <li style="background-color: rgb(115, 115, 115);color: white;"><a href="./Robot.php">人機對戰</a></li>
		  <li><a href="./Wait.php">玩家對戰</a></li>
          <li><a href="./Info.php">排名</a></li>
          <li><a href="./Rule.php">遊戲規則</a></li>
        </ul>
      </nav>
	  <div style="text-align:left;height:20px;">
		<span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspPOS(</span>
		<span id="posx">0</span>
		<span>,</span>
		<span id="posy">0</span>
		<span>)</span>
	  </div>
	  <div style="text-align:right;height:5px;">
		<p>對手&nbsp&nbsp:&nbsp&nbsp<b>電腦</b>(白)&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</p>
	  </div>
	  <div>
		<canvas id="chess" width="450px" height="450px"></canvas>
	  </div>
    </body>
	
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
		
		//贏法陣列
		var wins = [];
		for(var i=0;i<15;i++){                   //定義三維陣列
			wins[i]=[];
			for(var j=0;j<15;j++){
				wins[i][j]=[];
			}
		}
		var count = 0;                          //（x，y）在的贏法種類
		//橫線能贏情況
		for(var x=0;x<11;x++){                            
			for(var y=0;y<15;y++){
				for(var z=0;z<5;z++){           //z代表向後5個字
					wins[x+z][y][count]=true;   //true代表是一種贏法，用count記錄下來
				}
				count++;                        //(x,y)在另一個贏法中
			}
		}
		//豎線能贏情況
		for(var x=0;x<15;x++){
			for(var y=0;y<11;y++){
				for(var z=0;z<5;z++){
					wins[x][y+z][count]=true;
				}
				count++;
			}
		}
		//正斜線能贏情況
		for(var x=0;x<11;x++){
			for(var y=0;y<11;y++){
				for(var z=0;z<5;z++){
					wins[x+z][y+z][count]=true;
				}
				count++;
			}
		}
		//反斜線能贏情況
		for(var x=0;x<11;x++){
			for(var y=4;y<15;y++){
				for(var z=0;z<5;z++){
					wins[x+z][y-z][count]=true;
				}
				count++;
			}
		}
		//遍歷棋盤，是否有棋子，預設為0沒有
		var isChess = []
		for(var i=0;i<15;i++){
			isChess[i]=[];
			for(var j=0;j<15;j++){
				isChess[i][j]=0;
			}
		}
		//人和電腦贏的子佔贏法的情況
		var manWin=[];
		var computerWin=[];
		for(var i=0;i<count;i++){
			manWin[i]=0;
			computerWin[i]=0;
		}
		//重置
		function reset(){
			window.location.href="./Robot.php";
		}
		//判斷輸贏
		function checkWin(x,y,player){
			if(player){
				for(var i=0;i<count;i++){
					if(wins[x][y][i]){
						manWin[i]++;
					}
					if(manWin[i]==5){
						setTimeout(function(){
							alert("你贏了");
							reset();
						},20);
					}
				}
			}
			else{
				for(var i=0;i<count;i++){
					if(wins[x][y][i]){
						computerWin[i]++;
					}
					if(computerWin[i]==5){
						setTimeout(function(){
							alert("電腦贏了");
							reset();
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
		
		chess.onclick=function(e){
			var x =(e.offsetX/30)|0;   //得到點選的x座標
			var y = (e.offsetY/30)|0;  //得到點選的y座標
			if(isChess[x][y]==0){      //是否有棋子，沒有下子
		    	isChess[x][y]=1;        //值變一，代表有棋子
		    	oneStep(x,y,true);      //玩家顏色
		    	checkWin(x,y,true);		//判斷輸贏
				setTimeout(function(){
					computerPlayerAction();   //玩家下過棋後該電腦下
				},50);
		    }
		}
		function oneStep(x,y,player){
			var color;
			context.beginPath();                              //開始畫圓
		    context.arc(15+30*x,15+30*y,13,0,2*Math.PI)       //（x,y,半徑，起始點，終止點2*PI即360度）
		    context.closePath();                              //結束畫圓
		    
		    if(player){
		    	color="black";                                //玩家是黑色
		    }else{
		    	color="white";                                //電腦是白色
		    }
		    context.fillStyle=color;                          //設定填充色
		    context.fill();                                   //填充顏色
		}
		
		//電腦下棋
		function computerPlayerAction(){
			var max=0;
			var u=0;                   //電腦棋x座標
			var v=0;                   //電腦棋y座標
			var manOfValue=[];          //玩家贏的權值
			var computerOfValue=[];     //電腦贏的權值
			
			for(var x=0;x<15;x++){
				manOfValue[x]=[];
				computerOfValue[x]=[];
				for(var y=0;y<15;y++){
					manOfValue[x][y]=0;
					computerOfValue[x][y]=0;
				}
			}
			
			for(var x=0;x<15;x++){
				for(var y=0;y<15;y++){
					if(isChess[x][y]==0){     //查詢空白棋
						for(var i=0;i<count;i++){    //遍歷count
							if(wins[x][y][i]){
								if(manWin[i]==1)
								{manOfValue[x][y]+=200;}    //給予權值
								else if(manWin[i]==2)
								{manOfValue[x][y]+=400;}
								else if(manWin[i]==3)
								{manOfValue[x][y]+=2000;}
								else if(manWin[i]==4)
								{manOfValue[x][y]+=10000;}
								
								if(computerWin[i]==1)
								{computerOfValue[x][y]+=220;}    //電腦相同條件權值要比玩家高，主要還是自己贏
								else if(computerWin[i]==2)
								{computerOfValue[x][y]+=420;}
								else if(computerWin[i]==3)
								{computerOfValue[x][y]+=2200;}
								else if(computerWin[i]==4)
								{computerOfValue[x][y]+=20000;}
							}	
						}
						
					    if(manOfValue[x][y]>max){          //迴圈判斷最大權值
					    	max=manOfValue[x][y];
					    	u=x;
					    	v=y;
					    }
					    if(computerOfValue[x][y]>max){
					    	max=computerOfValue[x][y];
					    	u=x;
					    	v=y;
					    }
					}
				}
			}
			isChess[u][v]=1;      //標記已下
			oneStep(u,v,false);   //電腦判斷完成，下棋
			checkWin(u,v,false);  //判斷輸贏
		}
	</script>
</html>
