<?php
/**
 * create_password.php est utilisé afin de hacher le mot de passe
 * Une fois haché, il est incororpor� manuellement dans la base de données
 * utilisateur
 * PHP version 5
 *
 * @category  none
 * @package   none
 * @author    André Mooser <andre.mooser@bluewin.ch>
 * @author    Thierry Sémon <thierry.semon@space.unibe.ch>
 * @copyright 2017 André Mooser et Thierry Sémon
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      www.anjumo.ch/projetphp
 *
 * @todo créer l'inteface pour que le manager puisse ajouter et modifier
 * les utilisateurs à sa guise
 */
$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
$salt = base64_encode($salt);
$salt = str_replace('+', '.', $salt);
$hash = crypt('andre', '$2y$10$'.$salt.'$');

echo $hash;
echo "</br>";

//Vérification
if (password_verify('andre', $hash)) {
    echo 'Le mot de passe est valide !';
} else {
    echo 'Le mot de passe est invalide.';
}
