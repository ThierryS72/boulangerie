<?php

	function db_connect()
	{
		try
		{
			// connection à base de donnée PDO
			$db = new PDO ('mysql:host = localhost; dbname = boulangerie', 'root', 'root',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			return $db;
		}
		catch (PDOException $e)
		{
			// En cas d'erreur: affichage message (et exit?)
			echo "PDO: " . htmlentities($e->getMessage());
		}
	}
	function validation_utilisateur()
	{
		return 1;
	}

	function a($u, $t, $a) {
		$callback = function($x) use ($a) {
      return urlencode($x) . "=" . urlencode($a[$x]);
   };

   return '<a href="'
          . $u . '?'
          . join("&amp;", array_map($callback, array_keys($a)))
	  . '">'
	  . $t
	  . '</a>';
}
