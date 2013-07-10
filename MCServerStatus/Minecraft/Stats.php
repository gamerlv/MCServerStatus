<?php

namespace Minecraft;

class Stats {

	public static function retrieve( \Minecraft\Server $server ) {

		$socket = @stream_socket_client(sprintf('tcp://%s:%u', $server->getHostname(), $server->getPort()), $errno, $errstr, 1);

		if (!$socket) {
			throw new StatsException("Could not connect to the Minecraft server.");
		}

		fwrite($socket, "\xfe\x01\xfa");
    	$data = fread($socket, 1024); 
		fclose($socket);

		$stats = new \stdClass;

		// Is this a disconnect with the ping?
		if ($data == false AND substr($data, 0, 1) != "\xFF") { 
			$stats->is_online = false;
			return $stats;
		}

		$data = substr($data, 1);
		$data = mb_convert_encoding($data, 'auto', 'UCS-2');

//Code from mc-creative.nl status checker

		//Split into array
		$z = explode("\x00", $data);
		$d = array('');

/*
* Motd kleur codes
		//This may confuse you, ANONYMOUS FUNCTION FTW
		$colorIt = function($str, $colorCode){
			$colors = array(
				'0'=>'#000000',
				'1'=>'#0000AA',
				'2'=>'#00AA00',
				'3'=>'#00AA00',
				'4'=>'#AA0000',
				'5'=>'#AA00AA',
				'6'=>'#FFAA00',
				'7'=>'#AAAAAA',
				'8'=>'#555555',
				'9'=>'#5555FF',
				'a'=>'#55FF55',
				'b'=>'#55FFFF',
				'c'=>'#FF5555',
				'd'=>'#FF55FF',
				'e'=>'#FFFF55',
				'f'=>'#FFFFFF',
			);
			if ( !isset($colors[$colorCode]) ) return $str;//Return unmodified string if colo not found

			return '<span style="color:'.$colors[$colorCode].'">'.$str.'</span>';
		};
		
		$total = count($z)-3;
		foreach($z as $i => $v) {
			if ( $i == 0 ) { $d[0] .= $v; continue; } //Add it then skip the rest
			if ( $i <= $total ){
				$colorCode = substr($v,0,1);
				$d[0] .= $colorIt(substr($v,1), $colorCode );
			} else {
				$d[] = $v;
			}
		}
		$data = $d;
		*/
		array_shift($z); //Pop first element off, this is always §1

		$stats->is_online = true;
		list( $stats->protocol_version, $stats->game_version, $stats->motd, $stats->online_players, $stats->max_players) = $z;

		return $stats;

	}

}