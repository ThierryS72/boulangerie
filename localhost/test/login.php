<?php
	// First start a session. This should be right at the top of your login page.
	session_start();

  require "fonctions.php";
  require "DB_conf.php";
  // require "welcome.php";

  $url_page = $_SERVER['PHP_SELF'];

	// Check to see if this run of the script was caused by our login submit button being clicked.
	if (isset($_POST['login-submit'])) {
		// Also check that our email address and password were passed along. If not, jump
		// down to our error message about providing both pieces of information.
		if (isset($_POST['emailaddress']) && isset($_POST['pass'])) {
			$email = $_POST['emailaddress'];
			$pass = $_POST['pass'];


			// Connect to the database and select the user based on their provided email address.
			// Be sure to retrieve their password and any other information you want to save for the user session.
$db = db_connect();
      $result = $db->query("SELECT id, email, password, nom, prenom, entreprise, level FROM boulangerie.utilisateurs WHERE email = '" . $email . "'");
      $row = $result->fetch(PDO::FETCH_ASSOC);
			// If the user record was found, compare the password on record to the one provided hashed as necessary.
			// If successful, now set up session variables for the user and store a flag to say they are authorized.
			// These values follow the user around the site and will be tested on each page.
    if (($row !== false) && ($result->rowCount() > 0)) {

				// if ($row['password'] == hash('sha256', $pass)) {
        if ($row['password'] == $pass) {

					// is_auth is important here because we will test this to make sure they can view other pages
					// that are needing credentials.
					$_SESSION['is_auth'] = true;
					$_SESSION['user_level'] = $row['level'];
					$_SESSION['user_id'] = $row['id'];
					$_SESSION['user_nom'] = $row['nom'];
					$_SESSION['user_prenom'] = $row['prenom'];
					$_SESSION['user_entreprise'] = $row['entreprise'];

					// Once the sessions variables have been set, redirect them to the landing page / home page.
					header('location: welcome.php');
					exit;
				}
				else {
					$error = "1 Invalid email or password. Please try again.";
				}
			}
			else {
				$error = "2 Invalid email or password. Please try again.";
			}
		}
		else {
			$error = "Please enter an email and password to login.";
		}
	}
?>
<!--
/* Formulaire de saisie du login
*
*/
 -->
<!-- This form will post to current page and trigger our PHP script. -->
<h2>Login et mots de passe pour les tests</h2>
<p>email, mot de passe, type d'utilisateur</p>
<p>'thierry.semon@space.unibe.ch', 'thierry', 'manager'</p>
<p>'toto@toto.ch', 'toto', 'client'</p>
<p>'tata@tata.ch', 'tata', 'client'</p>
<p>'titi@titi.ch', 'titi', 'manager'</p><br/>
<form method="post" action="<?php echo $url_page ?>">
	<div class="login-body">
		<?php
			if (isset($error)) {
				echo "<div class='errormsg'>$error</div>";
			}
		?>
		<div class="form-row">
			<label for="emailaddress">Email:</label>
			<input type="text" name="emailaddress" id="emailaddress" placeholder="Email Address" maxlength="100">
		</div>
		<div class="form-row">
			<label for="pass">Password:</label>
			<input type="password" name="pass" id="pass" placeholder="Password" maxlength="100">
		</div>

		<div class="login-button-row">
			<input type="submit" name="login-submit" id="login-submit" value="Login" title="Login now">
		</div>
	</div>
</form>

<!--
/* Fin du formulaire de saisie du login
*
*/
-->
