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
			quantite_restante INTEGER,
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
