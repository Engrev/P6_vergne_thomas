-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 17 août 2020 à 14:24
-- Version du serveur :  5.7.24
-- Version de PHP : 7.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `snowtricks`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20200702115339', '2020-07-02 11:56:18', 580),
('DoctrineMigrations\\Version20200702125937', '2020-07-02 12:59:56', 108),
('DoctrineMigrations\\Version20200702134354', '2020-07-02 13:44:11', 625),
('DoctrineMigrations\\Version20200702155833', '2020-07-02 15:58:42', 176),
('DoctrineMigrations\\Version20200706121033', '2020-07-06 12:10:44', 685),
('DoctrineMigrations\\Version20200706140628', '2020-07-06 14:06:35', 641),
('DoctrineMigrations\\Version20200706192328', '2020-07-06 19:23:38', 624),
('DoctrineMigrations\\Version20200707125413', '2020-07-07 12:55:45', 647),
('DoctrineMigrations\\Version20200707140112', '2020-07-07 14:01:26', 575),
('DoctrineMigrations\\Version20200707143747', '2020-07-07 14:37:50', 35),
('DoctrineMigrations\\Version20200707153331', '2020-07-07 15:33:35', 112),
('DoctrineMigrations\\Version20200707163209', '2020-07-07 16:32:14', 627),
('DoctrineMigrations\\Version20200707220629', '2020-07-07 22:06:34', 654),
('DoctrineMigrations\\Version20200708133357', '2020-07-08 13:34:09', 711),
('DoctrineMigrations\\Version20200713161343', '2020-07-13 16:13:55', 650),
('DoctrineMigrations\\Version20200716114258', '2020-07-16 11:43:09', 611),
('DoctrineMigrations\\Version20200716142529', '2020-07-16 14:25:36', 638),
('DoctrineMigrations\\Version20200716143135', '2020-07-16 14:31:38', 32),
('DoctrineMigrations\\Version20200716203210', '2020-07-16 20:32:21', 144),
('DoctrineMigrations\\Version20200716221128', '2020-07-16 22:12:13', 1007),
('DoctrineMigrations\\Version20200721083304', '2020-07-21 08:33:31', 676),
('DoctrineMigrations\\Version20200721120929', '2020-07-21 12:09:55', 677),
('DoctrineMigrations\\Version20200727090725', '2020-07-27 09:07:53', 256),
('DoctrineMigrations\\Version20200729114810', '2020-07-29 11:48:52', 117),
('DoctrineMigrations\\Version20200729161604', '2020-07-29 16:16:17', 192);

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `selector` varchar(20) NOT NULL,
  `hashed_token` varchar(100) NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `st_categories`
--

CREATE TABLE `st_categories` (
  `id` int(11) NOT NULL,
  `link` varchar(105) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` longtext,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `st_categories`
--

INSERT INTO `st_categories` (`id`, `link`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, '1-grabs', 'Grabs', 'Un grab consiste à attraper la planche avec la main pendant le saut.', '2020-06-29 15:12:12', '2020-06-29 15:12:12'),
(2, '2-rotations', 'Rotations', 'On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués.', '2020-06-29 15:12:12', '2020-06-29 15:12:12'),
(3, '3-flips', 'Flips', 'Un flip est une rotation verticale. On distingue les front flips, rotations en avant et les back flips, rotations en arrière. Il est possible de faire plusieurs flips à la suite et d\'ajouter un grab à la rotation.', '2020-06-29 15:12:12', '2020-06-29 15:12:12'),
(4, '4-rotations-desaxees', 'Rotations désaxées', 'Une rotation désaxée est une rotation initialement horizontale mais lancée avec un mouvement des épaules particulier qui désaxe la rotation.', '2020-06-29 15:12:12', '2020-06-29 15:12:12'),
(5, '5-slides', 'Slides', 'Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec la planche dans l\'axe de la barre, soit perpendiculaire, soit plus ou moins désaxé. On peut slider avec la planche centrée par rapport à la barre (celle-ci se situe approximativement au-dessous des pieds du rideur), mais aussi en nose slide, c\'est-à-dire l\'avant de la planche sur la barre, ou en tail slide, l\'arrière de la planche sur la barre.', '2020-06-29 15:12:12', '2020-06-29 15:12:12'),
(6, '6-one-foot-tricks', 'One foot tricks', 'Figures réalisée avec un pied décroché de la fixation, afin de tendre la jambe correspondante pour mettre en évidence le fait que le pied n\'est pas fixé.', '2020-06-29 15:12:12', '2020-06-29 15:12:12');

-- --------------------------------------------------------

--
-- Structure de la table `st_figures`
--

CREATE TABLE `st_figures` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `description` longblob,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `st_figures`
--

INSERT INTO `st_figures` (`id`, `category_id`, `name`, `picture`, `description`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mute', 'uploads\\figures\\1\\8ee4c19a013c4f429151f5e374adf6ff.jpeg', 0x3c703e536169736965206465206c61206361727265203c656d3e66726f6e74736964653c2f656d3e206465206c6120706c616e6368652c20656e747265206c657320646575782070696564732c2061766563206c61206d61696e203c656d3e61727269266567726176653b72653c2f656d3e2e3c2f703e, 1, '2020-06-29 15:29:11', '2020-07-31 15:37:07'),
(2, 1, 'Sad', 'uploads\\figures\\2\\ffd9493bdbc9e5d517d747ae2668acb5.jpeg', 0x3c703e536169736965206465206c61206361727265203c656d3e6261636b736964653c2f656d3e206465206c6120706c616e6368652c20656e747265206c657320646575782070696564732c2061766563206c61206d61696e203c656d3e6176616e743c2f656d3e2e3c2f703e, 1, '2020-06-29 15:29:11', '2020-07-31 15:39:18'),
(3, 1, 'Indy', 'uploads\\figures\\3\\5fbff41f60d711b2432db4443c9c6f32.jpeg', 0x3c703e536169736965206465206c61206361727265203c656d3e66726f6e74736964653c2f656d3e206465206c6120706c616e6368652c20656e747265206c657320646575782070696564732c2061766563206c61206d61696e203c656d3e61727269266567726176653b72653c2f656d3e20286368616e67656d656e74206465206c61206d61696e2070617220726170706f7274206175204d757465292e3c2f703e, 1, '2020-06-29 15:29:11', '2020-08-12 10:11:29'),
(4, 1, 'Stalefish', 'uploads\\figures\\4\\89d124bc9ad74c9698cc54a9ba7c423d.jpeg', 0x3c703e536169736965206465206c61206361727265203c656d3e6261636b736964653c2f656d3e206465206c6120706c616e6368652c20656e747265206c657320646575782070696564732c2061766563206c61206d61696e203c656d3e61727269266567726176653b72653c2f656d3e20286368616e67656d656e74206465206c61206d61696e2070617220726170706f727420617520536164292e3c2f703e, 1, '2020-06-29 15:29:11', '2020-07-31 15:52:28'),
(5, 1, 'Tail Grab', 'uploads\\figures\\5\\8acbcac78518f6f24ed0d7cf1345afff.jpeg', 0x3c703e536169736965206465206c61203c656d3e7061727469652061727269266567726176653b72653c2f656d3e206465206c6120706c616e6368652061766563206c61206d61696e203c656d3e61727269266567726176653b72653c2f656d3e2e3c2f703e, 1, '2020-06-29 15:29:11', '2020-07-31 15:54:14'),
(6, 1, 'Nose Grab', 'uploads\\figures\\6\\e86c1699bb3d91924ba812936804f94e.jpeg', 0x3c703e536169736965206465206c61203c656d3e706172746965206176616e743c2f656d3e206465206c6120706c616e6368652061766563206c61206d61696e203c656d3e6176616e743c2f656d3e2e3c2f703e, 1, '2020-06-29 15:29:11', '2020-08-01 00:20:44'),
(7, 1, 'Japan', 'uploads\\figures\\7\\181f6d998380e449bc2fa2f95c21f455.jpeg', 0x3c703e536169736965206465206c61203c656d3e706172746965206176616e743c2f656d3e206465206c6120706c616e6368652061766563206c61206d61696e203c656d3e6176616e743c2f656d3e2c2064752063266f636972633b74266561637574653b206465206c61206361727265203c656d3e66726f6e74736964653c2f656d3e2e3c2f703e, 1, '2020-06-29 15:29:11', '2020-07-31 15:55:57'),
(8, 1, 'Seat Belt', 'uploads\\figures\\8\\25c4d9e3af5ed13439838680f8a20a33.jpeg', 0x3c703e536169736965206465206c61203c656d3e7061727469652061727269266567726176653b72653c2f656d3e206465206c6120706c616e6368652061766563206c61206d61696e203c656d3e6176616e743c2f656d3e2c2064752063266f636972633b74266561637574653b206465206c61206361727265203c656d3e66726f6e74736964653c2f656d3e2e3c2f703e, 1, '2020-06-29 15:29:11', '2020-07-31 15:57:37'),
(9, 1, 'Truck Driver', 'uploads\\figures\\9\\506921bbe2e62487767b561e11df42ac.jpeg', 0x3c703e53616973696520646573203c656d3e64657578206361727265733c2f656d3e2061766563203c656d3e636861717565206d61696e3c2f656d3e2e3c2f703e, 1, '2020-06-29 15:29:11', '2020-07-31 15:58:18'),
(10, 2, '180', 'uploads\\figures\\10\\f1ab593cebd94b573cf3e29b4e176da5.png', 0x3c703e556e2064656d692d746f75722e3c2f703e, 1, '2020-06-29 15:45:13', '2020-07-31 16:36:28'),
(11, 2, '360', 'uploads\\figures\\11\\52292e7bb7a07cfe7785095db6fecdaa.png', 0x3c703e556e20746f757220636f6d706c65742e3c2f703e, 1, '2020-06-29 15:45:13', '2020-07-31 16:36:34'),
(12, 2, '540', 'uploads\\figures\\12\\6cd3c76dc8204e01d0ed6093c1c7fb81.png', 0x3c703e556e20746f75722065742064656d692e3c2f703e, 1, '2020-06-29 15:45:13', '2020-07-31 16:36:39'),
(13, 2, '720', 'uploads\\figures\\13\\92b55bb0dd9ac3f45664c44fa3596207.png', 0x3c703e4465757820746f75727320636f6d706c6574732e3c2f703e, 1, '2020-06-29 15:45:13', '2020-07-31 16:36:45'),
(14, 2, '900', 'uploads\\figures\\14\\dbee2ad47c8e6f06c0fb7baa36365981.png', 0x3c703e4465757820746f7572732065742064656d692e3c2f703e, 1, '2020-06-29 15:45:13', '2020-07-31 16:36:50'),
(15, 2, '1080', 'uploads\\figures\\15\\3c6590ef49144342a3e5d9fbe15cdddd.png', 0x3c703e54726f697320746f75727320636f6d706c6574732e20417573736920617070656c266561637574653b203c7374726f6e673e42696720466f6f743c2f7374726f6e673e2e3c2f703e, 1, '2020-06-29 15:45:13', '2020-07-31 16:36:55'),
(16, 5, '50 50', 'uploads\\figures\\16\\37b631bdc24bb87aed11b7a2c3d5c2a7.jpeg', NULL, 1, '2020-07-31 14:56:12', '2020-07-31 14:56:12');

-- --------------------------------------------------------

--
-- Structure de la table `st_files`
--

CREATE TABLE `st_files` (
  `id` int(11) NOT NULL,
  `figure_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `name` longtext NOT NULL,
  `uploaded_name` longtext NOT NULL,
  `uploaded_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `st_files`
--

INSERT INTO `st_files` (`id`, `figure_id`, `path`, `name`, `uploaded_name`, `uploaded_at`) VALUES
(1, 3, 'uploads\\figures\\3\\2a3c3fc2a524b9e23ad1a2829ee487f5.jpeg', '2a3c3fc2a524b9e23ad1a2829ee487f5.jpeg', 'indy-grab-1.jpeg', '2020-07-31 17:25:22'),
(2, 3, 'uploads\\figures\\3\\97b5112eec1b5a97523f00be807443c1.jpeg', '97b5112eec1b5a97523f00be807443c1.jpeg', 'indy-grab-2.jpeg', '2020-07-31 17:25:22'),
(5, 3, 'https://www.youtube.com/embed/6yA3XqjTh_w', '6yA3XqjTh_w', '6yA3XqjTh_w', '2020-07-31 21:12:33');

-- --------------------------------------------------------

--
-- Structure de la table `st_messages`
--

CREATE TABLE `st_messages` (
  `id` int(11) NOT NULL,
  `figure_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` longblob NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `st_messages`
--

INSERT INTO `st_messages` (`id`, `figure_id`, `user_id`, `content`, `created_at`) VALUES
(1, 16, 1, 0x3c703e496c2066617574206a7573746520657373617965722064652072657374657220656e20266561637574653b7175696c69627265202120f09f98843c2f703e, '2020-08-01 00:22:15');

-- --------------------------------------------------------

--
-- Structure de la table `st_users`
--

CREATE TABLE `st_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roles` json NOT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `is_verified_at` datetime DEFAULT NULL,
  `last_connection_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `st_users`
--

INSERT INTO `st_users` (`id`, `username`, `email`, `password`, `roles`, `avatar`, `is_active`, `is_verified`, `is_verified_at`, `last_connection_at`, `created_at`, `updated_at`) VALUES
(1, 'Engrev', 'tvergne83@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$TC5sVW1YejJXQTVCV0JJTg$R+tryjh+W5A0AzNme++zhxSs+vey2YH4zRaEvMlDFg0', '[\"ROLE_SUPER_ADMIN\"]', 'uploads\\users\\1\\955313801da057dad0ef1f7b40af9b5f.jpeg', 1, 1, NULL, NULL, '2020-07-04 18:40:56', '2020-07-29 18:56:22'),
(2, 'Thomas', 'tvergne83@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$T2ZQT1lzVkZuRG9OWW13Nw$/y+kA8A6mIm/FshmM3ilKlKTvxPf8rDY2GRqLwyyziY', '[\"ROLE_ADMIN\"]', NULL, 1, 1, '2020-07-07 22:52:40', NULL, '2020-07-07 22:37:05', '2020-07-07 22:37:05'),
(3, 'Romain', 'tvergne83@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$ZDBRS29VR1ZyL0J5VjFhdQ$8ztcB+E4yiXpyAIBsUPmWeWeZ3jGWOQCH7RnD72/VGU', '[\"ROLE_USER\"]', NULL, 0, 0, NULL, NULL, '2020-07-07 22:51:51', '2020-07-07 22:51:51'),
(4, 'Snowtricks', 'tvergne83@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$ellSL3RVazJNS0pTLk1KWQ$XKTTkVp7Jd9z6AkwI5di0VVso6sOO/wYmx7PbOvN0V8', '[\"ROLE_ADMIN\"]', NULL, 1, 1, '2020-07-08 00:10:18', NULL, '2020-07-08 00:24:29', '2020-07-08 00:24:29'),
(5, 'Toto', 'tvergne83@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$RVp6NGowVi9CdWdqbWM3UA$dVbp5QzQ6us/SmLiigBW8RqKqxVw6tCvQcJGp1AbrCw', '[\"ROLE_USER\"]', NULL, 1, 1, '2020-07-31 02:04:33', NULL, '2020-07-31 02:04:32', '2020-07-31 02:31:25');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Index pour la table `st_categories`
--
ALTER TABLE `st_categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `st_figures`
--
ALTER TABLE `st_figures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_name` (`name`),
  ADD KEY `IDX_E920FABA12469DE2` (`category_id`),
  ADD KEY `IDX_E920FABAA76ED395` (`user_id`);

--
-- Index pour la table `st_files`
--
ALTER TABLE `st_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6215B0445C011B5` (`figure_id`);

--
-- Index pour la table `st_messages`
--
ALTER TABLE `st_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E02EEFA45C011B5` (`figure_id`),
  ADD KEY `IDX_E02EEFA4A76ED395` (`user_id`);

--
-- Index pour la table `st_users`
--
ALTER TABLE `st_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_username` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `st_categories`
--
ALTER TABLE `st_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `st_figures`
--
ALTER TABLE `st_figures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `st_files`
--
ALTER TABLE `st_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `st_messages`
--
ALTER TABLE `st_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `st_users`
--
ALTER TABLE `st_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `st_users` (`id`);

--
-- Contraintes pour la table `st_figures`
--
ALTER TABLE `st_figures`
  ADD CONSTRAINT `FK_E920FABA12469DE2` FOREIGN KEY (`category_id`) REFERENCES `st_categories` (`id`),
  ADD CONSTRAINT `FK_E920FABAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `st_users` (`id`);

--
-- Contraintes pour la table `st_files`
--
ALTER TABLE `st_files`
  ADD CONSTRAINT `FK_6215B0445C011B5` FOREIGN KEY (`figure_id`) REFERENCES `st_figures` (`id`);

--
-- Contraintes pour la table `st_messages`
--
ALTER TABLE `st_messages`
  ADD CONSTRAINT `FK_E02EEFA45C011B5` FOREIGN KEY (`figure_id`) REFERENCES `st_figures` (`id`),
  ADD CONSTRAINT `FK_E02EEFA4A76ED395` FOREIGN KEY (`user_id`) REFERENCES `st_users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
