<?php
/**
 * create_password.php est utilis� afin de hacher le mot de passe
 * Une fois hach�, il est incororpor� manuellement dans la base de donn�e
 * utilisateur
 * @author Andr� Mooser <andre.mooser@bluewin.ch>
 * @author Thierry S�mon <thierry.semon@space.unibe.ch>
 * @todo cr�er l'inteface pour que le manager puisse ajouter et modifier 
 * les utilisateurs � sa guise
*/
$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
$salt = base64_encode($salt);
$salt = str_replace('+', '.', $salt);
$hash = crypt('andre', '$2y$10$'.$salt.'$');

echo $hash;
echo "</br>";

//V�rification
if (password_verify('andre', $hash)) {
    echo 'Le mot de passe est valide !';
} else {
    echo 'Le mot de passe est invalide.';
}