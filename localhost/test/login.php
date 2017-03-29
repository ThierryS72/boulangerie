<?php
/**
* Page de login
*
* L'accès au site de réservation se fait au moyen de cette page de login.

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

/* Initialisation de la session. */
session_start();

require "fonctions.php";
require "DB_conf.php";

$url_page = $_SERVER['PHP_SELF'];

// Test pour savoir si le lancement du script est causé par un click sur le bouton login.
if (isset($_POST['login-submit'])) {
	// Vérifie que l'adresse e-mail et le mot de passe ont été transmis.
	// Si ce n'est pas le cas, on va au message d'erreur à propos des deux éléments d'information.
	if (isset($_POST['emailaddress']) && isset($_POST['pass'])) {
		$email = $_POST['emailaddress'];
		$pass = $_POST['pass'];


		// Connexion à la base de données et sélection de l'utilisateur sur la base de l'adresse mail fournie.
		// Extraction du mot de passe et des autres informations nécessaires pour la session.
		$db = db_connect();
		$result = $db->query("SELECT id, email, password, nom, prenom, entreprise, level FROM utilisateurs WHERE email = '" . $email . "'");
		$row = $result->fetch(PDO::FETCH_ASSOC);
		// Si les informations utilisateurs ont été trouvées, comparaison du mot de passe fourni.
		// En cas de succès, création de variables de session pour l'utilisateur et d'un flag d'authorisation.
		// Ces données suivent l'utilisateur sur tout le site et sont (peuvent être) testées sur chaque page.
		if (($row !== false) && ($result->rowCount() > 0)) {

			if (password_verify($pass, $row['password'])){
				// is_auth est important et est testé sur les autres pages pour savoir
				// si l'accès y est autorisé ou non
				$_SESSION['is_auth'] = true;
				$_SESSION['user_level'] = $row['level'];
				$_SESSION['user_id'] = $row['id'];
				$_SESSION['user_nom'] = $row['nom'];
				$_SESSION['user_prenom'] = $row['prenom'];
				$_SESSION['user_entreprise'] = $row['entreprise'];

				// Dès que les variables de session on été initialisées, redirection sur la page welcome.php.
				header('location: welcome.php');
				exit;
			}
			else {
				$error = "Email ou mot de passe non-valide. Essayez à nouveau SVP.";
			}
		}
		else {
			$error = "Email ou mot de passe non-valide. Essayez à nouveau SVP.";
		}
	}
	else {
		$error = "Saisissez un email et un mot de passe pour vous connecter.";
	}
}
?>
<!--
/**
* Formulaire de saisie du login
*
* Le login de tous les utilisateurs se fait au moyen de ce formulaire
*/
-->
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
		<h2>Login et mots de passe pour les tests</h2><br/>
		<p>entreprise, email, mot de passe, type d'utilisateur</p>
		<p>'Moulin SA', 'thierry.semon@space.unibe.ch', 'thierry', 'manager'</p>
		<p>'Entreprise 1', 'toto@toto.ch', 'toto', 'client'</p>
		<p>'Entreprise 2', 'tata@tata.ch', 'tata', 'client'</p>
		<p>'Boulangerie', 'titi@titi.ch', 'titi', 'manager'</p>
		<p>'Entreprise 3', 'albert@albert.ch', 'albert', 'client'</p>
		<p>'Entreprise 1', 'andre@andre.ch', 'andre', 'client'</p><br/>
		<form method="post" action="<?php echo $url_page ?>" class="form-inrow">
			<div class="login-body form-inline">
				<?php
				if (isset($error)) {
					echo "<div class='errormsg'>" . htmlentities($error) . "</div>";
				}
				?>
				<div class="form-group">
					<label for="emailaddress">Email:</label>
					<input type="text" name="emailaddress" id="emailaddress" placeholder="Email Address" maxlength="100">
				</div>
				<div class="form-group">
					<label for="pass">Password:</label>
					<input type="password" name="pass" id="pass" placeholder="Password" maxlength="100">
				</div>

				<div class="login-button-row form-group">
					<input type="submit" name="login-submit" id="login-submit" value="Login" title="Login now" class="btn btn-success">
				</div>
			</div>

		</form>
	</main>
</body>
