<?php

	function db_connect()
	{
		// global $host;
		// global $dbname;
		// global $user;
		// global $pw;
		$host = "localhost";
		$dbname = "boulangerie";
		$user = "root";
		$pw = "root";

		/*
		 *Problème avec $host et$dbname: je peux mettre n'importe quoi dans ces 2 variables et la connexion se fait quand même ???
		 */
		try
		{
			// connection à base de donnée PDO
			// les infos de connexion ci-dessous dans un fichier séparé DB_conf.php
			$db = new PDO ("mysql:host = $host; dbname = $dbname", $user, $pw, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
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

	function check_time()
	{
		//Possibilité de passer des commandes entre 6h et 8h du matin
		$ouvert = 60000; //06:00:00
		$ferme  = 230000; //08:00:00

    $currentTime = (int) date('Gis');

		if ($currentTime > $ouvert && $currentTime < $ferme )
		{
				$status=1;
		}
		else
		{
				$status=0;
		}
		return $status;
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
