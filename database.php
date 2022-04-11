<?php
	$host = 'localhost';
	$user = 'u214993942_valjuniel111';
	$password = 'valjunielAKO22';
	$database = 'u214993942_spotchecking';
	$port = 3306;

	$conn=mysqli_connect($host, $user, $password, $database, $port);

	// Check connection
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
?>
