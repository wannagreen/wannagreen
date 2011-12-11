-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Jeu 12 Mai 2011 à 12:14
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `wannagreen`
--



--
-- Contenu de la table `contact`
--


--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `email`, `password`, `nom`, `prenom`, `adresse`, `latitude`, `longitude`, `sexe`, `date_naissance`, `avatar`, `date_creation`, `date_maj`) VALUES
(1, 'j.paroche@gmail.com', 'b0c96252cd12fe506fdad4fd745c2f3038a73ac2', 'Paroche', 'Julien', 'Verrières-le-Buisson', NULL, NULL, 'M', '03/02/1989', 'QuietCanada.png', '2011-04-16 15:21:12', '2011-05-06 01:55:04'),
(2, 'nikola0210@gmail.com', 'b0c96252cd12fe506fdad4fd745c2f3038a73ac2', 'Duarte', 'Nicolas', 'Maisons-Laffitte', NULL, NULL, 'M', '02/10/1988', '225749_7093001590_547366590_264701_9825_nb.jpg', '2011-04-20 20:47:28', '2011-05-06 13:02:21'),
(3, 'elyadari.mohamed@gmail.com', 'b0c96252cd12fe506fdad4fd745c2f3038a73ac2', 'El Yadari', 'Mohamed', 'Andrésy', NULL, NULL, 'M', '10/04/1989', 'fleur-smiley.gif', '2011-04-22 17:37:51', '2011-05-06 14:07:10'),
(4, 'judikael.r@gmail.com', 'b0c96252cd12fe506fdad4fd745c2f3038a73ac2', 'Robert', 'Judikael', 'Paris', NULL, NULL, 'F', '25/12/1989', 'sticker-concept-ecologie,8305883[2].jpg', '2011-04-22 17:42:42', '2011-05-06 10:33:37'),
(5, 'raphael.granmagnat@gmail.com', 'b0c96252cd12fe506fdad4fd745c2f3038a73ac2', 'Granmagnat', 'Raphael', 'Issy-Les Moulineaux', NULL, NULL, 'M', '23/09/1987', 'chuckie1.jpg', '2011-04-22 17:47:51', '2011-05-06 16:22:45'),
(6, 'florian.neuveux@gmail.com', 'b0c96252cd12fe506fdad4fd745c2f3038a73ac2', 'Neuveux', 'Florian', 'Villejuif', NULL, NULL, 'M', '30/08/1989', 'goutte-d-eau[1].jpg', '2011-04-22 17:51:11', '2011-05-06 02:01:04'),
(7, 'mplasse@free.fr', '45e93a12e1bfe2de1eda6f613b31ce72deab97aa', 'Plasse', 'Michel', NULL, NULL, NULL, 'M', NULL, NULL, '2011-05-03 10:51:05', NULL),
(10, 'test@gmail.com', '9aa4f85ecc1bb96ac1c480373d199e7889f2bb3d', 'test', 'test', NULL, NULL, NULL, NULL, NULL, NULL, '2011-05-06 15:55:27', NULL),
(11, 'thomas_7204@hotmail.com', '58cc6ccea06b9b05d84663306478b89f175adfd4', 'lav', 'tom', 'nanterre', NULL, NULL, 'M', '30/11/1986', NULL, '2011-05-07 14:17:35', NULL),
(12, 'anis.madjour@hotmail.fr', 'a7b24eaa394f8791fd3ffcf7fe6af8b363b3a3e0', 'Madjour', 'Anis', NULL, NULL, NULL, 'M', NULL, NULL, '2011-05-09 16:14:25', NULL),
(13, 'alexandre.bodelot@gmail.com', '2e3f3ee64ab34aa53b05c3dea049c41d9a0560c5', 'Bodelot', 'Alexandre', 'Paris', NULL, NULL, 'M', '09/12/1987', NULL, '2011-05-10 11:35:28', NULL);

--
-- Contenu de la table `groupe`
--

INSERT INTO `groupe` (`id_groupe`, `nom`, `description`, `avatar`, `poids_recommandation`, `ferme`, `date_creation`, `date_maj`, `id_utilisateur`) VALUES
(1, 'PPD Réseau social', 'Lancer un réseau social dans les domaines de l''activité verte et solidaire.', 'sn.png', 1, 1, '2011-05-02 09:44:33', NULL, 6),
(2, 'PPD Toiture solaire', 'Etudier et promouvoir le projet d''installer des panneaux solaires à l''université.\r\nIl faut faire le point sur l''ensemble des aspects du projet : coût, retour sur investissement, aspects juridiques, prestataires extérieurs ; et promouvoir ce projet auprès de la direction et de tous les décideurs potentiels par tous les moyens utiles.', NULL, 1, 0, '2011-05-02 09:50:08', NULL, 2),
(3, 'PPD Serveur de connaissances', 'Mettre en place un serveur de connaissances pour un centre dans un pays en développement, autonome en énergie, et un dispositif simple et efficace pour le mettre à jour régulièrement.', NULL, 1, 1, '2011-05-05 18:38:50', NULL, 1),
(4, 'PPD Tri sélectif', 'Développer le tri sélectif et le recyclage à Paris Descartes.\n\nY compris le recyclage du matériel informatique.', '54b1fa2152719e5dafe8abf5b0981741-bpfull.jpg', 1, 0, '2011-05-05 19:22:01', NULL, 2),
(5, 'PPD Publication solidaire', 'Lancer une publication de vulgarisation vendue par les sans abris et les plus démunis.\n\nLa publication devra être une entreprise sociale, au budget équilibré. Elle devra aider le public à mieux comprendre ou connaître notre monde et les avancées scientifiques, techniques, organisationnelles ou humaines : tout ce qui touche les gens.', NULL, 1, 0, '2011-05-05 19:30:21', NULL, 2),
(6, 'Université solidaire', 'Publication de vulgarisation scientifique vendue par des personnes défavorisées', NULL, 1, 0, '2011-05-09 16:15:59', NULL, 12);

--
-- Contenu de la table `adhesion`
--

INSERT INTO `adhesion` (`id_groupe`, `id_utilisateur`, `type`, `statut`, `date_creation`, `date_maj`) VALUES
(1, 1, 'membre', 1, '2011-05-05 18:42:17', NULL),
(1, 2, 'membre', 1, '2011-05-05 18:44:32', NULL),
(1, 3, 'membre', 1, '2011-05-05 18:43:34', NULL),
(1, 4, 'membre', 1, '2011-05-06 13:37:42', '2011-05-06 13:38:46'),
(1, 5, 'membre', 1, '2011-05-02 15:12:03', '2011-05-02 15:12:28'),
(1, 10, 'membre', 1, '2011-05-06 15:57:10', '2011-05-06 15:58:04'),
(2, 1, 'favoris', 1, '2011-05-05 18:42:29', NULL),
(2, 6, 'membre', 1, '2011-05-05 23:12:06', NULL),
(3, 6, 'favoris', 1, '2011-05-05 23:12:15', NULL);

--
-- Contenu de la table `partenariat`
--

INSERT INTO `partenariat` (`id_groupe_demandeur`, `id_groupe_demande`, `statut`, `date_creation`, `date_maj`) VALUES
(1, 2, 1, '2011-05-05 18:50:56', '2011-05-05 18:50:58'),
(1, 3, 1, '2011-05-05 18:51:12', '2011-05-05 18:51:15');

--
-- Contenu de la table `site_social`
--

INSERT INTO `site_social` (`id_site_social`, `libelle`, `avatar`, `date_creation`, `date_maj`) VALUES
(1, 'Facebook', 'soc_facebook.png', '2011-05-18 10:05:33', NULL),
(2, 'Twitter', 'soc_twitter.png', '2011-05-18 10:05:33', NULL),
(3, 'Delicious', 'soc_delicious.png', '2011-05-18 10:05:33', NULL),
(4, 'LinkedIn', 'soc_linkedin.png', '2011-07-04 01:30:00', NULL),
(5, 'MySpace', 'soc_myspace.png', '2011-07-04 01:18:17', NULL),
(6, 'Default', 'soc_blank.png', '2011-07-04 01:19:01', NULL),
(7, 'StumbleUpon', 'soc_stumbleupon.png', '2011-07-04 01:28:32', NULL),
(8, 'Google', 'soc_google.png', '2011-07-04 01:28:00', NULL),
(9, 'Youtube', 'soc_youtube.png', '2011-07-04 01:28:00', NULL),
(10, 'Flickr', 'soc_flickr.png', '2011-07-04 01:29:54', NULL);

--
-- Contenu de la table `profil_externe`
--

INSERT INTO `profil_externe` (`id_utilisateur`, `id_site_social`, `url`, `date_creation`, `date_maj`) VALUES
(1, 1, 'jparoche', '2011-05-18 10:10:33', NULL),
(1, 2, 'julienpa', '2011-05-18 10:10:33', NULL),
(2, 2, 'nicolas_duarte', '2011-05-18 10:10:33', NULL),
(3, 2, 'el_mohamed', '2011-05-18 10:10:33', NULL),
(4, 2, 'judi_r', '2011-05-18 10:10:33', NULL),
(5, 2, 'RaphGrams', '2011-05-18 10:10:33', NULL),
(4, 4, '75410684', '2011-07-04 01:14:56', NULL),
(1, 9, 'darkfalco3', '2011-07-04 01:44:15', NULL);

--
-- Contenu de la table `publication`
--

INSERT INTO `publication` (`id_publication`, `type`, `prive`, `date_creation`, `date_maj`, `id_utilisateur`) VALUES
(1, 'lien', 0, '2011-07-05 01:45:46', NULL, 4),
(2, 'lien', 0, '2011-07-05 01:49:19', NULL, 1),
(3, 'lien', 0, '2011-07-05 01:51:50', NULL, 1),
(4, 'lien', 0, '2011-07-05 01:55:50', NULL, 2);

--
-- Contenu de la table `publication_groupe`
--

INSERT INTO `publication_groupe` (`id_publication`, `id_groupe`, `date_creation`, `date_maj`) VALUES
(1, 1, '2011-07-05 01:45:46', NULL),
(2, 1, '2011-07-05 01:49:19', NULL),
(3, 3, '2011-07-05 01:51:50', NULL),
(4, 1, '2011-07-05 01:55:50', NULL);

--
-- Contenu de la table `publication_info`
--

INSERT INTO `publication_info` (`id_publication_info`, `libelle`, `contenu`, `id_publication`) VALUES
(1, 'titre', 'Kwixo !', 1),
(2, 'url', 'http://www.youtube.com/watch?v=xCBzia6iMKo', 1),
(3, 'date', '2011-07-05 01:45:46', 1),
(4, 'titre', 'Google+', 2),
(5, 'url', 'http://plus.google.com/', 2),
(6, 'date', '2011-07-05 01:49:19', 2),
(7, 'titre', 'Vidéo pour les amateurs de construction !', 3),
(8, 'url', 'http://www.dailymotion.com/video/xjg32k_publicite-hd-utilitaires-volkswagen-les-professionnels-v-2-2011_auto', 3),
(9, 'date', '2011-07-05 01:51:50', 3),
(10, 'titre', 'Jouer avec le feu', 4),
(11, 'url', 'http://www.wideo.fr/video/iLyROoaftu7P.htmlPUGvf9M4Beg', 4),
(12, 'date', '2011-07-05 01:55:50', 4);

--
-- Contenu de la table `recommandation`
--


--
-- Contenu de la table `tag`
--


--
-- Contenu de la table `tag_groupe`
--


--
-- Contenu de la table `tag_publication`
--


--
-- Contenu de la table `tag_utilisateur`
--


--
-- Contenu de la table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `contenu`, `date_creation`, `date_maj`, `id_utilisateur`, `id_publication`) VALUES
(1, 'ça marche', '2011-06-30 16:35:40', NULL, 1, 4),
(2, 'c est cool', '2011-06-30 16:35:40', NULL, 5, 5),
(3, 'jaime lastfm', '2011-06-30 16:35:40', NULL, 5, 9);

