# mysql < boulangerie.sql

	-- SET NAMES "utf8";

	-- CREATE or replace DATABASE boulangerie;
	-- USE boulangerie;

	-- CREATE TABLE IF NOT EXISTS
		 -- produits(id INT AUTO_INCREMENT NOT NULL,
				-- nom VARCHAR(100) NOT NULL,
				-- prix NUMERIC(10,2) NOT NULL,
				-- quantite INTEGER NOT NULL,
				-- time_stamp DATETIME NOT NULL,   
				-- PRIMARY KEY(id),
				-- UNIQUE(nom));

	-- INSERT INTO produits(nom, prix) VALUES('Croissant au chocolat', 2.50);
	-- INSERT INTO produits(nom, prix) VALUES('Croissant au beurre', 1.50);

SET NAMES "utf8";

CREATE DATABASE boulangerie;
USE boulangerie;

CREATE TABLE produits(id INT AUTO_INCREMENT NOT NULL,
      nom VARCHAR(100) NOT NULL,
      prix NUMERIC(10,2) NOT NULL,
			quantite INTEGER,
			time_stamp DATETIME,   
      PRIMARY KEY(id),
      UNIQUE(nom));

INSERT INTO produits(nom, prix) VALUES('Croissant au chocolat', 2.50);
INSERT INTO produits(nom, prix) VALUES('Croissant au beurre', 1.50);
