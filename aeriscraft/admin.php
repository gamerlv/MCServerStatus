<?php
session_start();
include '../vendor/autoload.php';
use RedBean_Facade as R;


R::setup('sqlite:../data/db.sqlite','','');

$user = R::load('user', (isset($_SESSION['user'])? $_SESSION['user'] : 0) );
// $user->admin = true; //Voor toegang zonder in te loggen
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Minecraft Server Status &raquo;</title>
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
			<h1>AerisCraft Servers status &raquo; <small>Admin</small> </h1>
			<p>Hier kunt u de servers op de status pagina bewerken</p>
		</div>

<?php if ( $user->admin ): ?>
		<div class="row">
			<div class="pull-right">
				<a href="admin.php?new"><i class="icon icon-plus-sign"></i> Server toevoegen</a> &nbsp;
				<a href="admin.php?newadmin"><i class="icon icon-user"></i> Admin toevoegen</a>
			</div>
		</div>
<?php endif; ?>

		<div class="row">
			<?php
			if ( !$user->admin ){
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					$u = R::findOne('user', 'username = ?' , array($_POST['username']) );
					if ( !is_null($u) && $u->password == sha1($_POST['password']) ){
						$_SESSION['user'] = $u->id;
						header('Location: admin.php');exit();
					} else {

					echo '<div class="alert alert-error">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							Foutief wachtwoord of gebruiker niet gevonden.
						</div>';
					}
				}

				include 'admin-paginas/login.php';
			} else {

				if ( isset($_GET['newadmin']) && !is_null( $_GET['newadmin'] ) ){
					$user = R::dispense('user');
					if ($_SERVER['REQUEST_METHOD'] == 'POST') {

						$user->username = $_POST['username'];
						$user->password = sha1($_POST['password']);
						$user->admin = true;

						R::store($user);

					echo '<div class="alert alert-info">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							Nieuwe gebruiker toegevoegd. (gebruikersnaam: '.$_POST['username'].', Wachtwoord: '.$_POST['password'].')
						</div>';
					} else {
					echo	'<div class="row">
							<form action="admin.php?newadmin" method="post">
								<p><label for="">Gebruikersnaam</label><input type="text" name="username"></p>
								<p><label for="">Wachtwoord</label><input type="password" name="password"></p>
								<p><input type="submit" value="Nieuwe gebruiker toevoegen" class="btn btn-primary"></p>
							</form>
						</div>';
					}
				}


				if ( isset($_GET['new']) && !is_null( $_GET['new'] ) ){
					$server = R::dispense('server');
					include 'admin-paginas/edit-server.php';
				}

				if ( isset($_GET['edit']) && !is_null( $_GET['edit'] ) ){
					$server = R::load('server', $_GET['edit']);
					if ($_SERVER['REQUEST_METHOD'] == 'POST') {
						$server->name = $_POST['name'];
						$server->address = $_POST['address'];
						$server->port = $_POST['port'];
						$server->active = $_POST['active'];

						R::store($server);

					echo '<div class="alert alert-info">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							Server <code>'.$server->getAddress().'</code> is opgeslagen.
						</div>';
					} else {
						include 'admin-paginas/edit-server.php';
					}
				}

				if ( isset($_GET['changeStatus']) && !is_null( $_GET['changeStatus'] ) ){
					$server = R::load('server', $_GET['changeStatus']);

					if ($server->active == 0) $server->active = 1; else $server->active = 0;
					R::store($server);

					echo '<div class="alert alert-info">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							Server <code>'.$server->getAddress().'</code> is '.( $server->active == 0? "gedeactiveerd":"geactiveerd" ).'.
						</div>';
				}

				if ( isset($_GET['del']) && !is_null( $_GET['del'] ) ){
					$server = R::load('server', $_GET['del']);
					R::trash($server);
					echo '<div class="alert alert-info">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							Server <code>'.$server->getAddress().'</code> is verwijderd.
						</div>';
				}

				include 'admin-paginas/standaard.php';

			}
			?>
		</div>
		<div class="row">
			<p class="center">Gemaakt door <a href="https://www.orangelemon.nl">OrangeLemon</a> voor AerisCraft</p>
		</div>
	</div>
</body>
</html>