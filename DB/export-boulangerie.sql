-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:8889
-- Généré le :  Dim 26 Mars 2017 à 21:42
-- Version du serveur :  5.6.35
-- Version de PHP :  7.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `boulangerie`
--

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `entreprise` varchar(100) NOT NULL,
  `produit` varchar(100) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_total` decimal(10,2) NOT NULL,
  `time_stamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `commandes`
--

INSERT INTO `commandes` (`id`, `nom`, `prenom`, `entreprise`, `produit`, `quantite`, `prix_total`, `time_stamp`) VALUES
(1, 'Cutugno', 'Toto', 'Entreprise 1', 'Croissant au beurre', 3, '4.50', '2017-03-25 10:23:44'),
(2, 'Cutugno', 'Toto', 'Entreprise 1', 'Escargot', 1, '2.50', '2017-03-25 10:23:49'),
(3, 'Cutugno', 'Toto', 'Entreprise 1', 'Délice au jambon', 1, '3.50', '2017-03-25 10:23:54'),
(4, 'Varta', 'Tata', 'Entreprise 2', 'Croissant au beurre', 1, '1.50', '2017-03-25 10:24:13'),
(5, 'Varta', 'Tata', 'Entreprise 2', 'Salade tomates-mozzarella', 1, '7.20', '2017-03-25 10:24:18'),
(6, 'Varta', 'Tata', 'Entreprise 2', 'Sandwich paillasse au salami', 1, '5.40', '2017-03-25 10:24:23'),
(7, 'Lapierre', 'Albert', 'Entreprise 3', 'Croissant au beurre', 1, '1.50', '2017-03-25 10:24:56'),
(8, 'Lapierre', 'Albert', 'Entreprise 3', 'Muffins', 1, '2.90', '2017-03-25 10:25:00'),
(9, 'Mooser', 'André', 'Entreprise 1', 'Croissant au beurre', 1, '1.50', '2017-03-25 10:25:18'),
(10, 'Mooser', 'André', 'Entreprise 1', 'Salade poulet-curry', 1, '9.20', '2017-03-25 10:25:22'),
(11, 'Mooser', 'André', 'Entreprise 1', 'Délice au salami', 1, '3.50', '2017-03-25 10:25:28'),
(12, 'Cutugno', 'Toto', 'Entreprise 1', 'Croissant au chocolat', 1, '2.50', '2017-03-25 10:43:02'),
(13, 'Cutugno', 'Toto', 'Entreprise 1', 'Tranche au citron', 1, '4.40', '2017-03-25 10:43:08'),
(14, 'Mooser', 'André', 'Entreprise 1', 'Petit pain', 2, '2.60', '2017-03-25 10:43:30'),
(15, 'Mooser', 'André', 'Entreprise 1', 'Croissant vanille', 1, '2.50', '2017-03-25 16:44:46'),
(16, 'Mooser', 'André', 'Entreprise 1', 'Salade mêlée', 1, '7.20', '2017-03-25 16:44:51'),
(17, 'Lapierre', 'Albert', 'Entreprise 3', 'Tranche d\'étudiant', 1, '2.70', '2017-03-25 16:56:50'),
(18, 'Lapierre', 'Albert', 'Entreprise 3', 'Vienne en cage', 1, '3.20', '2017-03-25 16:56:53'),
(19, 'Lapierre', 'Albert', 'Entreprise 3', 'Croissant complet', 2, '3.00', '2017-03-25 17:01:04'),
(20, 'Lapierre', 'Albert', 'Entreprise 3', 'Rameqin au fromage', 1, '3.20', '2017-03-25 17:17:12'),
(21, 'Cutugno', 'Toto', 'Entreprise 1', 'Croissant au chocolat', 1, '2.50', '2017-03-25 17:44:47'),
(22, 'Cutugno', 'Toto', 'Entreprise 1', 'Rissole', 1, '3.20', '2017-03-25 17:50:28'),
(23, 'Lapierre', 'Albert', 'Entreprise 3', 'Boule de Berlin', 1, '2.50', '2017-03-25 19:16:48'),
(24, 'Lapierre', 'Albert', 'Entreprise 3', 'Sandwich baguette favorite au thon', 1, '5.30', '2017-03-25 19:16:55'),
(25, 'Lapierre', 'Albert', 'Entreprise 3', 'Bircher', 1, '4.70', '2017-03-25 19:17:00'),
(26, 'Cutugno', 'Toto', 'Entreprise 1', 'Croissant au beurre', 2, '3.00', '2017-03-26 09:42:38'),
(27, 'Cutugno', 'Toto', 'Entreprise 1', 'Pièce sèche', 1, '2.30', '2017-03-26 09:42:42'),
(28, 'Cutugno', 'Toto', 'Entreprise 1', 'Muffins', 1, '2.90', '2017-03-26 09:42:47'),
(29, 'Varta', 'Tata', 'Entreprise 2', 'Croissant au beurre', 1, '1.50', '2017-03-26 09:43:09'),
(30, 'Varta', 'Tata', 'Entreprise 2', 'Petit pain au sucre', 1, '2.50', '2017-03-26 09:43:13'),
(31, 'Varta', 'Tata', 'Entreprise 2', 'Délice au salami', 1, '3.50', '2017-03-26 09:43:16'),
(32, 'Lapierre', 'Albert', 'Entreprise 3', 'Croissant au chocolat', 1, '2.50', '2017-03-26 09:43:41'),
(33, 'Lapierre', 'Albert', 'Entreprise 3', 'Rameqin au fromage', 1, '3.20', '2017-03-26 09:43:51'),
(34, 'Lapierre', 'Albert', 'Entreprise 3', 'Sandwich poulet-curry', 1, '5.90', '2017-03-26 09:43:59'),
(35, 'Mooser', 'André', 'Entreprise 1', 'Sandwich pâte à petit pain au jambon', 1, '4.00', '2017-03-26 09:44:20'),
(36, 'Mooser', 'André', 'Entreprise 1', 'Salade au thon', 1, '9.20', '2017-03-26 09:44:25'),
(37, 'Mooser', 'André', 'Entreprise 1', 'Sandwich poulet-curry', 1, '5.90', '2017-03-26 09:44:31'),
(38, 'Mooser', 'André', 'Entreprise 1', 'Vienne en cage', 1, '3.20', '2017-03-26 09:44:38'),
(39, 'Mooser', 'André', 'Entreprise 1', 'Pain vanille', 1, '2.50', '2017-03-26 10:03:42'),
(40, 'Mooser', 'André', 'Entreprise 1', 'Croissant complet', 1, '1.50', '2017-03-26 10:08:23');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `quantite` int(11) NOT NULL,
  `quantite_restante` int(11) DEFAULT NULL,
  `time_stamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `prix`, `quantite`, `quantite_restante`, `time_stamp`) VALUES
(41, 'Petit pain', '1.30', 3, NULL, '2017-03-26 10:03:21'),
(42, 'Croissant au beurre', '1.50', 6, NULL, NULL),
(43, 'Croissant au chocolat', '2.50', 2, NULL, NULL),
(44, 'Mini-tresse', '2.50', 1, NULL, NULL),
(45, 'Mini-cuchaule', '2.20', 2, NULL, NULL),
(46, 'Brioche', '1.90', 1, NULL, NULL),
(47, 'Ballon', '1.30', 2, NULL, NULL),
(48, 'Ballon tournesol', '1.50', 2, NULL, NULL),
(49, 'Ballon croustigrain', '1.30', 2, NULL, NULL),
(50, 'Croissant complet', '1.50', 2, NULL, NULL),
(51, 'Petit pain au sucre', '2.50', 1, NULL, NULL),
(52, 'Pain vanille', '2.50', 1, NULL, NULL),
(53, 'Croissant vanille', '2.50', 1, NULL, NULL),
(54, 'Escargot', '2.50', 1, NULL, NULL),
(55, 'Boule de Berlin', '2.50', 1, NULL, NULL),
(56, 'Pièce sèche', '2.30', 2, NULL, NULL),
(57, 'Muffins', '2.90', 2, NULL, NULL),
(58, 'Tranche au citron', '4.40', 1, NULL, NULL),
(59, 'Tranche d\'étudiant', '2.70', 1, NULL, NULL),
(60, 'Rissole', '3.20', 3, NULL, NULL),
(61, 'Croissant au jambon', '3.20', 2, NULL, NULL),
(62, 'Rameqin au fromage', '3.20', 2, NULL, NULL),
(63, 'Vienne en cage', '3.20', 2, NULL, NULL),
(64, 'Sandwich pâte à petit pain au jambon', '4.00', 2, NULL, NULL),
(65, 'Sandwich pâte à petit pain au salami', '4.00', 1, NULL, NULL),
(66, 'Sandwich pâte à petit pain au thon', '4.70', 1, NULL, NULL),
(67, 'Délice au jambon', '3.50', 1, NULL, NULL),
(68, 'Délice au salami', '3.50', 1, NULL, NULL),
(69, 'Sandwich baguette favorite au jambon', '5.00', 1, NULL, NULL),
(70, 'Sandwich baguette favorite au salami', '5.00', 1, NULL, NULL),
(71, 'Sandwich baguette favorite au thon', '5.30', 2, NULL, NULL),
(72, 'Sandwich poulet-curry', '5.90', 2, NULL, NULL),
(73, 'Sandwich paillasse au jambon', '5.40', 1, NULL, NULL),
(74, 'Sandwich paillasse au salami', '5.40', 1, NULL, NULL),
(75, 'Sandwich paillasse au fromage', '5.40', 1, NULL, NULL),
(76, 'Salade mêlée', '7.20', 2, NULL, NULL),
(77, 'Salade tomates-mozzarella', '7.20', 1, NULL, NULL),
(78, 'Salade au thon', '9.20', 1, NULL, NULL),
(79, 'Salade poulet-curry', '9.20', 1, NULL, NULL),
(80, 'Bircher', '4.70', 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `entreprise` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(256) NOT NULL,
  `level` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `entreprise`, `email`, `password`, `level`) VALUES
(1, 'Sémon', 'Thierry', 'Moulin SA', 'thierry.semon@space.unibe.ch', '$2y$10$cUwStvfR2JEbb/Fhy2hOdeNlRiALaYpizVCwkQW2XIB6js8aRVIKq', 'manager'),
(2, 'Cutugno', 'Toto', 'Entreprise 1', 'toto@toto.ch', '$2y$10$j/WQub1Ho9jF8KdqyBd7aODwUVfZuCV.Mc4FU1C1tER6V4/J9J0T6', 'client'),
(3, 'Varta', 'Tata', 'Entreprise 2', 'tata@tata.ch', '$2y$10$2bGf7Ps4uyww22J34LldTu30lXwHisOMs/1/cx9piNEFdRdlx5NRO', 'client'),
(4, 'Leriquiqui', 'Titi', 'Boulangerie', 'titi@titi.ch', '$2y$10$9M3xbCaO6bVzgLMtyQ9EBOT9/8GxedZ4ijTDC.FP7rGXngtxOALEa', 'manager'),
(5, 'Lapierre', 'Albert', 'Entreprise 3', 'albert@albert.ch', '$2y$10$q58xbuaQsMffC3r2J8kdX./7z3vpWhbMBtgFBBsdz6jfuvWbyfWZO', 'client'),
(6, 'Mooser', 'André', 'Entreprise 1', 'andre@andre.ch', '$2y$10$3wh9gvPPKT1X2fTdeYtqP.pO3XqP4B2d9WgiuQ5ps1lrjEXRHF4hG', 'client');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
