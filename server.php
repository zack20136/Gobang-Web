<?php
	require __DIR__ . '/vendor/autoload.php';

	use Workerman\Worker;
	use PHPSocketIO\SocketIO;
	
	$io = new SocketIO(3000);
	$io->on('connection', function($socket)use($io){
		$socket->on('send message', function($msg)use($io){
			$io->emit('new message', $msg);
		});
		$socket->on('chessboard', function($room,$turn,$x,$y)use($io){
			$io->emit('new chessboard', $room, $turn, $x ,$y);
		});
	});
	
	Worker::runAll();
?>