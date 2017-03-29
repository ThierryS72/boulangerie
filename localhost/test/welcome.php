<?php
/**
* Page welcome.php
*
* Page principale du site
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

error_reporting(0); //Pour visualisation finale

session_start(); // a placer en tout premier, avant HTML car utilise un cookie

// Teste la session pour voir si le flag is_auth est activé (signifie que le login est ok)
// Si pas ok, renvoie l'utilisateur sur la page login.php et empeche l'accès au reste de la page.
if (!isset($_SESSION["is_auth"])) {
	header("location: login.php");
	exit;
}
else if (isset($_REQUEST['logout']) && $_REQUEST['logout'] == true) {
	// On peut en tout temps faire un logout en envoyant un "logout" qui va déselectionner le flag is_auth.
	// On peut aussi détruire la session si désiré.
	unset($_SESSION['is_auth']);
	session_destroy();

	// Après le logout, renvoie sur la page login.php
	header("location: login.php");
	exit;
}


require "fonctions.php";
require "DB_conf.php";
$url_page = $_SERVER['PHP_SELF'];


if (isset($_SESSION['commandes'])) {
	$commandes = $_SESSION['commandes'];
}
else
{
	unset ($commandes);
	unset($_SESSION['commandes']);
}



$utilisateur['nom'] = $_SESSION['user_nom'];
$utilisateur['prenom'] = $_SESSION['user_prenom'];
$utilisateur['entreprise'] = $_SESSION['user_entreprise'];
$utilisateur['type'] = $_SESSION['user_level']; //"manager" ou "client"

$db = db_connect();

// Si manager
if ($utilisateur['type'] == "manager")
{
	// Impression des commandes sous forme pdf en appelant la page commandespdf.php
	if (isset($_GET['printpdf']))
	{
		header("location: commandespdf.php");
		exit;
	}
	// Mise à jour base de donnée produits si changements demandés
	if(isset($_POST['nom']) && isset($_POST['quantite']) && isset($_POST['prix']))
	{
		$sql_query = "UPDATE produits
									SET quantite = ?, prix = ? , time_stamp = ?
									WHERE nom = ?";
		$st = $db->prepare($sql_query);
		$p = array($_POST['quantite'], $_POST['prix'], date('Y-m-d H:i:s'), $_POST['nom']);
		$st->execute($p);
	}
}
else
// Si client
{
	// Contrôle si le client effectue la commande durant l'horaire défini
	if (check_time() && ($utilisateur['type'] = "client"))
	{
		// Récupère contenu de la table produits dont la quantité est supérieure à 0
		// $col_element = array('nom', 'prix', 'quantite');
		// $sql_query = ("SELECT " . join(", ", $col_element) . "
		// 							FROM produits where quantite != 0");
	}
	else
	{
		echo "Désolé les commandes ne sont possibles qu'entre 6 heures et 8 heures du matin!";
		unset($_SESSION['is_auth']);
		session_destroy();
		exit;
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<style type="text/css">
	main { padding: 60px; }
	</style>
	<title>Liste des produits</title>
</head>
<body>
	<main class="container">
		<h1>Liste des produits</h1>
		<?php
		//Table des produits disponibles
		if ($utilisateur['type'] == "manager") //le boulanger
		{
			?>
			<div class="bg-info">Pour les produits non disponible mettre "nombre à disposition" à 0!</div>
			<?php
			$modifier = 0;
			if (isset($_GET['modifier']) && is_scalar($_GET['modifier']))
			{
				$st = $db->prepare("SELECT nom, prix, quantite
														FROM produits
														WHERE (id = ?)");
				if ($st && $st->execute(array($_GET['modifier'])))
				{
					$row = $st->fetch(PDO::FETCH_ASSOC);
					if (isset($row['nom']) && isset($row['quantite']) && isset($row['prix'])) {
						$nom = $row['nom'];
						$quantite = $row['quantite'];
						$prix = $row['prix'];
					}
				}
				?>
				<br/><br/>
				<form action="<?php echo $url_page ?>" method="post" class="form-inline">
					<div class="form-group">
						<label for="nom">Nom du produit :</label><input type="text" name="nom" id="nom" class="form-control" value="<?php if (isset($nom)) { echo htmlentities($nom); } ?>" />
					</div>
					<div class="form-group">
						<label for="prix">Prix unitaire :</label><input type="text" name="prix" id="prix" class="form-control" value="<?php if (isset($prix)) { echo htmlentities($prix); } ?>" />
					</div>
					<div class="form-group">
						<label for="quantite">Quantité max :</label><input type="text" name="quantite" class="form-control" value="<?php if (isset($quantite)) { echo htmlentities($quantite); } ?>" />
					</div>
					<input type="submit" class='btn btn-success'/>
				</form>
				<?php
			}
			// Récupère contenu de la table produits
			$sql_query = ("SELECT * FROM produits");
			$col_nom = array('nom du produit', 'prix unitaire', 'nombre à disposition', 'action');
			$col_element = array('nom', 'prix', 'quantite');
			$type = 1;
			$color = '"#CCCCFF"';
			$parametre = 0;
			// Affichage contenu de la table produits disponibles
			// Bouton imprimer les commandes de la journée sous forme pdf
			$bouton = new bouton("GET", "printpdf", "Imprimer les commandes de la journée", "btn btn-success");
			echo $bouton->get_bouton();
			// Bouton Se déconnecter
			$bouton = new bouton("GET", "logout", "Se déconnecter", "btn btn-danger btn-sm");
			echo $bouton->get_bouton();
			echo "<br/>";
			echo makeTable($type, $col_nom, $col_element, $db->query($sql_query), $color, $url_page, $parametre);
			// Bouton se déconnecter place au bas du tableau
			$bouton = new bouton("get", "logout", "Se déconnecter", "btn btn-danger btn-sm");
			echo $bouton->get_bouton();
		}
		else //Les clients
		{
			if (check_time() && ($utilisateur['type'] = "client"))
			{
				// Ecriture de la commande effectuée dans la table commandes
				if(isset($_POST['passercommande']))
				{
					$commandes = $_SESSION['commandes'];
					foreach ($commandes as $TimeStamp => $commande) {
						$sql_query = "INSERT INTO commandes( nom, prenom, entreprise,produit, quantite, prix_total, time_stamp)
													VALUES (?, ?, ?, ?, ?, ?, ?)";
						try {
							$id = $db->LastInsertId();
							$st = $db->prepare($sql_query);
							$p = array($utilisateur['nom'], $utilisateur['prenom'], $utilisateur['entreprise'], $commande['produit'], $commande['quantite'], $commande['prix-total'], $TimeStamp);
							$st->execute($p);

						}
						catch (PDOException $e) {
							echo "insert error: " . htmlentities($e->getMessage()) . "<br/><br/>";
						}

					};
					// Affichage des produits commandés par le client aujourd'hui
					echo "Commande du ". aujourdhui() . " effectuée avec succès<br/><br/>";
					$type = 2;
					$col_nom = array('quantité commandée', 'nom du produit', 'prix total');
					$col_element = array('quantite', 'produit', 'prix_total');
					$color = '"#CCCCFF"';
					$parametre = 0;

					$sql_query = ("SELECT " . join(", ", $col_element) . "
												FROM commandes
												WHERE nom = '" . $utilisateur['nom'] . "'
												AND prenom = '" . $utilisateur['prenom'] . "'
												AND entreprise = '" . $utilisateur['entreprise'] . "'
												AND time_stamp LIKE'" . aujourdhui() . "%'");

					echo '<p><strong>Liste des produits commandés:</strong ></p>';
					echo makeTable($type, $col_nom, $col_element, $db->query($sql_query), $color, $url_page, $parametre);
					echo "<h2>Montant de la commande: Fr. " . htmlentities(number_format($parametre, 2)) . "</h2>";
					unset($_SESSION['commandes']); // Suppression de la variable de session pour recommencer avec une commande neutre
					unset($_SESSION['asoustraire']);
					session_destroy();
					exit;
				}
				// Fin de l'écriture de la commande effectuée dans la table commandes



				// Affichage contenu de la commande en cours
				// $result = $db->query('SELECT *
				// 											FROM produits
				// 											where quantite != 0');
				// while($row = $result->fetch(PDO::FETCH_ASSOC))
				// {
				// 	$clé = $row['nom'];
				// 	$valeur = $row['quantite'];
				// }

				/* On récupère si il existe le nom du produit commandé par le formulaire */
				$prodcommande = isset($_POST['produitform'])?$_POST['produitform']:null;
				$quantitecommande = isset($_POST['quantiteform'])?$_POST['quantiteform']:null;

				?>
				<!-- Formulaire de commande des produits par les clients
				Récupération depuis la base de données de tous les produits possibles
				Soustraction des produits déjà commandés aujourd'hui figurant dans la base de données
				Soustraction des produits de la commande en cours-->
				<form action="<?php echo $url_page ?>" method="post" id="commande" class="form-inline">
					<!--<fieldset style="border: 3px double #333399"> -->
					<legend>Sélectionnez un produit</legend>
					<?php
					$produitPossible = recuperationproduits($db);
					soustraireproduitscommandes($db, $produitPossible);
					soustraireproduitsencours($commandes, $produitPossible);
					echo '<select class=form-control name="' . urlencode('produitform') . '" onchange="document.forms[\'commande\'].submit();">';
					// Liste déroulante avec le nom des produits
					echo '<option value="-1">- - - Choisissez un produit - - -</option>';
					foreach($produitPossible as $key => $value)
					{
						echo '<option value="' . htmlentities($key) . '" ' . ((isset($prodcommande) && $prodcommande == $key)?" selected=\"selected\"":null) . '>' . htmlentities($key) . '</option>';
					}
					echo '</select>';
					echo '<select class=form-control name="quantiteform">';
					for ($i=1; $i < $produitPossible[$prodcommande]['quantite']+1; $i++) {
						// Liste déroulante avec le nombre de produits restants
						echo '<option value="' .htmlentities($i). '" ' . '>' . htmlentities($i) . '</option>';
					}
					echo '<br /><input type="submit" name="ok" id="ok" value="Sélectionner" class="btn btn-success btn-sm"/>';
					echo '</select>';
					?>
					<!--</fieldset>-->
				</form>
				<br/>
				<?php
				//Début de la partie qui pourrait être placée avant le HTML, dans la partie client?

				// Suppression de la ligne sélectionnée de l'array des commandes en cours
				if (isset($_GET['supprimer']) && is_scalar($_GET['supprimer']))
				{
					unset($commandes[$_GET['supprimer']]);
					$_SESSION['commandes'] = $commandes;
				}

				if(isset($_POST['ok']) && isset($_POST['produitform']) && isset($_POST['quantiteform']))
				{
					$currentTime = date('Y-m-d H:i:s');
					$commandes[$currentTime] = array("produit" => $_POST["produitform"],
					"quantite" => $_POST["quantiteform"],
					"prix" => $produitPossible[$prodcommande]['prix'],
					"prix-total" => $_POST["quantiteform"] * $produitPossible[$prodcommande]['prix']);
					// sauvegarde dans la Session et affichage sous forme de tableau
					$_SESSION['commandes'] = $commandes;

				}

				//Fin de la partie qui pourrait être placée avant le HTML, dans la partie client?
				if(count($commandes)!= 0)
				{
					$type = 3;
					$col_nom = array('TimeStamp', 'Nom du produit', 'Quantité', 'Prix unitaire', 'Prix total', 'action');
					$col_element = array('quantite', 'produit', 'prix_total');
					$color = '"#CCCCFF"';
					echo makeTable($type, $col_nom, $col_element, $db, $color, $url_page, $commandes);
					// Bouton Passer commande
					$bouton = new bouton("post", "passercommande", "Passer commande", "btn btn-info btn-sm");
					echo $bouton->get_bouton();

				} else {
					echo "<br/>Vous devez choisir un article au minimum pour pouvoir passer commande";
				}
				// Bouton Se déconnecter
				$bouton = new bouton("get", "logout", "Se déconnecter", "btn btn-danger btn-sm");
				echo $bouton->get_bouton();
			}
		}
		?>
	</main>
</body>
</html>
<?php
//$result->closeCursor();
unset($db);
?>
