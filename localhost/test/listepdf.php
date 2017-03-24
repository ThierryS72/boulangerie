<?php
require "fonctions.php";
// $content = "
ob_start();
?>

<page>

  <h2>Commande du <?php echo htmlentities(aujourdhui())?>  effectuée avec succès</h2><br/>
<?php

// $utilisateur['nom'] = $_SESSION['user_nom'];
// $utilisateur['prenom'] = $_SESSION['user_prenom'];
// $utilisateur['entreprise'] = $_SESSION['user_entreprise'];
// $utilisateur['type'] = $_SESSION['user_level']; //"manager" ou "client"

$utilisateur['nom'] = 'toto';
$utilisateur['prenom'] = 'cutugno';
$utilisateur['entreprise'] = 'Entreprise 1';
$utilisateur['type'] = 'manager'; //"manager" ou "client"

$db = db_connect();

  $type = 2;
  $col_nom = array('quantité commandée', 'nom du produit', 'prix total');
  $col_element = array('quantite', 'produit', 'prix_total');
  $color = '"#CCCCFF"';
  $parametre = 0;

  $sql_query = ("SELECT " . join(", ", $col_element) . " FROM boulangerie.commandes
                WHERE nom = '" . $utilisateur['nom'] . "'
                AND prenom = '" . $utilisateur['prenom'] . "'
                AND entreprise = '" . $utilisateur['entreprise'] . "'
                AND time_stamp LIKE'" . aujourdhui() . "%'");

  echo '<p><strong>Liste des produits commandés:</strong ></p>';
  echo makeTable($type, $col_nom, $col_element, $db->query($sql_query), $color, $url_page, $parametre);
  echo "<h2>Montant de la commande: Fr.- " . htmlentities(number_format($parametre, 2)) . "</h2>";
  echo "<br/>" . $utilisateur['nom'];
?>

</page>

<?php
$content = ob_get_clean();

printpdf($content);
