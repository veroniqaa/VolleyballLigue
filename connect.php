<?php

	$host = "";
	$db_user = "";
	$db_password = "";
	$db_name = "";

	$polaczenie = pg_connect("host=".$host." port=5432 dbname=".$db_name." user=".$db_user." password=".$db_password) or die('Could not connect: '.preg_last_error());
?>