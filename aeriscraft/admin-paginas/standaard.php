<?php
use RedBean_Facade as R;
$servers = R::findAll('server');
?>
<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th class="status">Status</th>
						<th class="motd">Server</th>
						<th>Players</th>
						<th>Opties</th>
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
						<td> 
							<span><a href="admin.php?edit=<?=$server->id?>"><i class="icon icon-edit"></i> Bewerk server</a></span>
							<?php if ( $server->active ): ?>
								<span><a href="admin.php?changeStatus=<?=$server->id?>"><i class="icon icon-off"></i> Deactiveer server</a></span>
							<?php else: ?>
								<span><a href="admin.php?changeStatus=<?=$server->id?>"><i class="icon icon-off"></i> Activeer server</a></span>
							<?php endif; ?>
							<span><a href="admin.php?del=<?=$server->id?>"><i class="icon icon-remove"></i> Verwijder server</a></span>
						</td>
					</tr>
					<?php unset($stats); ?>
					<?php endforeach; ?>
				</tbody>
			</table>