<?php
/**
 * Page contenant les fonctions
 *
 * Les fonctions appelées depuis les pages login.php et welcome.php se
 * trouvent.
 *
 * @author André Mooser <andre.mooser@bluewin.ch>
 * @author Thierry Sémon <thierry.semon@space.unibe.ch>
 */




/**
 * Fonction connexion à la base de données
 *
 * Permet de se connecter à la base de donnée boulangerie avec le
 * nom de login "root" et le mot de passe "root" pour MAMP sur Mac
 * ou "" pour une utilisation sur pc
 *
 * @todo Problème avec $host et$dbname: on peux mettre n'importe quoi dans
 * ces 2 variables et la connexion se fait quand même -> voir pourquoi ???
 */
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
		// En cas d'erreur: affichage message et exit
		echo "PDO: " . htmlentities($e->getMessage());
		exit;
	}
}

/**
 * Fonction de validation des utilisateurs
 *
 * Cette fonction retoure "true (1)" si l'utilisateur est valide. Actuellement
 * figé sur "true".
 *
 * @return "true" ou "false" = 1 ou 0
 *
 * @todo utiliser le résultat du login ou programmer différement pour qu'elle
 * réagisse à une condition et ne soit plus figée sur "true"
 */
function validation_utilisateur()
{
	return 1;
}

/**
 * Fonction contrôle de temps
 *
 * Si un utilisateur se connecte en dehors des heures de commandes
 * possibles (entre 06h00 et 08h00 par défaut), il recevra un message
 * comme quoi il n'est pas possible de commander.
 *
 * @return un boolean: 1 = commande possible, 0 = commande pas possible
 */
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

function aujourdhui()
{
	$maintenant = date("Y-m-d");
	return $maintenant;
	// echo $aujourdhui . "<br/>";
}

/**
 * Fonction de récupération des produits dans la DB
 *
 * Sélectionne tous les produits de la tabel "produis" dont
 * la quantité est supérieure à zéro.
 * Crée un tableau associatif $produitdefinition groupant le nom de chaque
 * produit, lui-même étant un tableau associatif comprenant la quantité et
 * le prix.
 *
 * @return le tableau associatif $produitdefinition contenant tous les produits
 * existants avec leur quantité maximale
 */
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

/**
 * Fonction de soustraction des produits déjà commandés
 *
 * Reçoit les produits possibles (extraits dans recuperationproduits()) et
 * y soustrait pour chacun la quantité de produits qui figurent déjà dans
 * la table commandes et retourne les produits encore disponibles
 *
 * @return $produitPossible = produits existants moins les produits déjà
 * commandés par tous les clients
 *
 * @todo récuper la date du jour et ne soustaire que les produits commandés
 * ce jour
 *
 */
function soustraireproduitscommandes($db, &$produitPossible)
{
	foreach ($produitPossible as $key => $value) {
		$result = $db->query("SELECT * FROM boulangerie.commandes where produit ='" . $key . "' AND time_stamp LIKE'" . aujourdhui() . "%'");
		// $result = $db->query("SELECT * FROM boulangerie.commandes where produit ='" . $key . "'");
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$produitPossible[$key]['quantite'] = $produitPossible[$key]['quantite'] - $row['quantite'];
		}
	}
}

/**
 * Fonction qui soustrait les produits que l'on vient de choisir
 *
 * Reçoit les produits encore disponibles
 * (extraits de soustraireproduitscommandes) et y enlève les produits que
 * l'on sélectionne pour la commande, mais qui ne sont pas encore passés
 * dans la table "commnades"
 *
 * @return $produitPossible = produits existants moins les produits déjà commandés
 * par tous les clients moins les produite en cours de commande par le client actuel
 */
function soustraireproduitsencours($commandes, &$produitPossible)
{
	foreach ($commandes as $TimeStamp => $commande) {
		if (array_key_exists($commande['produit'], $produitPossible))
		{
			$produitPossible[$commande['produit']]['quantite'] = $produitPossible[$commande['produit']]['quantite'] - $commande['quantite'];
		}
	}
}

/**
 * DESCRIPTION
 * @return compose un élément a, retourné sous forme de chaîne, avec
 * l'URL de base $u, le contenu $t et les clés-valeurs de paramètres
 * d'URL GET.
 */
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

/**
 * Cette fonction crée un tableau <table> en fonction des paramètres reçus
 *
 * Reçoit le type (1, 2 ou 3), le nom des colonnes, les éléments de chaque colonne
 * la ligne de requete à la base de données, la couleur de la ligne d'entete,
 * l'URL sur l'élément, les paramètres
 *
 * @return $string qui contient les balises et les données de création du tableau
*/
function makeTable($type, $col_nom, $col_element, $db_request, $color, &$url_page, &$parametre) {
	$string = "<table border=1>"
	         . "<tr>";
	foreach ($col_nom as $c) {
						$string = $string . elt($c, "th", $color);
	}
	$string =  $string . "</tr>";

	if (($type==1)||($type==2))
	{
		foreach (($db_request) as $row) {
			 $string =  $string . "<tr>";
			 $string =  $string . elt(array_map("htmlentities", getcol($row, $col_element)));
			 if ($type==1){
			 $string =  $string . "<td>" . a($url_page, "modifier", array("modifier" => $row['id'])) .
			 "</td>";
			 }
			 $string =  $string .  "</tr>";
			 if ($type==2){
			 $parametre =  $parametre + $row["prix_total"]; //retourne prix total;
			 }
		}
	}
	if ($type==3)
	{
		foreach ($parametre as $TimeStamp => $commande) {
						$string =  $string . "<tr><td>" . htmlentities($TimeStamp) . "</td>";
						foreach ($commande as $key => $value) {
							$string =  $string . "<td>" . htmlentities($value) . "</td>";
						}
						$string =  $string . "<td>" . a($url_page, "supprimer", array("supprimer" => $TimeStamp)) .
						'</td>';
						$string =  $string . "</tr>";
					}
	}
	$string =  $string . "</table>";
	return $string;
}

/**
/* DESCRIPTION
 * @return retourne autant d'éléments de type $t que désirés avec les
 * valeurs du tableau $a dans le cas ou le paramètre $color n'est pas spécifié
 * dans le cas contraire, on retourne le libellé des colonnes du tableau dans la
 * couleur spécifié par le paramètre $color
 */
function elt($a, $t = "td", $color = "") {
   // use: accès aux variables de la fonction englobante, dès PHP 5.3
   if ($color == "")
	 {
		 $callback = function($s) use ($t) {
				return "<" . $t . ">" . $s . "</" . $t . ">";
		 };
		 return join("", array_map($callback, $a));
	 }
	 else
	 {
		 return "<" . $t . " bgcolor = " . $color . ">" . $a . "</" . $t . ">";
	 }
}

/**
/* DESCRIPTION
 * @return retourne les valeurs du tableau $a dans l'ordre du tableau $c
 */
function getcol($a, $c) {
   $x = array();

   foreach ($c as $y) {
      $x[] = $a[$y];
   }
   return $x;
}

/**
 * Fonction printpdf
 *
 * Imprime en pdf la page reçue par le paramètre $content
 *
 * @return la page imprimée
 */
function printpdf($content) {
	require_once(dirname(__FILE__).'/html2pdf/vendor/autoload.php');
	try {
	  $html2pdf = new HTML2PDF('P','A4','fr');
	  $html2pdf->SetDefaultFont('Arial');
	  $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
	  $html2pdf->pdf->IncludeJS('print(true)'); // Devrait afficher les options d'impressions - ne semble pas fonctionner sur Mac
	  $html2pdf->pdf->SetDisplayMode('fullpage'); // Affichage d'une page entière
	  $html2pdf->Output('testpdf.pdf');
	}
	catch(HTML2PDF_exception $e) {
	  echo $e;
	}
}

function extractionInfo($col_element, $db_request, &$resultat) {
	$resultat = array();
	foreach (($db_request) as $row) {
		// $resultat[] = $row['entreprise'];
		$resultat[] = getcol($row, $col_element);

	}
}
