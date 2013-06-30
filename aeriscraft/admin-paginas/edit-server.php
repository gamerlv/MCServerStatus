<form action="admin.php?edit=<?=$server->id?>" method="POST">
	<p><label for="">Server naam</label><input type="text" name="name" value="<?=$server->name?>"></p>
	<p>
		<label for="">Adres</label><input type="text" name="address" value="<?=$server->address?>">
		<label for="">Poort</label><input type="text" name="port" value="<?=(!is_null($server->port)? $server->port : '25565' )?>">
	</p>
	<p><label for="">Zichtbaar</label>
	<select name="active">
		<option value="1">Ja</option>
		<option value="0">Nee</option>
	<select> </p>
	<p><input type="submit" class="btn"></p>
</form>