-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 19 juin 2024 à 13:07
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `hackathon`
--

-- --------------------------------------------------------

--
-- Structure de la table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `classes`
--

INSERT INTO `classes` (`id`, `name`, `description`) VALUES
(12, 'Licence 1 WMBI', 'Licence professionnelle Informatique');

-- --------------------------------------------------------

--
-- Structure de la table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `courses`
--

INSERT INTO `courses` (`id`, `module_id`, `name`, `description`) VALUES
(1, 23, 'Securite Informatique', 'La sécurité dans l\'informatique'),
(3, 23, 'Le Big Data', 'Big Data : définition, technologies, utilisations, formations'),
(4, 23, 'Testez le débit de votre connexion Internet', 'votre disposition un speedtest. Ce test de débit gratuit permet, d’un simple clic');

-- --------------------------------------------------------

--
-- Structure de la table `course_contents`
--

CREATE TABLE `course_contents` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `course_contents`
--

INSERT INTO `course_contents` (`id`, `course_id`, `title`, `body`) VALUES
(1, 1, 'Qu\'est-ce que la sécurité informatique ?', '<p><strong>La s&eacute;curit&eacute; informatique&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </strong>La s&eacute;curit&eacute; informatique (abr&eacute;viation de s&eacute;curit&eacute; des technologies de l&#39;information ou IT) consiste &agrave; prot&eacute;ger les actifs informatiques d&#39;une organisation, &agrave; savoir les syst&egrave;mes informatiques, les r&eacute;seaux, les appareils num&eacute;riques et les donn&eacute;es, contre les acc&egrave;s non autoris&eacute;s, les&nbsp;<a href=\"https://www.ibm.com/fr-fr/topics/data-breach\" target=\"_blank\">violations de donn&eacute;es</a>, les&nbsp;<a href=\"https://www.ibm.com/fr-fr/topics/cyber-attack\" target=\"_blank\">cyberattaques</a>&nbsp;et autres activit&eacute;s malveillantes.</p>\r\n\r\n<p>Le champ d&#39;application de la s&eacute;curit&eacute; informatique est vaste et implique souvent un m&eacute;lange de technologies et de solutions de s&eacute;curit&eacute; qui se combinent pour rem&eacute;dier aux vuln&eacute;rabilit&eacute;s des appareils num&eacute;riques, des r&eacute;seaux informatiques, des serveurs, des bases de donn&eacute;es et des applications logicielles. Les exemples les plus couramment cit&eacute;s de s&eacute;curit&eacute; informatique comprennent les comp&eacute;tences en mati&egrave;re de s&eacute;curit&eacute; num&eacute;rique telles que la&nbsp;<a href=\"https://www.ibm.com/fr-fr/topics/endpoint-security\" target=\"_blank\">s&eacute;curit&eacute; des terminaux</a>,&nbsp;<a href=\"https://www.ibm.com/fr-fr/topics/cloud-security\" target=\"_blank\">du cloud</a>,&nbsp;<a href=\"https://www.ibm.com/fr-fr/topics/network-security\" target=\"_blank\">du r&eacute;seau</a>&nbsp;et des applications. Mais la s&eacute;curit&eacute; informatique comprend &eacute;galement des mesures de s&eacute;curit&eacute; physique (verrous, cartes d&#39;identit&eacute;, cam&eacute;ras de surveillance) n&eacute;cessaires pour prot&eacute;ger les b&acirc;timents et les appareils qui abritent des donn&eacute;es et des actifs informatiques.</p>\r\n'),
(6, 3, 'Introduction au Big Data', '<p>Le Big Data fait r&eacute;f&eacute;rence &agrave; un ensemble de donn&eacute;es massives, complexes et souvent h&eacute;t&eacute;rog&egrave;nes qui sont difficiles &agrave; g&eacute;rer et &agrave; traiter avec des outils traditionnels de gestion de donn&eacute;es. La&nbsp;<strong>quantit&eacute; de donn&eacute;es g&eacute;n&eacute;r&eacute;es chaque jour est en constante augmentation</strong>, ce qui rend le traitement et l&rsquo;analyse des donn&eacute;es de plus en plus complexe. Les donn&eacute;es peuvent provenir de diff&eacute;rentes sources telles que les r&eacute;seaux sociaux, les capteurs, les transactions, les images, les vid&eacute;os, les emails, etc.</p>\r\n\r\n<p>Le Big Data est important car il permet d&rsquo;extraire des informations pr&eacute;cieuses &agrave; partir des donn&eacute;es qui peuvent &ecirc;tre utilis&eacute;es pour am&eacute;liorer les processus d&eacute;cisionnels et les performances des entreprises, des organisations gouvernementales, des chercheurs et d&rsquo;autres acteurs. Par exemple, le Big Data peut &ecirc;tre utilis&eacute; pour am&eacute;liorer les pr&eacute;visions de ventes, optimiser les processus de production, mieux comprendre les comportements des clients, am&eacute;liorer la qualit&eacute; de vie, etc.</p>\r\n'),
(7, 4, 'Testez le débit de votre connexion Internet', '<p>L&rsquo;UFC-Que Choisir met &agrave; votre disposition un speedtest. Ce test de d&eacute;bit gratuit permet, d&rsquo;un simple clic, de conna&icirc;tre le d&eacute;bit descendant, le d&eacute;bit montant (bande passante) et le ping (ou temps de latence)&nbsp;de votre connexion &agrave; Internet. Vous pourrez ainsi, en quelques secondes, avoir une id&eacute;e pr&eacute;cise de la qualit&eacute; de votre connexion &agrave; Internet.</p>\r\n');

-- --------------------------------------------------------

--
-- Structure de la table `emploi_du_temps`
--

CREATE TABLE `emploi_du_temps` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `classe_id` int(11) DEFAULT NULL,
  `jour` enum('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche') DEFAULT NULL,
  `heure_debut` time DEFAULT NULL,
  `heure_fin` time DEFAULT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `google_meet` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `emploi_du_temps`
--

INSERT INTO `emploi_du_temps` (`id`, `user_id`, `module_id`, `classe_id`, `jour`, `heure_debut`, `heure_fin`, `date_debut`, `date_fin`, `google_meet`) VALUES
(4, 52, 23, 12, 'Lundi', '13:00:00', '17:00:00', '2024-06-18', '2024-12-30', 'meet.google.com/zxn-dnrx-cvd'),
(7, 52, 23, 12, 'Lundi', '13:00:00', '17:00:00', '2024-06-18', '2024-12-30', 'meet.google.com/zxn-dnrx-cvd'),
(8, 52, 23, 12, 'Lundi', '13:00:00', '17:00:00', '2024-06-18', '2024-12-30', 'meet.google.com/zxn-dnrx-cvd'),
(9, 52, 23, 12, 'Lundi', '13:00:00', '17:00:00', '2024-06-18', '2024-12-30', 'meet.google.com/zxn-dnrx-cvd'),
(39, 52, 23, 12, 'Lundi', '09:33:00', '09:33:00', '2024-06-20', '2024-06-21', 'meet.google.com/zxn-dnrx-cvd'),
(58, 52, 23, 12, 'Mardi', '09:43:00', '12:44:00', '2024-06-20', '2024-07-07', 'meet.google.com/zxn-dnrx-cvd'),
(59, 52, 23, 12, 'Mercredi', '09:46:00', '12:46:00', '2024-06-20', '2024-07-07', 'meet.google.com/zxn-dnrx-cvd'),
(60, 52, 23, 12, 'Lundi', '08:59:00', '12:59:00', '2024-06-20', '2024-06-22', 'meet.google.com/zxn-dnrx-cvd'),
(61, 52, 23, 12, 'Vendredi', '10:16:00', '14:17:00', '2024-06-20', '2024-06-21', 'meet.google.com/zxn-dnrx-cvd'),
(62, 52, 23, 12, 'Jeudi', '09:24:00', '12:24:00', '2024-06-19', '2024-07-07', 'meet.google.com/zxn-dnrx-cvd'),
(63, 52, 23, 12, 'Mardi', '10:26:00', '10:26:00', '2024-06-20', '2024-06-21', 'meet.google.com/zxn-dnrx-cvd'),
(64, NULL, NULL, NULL, 'Mercredi', '10:17:00', '12:17:00', '2024-06-20', '2024-07-07', 'meet.google.com/zxn-dnrx-cvd'),
(65, NULL, NULL, NULL, 'Mercredi', '10:17:00', '12:17:00', '2024-06-20', '2024-07-07', 'meet.google.com/zxn-dnrx-cvd'),
(66, NULL, NULL, NULL, 'Vendredi', '10:23:00', '13:23:00', '2024-06-21', '2024-07-07', 'meet.google.com/zxn-dnrx-cvd'),
(67, NULL, NULL, NULL, 'Vendredi', '10:23:00', '13:23:00', '2024-06-21', '2024-07-07', 'meet.google.com/zxn-dnrx-cvd'),
(68, 52, 23, 12, 'Jeudi', '10:27:00', '14:27:00', '2024-06-20', '2024-07-07', 'meet.google.com/zxn-dnrx-cvd'),
(69, 52, 23, 12, 'Mercredi', '10:31:00', '14:31:00', '2024-06-19', '2024-07-06', 'meet.google.com/zxn-dnrx-cvd'),
(70, 52, 23, 12, 'Mardi', '10:34:00', '14:34:00', '2024-06-19', '2024-07-07', 'meet.google.com/zxn-dnrx-cvd'),
(71, 52, 23, 12, 'Mercredi', '10:37:00', '14:37:00', '2024-06-20', '2024-07-05', 'meet.google.com/zxn-dnrx-cvd'),
(72, 52, 23, 12, 'Vendredi', '10:42:00', '14:42:00', '2024-06-20', '2024-07-07', 'meet.google.com/zxn-dnrx-cvd');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `type` enum('qcm','file') NOT NULL,
  `question` varchar(255) DEFAULT NULL,
  `answer_a` varchar(255) DEFAULT NULL,
  `answer_b` varchar(255) DEFAULT NULL,
  `answer_c` varchar(255) DEFAULT NULL,
  `correct_answer` enum('A','B','C') DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `available_until` datetime DEFAULT NULL,
  `available_from` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `evaluations`
--

INSERT INTO `evaluations` (`id`, `course_id`, `type`, `question`, `answer_a`, `answer_b`, `answer_c`, `correct_answer`, `file_name`, `file_path`, `available_until`, `available_from`) VALUES
(1, 1, 'qcm', 'Qu\'est ce que l\'intelligence artificielle', 'tout comportement comparable à celui d’un être humain présenté par une machine ou un système', 'réflexion stratégique ou d’un autre type d’intelligence artificielle', 'l’IA fait désormais partie de notre vie quotidienne', 'A', NULL, NULL, NULL, NULL),
(2, 1, 'qcm', 'Qu\'est ce que l\'intelligence artificielle', 'tout comportement comparable à celui d’un être humain présenté par une machine ou un système', 'réflexion stratégique ou d’un autre type d’intelligence artificielle', 'l’IA fait désormais partie de notre vie quotidienne', 'A', NULL, NULL, '2024-06-14 11:23:00', '2024-06-13 11:23:00'),
(3, 1, 'file', NULL, NULL, NULL, NULL, NULL, 'Gestion des taches avec laravel.docx', '../uploads/Gestion des taches avec laravel.docx', '2024-06-14 11:26:00', '2024-06-13 11:26:00'),
(4, 1, 'qcm', 'Qu’est-ce que la sécurité informatique ?', 'La sécurité informatique consiste à protéger les actifs informatiques d\'une organisation', 'La sécurité informatique est souvent confondue avec la cybersécurité,', 'l\'ensemble de l\'infrastructure technique d\'une organisation', 'A', NULL, NULL, '2024-06-16 14:33:00', '2024-06-15 14:32:00'),
(5, 1, 'qcm', 'Qu\'est-ce que le Marketing ? ', 'Cette discipline est centrée sur l’étude des comportements du marché', 'Le marketing est le processus qui consiste à intéresser des clients potentiels à vos produits et services', 'La définition du marketing est l’action ou l’activité de promotion et de vente de produits ou de services', 'B', NULL, NULL, '2024-06-16 14:33:00', '2024-06-15 14:32:00'),
(7, 3, 'qcm', 'Qu\'est ce Big Data', 'permet d’extraire des informations précieuses à partir des données qui peuvent être utilisées pour améliorer les processus décisionnels', 'le Big Data peut être utilisé pour améliorer les prévisions de ventes, optimiser les processus de production', 'La quantité de données générées chaque jour est en constante augmentation', 'A', NULL, NULL, '2024-06-15 17:56:00', '2024-06-13 17:56:00'),
(8, 4, 'qcm', 'Qu\'est ce Big Data', 'permet d’extraire des informations précieuses à partir des données qui peuvent être utilisées pour améliorer les processus décisionnels', 'le Big Data peut être utilisé pour améliorer les prévisions de ventes, optimiser les processus de production', 'La quantité de données générées chaque jour est en constante augmentation', 'B', NULL, NULL, '2024-06-15 18:38:00', '2024-06-13 18:38:00');

-- --------------------------------------------------------

--
-- Structure de la table `evaluation_results`
--

CREATE TABLE `evaluation_results` (
  `id` int(11) NOT NULL,
  `evaluation_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `grade` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `evaluation_results`
--

INSERT INTO `evaluation_results` (`id`, `evaluation_id`, `student_id`, `grade`) VALUES
(1, 2, 51, 12.00),
(2, 3, 51, 13.00);

-- --------------------------------------------------------

--
-- Structure de la table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `grade` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `modules`
--

INSERT INTO `modules` (`id`, `name`, `description`, `teacher_id`) VALUES
(23, 'Culture Générale Informatique', 'Information generale sur l\'informatique', 52);

-- --------------------------------------------------------

--
-- Structure de la table `module_assignments`
--

CREATE TABLE `module_assignments` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `module_assignments`
--

INSERT INTO `module_assignments` (`id`, `class_id`, `module_id`) VALUES
(29, 12, 23);

-- --------------------------------------------------------

--
-- Structure de la table `periode`
--

CREATE TABLE `periode` (
  `id` int(11) NOT NULL,
  `libelle` varchar(12) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `periode`
--

INSERT INTO `periode` (`id`, `libelle`, `description`) VALUES
(10, '2024-2025', '1ere session de la licence professionnelle ');

-- --------------------------------------------------------

--
-- Structure de la table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `schedule_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `prenom` varchar(25) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `prenom`, `telephone`, `password`, `email`, `role`, `created_at`, `status`) VALUES
(4, 'admin', 'Admin', '07918551', '21232f297a57a5a743894a0e4a801fc3', 'admin@gmail.com', 'admin', '2024-06-10 12:57:20', 'active'),
(49, 'Traore', 'Mouna Myriam', '0777562461', '81dc9bdb52d04dc20036dbd8313ed055', 'mounamyriam3@gmail.com', 'admin', '2024-06-11 16:22:48', 'active'),
(51, 'Ehouman', 'Ange Landry', '0708844123', '81dc9bdb52d04dc20036dbd8313ed055', 'ehouman.ange-landry@eranoveacademy-edu.ci', 'student', '2024-06-12 18:29:28', 'active'),
(52, 'Kouakou', 'Yao Vincent', '0749857206', '81dc9bdb52d04dc20036dbd8313ed055', 'vy@gs2e.ci', 'teacher', '2024-06-12 18:33:22', 'active');

-- --------------------------------------------------------

--
-- Structure de la table `user_assignments`
--

CREATE TABLE `user_assignments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `periode_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_assignments`
--

INSERT INTO `user_assignments` (`id`, `student_id`, `class_id`, `periode_id`) VALUES
(6, 51, 12, 10);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_id` (`module_id`);

--
-- Index pour la table `course_contents`
--
ALTER TABLE `course_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Index pour la table `emploi_du_temps`
--
ALTER TABLE `emploi_du_temps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `module_id` (`module_id`),
  ADD KEY `classe_id` (`classe_id`);

--
-- Index pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Index pour la table `evaluation_results`
--
ALTER TABLE `evaluation_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluation_id` (`evaluation_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Index pour la table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Index pour la table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Index pour la table `module_assignments`
--
ALTER TABLE `module_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_id` (`module_id`),
  ADD KEY `module_assignments_ibfk_1` (`class_id`);

--
-- Index pour la table `periode`
--
ALTER TABLE `periode`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `user_assignments`
--
ALTER TABLE `user_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `periode_id` (`periode_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `course_contents`
--
ALTER TABLE `course_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `emploi_du_temps`
--
ALTER TABLE `emploi_du_temps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT pour la table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `evaluation_results`
--
ALTER TABLE `evaluation_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `module_assignments`
--
ALTER TABLE `module_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `periode`
--
ALTER TABLE `periode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pour la table `user_assignments`
--
ALTER TABLE `user_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`);

--
-- Contraintes pour la table `course_contents`
--
ALTER TABLE `course_contents`
  ADD CONSTRAINT `course_contents_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `emploi_du_temps`
--
ALTER TABLE `emploi_du_temps`
  ADD CONSTRAINT `emploi_du_temps_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `emploi_du_temps_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`),
  ADD CONSTRAINT `emploi_du_temps_ibfk_3` FOREIGN KEY (`classe_id`) REFERENCES `classes` (`id`);

--
-- Contraintes pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `evaluation_results`
--
ALTER TABLE `evaluation_results`
  ADD CONSTRAINT `fk_evaluation_results_evaluation_id` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_evaluation_results_student_id` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`);

--
-- Contraintes pour la table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modules_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `module_assignments`
--
ALTER TABLE `module_assignments`
  ADD CONSTRAINT `module_assignments_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `module_assignments_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`);

--
-- Contraintes pour la table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`);

--
-- Contraintes pour la table `user_assignments`
--
ALTER TABLE `user_assignments`
  ADD CONSTRAINT `user_assignments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_assignments_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `user_assignments_ibfk_3` FOREIGN KEY (`periode_id`) REFERENCES `periode` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
