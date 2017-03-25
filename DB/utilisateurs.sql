USE boulangerie;

CREATE TABLE utilisateurs(id INT AUTO_INCREMENT NOT NULL,
      nom VARCHAR(100) NOT NULL,
			prenom VARCHAR(100) NOT NULL,
			entreprise VARCHAR(100) NOT NULL,
			email VARCHAR(100) NOT NULL,
      password VARCHAR(256) NOT NULL,
      level VARCHAR(100) NOT NULL,
      PRIMARY KEY(id)) ENGINE=InnoDB CHARSET=utf8;

INSERT INTO utilisateurs(nom, prenom, entreprise, email, password, level) VALUES('Sémon', 'Thierry', 'Moulin SA', 'thierry.semon@space.unibe.ch', '$2y$10$cUwStvfR2JEbb/Fhy2hOdeNlRiALaYpizVCwkQW2XIB6js8aRVIKq', 'manager');
INSERT INTO utilisateurs(nom, prenom, entreprise, email, password, level) VALUES('Cutugno', 'Toto', 'Entreprise 1', 'toto@toto.ch', '$2y$10$j/WQub1Ho9jF8KdqyBd7aODwUVfZuCV.Mc4FU1C1tER6V4/J9J0T6', 'client');
INSERT INTO utilisateurs(nom, prenom, entreprise, email, password, level) VALUES('Varta', 'Tata', 'Entreprise 2', 'tata@tata.ch', '$2y$10$2bGf7Ps4uyww22J34LldTu30lXwHisOMs/1/cx9piNEFdRdlx5NRO', 'client');
INSERT INTO utilisateurs(nom, prenom, entreprise, email, password, level) VALUES('Leriquiqui', 'Titi', 'Boulangerie', 'titi@titi.ch', '$2y$10$9M3xbCaO6bVzgLMtyQ9EBOT9/8GxedZ4ijTDC.FP7rGXngtxOALEa', 'manager');
INSERT INTO utilisateurs(nom, prenom, entreprise, email, password, level) VALUES('Lapierre', 'Albert', 'Entreprise 3', 'albert@albert.ch', '$2y$10$q58xbuaQsMffC3r2J8kdX./7z3vpWhbMBtgFBBsdz6jfuvWbyfWZO', 'client');
INSERT INTO utilisateurs(nom, prenom, entreprise, email, password, level) VALUES('Mooser', 'André', 'Entreprise 1', 'andre@andre.ch', '$2y$10$3wh9gvPPKT1X2fTdeYtqP.pO3XqP4B2d9WgiuQ5ps1lrjEXRHF4hG', 'client');
