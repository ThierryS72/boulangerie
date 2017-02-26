<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Test DB access</title>
	</head>
	<body>
	<?php
		try
		{
			// connection à base de donnée PDO
			$db = new PDO ('mysql:host = localhost; dbname = boulangerie', 'root', '');
		}
		catch (PDOException $e)
		{
			// En cas d'erreur: affichage message (et exit?)
			echo "PDO: " . htmlentities($e->getMessage());
		}
		// Récupère contenu de la table produits
		$result = $db->query('SELECT * FROM boulangerie.produits');
		
		// Affichage contenu de la table produits
		echo '<p><strong>Produits:</strong ></p>'; 
		while ( $produit = $result->fetch(PDO::FETCH_ASSOC))
		{
			echo htmlentities($produit['nom']) . ' coûte : ' . htmlentities($produit['prix']). 'CHF' . '<br />';
		}
		
		//Insertion nouveau produit
		/*
		$name = 'Croissant au beurre';
		$prix = '1.2';
		$st = $db->prepare('INSERT INTO boulangerie.produits(nom, prix) '. 'VALUES (?, ?)');
		$st->execute(array($name, $prix));
		*/
		
		//Suppression d'un produit
		/*
		$name = 'Croissant au beurre';
		$st = $db->prepare("DELETE FROM boulangerie.produits WHERE (nom = ?)");
    if ($st && $st->execute(array($name))) {
         echo "Produit " . $name . " supprimé !";
    }*/
				
		$result -> closeCursor ();

	?>
  </body>
</html>