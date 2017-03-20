<?php
/**
 * Page contenant les informations de configuration de la base de donnée
 *
 * Actuellement, cette page est "contournée", car il y a un souci avec db_connect()
 *
 * @todo voir problème décrit dans la fonction db_connect() pour rétablir cette page
 *
 * @author André Mooser <andre.mooser@bluewin.ch>
 * @author Thierry Sémon <thierry.semon@space.unibe.ch>
 */

// configuration
$host = "localhosti";
$dbname = "boulangeriei";
$user = "root";
$pw = ""; // � changer

// ne pas fermer le php pour
// eviter les newlines HTML
// voir http://www.php-fig.org/psr/psr-2/ section 2.2
