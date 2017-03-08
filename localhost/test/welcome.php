<?php
session_start(); // a placer en tout premier, avant HTML car utilise un cookie

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
// Ecriture de la commande effectuée dans la table commandes
			if(isset($_POST['passercommande']))
			{
				$commandes = $_SESSION['commandes'];
				foreach ($commandes as $TimeStamp => $commande) {
					$sql_query = "INSERT INTO boulangerie.commandes( nom, prenom, entreprise,produit, quantite, prix_total, time_stamp) VALUES (?, ?, ?, ?, ?, ?, ?)";
				try {
					$id = $db->LastInsertId();
					$st = $db->prepare($sql_query);
					// $p = array( 'Semon', 'Thierry', 'Moulin SA', 'ballon', '1', '10', '2017-03-07 09:38:21');
					$p = array($utilisateur['nom'], $utilisateur['prenom'], $utilisateur['entreprise'], $commande['produit'], $commande['quantite'], $commande['prix-total'], $TimeStamp);
					$st->execute($p);

				}
				catch (PDOException $e) {
					echo "insert error: " . htmlentities($e->getMessage()) . "<br/><br/>";
				}

				};
				echo "Commande effectuée avec succès<br/>";
				?>
				<p><strong>Lise des produits commandés:</strong ></p>
				<table border=1>
				<tr><th bgcolor = "#CCCCFF">quantité commandée</td>
				<th bgcolor = "#CCCCFF">nom du produit</td>
				<th bgcolor = "#CCCCFF">prix total</td>
				<tr>
				<?php
				$result = $db->query("SELECT * FROM boulangerie.commandes WHERE nom = '" . $utilisateur['nom'] . "' AND prenom = '" . $utilisateur['prenom'] . "' AND entreprise = '" . $utilisateur['entreprise'] . "'");
				// $result = $db->query("SELECT * FROM boulangerie.commandes WHERE (nom = utilisateur['nom']) ");
				$prixtotalcommande = 0;
				while ( $produitscommandes = $result->fetch(PDO::FETCH_ASSOC))
				{
					echo '<tr><td>' . htmlentities($produitscommandes['quantite']) .
					'</td><td>' . htmlentities($produitscommandes['produit']) .
					'</td><td>' . htmlentities($produitscommandes['prix_total']) .
					'</td></tr>';
					$prixtotalcommande = $prixtotalcommande + $produitscommandes['prix_total'];
				}
				echo '</table>';
				echo "<h2>Montant de la commande: " . htmlentities($prixtotalcommande) . "</h2>";
				unset($_SESSION['commandes']); // Suppression de la variable de session pour recommencer avec une commande neutre
				exit;
			}
// Fin de l'écriture de la commande effectuée dans la table commandes
		}
		else
		{
			echo "Désolé les commandes ne sont possibles qu'entre 6 heures et 8 heures du matin!";
		}
	}
}
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
			?>
			<!-- Affichage contenu de la table produits disponibles -->
			<p><strong>Lise des produits à disposition:</strong ></p>
			<table border=1>
			<tr><th bgcolor = "#CCCCFF">nom du produit</td>
			<th bgcolor = "#CCCCFF">prix unitaire</td>
			<th bgcolor = "#CCCCFF">nombre à disposition</td>
			<th bgcolor = "#CCCCFF">action</td>
			<tr>
			<?php

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
				$result = $db->query('SELECT * FROM boulangerie.produits where quantite != 0');
				while($row = $result->fetch(PDO::FETCH_ASSOC))
				{
					$clé = $row['nom'];
					$valeur = $row['quantite'];
					//prix : $valeur = $row['quantite'];
// $produitPossible[$clé] = $valeur;
				}
				// print_r ($produitPossible);

				//Récupération du nom, prix et quantité de chaque produit
				$produitPossible = recuperationproduits($db);
				$produitPossible = soustraireproduit($db, $produitPossible);
				// print_r($produitPossible1);
				// print_r ($produitPossible);
				// echo"session a soustraire: ";
				// print_r ($_SESSION['asoustraire']);

				// foreach ($produitPossible as $key => $value) {
				// 	$result = $db->query("SELECT * FROM boulangerie.commandes where produit ='" . $key . "'");
				// 	while($row = $result->fetch(PDO::FETCH_ASSOC))
				// 	{
				// 		$produitPossible[$key]['quantite'] = $produitPossible[$key]['quantite'] - $row['quantite'];

// 						// if (isset($_SESSION['asoustraire'])) {
// echo"<P>CECI EST LA SESSION ASOUSTRAIRE</P>";
				// 			$produitasoustraire = $_SESSION['asoustraire'];
				// 			if ($produitPossible[$key] == $produitasoustraire['produit'])
				// 			{
				// 				$produitPossible[$key]['quantite'] = $produitPossible[$key]['quantite'] - $produitasoustraire['quantite'];
				// 				unset($produitasoustraire);
				// 				$_SESSION['asoustraire'] = $produitasoustraire;
				// 			}
				// 		// }
				//

				// 	}
				// }print_r($produitPossible);

				// echo"session a soustraire: ";
				// print_r ($_SESSION['asoustraire']);

				// print_r ($produitPossible);

				// Suppression de la ligne sélectionnée de l'array des commandes en cours
				// if (isset($_GET['supprimer']) && is_scalar($_GET['supprimer']))
				// {
				// 	unset($commandes);
				// }

				/* On récupère si il existe le nom du produit commandé par le formulaire */
				$prodcommande = isset($_POST['produitform'])?$_POST['produitform']:null;
				$quantitecommande = isset($_POST['quantiteform'])?$_POST['quantiteform']:null;
				// echo "<p> Produit commandé: " . $prodcommande . " , nbre de pièces: " . $quantitecommande . "</p>";
				?>
				<form action="<?php echo $url_page ?>" method="post" id="commande">
					<fieldset style="border: 3px double #333399">
						<legend>Sélectionnez un produit</legend>
						<?php
						// $produitPossible = recuperationproduits($db);
						echo '<select name="' . urlencode('produitform') . '" onchange="document.forms[\'commande\'].submit();">';
						echo '<option value="-1">- - - Choisissez un produit - - -</option>';
						foreach($produitPossible as $key => $value)
						{
							echo '<option value="' . htmlentities($key) . '" ' . ((isset($prodcommande) && $prodcommande == $key)?" selected=\"selected\"":null) . '>' . htmlentities($key) . '</option>';
						}
						echo '</select>';
						echo '<select name="quantiteform">';
						for ($i=1; $i < $produitPossible[$prodcommande]['quantite']+1; $i++) {
						// for ($i=1; $i < $produitPossible[$prodcommande]+1; $i++) {
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

				if(isset($_POST['ok']) && isset($_POST['produitform']) && isset($_POST['quantiteform']))
				{
					$currentTime = date('Y-m-d H:i:s');
					$commandes[$currentTime] = array("produit" => $_POST["produitform"],
																					 "quantite" => $_POST["quantiteform"],
																				 	 "prix" => $produitPossible[$prodcommande]['prix'],
																				 	 "prix-total" => $_POST["quantiteform"] * $produitPossible[$prodcommande]['prix']);
					$produitasoustraire = array("produit" => $_POST["produitform"], "quantite" => $_POST["quantiteform"]);
					print_r($produitasoustraire);
					// sauvegarde dans la Session et affichage sous forme de tableau
					$_SESSION['commandes'] = $commandes;
					$_SESSION['asoustraire'] = $produitasoustraire;
					// $produitPossible = soustraireproduit($db, $produitPossible);
				} // mis fin du if ici
//Fin de la partie qui pourrait être placée avant le HTML, dans la partie client?
					?>
					<table border=1"px">
					<tr><th bgcolor = "#CCCCFF">TimeStamp</td>
					<th bgcolor = "#CCCCFF">Nom du produit</td>
					<th bgcolor = "#CCCCFF">Quantité</td>
					<th bgcolor = "#CCCCFF">Prix unitaire</td>
					<th bgcolor = "#CCCCFF">Prix total</td>
					<th bgcolor = "#CCCCFF">action</td>
					<tr>
					<?php
					foreach ($commandes as $TimeStamp => $commande) {
						echo "<tr><td>" . htmlentities($TimeStamp) . "</td>";
						foreach ($commande as $key => $value) {
							echo "<td>" . htmlentities($value) . "</td>";
						}
						// echo "<td>" . $commande['quantite'] * $commande['prix'] . "</td>";
						echo "<td>" . a($url_page, "supprimer", array("supprimer" => $TimeStamp)) .
						'</td>';
						echo "</tr>";
					}
					echo "</table>";
					if(count($commandes)!= 0)
					{
						?>
						<form action="<?php echo $url_page ?>" method="post" id="passercommande">
						<br /><input type="submit" name="passercommande" id="passercommande" value="Passer commande" />
					</form>
					<?php

					} else {
						echo "<br/>Vous devez choisir un article au minimum pour pouvoir passer commande";
					}


					echo "<p>Vous avez commandé " . $quantitecommande . " " . $prodcommande ."</p>";
					echo"session a soustraire mis à la fin: ";
					print_r ($_SESSION['asoustraire']);

					// print_r ($commandes);



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
