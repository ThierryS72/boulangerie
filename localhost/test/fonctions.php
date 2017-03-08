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
	$pw = "";

	/*
	*Problème avec $host et$dbname: je peux mettre n'importe quoi dans ces 2 variables et la connexion se fait quand même ???
	*/
	try
	{
		// connection à base de donnée PDO
		// les infos de connexion ci-dessous dans un fichier séparé DB_conf.php
		$db = new PDO ("mysql:host = $host; dbname = $dbname", $user, $pw, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

function recuperationproduits($db)
{
	$result = $db->query('SELECT * FROM boulangerie.produits where quantite != 0');
	while($row = $result->fetch(PDO::FETCH_ASSOC))
	{
		$produitnom = $row['nom'];
		$produitquantite = $row['quantite'];
		$produitprix = $row['prix'];
		$produitdefinition[$produitnom] = array('quantite' => $produitquantite,
		'prix' => $produitprix);
	}
	return $produitdefinition;
}

function soustraireproduitscommandes($db, &$produitPossible)
{
	foreach ($produitPossible as $key => $value) {
		$result = $db->query("SELECT * FROM boulangerie.commandes where produit ='" . $key . "'");
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$produitPossible[$key]['quantite'] = $produitPossible[$key]['quantite'] - $row['quantite'];
		}
	}
}

function soustraireproduitsencours($commandes, &$produitPossible)
{
	foreach ($commandes as $TimeStamp => $commande) {
		if (array_key_exists($commande['produit'], $produitPossible))
		{
			$produitPossible[$commande['produit']]['quantite'] = $produitPossible[$commande['produit']]['quantite'] - $commande['quantite'];
		}
	}
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

function makeTable($col_nom, $col_element, $db_action) {
	$string = "<table border=1>"
	         . "<tr>";
	foreach ($col_nom as $c) {
		
						$string = $string . elt2($c, "th");
	}
	$string =  $string . "</tr>";
				
	foreach (($db_action) as $row) {
     $string =  $string . "<tr>";
     $string =  $string . elt(array_map("htmlentities", getcol($row, $col_element)));
	   $string =  $string .  "</tr>";
		 //$string =  $string . $expression;
	}
	$string =  $string . "</table>";
	return $string;
}

function elt($a, $t = "td") {
   // use: accès aux variables de la fonction englobante, dès PHP 5.3
   $callback = function($s) use ($t) {
      return "<" . $t . ">" . $s . "</" . $t . ">";
   };

   return join("", array_map($callback, $a));
}

function elt2($a, $t = "td") {
      return "<" . $t . ">" . $a . "</" . $t . ">";
}

function getcol($a, $c) {
   $x = array();

   foreach ($c as $y) {
      $x[] = $a[$y];
   }
   return $x;
}
