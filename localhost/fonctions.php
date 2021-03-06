<?php
/**
 * Page contenant les fonctions
 *
 * Les fonctions appelées depuis les pages login.php et welcome.php se
 * trouvent.
 *
 * PHP version 5
 *
 * @category  none
 * @package   none
 * @author    André Mooser <andre.mooser@bluewin.ch>
 * @author    Thierry Sémon <thierry.semon@space.unibe.ch>
 * @copyright 2017 André Mooser et Thierry Sémon
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      www.anjumo.ch/projetphp
 */

/**
 * DB_conf contient les informations de connexion à la base de données
 */
require "DB_conf.php";


/**
 * Fonction connexion à la base de données
 *
 * Permet de se connecter à la base de donnée boulangerie
 * en récupérant les informations depuis DB_conf.php
 *
 */
function db_connect()
{
	global $host;
	global $dbname;
	global $user;
	global $pw;

	try
	{
		// connection à base de donnée PDO
		// les infos de connexion ci-dessous viennent du fichier séparé DB_conf.php
		$db = new PDO ("mysql:host=$host; dbname=$dbname", $user, $pw, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
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
 * Fonction contrôle de temps
 *
 * Si un utilisateur se connecte en dehors des heures de commandes
 * possibles (entre 06h00 et 08h00 par défaut), il recevra un message
 * comme quoi il n'est pas possible de commander.
 *
 * @return boolean	$status	1 = commande possible, 0 = commande pas possible
 */
function check_time()
{
	// Possibilité de passer des commandes entre 6h et 8h du matin
	// réglé sur 23h pour les tests
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

/**
 * Retourne la date d'aujourd'hui
 *
 * @var string	$maintenant
 *
 * @return string $maintenant
 */
function aujourdhui()
{
	$maintenant = date("Y-m-d");
	return $maintenant;
}

/**
 * Fonction de récupération des produits dans la DB
 *
 * Sélectionne tous les produits de la table "produits" dont
 * la quantité est supérieure à zéro.
 * Crée un tableau associatif $produitdefinition groupant le nom de chaque
 * produit, lui-même étant un tableau associatif comprenant la quantité et
 * le prix.
 *
 * @param string $db
 *
 * @return array $produitdefinition contenant tous les produits existants avec leur quantité maximale
 */
function recuperationproduits($db)
{
	$result = $db->query('SELECT * FROM produits where quantite != 0');
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
 * Fonction de soustraction des produits déjà commandés aujourdhui
 *
 * Reçoit les produits possibles (extraits depuis recuperationproduits()) et
 * y soustrait pour chacun la quantité de produits qui figurent déjà dans
 * la table commandes pour aujourdhui et retourne les produits encore disponibles
 *
 * @param	string	$db
 * @param	array	$produitPossible
 *
 * @return array $produitPossible produits existants moins les produits déjà
 * commandés par tous les clients, pour aujourdhui
 */
function soustraireproduitscommandes($db, &$produitPossible)
{
	foreach ($produitPossible as $key => $value) {
		$result = $db->query('SELECT * FROM commandes where produit ="' . $key . '" AND time_stamp LIKE"' . aujourdhui() . '%"');
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$produitPossible[$key]['quantite'] = $produitPossible[$key]['quantite'] - $row['quantite'];
		}
	}
}

/**
 * Fonction de soustraction des produits que l'on vient de choisir
 *
 * Reçoit les produits encore disponibles
 * (extraits de soustraireproduitscommandes) et y enlève les produits que
 * l'on sélectionne pour la commande, mais qui ne sont pas encore passés
 * dans la table "commnades"
 *
 * @param	array	$commandes
 * @param	array	$produitPossible
 *
 * @return array $produitPossible les produits existants moins les produits déjà commandés
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
 *
 * @param	string	$u	contient URL de base
 * @param	string	$t
 * @param string	$a
 *
 * @return string composé un élément a, retourné sous forme de chaîne, avec
 * l'URL de base $u, le contenu $t et les clés-valeurs de paramètres
 * d'URL GET.
 */
function a($u, $t, $a) {
	$callback = function($x) use ($a) {
		return urlencode($x) . "=" . urlencode($a[$x]);
	};

	return '<a class="btn btn-default btn-xs "' . 'href="'
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
 * type 1 = avec affichage d'un lien/bouton "modifier" en fonction de l'id
 * type 2 = avec affichage du prix total
 * type 3 = avec affichage d'un lien/bouton "supprimer" en fonction de time_stamp
 *
 * @param	string	$type					type 1, 2 ou 3
 * @param	string	$col_nom			nom des colonnes
 * @param	string	$col_element	élément de chaque colonne
 * @param	string	$db_request		requete à la base de données
 * @param	string	$color				couleur de la ligne d'entête
 * @param	string	$url_page			URL sur l'élément
 * @param	string	parametre			paramètres
 *
 * @return string $string qui contient les balises et les données de création du tableau
*/
function makeTable($type, $col_nom, $col_element, $db_request, $color, &$url_page, &$parametre) {
	$string = "<table class='table table-bordered table-striped table-hover'>"
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
 * Retourne les éléments d'un tableau
 *
 * Retourne autant d'éléments de type $t que désirés avec les
 * valeurs du tableau $a dans le cas ou le paramètre $color n'est pas spécifié
 * dans le cas contraire, on retourne le libellé des colonnes du tableau dans la
 * couleur spécifié par le paramètre $color
 *
 * @param	string	$a
 * @param	string	$t
 * @param	string	$color	spécification de la couleur
 *
 * @return string
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
 * Retourne les valeurs du tableau $a dans l'ordre du tableau $c
 *
 * @param	array	$a
 * @param	array	$c
 *
 * @return array $x
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
 * @param	string	$content	tout le contenu qui doit être transformé en pdf
 *
 * @return la page imprimée
 */
function printpdf($content) {
	require_once(dirname(__FILE__).'/html2pdf/vendor/autoload.php');
	try {
	  $html2pdf = new HTML2PDF('P','A4','fr');
	  $html2pdf->SetDefaultFont('Arial');
	  $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
	  $html2pdf->pdf->IncludeJS('print(true)'); // Affiche les options d'impressions - ne semble pas fonctionner avec Safari sur Mac
	  $html2pdf->pdf->SetDisplayMode('fullpage'); // Affichage d'une page entière
	  $html2pdf->Output('testpdf.pdf');
	}
	catch(HTML2PDF_exception $e) {
	  echo $e;
	}
}

/**
 * Fonction extraction des informations
 *
 * Extrait le contenu d'une colonne particulière d'une table de la base de données
 *
 * @param	string	$col_element	élément de chaque colonne
 * @param	string	$db_request		requete à la base de données
 * @param	string	$resultat			résultat retourné
 *
 * @return array $resultat
 */
function extractionInfo($col_element, $db_request, &$resultat) {
	$resultat = array();
	foreach (($db_request) as $row) {
		$resultat[] = getcol($row, $col_element);

	}
}

/**
 * Construire les boutons "Se déconnecter" et "Passer commande"
 *
 * Construit différents type de boutons au moyen des paramètres reçus
 *
 * @category  none
 * @package   none
 * @author    André Mooser <andre.mooser@bluewin.ch>
 * @author    Thierry Sémon <thierry.semon@space.unibe.ch>
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      www.anjumo.ch/projetphp
 */
class bouton {
	// {{{ properties

	/**
	 * Reçoit la méthode POST ou GET
	 *
	 * @var string $postGet
	 */
	private $postGet;

	/**
	 * Reçoit la valeur de id et name
	 *
	 * @var string
	 */
	private $idName;

	/**
	 * Reçoit la valeur du texte à afficher sur le bouton
	 *
	 * @var string $textValue
	 */
	private $textValue;

	/**
	 * Reçoit le type de la classe bootstrap
	 *
	 * @var string $typeClasse
	 */
	private $typeClasse;
	// }}}

	// {{{
	/**
	 * Construction
	 *
	 * @param string $postGet
	 * @param string $idName
	 * @param	string $textValue
	 * @param string $typeClasse
	 */
	function __construct($postGet, $idName, $textValue, $typeClasse) {
		$this->methode = $postGet;
		$this->idName = $idName;
		$this->textValue = $textValue;
		$this->typeClasse = $typeClasse;
	// }}}
	}

	/**
	 * Destruction
	 */
	function __destruct(){
	}

	/**
	 * Construit le code HTML nécessaire à l'affichage du bouton désiré
	 *
	 * @return string
	 */
	public function get_bouton() {
		return '<form action="' . $url_page . '" method="' . $this->methode . '" id="' . $this->idName . '">
			<br /><input type="submit" name="' . $this->idName . '" id="' . $this->idName . '" value="' . $this->textValue . '" class=\'' . $this->typeClasse . '\'/>
		</form>
		';
	}
}
