<?php
include '../vendor/autoload.php';
use RedBean_Facade as R;

R::setup('sqlite:../data/db.sqlite','','');
$servers = R::find('server', ' active = 1');

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Minecraft Server Status</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<style>
	tr td,tr th {text-align:center}tr td.motd,tr th.motd{text-align:left;}
	.status{width:50px;}
	code { float:right; }
	</style>
	<!-- HTML5 shim -->
    <!--[if lt IE 9]>
    	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
	<div class="container">
		<div class="row" style="margin:15px 0;">
			<h1>AerisCraft Servers status</h1>
			<p>Hier ziet u al onze server en hun status</p>
		</div>
		<div class="row">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th class="status">Status</th>
						<th class="motd">Server</th>
						<th>Players</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($servers as $server): ?>
					<?php
						try {
							$stats = \Minecraft\Stats::retrieve(new \Minecraft\Server( $server->getAddress() ));
						} catch (\Minecraft\StatsException $e) {
							$stats = new stdClass();
							$stats->motd = "ERROR: Kan niet verbinden met server";
							$stats->online_players = 0;
							$stats->max_players = 0;
							$stats->is_online = false;
						}
					?>
					<tr>
						<td>
							<?php if($stats->is_online): ?>
							<span class="badge badge-success"><i class="icon-ok icon-white"></i></span>
							<?php else: ?>
							<span class="badge badge-important"><i class="icon-remove icon-white"></i></span>
							<?php endif; ?>
						</td>
						<td class="motd"><?php echo $stats->motd; ?>  <code><?php echo $server->getAddress(); ?></code></td>
						<td><?php printf('%u/%u', $stats->online_players, $stats->max_players); ?></td>
					</tr>
					<?php unset($stats); ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</body>
</html>