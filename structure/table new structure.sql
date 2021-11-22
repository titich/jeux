-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 23 sep. 2021 à 20:12
-- Version du serveur : 10.3.30-MariaDB
-- Version de PHP : 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `jeux`
--

-- --------------------------------------------------------

--
-- Structure de la table `artist`
--

CREATE TABLE `artist` (
  `artist_id` int(11) NOT NULL,
  `artist_nom_fr` varchar(150) DEFAULT NULL,
  `artist_nom_en` varchar(150) DEFAULT NULL,
  `artist_bgg_id` int(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `artist_jeu`
--

CREATE TABLE `artist_jeu` (
  `artist_jeu_id` int(11) NOT NULL,
  `jeu` int(11) NOT NULL,
  `artist` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_nbre_par_artist`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `v_nbre_par_artist` (
`artist_id` int(11)
,`artist_nom_en` varchar(150)
,`nbre` bigint(21)
);

-- --------------------------------------------------------

--
-- Structure de la vue `v_nbre_par_artist`
--
DROP TABLE IF EXISTS `v_nbre_par_artist`;

CREATE ALGORITHM=UNDEFINED DEFINER=`jeux`@`localhost` SQL SECURITY DEFINER VIEW `v_nbre_par_artist`  AS SELECT `artist_jeu`.`artist` AS `artist_id`, `artist`.`artist_nom_en` AS `artist_nom_en`, count(0) AS `nbre` FROM ((`artist_jeu` left join `artist` on(`artist`.`artist_id` = `artist_jeu`.`artist`)) left join `jeu` on(`jeu`.`jeu_id` = `artist_jeu`.`jeu`)) WHERE `jeu`.`jeu_bgg_subtype` = 'boardgame' GROUP BY `artist_jeu`.`artist`, `artist`.`artist_nom_en` ORDER BY count(0) DESC ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `artist`
--
ALTER TABLE `artist`
  ADD PRIMARY KEY (`artist_id`),
  ADD UNIQUE KEY `artist_bgg_id` (`artist_bgg_id`);

--
-- Index pour la table `artist_jeu`
--
ALTER TABLE `artist_jeu`
  ADD PRIMARY KEY (`artist_jeu_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `artist`
--
ALTER TABLE `artist`
  MODIFY `artist_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `artist_jeu`
--
ALTER TABLE `artist_jeu`
  MODIFY `artist_jeu_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
