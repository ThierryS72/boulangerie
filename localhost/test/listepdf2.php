<?php
require "fonctions.php";
/**
 * Liste des principales query pour l'extraction des données pour imprimer le pdf des commandes
 */
 // Extraire la liste du total des produits commandés

session_start();

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

ob_start();
?>

<page>

  <h1>Liste des commandes du <?php echo htmlentities(aujourdhui())?></h1><br/>
<?php

$db = db_connect();

  $type = 2;
  $col_nom = array('quantité', 'nom du produit', 'date');
  $col_element = array('sum(quantite)', 'produit', 'time_stamp');
  $color = '"#CCCCFF"';
  $parametre = 0;

  $sql_query = ("SELECT sum(quantite), produit, time_stamp
                from boulangerie.commandes
                WHERE time_stamp LIKE'" . aujourdhui() . "%'
                group by produit
                order by produit asc");

  echo '<h2>Liste du total des produits réservés</h2>';
  echo makeTable($type, $col_nom, $col_element, $db->query($sql_query), $color, $url_page, $parametre);

echo "<h2>Liste des commandes par entreprise</h2>";

// Extraire la liste des entreprises dont les employés ont passé commande aujourd'hui
$sql_query = ("SELECT DISTINCT entreprise
              FROM boulangerie.commandes WHERE time_stamp LIKE '" . aujourdhui() . "%'
              order by entreprise asc");

$col_element = array('entreprise');
// echo makeTable($type, $col_nom, $col_element, $db->query($sql_query), $color, $url_page, $parametre);
extractionInfo($col_element, $db->query($sql_query), $resultat);
$entreprise = $resultat;
foreach ($entreprise as $key1 => $value1) {
  foreach ($value1 as $key2 => $afficherEntreprise) {
    echo "<h3>Commandes pour l'entreprise " . htmlentities($afficherEntreprise) . "</h3>";

    // Extraire la liste des employés d'une entreprise qui a passé commande aujourd'hui
    $sql_query = ("SELECT nom, prenom
                  FROM boulangerie.commandes
                  WHERE time_stamp LIKE '" . aujourdhui() . "%'
                  AND entreprise = '" . $afficherEntreprise . "'
                  group by nom, prenom
                  order by nom asc");
    $col_element = array('nom', 'prenom');
    extractionInfo($col_element, $db->query($sql_query), $resultat);
    $nomPrenom = $resultat;
    // print_r($nomPrenom);
    foreach ($nomPrenom as $key10 => $value10) {
      echo "Nom: " . $value10['0'] . "<br/>Prénom: " . $value10['1'] . "<br/>";

      // Extraire la liste des produits commandés par un employé aujourd'hui
      $sql_query = ("SELECT sum(quantite), produit, prix_total, time_stamp
                    FROM boulangerie.commandes
                    WHERE nom = '" . $value10['0'] . "'
                    AND prenom = '" . $value10['1'] . "'
                    AND entreprise = '" . $afficherEntreprise . "'
                    AND time_stamp LIKE'" . aujourdhui() . "%'
                    group by produit
                    order by produit asc");

      $type = 2;
      $col_nom = array('quantité', 'nom du produit', 'prix', 'date');
      $col_element = array('sum(quantite)', 'produit', 'prix_total', 'time_stamp');
      $color = '"#CCCCFF"';
      $parametre = 0;

      echo makeTable($type, $col_nom, $col_element, $db->query($sql_query), $color, $url_page, $parametre);
      echo "<h4>Montant de la commande: Fr.- " . htmlentities(number_format($parametre, 2)) . "</h4>";
    }
  }
}
?>

</page>

<?php
$content = ob_get_clean();
/**
 * Impression de $sontent au format pdf et destruction de la session
 */
printpdf($content);
unset($_SESSION['is_auth']);
session_destroy();
