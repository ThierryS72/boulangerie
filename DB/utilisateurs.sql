USE boulangerie;

CREATE TABLE utilisateurs(id INT AUTO_INCREMENT NOT NULL,
      nom VARCHAR(100) NOT NULL,
			prenom VARCHAR(100) NOT NULL,
			entreprise VARCHAR(100) NOT NULL,
			email VARCHAR(100) NOT NULL,
      password VARCHAR(256) NOT NULL,
      level VARCHAR(100) NOT NULL,
      PRIMARY KEY(id)) ENGINE=InnoDB CHARSET=utf8;

INSERT INTO utilisateurs(nom, prenom, entreprise, email, password, level) VALUES('Sémon', 'Thierry', 'Moulin SA', 'thierry.semon@space.unibe.ch', 'thierry', 'manager');
INSERT INTO utilisateurs(nom, prenom, entreprise, email, password, level) VALUES('toto', 'cutugno', 'Entreprise 1', 'toto@toto.ch', 'toto', 'client');
INSERT INTO utilisateurs(nom, prenom, entreprise, email, password, level) VALUES('tata', 'varta', 'Entreprise 2', 'tata@tata.ch', 'tata', 'client');
INSERT INTO utilisateurs(nom, prenom, entreprise, email, password, level) VALUES('titi', 'leriquiqui', 'Boulangerie', 'titi@titi.ch', 'titi', 'manager');
