<?php
session_start(); // a placer en tout premier, avant HTML car utilise un cookie
ini_set('default_charset', 'UTF-8');
header("Content-Type: text/html; charset=UTF-8");

require "fonctions.php";
require "DB_conf.php";

$url_page = $_SERVER['PHP_SELF'];

/*
- Faire authentification avant d'avoir accès aux données (tables)
- options d'affichage en fonction du type d'utilisateur et du moment où il se connecte à la base:
- Options clients --> Nombre de produit / Prix total et envoyer la commande (si encore dans les temps)
- Si plus dans les temps, le clients voit sa denière commande et n'a pas l'option de "passer la commande"
*/

if(validation_utilisateur())
{
	// TODO: Extraire les spécifications de utilisateurs de base de donnée utilisateur dans validation_utilisateur()
	// Test: mockup qui contient les spécifications des utilisateurs
	$utilisateur['nom'] = "Sémon";
	$utilisateur['prenom'] = "Thierry";
	$utilisateur['entreprise'] = "Moulin SA";
	//!!!!!Changer manuellement le type de l'utilisateur (client ou manager) pour simuler les interfaces!!!!
	$utilisateur['type'] = "client"; //"manager" ou "client"

	$db = db_connect();

	if ($utilisateur['type'] == "manager") //manager
	{
		if(isset($_POST['nom']) && isset($_POST['quantite']))
		{
			//Mise à jour base de donnée produits si changement
			$sql_query = "UPDATE boulangerie.produits SET quantite = ? WHERE nom = ?";
			$st = $db->prepare($sql_query);
			$p = array($_POST['quantite'], $_POST['nom']);
			$st->execute($p);
		}
		// Récupère contenu de la table produits
		$result = $db->query('SELECT * FROM boulangerie.produits');

		// Récupère contenu des commandes en cours
		//$result2 = $db->query('SELECT * FROM boulangerie.commandes');
	}
	else
	{
		if (check_time() && ($utilisateur['type'] = "client"))
		{
			// Récupère contenu de la table produits dont la quantité est supérieure à 0
			$result = $db->query('SELECT * FROM boulangerie.produits where quantite != 0');
		}
		else
		{
			echo "Désolé les commandes ne sont possibles qu'entre 6 heures et 8 heures du matin!";
		}
	} }
	else
	{
		echo 'Utilisateur non-valide!';
	}
	?>

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Liste des produits</title>
	</head>
	<body>
		<?php
		//Table des produits disponibles
		if ($utilisateur['type'] == "manager") //le boulanger
		{
			$modifier = 0;
			if (isset($_GET['modifier']) && is_scalar($_GET['modifier']))
			{
				$st = $db->prepare("SELECT nom, quantite FROM boulangerie.produits WHERE (id = ?)");
				if ($st && $st->execute(array($_GET['modifier'])))
				{
					$row = $st->fetch(PDO::FETCH_ASSOC);
					if (isset($row['nom']) && isset($row['quantite'])) {
						$nom = $row['nom'];
						$quantite = $row['quantite'];
					}
				}
				?>
				<form action="<?php echo $url_page ?>" method="post">
					<p><label for="nom">Nom du produit: </label><input type="text" name="nom" id="nom" value="<?php if (isset($nom)) { echo htmlentities($nom); } ?>" />
						<label for="quantite">Quantité max: </label><input type="text" name="quantite" value="<?php if (isset($quantite)) { echo htmlentities($quantite); } ?>" />
						<input type="submit" />
					</p>
				</form>
				<?php

			}
			// Affichage contenu de la table produits disponibles
			echo '<p><strong>Lise des produits à disposition:</strong ></p>';
			echo '<table border=1>';
			echo '<tr><th bgcolor = \"#CCCCFF\">nom du produit</td>
			<th bgcolor = \"#CCCCFF\">prix unitaire</td>
			<th bgcolor = \"#CCCCFF\">nombre à disposition</td>
			<th bgcolor = \"#CCCCFF\">action</td>
			<tr>';
			while ( $produit = $result->fetch(PDO::FETCH_ASSOC))
			{
				echo '<tr><td>' . htmlentities($produit['nom']) .
				'</td><td>' . htmlentities($produit['prix']) .
				'</td><td>' . htmlentities($produit['quantite']) .
				'</td><td>' . a($url_page, "modifier", array("modifier" => $produit['id'])) .
				'</td></tr>';
			}
			echo '</table>';
		}
		else //Les clients
		{
			if (check_time() && ($utilisateur['type'] = "client"))
			{
				// Affichage contenu de la commande en cours
				if(isset($produits_commande))
				{
					echo '<p><strong>Commande en cours:</strong ></p>';
					echo '<table border=1>';
					echo '<tr><th bgcolor = \"#CCCCFF\">nom du produit</td>
					<th bgcolor = \"#CCCCFF\">nombre</td>
					<th bgcolor = \"#CCCCFF\">Prix</td>
					<tr>';
					foreach ($produits_commande as $produitC)
					{
						echo '<tr><td>' . htmlentities($produitC['nom']) .
						'</td><td>' . htmlentities($produitC['quantite']) .
						'</td><td>' . htmlentities($produitC['prix']) .
						'</td></tr>';
					}
					echo '</table>';
				}
				$result = $db->query('SELECT * FROM boulangerie.produits where quantite != 0');
				while($row = $result->fetch(PDO::FETCH_ASSOC))
				{
					$clé = $row['nom'];
					$valeur = $row['quantite'];
					//prix : $valeur = $row['quantite'];
					$produitChoisi[$clé] = $valeur;
				}
				print_r ($produitChoisi);

				// Suppression de la ligne sélectionnée de l'array des commandes en cours
				// if (isset($_GET['supprimer']) && is_scalar($_GET['supprimer']))
				// {
				// 	unset($commandes);
				// }

				/* On récupère si il existe le nom du produit commandé par le formulaire */
				$prodcommande = isset($_POST['nomform'])?$_POST['nomform']:null;
				$quantitecommande = isset($_POST['quantiteform'])?$_POST['quantiteform']:null;
				echo "<p> Produit commandé: " . $prodcommande . " , nbre de pièces: " . $quantitecommande . "</p>";
				?>
				<form action="<?php echo $url_page ?>" method="post" id="commande">
					<fieldset style="border: 3px double #333399">
						<legend>Sélectionnez un produit</legend>
						<?php
						echo '<select name="' . urlencode('nomform') . '" onchange="document.forms[\'commande\'].submit();">';
						echo '<option value="-1">- - - Choisissez une produit - - -</option>';
						foreach($produitChoisi as $key => $value)
						{
							echo '<option value="' . htmlentities($key) . '" ' . ((isset($prodcommande) && $prodcommande == $key)?" selected=\"selected\"":null) . '>' . htmlentities($key) . '</option>';
						}
						echo '</select>';
						echo '<select name="quantiteform">';
						for ($i=1; $i < $produitChoisi[$prodcommande]+1; $i++) {
							echo '<option value="' .htmlentities($i). '" ' . '>' . htmlentities($i) . '</option>';
						}
						echo '</select>';
						?>

						<br /><input type="submit" name="ok" id="ok" value="Sélectionner" />
					</fieldset>
				</form>
				<?php
//Début de la partie qui pourrait être placée avant le HTML, dans la partie client?
				if (isset($_SESSION['commandes'])) {
					$commandes = $_SESSION['commandes'];
				}

				// Suppression de la ligne sélectionnée de l'array des commandes en cours
				if (isset($_GET['supprimer']) && is_scalar($_GET['supprimer']))
				{
					unset($commandes[$_GET['supprimer']]);
					$_SESSION['commandes'] = $commandes;
				}

				if(isset($_POST['ok']) && isset($_POST['nomform']) && isset($_POST['quantiteform']))
				{
					$currentTime = (int) date('Gis');
					$commandes[$currentTime] = array("produit" => $_POST["nomform"],
																					 "quantite" => $_POST["quantiteform"]);
					// sauvegarde dans la Session et affichage sous forme de tableau
					$_SESSION['commandes'] = $commandes;
				} // mis fin du if ici
//Fin de la partie qui pourrait être placée avant le HTML, dans la partie client?
					echo "<table border=1\"px\">";
					echo '<tr><th bgcolor = \"#CCCCFF\">TimeStamp</td>
					<th bgcolor = \"#CCCCFF\">Nom du produit</td>
					<th bgcolor = \"#CCCCFF\">Quantité</td>
					<th bgcolor = \"#CCCCFF\">action</td>
					<tr>';
					foreach ($commandes as $TimeStamp => $commande) {
						echo "<tr><td>" . htmlentities($TimeStamp) . "</td>";
						foreach ($commande as $key => $value) {
							echo "<td>" . htmlentities($value) . "</td>";
						}
						echo "<td>" . a($url_page, "supprimer", array("supprimer" => $TimeStamp)) .
						'</td>';
						echo "</tr>";
					}
					echo "</table>";


					echo "<p>Vous avez commandé " . $quantitecommande . " " . $prodcommande ."</p>";

					print_r ($commandes);



				// produit possible a commander avec "scroll bar" pour la selection du produit et indication du nombre de produit souhaitée

				// Résumé de la commande en cours avec les produits sélectionnés et le prix total

				//bouton "Passer la commande" --> "Ajout du/des produit(s) commandé(s) par le client dans table des commandes
			}
		}
		?>
	</body>
	</html>
	<?php
	$result->closeCursor();
	unset($db);
	?>
