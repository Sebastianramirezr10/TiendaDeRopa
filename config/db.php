<?php

class Database
{
	public static function connect()
	{
		$db = new mysqli('localhost', 'root', '', 'tienda_master');
		$db->query("SET NAMES 'utf8'");
		return $db;
	}
}

// Iniciar la sesión
if (!isset($_SESSION)) {
	session_start();
}
