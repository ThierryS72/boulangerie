# mysql < boulangerie.sql
# forcer le travail en UTF-8
SET collation_connection = utf8_general_ci;
SET NAMES 'utf8';

CREATE DATABASE IF NOT EXISTS boulangerie;
USE boulangerie;

CREATE TABLE produits(id INT AUTO_INCREMENT NOT NULL,
      nom VARCHAR(100) NOT NULL,
      prix NUMERIC(10,2) NOT NULL,
			quantite INTEGER NOT NULL,
			time_stamp DATETIME,
      PRIMARY KEY(id),
      UNIQUE(nom)) ENGINE=InnoDB CHARSET=utf8;

CREATE TABLE commandes(id INT AUTO_INCREMENT NOT NULL,
      nom VARCHAR(100) NOT NULL,
			prenom VARCHAR(100) NOT NULL,
			entreprise VARCHAR(100) NOT NULL,
			produit VARCHAR(100) NOT NULL,
			quantite INTEGER NOT NULL,
			prix_total NUMERIC(10,2) NOT NULL,
			time_stamp DATETIME,
      PRIMARY KEY(id)) ENGINE=InnoDB CHARSET=utf8;

INSERT INTO produits(nom, prix, quantite) VALUES('Petit pain', 1.30, 3);
INSERT INTO produits(nom, prix, quantite) VALUES('Croissant au beurre', 1.50, 6);
INSERT INTO produits(nom, prix, quantite) VALUES('Croissant au chocolat', 2.50, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Mini-tresse', 2.50, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Mini-cuchaule', 2.20, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Brioche', 1.90, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Ballon', 1.30, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Ballon tournesol', 1.50, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Ballon croustigrain', 1.30, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Croissant complet', 1.50, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Petit pain au sucre', 2.50, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Pain vanille', 2.50, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Croissant vanille', 2.50, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Escargot', 2.50, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Boule de Berlin', 2.50, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Pièce sèche', 2.30, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Muffins', 2.90, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Tranche au citron', 4.40, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Tranche d''étudiant', 2.70, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Rissole', 3.20, 3);
INSERT INTO produits(nom, prix, quantite) VALUES('Croissant au jambon', 3.20, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Rameqin au fromage', 3.20, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Vienne en cage', 3.20, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Sandwich pâte à petit pain au jambon', 4.00, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Sandwich pâte à petit pain au salami', 4.00, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Sandwich pâte à petit pain au thon', 4.70, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Délice au jambon', 3.50, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Délice au salami', 3.50, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Sandwich baguette favorite au jambon', 5.00, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Sandwich baguette favorite au salami', 5.00, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Sandwich baguette favorite au thon', 5.30, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Sandwich poulet-curry', 5.90, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Sandwich paillasse au jambon', 5.40, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Sandwich paillasse au salami', 5.40, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Sandwich paillasse au fromage', 5.40, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Salade mêlée', 7.20, 2);
INSERT INTO produits(nom, prix, quantite) VALUES('Salade tomates-mozzarella', 7.20, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Salade au thon', 9.20, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Salade poulet-curry', 9.20, 1);
INSERT INTO produits(nom, prix, quantite) VALUES('Bircher', 4.70, 2);


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
INSERT INTO utilisateurs(nom, prenom, entreprise, email, password, level) VALUES('Mooser', 'André', 'Entreprise 1', 'andre.mooser@bluewin.ch', '$2y$10$3wh9gvPPKT1X2fTdeYtqP.pO3XqP4B2d9WgiuQ5ps1lrjEXRHF4hG', 'client');
