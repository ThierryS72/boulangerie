<?php
/**
* Extraction des données de la commande du jour et export au format pdf
*
* Les données de la commande du jour sont extraites de la base de données
* et une page est créée au format pdf afin de pouvoir facilement l'imprimer
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

// Extraire la liste du total des produits commandés

require "fonctions.php";

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

<page> <!-- Balise spécifique à la bibliothpèque html2pdf -->

  <h1>Liste des commandes du <?php echo htmlentities(aujourdhui())?></h1><br/>
  <?php

  $db = db_connect();
  // Extraire la liste de toutes les commandes passsées aujourdhui
  // La date est affichée à titre indicatif
  $type = 2;
  $col_nom = array('Quantité', 'Nom du produit', 'Date');
  $col_element = array('sum(quantite)', 'produit', 'time_stamp');
  $color = '"#CCCCFF"';
  $parametre = 0;

  $sql_query = ("SELECT sum(quantite), produit, time_stamp
                from commandes
                WHERE time_stamp LIKE'" . aujourdhui() . "%'
                group by produit
                order by produit asc");

  echo '<h2>Liste du total des produits réservés</h2>';
  echo makeTable($type, $col_nom, $col_element, $db->query($sql_query), $color, $url_page, $parametre);

  echo "<h2>Liste des commandes par entreprise</h2>";

  /**
  * Extraire la liste des entreprises qui ont des employés qui ont passé commande aujourd'hui
  * afin d'afficher le regroupement des employés par entreprise
  */
  $sql_query = ("SELECT DISTINCT entreprise
    FROM commandes WHERE time_stamp LIKE '" . aujourdhui() . "%'
    order by entreprise asc");

    $col_element = array('entreprise');
    extractionInfo($col_element, $db->query($sql_query), $resultat);
    $entreprise = $resultat;
    foreach ($entreprise as $key1 => $value1) {
      foreach ($value1 as $key2 => $afficherEntreprise) {
        echo "<h3>Commandes pour l'entreprise " . htmlentities($afficherEntreprise) . "</h3>";

        // Extraire la liste des employés d'une entreprise qui a passé commande aujourd'hui
        $sql_query = ("SELECT nom, prenom
                      FROM commandes
                      WHERE time_stamp LIKE '" . aujourdhui() . "%'
                      AND entreprise = '" . $afficherEntreprise . "'
                      group by nom, prenom
                      order by nom asc");

          $col_element = array('nom', 'prenom');
          extractionInfo($col_element, $db->query($sql_query), $resultat);
          $nomPrenom = $resultat;

          // Extraire la liste des produits commandés par un employé aujourd'hui
          foreach ($nomPrenom as $key10 => $value10) {
            echo "Nom: " . htmlentities($value10['0']) . "<br/>Prénom: " . htmlentities($value10['1']) . "<br/>";
            $sql_query = ("SELECT sum(quantite), produit, prix_total, time_stamp
                          FROM commandes
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
            echo "<h4>Montant de la commande: Fr. " . htmlentities(number_format($parametre, 2)) . "</h4>";
          }
        }
      }
      ?>

    </page>

    <?php
    $content = ob_get_clean();
    /**
    * Impression de $sontent au format pdf et destruction de la session
    * ce qui redirigera ensuite sur la page login.php
    */
    printpdf($content);
    unset($_SESSION['is_auth']);
    session_destroy();
