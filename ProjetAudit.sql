-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `ProjetAudit` /*!40100 DEFAULT CHARACTER SET utf8 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ProjetAudit`;

CREATE TABLE `Actualites` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `User` int NOT NULL,
  `Actualite` tinytext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Eval_Number` int DEFAULT NULL,
  `Audit_Number` int DEFAULT NULL,
  `Facility` int DEFAULT NULL,
  `Auditor` int DEFAULT NULL,
  `Assistant1` int DEFAULT NULL,
  `Assistant2` int DEFAULT NULL,
  `DateAndHour` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8;

INSERT INTO `Actualites` (`ID`, `User`, `Actualite`, `Eval_Number`, `Audit_Number`, `Facility`, `Auditor`, `Assistant1`, `Assistant2`, `DateAndHour`) VALUES
(118,	6,	'a été audité !',	NULL,	1,	9,	3,	4,	9,	'2021-03-28 16:08:15');

CREATE TABLE `Audits` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Audit_Number` int NOT NULL,
  `User_ID` int NOT NULL,
  `DateAndHour` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Completed` bit(1) NOT NULL,
  `Auditor` int NOT NULL,
  `Assistant1` int DEFAULT NULL,
  `Assistant2` int DEFAULT NULL,
  `Facility` int DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;

INSERT INTO `Audits` (`ID`, `Audit_Number`, `User_ID`, `DateAndHour`, `Completed`, `Auditor`, `Assistant1`, `Assistant2`, `Facility`) VALUES
(78,	1,	6,	'2021-03-28 16:08:15',	CONV('1', 2, 10) + 0,	3,	4,	9,	9);

CREATE TABLE `AuditsReports` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Audit_Number` int NOT NULL,
  `Question` int NOT NULL,
  `Report` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Observation` tinytext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `User_ID` int NOT NULL,
  `Auditor` int NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5851 DEFAULT CHARSET=utf8;

INSERT INTO `AuditsReports` (`ID`, `Audit_Number`, `Question`, `Report`, `Observation`, `User_ID`, `Auditor`) VALUES
(5779,	1,	1,	'Conforme',	NULL,	6,	3),
(5780,	1,	3,	'Conforme',	NULL,	6,	3),
(5781,	1,	4,	'NC',	'pas eu le temps',	6,	3),
(5782,	1,	5,	'NA',	NULL,	6,	3),
(5783,	1,	6,	'NDA',	NULL,	6,	3),
(5784,	1,	7,	'Conforme',	NULL,	6,	3),
(5785,	1,	8,	'Conforme',	NULL,	6,	3),
(5786,	1,	9,	'Conforme',	NULL,	6,	3),
(5787,	1,	10,	'Conforme',	NULL,	6,	3),
(5788,	1,	11,	'Conforme',	NULL,	6,	3),
(5789,	1,	12,	'Conforme',	NULL,	6,	3),
(5790,	1,	13,	'Conforme',	NULL,	6,	3),
(5791,	1,	14,	'Conforme',	NULL,	6,	3),
(5792,	1,	15,	'Conforme',	NULL,	6,	3),
(5793,	1,	16,	'Conforme',	NULL,	6,	3),
(5794,	1,	17,	'Conforme',	NULL,	6,	3),
(5795,	1,	18,	'Conforme',	NULL,	6,	3),
(5796,	1,	19,	'Conforme',	NULL,	6,	3),
(5797,	1,	20,	'Conforme',	NULL,	6,	3),
(5798,	1,	21,	'Conforme',	NULL,	6,	3),
(5799,	1,	22,	'Conforme',	NULL,	6,	3),
(5800,	1,	23,	'Conforme',	NULL,	6,	3),
(5801,	1,	24,	'Conforme',	NULL,	6,	3),
(5802,	1,	25,	'Conforme',	NULL,	6,	3),
(5803,	1,	26,	'Conforme',	NULL,	6,	3),
(5804,	1,	27,	'Conforme',	NULL,	6,	3),
(5805,	1,	28,	'Conforme',	NULL,	6,	3),
(5806,	1,	29,	'Conforme',	NULL,	6,	3),
(5807,	1,	30,	'Conforme',	NULL,	6,	3),
(5808,	1,	31,	'Conforme',	NULL,	6,	3),
(5809,	1,	32,	'Conforme',	NULL,	6,	3),
(5810,	1,	33,	'Conforme',	NULL,	6,	3),
(5811,	1,	34,	'Conforme',	NULL,	6,	3),
(5812,	1,	35,	'Conforme',	NULL,	6,	3),
(5813,	1,	36,	'Conforme',	NULL,	6,	3),
(5814,	1,	37,	'Conforme',	NULL,	6,	3),
(5815,	1,	38,	'Conforme',	NULL,	6,	3),
(5816,	1,	39,	'Conforme',	NULL,	6,	3),
(5817,	1,	40,	'Conforme',	NULL,	6,	3),
(5818,	1,	41,	'Conforme',	NULL,	6,	3),
(5819,	1,	42,	'Conforme',	NULL,	6,	3),
(5820,	1,	43,	'Conforme',	NULL,	6,	3),
(5821,	1,	44,	'Conforme',	NULL,	6,	3),
(5822,	1,	45,	'Conforme',	NULL,	6,	3),
(5823,	1,	46,	'Conforme',	NULL,	6,	3),
(5824,	1,	47,	'Conforme',	NULL,	6,	3),
(5825,	1,	48,	'Conforme',	NULL,	6,	3),
(5826,	1,	49,	'Conforme',	NULL,	6,	3),
(5827,	1,	50,	'Conforme',	NULL,	6,	3),
(5828,	1,	51,	'Conforme',	NULL,	6,	3),
(5829,	1,	52,	'Conforme',	NULL,	6,	3),
(5830,	1,	53,	'Conforme',	NULL,	6,	3),
(5831,	1,	54,	'Conforme',	NULL,	6,	3),
(5832,	1,	55,	'Conforme',	NULL,	6,	3),
(5833,	1,	56,	'Conforme',	NULL,	6,	3),
(5834,	1,	57,	'Conforme',	NULL,	6,	3),
(5835,	1,	58,	'Conforme',	NULL,	6,	3),
(5836,	1,	59,	'Conforme',	NULL,	6,	3),
(5837,	1,	60,	'Conforme',	NULL,	6,	3),
(5838,	1,	61,	'Conforme',	NULL,	6,	3),
(5839,	1,	62,	'Conforme',	NULL,	6,	3),
(5840,	1,	63,	'Conforme',	NULL,	6,	3),
(5841,	1,	64,	'Conforme',	NULL,	6,	3),
(5842,	1,	65,	'Conforme',	NULL,	6,	3),
(5843,	1,	66,	'Conforme',	NULL,	6,	3),
(5844,	1,	67,	'Conforme',	NULL,	6,	3),
(5845,	1,	68,	'Conforme',	NULL,	6,	3),
(5846,	1,	69,	'Conforme',	NULL,	6,	3),
(5847,	1,	70,	'Conforme',	NULL,	6,	3),
(5848,	1,	71,	'Conforme',	NULL,	6,	3),
(5849,	1,	72,	'Conforme',	NULL,	6,	3),
(5850,	1,	79,	'Conforme',	NULL,	6,	3);

CREATE TABLE `Autoevaluations` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Evaluation_Number` int NOT NULL,
  `User_ID` int NOT NULL,
  `DateAndHour` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Completed` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=utf8;

INSERT INTO `Autoevaluations` (`ID`, `Evaluation_Number`, `User_ID`, `DateAndHour`, `Completed`) VALUES
(146,	1,	3,	'2021-03-28 19:53:41',	CONV('0', 2, 10) + 0),
(147,	1,	1,	'2021-03-28 23:25:41',	CONV('0', 2, 10) + 0);

CREATE TABLE `BugReports` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Bug` tinytext NOT NULL,
  `ErrorCode` tinytext,
  `DateAndHour` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SubmittedBy` int NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


CREATE TABLE `CategoriesQuestionsAudit` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

INSERT INTO `CategoriesQuestionsAudit` (`ID`, `Name`) VALUES
(1,	'Organisation de la formation'),
(2,	'Accueil - Intégration des stagiaires'),
(3,	'Suivi des parcours de formation '),
(4,	'Organisation de la certification'),
(5,	'Sécurité'),
(6,	'Traçabilité et archivage'),
(7,	'Fin de formation'),
(8,	'Amélioration continue');

CREATE TABLE `CategoriesQuestionsAutoevaluation` (
  `Category` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`Category`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO `CategoriesQuestionsAutoevaluation` (`Category`, `Name`) VALUES
(1,	'Organisation de la formation'),
(2,	'Accueil - Intégration des stagiaires'),
(3,	'Suivi des parcours de formation'),
(4,	'Organisation de la certification'),
(5,	'Traçabilité et archivage'),
(6,	'Fin de formation'),
(7,	'Amélioration continue');

CREATE TABLE `Documents` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Document` varchar(255) NOT NULL,
  `Link` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

INSERT INTO `Documents` (`ID`, `Document`, `Link`) VALUES
(41,	'Audit formateur',	'Audit_formateur.pdf'),
(42,	'Autoevaluation formateur',	'Autoevaluation_formateur.pdf');

CREATE TABLE `Facilities` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Localisation` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

INSERT INTO `Facilities` (`ID`, `Localisation`) VALUES
(1,	'Amiens'),
(2,	'Arras'),
(3,	'Calais'),
(4,	'Cambrais'),
(5,	'Compiègne'),
(6,	'Creil'),
(7,	'Direction régionale'),
(8,	'Douai'),
(9,	'Dunkerque'),
(10,	'Hazebrouck'),
(11,	'Laon'),
(12,	'Liévin'),
(13,	'Lomme'),
(14,	'Maubeuge'),
(15,	'Roubaix'),
(16,	'Valenciennes');

CREATE TABLE `QuestionsAudit` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `CreatedBy` int NOT NULL,
  `DateAndHour` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Question` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Evidence` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Active` bit(1) NOT NULL DEFAULT b'1',
  `Category` int NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;

INSERT INTO `QuestionsAudit` (`ID`, `CreatedBy`, `DateAndHour`, `Question`, `Evidence`, `Active`, `Category`) VALUES
(1,	1,	'2021-03-17 14:10:18',	'Connaissance des engagements contractuels pris avec l\'AFPA ?',	'Cahier des charges PRF',	CONV('1', 2, 10) + 0,	1),
(2,	1,	'2021-03-28 14:29:47',	'Ressources accessibles de la formation ?',	'Kit banques de données ingénieries (BNRA BNI)',	CONV('0', 2, 10) + 0,	1),
(3,	1,	'2021-03-10 08:29:46',	'Planification des moyens ?',	'Revue de lancement',	CONV('1', 2, 10) + 0,	1),
(4,	1,	'2021-03-10 08:29:46',	'Comment est réalisé la planification des activités ?',	'Revue de lancement',	CONV('1', 2, 10) + 0,	1),
(5,	1,	'2021-03-10 08:29:46',	'Pouvez vous me montrer la planification des activités ?',	'Calendrier de la formation',	CONV('1', 2, 10) + 0,	1),
(6,	1,	'2021-03-10 08:29:46',	'Les dates de PAE sont elles définies ?',	'Calendrier de la formation',	CONV('1', 2, 10) + 0,	1),
(7,	1,	'2021-03-10 08:34:37',	'Existe-t-il un rituel d\'accueil ?',	'Présentation formation et métier , REAC, RE, formalités administratives',	CONV('1', 2, 10) + 0,	2),
(8,	1,	'2021-03-10 08:35:00',	'Présentation du livret d\'accueil ?',	'Livret d\'accueil',	CONV('1', 2, 10) + 0,	2),
(9,	1,	'2021-03-10 08:35:16',	'Présentation du règlement intérieur ?',	'Règlement intérieur',	CONV('1', 2, 10) + 0,	2),
(10,	1,	'2021-03-10 08:35:58',	'Avez-vous une liste des associations pour les aides (hébergement, transport) ?',	'Liste des associations',	CONV('1', 2, 10) + 0,	2),
(11,	1,	'2021-03-10 08:36:16',	'La première semaine, l\'élection des délégués stagiaires est elle faite ?',	'PV élections délégués stagiaires',	CONV('1', 2, 10) + 0,	2),
(12,	1,	'2021-03-10 08:36:31',	'Existe-t-il une traçabilité des EPI ?',	'Liste des EPI',	CONV('1', 2, 10) + 0,	2),
(13,	1,	'2021-03-10 08:36:51',	'Avez-vous du matériel de prêt ?',	'Décharge matériel de prêt',	CONV('1', 2, 10) + 0,	2),
(14,	1,	'2021-03-10 08:37:06',	'Existe-t-il une traçabilité du matériel de prêt ?',	'Décharge matériel de prêt',	CONV('1', 2, 10) + 0,	2),
(15,	1,	'2021-03-10 08:37:30',	'Présentation du parcours ?',	'Etat d\'avancement groupe et individuel',	CONV('1', 2, 10) + 0,	2),
(16,	1,	'2021-03-10 08:39:38',	'Présentation des évaluations (forme, contexte) ?',	'ECF, DP, certification',	CONV('1', 2, 10) + 0,	2),
(17,	1,	'2021-03-10 08:39:58',	'Présentation du règlement général de session (REGS) ?',	'Affichage du REGS dans tous les espaces d\'examen',	CONV('1', 2, 10) + 0,	2),
(18,	1,	'2021-03-10 08:40:16',	'Présentation du Référentiel emploi activité ?',	'REAC',	CONV('1', 2, 10) + 0,	2),
(19,	1,	'2021-03-10 08:40:30',	'Présentation du Référentiel d\'évaluation ?',	'RE ou RC',	CONV('1', 2, 10) + 0,	2),
(20,	1,	'2021-03-10 08:40:50',	'Existe-t-il un état d\'avancement du programme de la formation ?',	'Etat d\'avancement de groupe affiché et rempli',	CONV('1', 2, 10) + 0,	2),
(21,	1,	'2021-03-10 08:40:59',	'L\'état d\'avancement du programme de la formation est-il rempli ?',	'Etat d\'avancement de groupe affiché et rempli',	CONV('1', 2, 10) + 0,	2),
(22,	1,	'2021-03-10 08:41:25',	'Existe-t-il un état d\'avancement individuel ?',	'Etat d\'avancement individuel rempli',	CONV('1', 2, 10) + 0,	2),
(23,	1,	'2021-03-10 08:41:42',	'L\'état d\'avancement individuel est-il rempli ?',	'Etat d\'avancement individuel rempli',	CONV('1', 2, 10) + 0,	2),
(24,	1,	'2021-03-10 08:43:26',	'Existe-t-il une évaluation des prérequis ?',	'ECAP (évaluation positionnement métier)',	CONV('1', 2, 10) + 0,	3),
(25,	1,	'2021-03-10 08:43:59',	'Evaluez vous le savoir être du stagiaire ?',	'Fiche comportement',	CONV('1', 2, 10) + 0,	3),
(26,	1,	'2021-03-10 08:44:18',	'Adaptez vous le parcours aux stagiaires ?',	'Individualisation des parcours',	CONV('1', 2, 10) + 0,	3),
(27,	1,	'2021-03-10 08:44:36',	'Existe-t-il un rituel pour informer les stagiaires de leurs progressions ?',	'Etat d\'avancement individuel',	CONV('1', 2, 10) + 0,	3),
(28,	1,	'2021-03-10 08:45:26',	'Rituels de surveillance ?',	'Evaluation en cours de parcours non obligatoire',	CONV('1', 2, 10) + 0,	3),
(29,	1,	'2021-03-10 08:45:42',	'Possédez-vous des ressources ?',	'Métis, banque d\'informations',	CONV('1', 2, 10) + 0,	3),
(30,	1,	'2021-03-10 08:45:58',	'Existe-t-il un kit formateur ?',	'Kit formateur',	CONV('1', 2, 10) + 0,	3),
(31,	1,	'2021-03-10 08:46:15',	'Sans ressources existantes, créez vous vos propres ressources ?',	'Cours créés',	CONV('1', 2, 10) + 0,	3),
(32,	1,	'2021-03-10 08:46:42',	'Existe-t-il une traçabilité pour vérifier de la présence de vos stagiaires ?',	'Feuille émargement',	CONV('1', 2, 10) + 0,	3),
(33,	1,	'2021-03-10 08:46:56',	'En cas d\'absence, existe-t-il une démarche ?',	'Fiche demande d\'absence',	CONV('1', 2, 10) + 0,	3),
(34,	1,	'2021-03-10 08:47:21',	'Existe-t-il une évaluation de la satisfaction du groupe a mi parcours ?',	'Espace de dialogue intermédiaire',	CONV('1', 2, 10) + 0,	3),
(35,	1,	'2021-03-10 08:47:49',	'Existe-t-il une évaluation de la satisfaction stagiaire a mi parcours ?',	'Enquête de satisfaction mi parcours',	CONV('1', 2, 10) + 0,	3),
(36,	1,	'2021-03-10 08:48:11',	'Existe-t-il des évaluations en cours de parcours ?',	'ECF, QCM',	CONV('1', 2, 10) + 0,	3),
(37,	1,	'2021-03-10 08:48:30',	'Existe-t-il des évaluations à la fin du parcours ?',	'ECF, DP, certification',	CONV('1', 2, 10) + 0,	3),
(38,	1,	'2021-03-10 08:49:18',	'Accompagnez vous les stagiaires vers l\'emploi ?',	'CV + LM fait & offres d\'emploi affichée',	CONV('1', 2, 10) + 0,	3),
(39,	1,	'2021-03-10 08:49:34',	'Possédez-vous une liste de votre réseau de partenaires ?',	'Liste formalisée d\'entreprises',	CONV('1', 2, 10) + 0,	3),
(40,	1,	'2021-03-10 08:49:47',	'Donnez-vous la liste d\'entreprises ?',	'Liste formalisée d\'entreprises',	CONV('1', 2, 10) + 0,	3),
(41,	1,	'2021-03-19 09:29:20',	'Remplissez vous la fiche projet présentation en entreprise (objectifs) ?',	'Fiche projet présentation entreprise avec les objectifs',	CONV('1', 2, 10) + 0,	3),
(42,	1,	'2021-03-10 08:50:24',	'Réalisez vous le suivi de la PAE ?',	'Fiche évaluation tuteur PAE',	CONV('1', 2, 10) + 0,	3),
(43,	1,	'2021-03-10 08:50:38',	'Présentation du déroulé de session de la certification ?',	'Déroulé de session',	CONV('1', 2, 10) + 0,	4),
(44,	1,	'2021-03-10 08:51:27',	'Présentez vous la possibilité d\'une adaptation de la certification au TH ?',	'Adaptation TH',	CONV('1', 2, 10) + 0,	4),
(45,	1,	'2021-03-10 08:51:40',	'Présentation de l\'organisation de la certification ?',	'Attestation certification formation stagiaire',	CONV('1', 2, 10) + 0,	4),
(46,	1,	'2021-03-10 08:51:57',	'Présentez vous la réalisation d\'un dossier professionnel DP ?',	'Exemple de DP',	CONV('1', 2, 10) + 0,	4),
(47,	1,	'2021-03-10 08:52:10',	'Gérez vous les conventions ?',	'Convention de certification',	CONV('1', 2, 10) + 0,	4),
(48,	1,	'2021-03-10 08:52:39',	'Précisez vous la gestion de la mise en œuvre de l\'ECF ?',	'ECF à chaque fin de module, noter l\'évaluation mis en œuvre, deux passages possibles, signature RF, signature formateur, signature stagiaire',	CONV('1', 2, 10) + 0,	4),
(49,	1,	'2021-03-10 08:52:59',	'Présentation du règlement général de session (REGS) ?',	'Affichage du REGS dans tous les espaces d\'examen',	CONV('1', 2, 10) + 0,	4),
(50,	1,	'2021-03-10 08:53:13',	'Présentez vous le référentiel d\'évaluation ?',	'RE ou RC',	CONV('1', 2, 10) + 0,	4),
(51,	1,	'2021-03-10 08:53:25',	'Présentez vous le plateau technique (PT) ?',	'PT',	CONV('1', 2, 10) + 0,	4),
(52,	1,	'2021-03-10 08:53:41',	'Participez vous à la réalisation du DU ?',	'DU du centre',	CONV('1', 2, 10) + 0,	5),
(53,	1,	'2021-03-10 08:54:37',	'Connaissez-vous le DU de votre formation ?',	'DU du centre',	CONV('1', 2, 10) + 0,	5),
(54,	1,	'2021-03-10 08:55:51',	'Avez-vous un défibrillateur ?',	NULL,	CONV('1', 2, 10) + 0,	5),
(55,	1,	'2021-03-10 08:57:21',	'Connaissez-vous l\'emplacement du défibrillateur ?',	'Site internet',	CONV('1', 2, 10) + 0,	5),
(56,	1,	'2021-03-10 08:57:49',	'Connaissez vous l\'emplacement du point de rassemblement ?',	'Plan d\'évacuation incendie',	CONV('1', 2, 10) + 0,	5),
(57,	1,	'2021-03-10 08:58:06',	'Possédez vous des affichages sécurité ?',	'Affichage EPI, plan intervention incendie',	CONV('1', 2, 10) + 0,	5),
(58,	1,	'2021-03-10 08:58:24',	'La trousse de secours est elle à jour ?',	NULL,	CONV('1', 2, 10) + 0,	5),
(59,	1,	'2021-03-10 08:58:52',	'Vérifiez vous les machines annuellement (étiquettes) ?',	'Etiquette de vérification sur machine et rapport',	CONV('1', 2, 10) + 0,	5),
(60,	1,	'2021-03-10 08:59:05',	'Les EPI sont ils portés ?',	NULL,	CONV('1', 2, 10) + 0,	5),
(61,	1,	'2021-03-10 08:59:57',	'Archivez vous les informations des stagiaires confidentielles ?',	NULL,	CONV('1', 2, 10) + 0,	6),
(62,	1,	'2021-03-10 09:01:45',	'Connaissez-vous les documents à archiver à l\'administration (dans le G-DOS) ?',	'Check-list formateur',	CONV('1', 2, 10) + 0,	6),
(63,	1,	'2021-03-10 09:02:07',	'Remontez vous l\'absence d\'un stagiaire à l\'administration ?',	'48 heures retour du stagiaire',	CONV('1', 2, 10) + 0,	6),
(64,	1,	'2021-03-10 09:02:30',	'Analysez vous les retards et absences ?',	NULL,	CONV('1', 2, 10) + 0,	6),
(65,	1,	'2021-03-10 09:02:44',	'Archivez vous les évaluations ?',	NULL,	CONV('1', 2, 10) + 0,	6),
(66,	1,	'2021-03-10 09:03:11',	'Existe-t-il une attestation de compétences pour les personnes ne validant que partiellement la certification ?',	'Attestation de compétences acquises',	CONV('1', 2, 10) + 0,	6),
(67,	1,	'2021-03-10 09:03:49',	'Participation au bilan de fin de formation ?',	NULL,	CONV('1', 2, 10) + 0,	7),
(68,	1,	'2021-03-10 09:04:56',	'Réalisez vous l\'enquête de satisfaction des stagiaires en fin de formation ?',	'Enquête de satisfaction fin de parcours',	CONV('1', 2, 10) + 0,	7),
(69,	1,	'2021-03-10 09:05:09',	'Avez-vous connaissances des résultats de satisfaction ?',	'Résultats de l\'enquête de satisfaction',	CONV('1', 2, 10) + 0,	7),
(70,	1,	'2021-03-10 09:05:25',	'Existe-t-il une évaluation de la satisfaction du groupe à la fin du parcours (espace de dialogue) ?',	'Espace de dialogue final',	CONV('1', 2, 10) + 0,	7),
(71,	1,	'2021-03-10 09:05:37',	'Remontez vous les réclamations au RF ?',	'Mail',	CONV('1', 2, 10) + 0,	8),
(72,	1,	'2021-03-10 09:05:54',	'Existe-t-il une traçabilité des réclamations ?',	'Mail',	CONV('1', 2, 10) + 0,	8),
(79,	1,	'2021-03-19 08:26:18',	'Participez vous au bilan interne ?	',	'Date bilan',	CONV('1', 2, 10) + 0,	8);

CREATE TABLE `QuestionsAutoevaluation` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `CreatedBy` int NOT NULL,
  `DateAndHour` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Question` varchar(255) NOT NULL,
  `Category` int NOT NULL,
  `Active` bit(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8;

INSERT INTO `QuestionsAutoevaluation` (`ID`, `CreatedBy`, `DateAndHour`, `Question`, `Category`, `Active`) VALUES
(40,	1,	'2021-02-25 14:21:01',	'Utilisation du référentiel emploi activité REAC ?',	1,	CONV('1', 2, 10) + 0),
(41,	1,	'2021-02-24 14:32:16',	'Application du référentiel d\'évaluation (RE ou RC) ?',	1,	CONV('1', 2, 10) + 0),
(42,	1,	'2021-02-24 14:32:16',	'Application du référentiel plateau technique (PT) ?',	1,	CONV('1', 2, 10) + 0),
(43,	1,	'2021-02-24 14:32:16',	'Utilisation de la fiche individuelle stagiaire ?',	2,	CONV('1', 2, 10) + 0),
(44,	1,	'2021-02-24 14:32:16',	'Application de la liste des EPI donnés ?',	2,	CONV('1', 2, 10) + 0),
(45,	1,	'2021-02-24 14:32:16',	'Emploi de la décharge de matériel de prêt ?',	2,	CONV('1', 2, 10) + 0),
(46,	1,	'2021-02-24 14:32:16',	'Utilisation de la liste des associations ?',	2,	CONV('1', 2, 10) + 0),
(47,	1,	'2021-02-24 14:32:16',	'Application des élections délégués stagiaires ?',	2,	CONV('1', 2, 10) + 0),
(48,	1,	'2021-02-24 14:32:16',	'Emploi du règlement intérieur stagiaire ?',	2,	CONV('1', 2, 10) + 0),
(49,	1,	'2021-02-24 14:32:16',	'Utilisation du livret d\'accueil ?',	2,	CONV('1', 2, 10) + 0),
(50,	1,	'2021-02-24 14:32:16',	'Application de l\'état avancement programme de formation (groupe) ?',	2,	CONV('1', 2, 10) + 0),
(51,	1,	'2021-02-24 14:32:16',	'Emploi de l\'état d\'avancement individuel ?',	2,	CONV('1', 2, 10) + 0),
(52,	1,	'2021-02-24 14:32:16',	'Utilisation de la fiche comportement ?',	3,	CONV('1', 2, 10) + 0),
(53,	1,	'2021-02-24 14:32:16',	'Application de la feuille d\'émargement ?',	3,	CONV('1', 2, 10) + 0),
(54,	1,	'2021-02-24 14:32:16',	'Emploi de la fiche d\'information d\'absence ?',	3,	CONV('1', 2, 10) + 0),
(55,	1,	'2021-02-24 14:32:16',	'Utilisation du lien vers l\'enquête intermédiaire ?',	3,	CONV('1', 2, 10) + 0),
(56,	1,	'2021-02-24 14:32:16',	'Application d\'un espace de dialogue intermédiaire ?',	3,	CONV('1', 2, 10) + 0),
(57,	1,	'2021-02-24 14:32:16',	'Emploi de l\'état d\'avancement individuel ?',	3,	CONV('1', 2, 10) + 0),
(58,	1,	'2021-02-24 14:32:16',	'Utilisation de la liste formalisée des entreprises ?',	3,	CONV('1', 2, 10) + 0),
(59,	1,	'2021-02-24 14:32:16',	'Utilisation de la présentation entreprise (objectifs PE) ?',	3,	CONV('1', 2, 10) + 0),
(60,	1,	'2021-02-24 14:32:16',	'Application de la convention PE ?',	3,	CONV('1', 2, 10) + 0),
(61,	1,	'2021-02-24 14:32:16',	'Emploi de l\'évaluation tuteur PE présentiel ?',	3,	CONV('1', 2, 10) + 0),
(62,	1,	'2021-02-24 14:32:16',	'Utilisation de l\'attestation certification formation stagiaire ?',	4,	CONV('1', 2, 10) + 0),
(63,	1,	'2021-02-24 14:32:16',	'Emploi de l\'aménagement modalité épreuves : adaptation TH ?',	4,	CONV('1', 2, 10) + 0),
(64,	1,	'2021-02-24 14:32:16',	'Utilisation de l\'évaluation ECF ?',	4,	CONV('1', 2, 10) + 0),
(65,	1,	'2021-02-24 14:32:16',	'Application du dossier professionnel ?',	4,	CONV('1', 2, 10) + 0),
(66,	1,	'2021-02-24 14:32:16',	'Utilisation des convocations stagiaires (1 mois avant l\'examen) ?',	4,	CONV('1', 2, 10) + 0),
(67,	1,	'2021-02-24 14:32:16',	'Application du référentiel d\'évaluation (RE ou RC) + PT ?',	4,	CONV('1', 2, 10) + 0),
(68,	1,	'2021-02-24 14:32:16',	'Utilisation du réglement certification (affichage dans tous les espaces examen) ?',	4,	CONV('1', 2, 10) + 0),
(69,	1,	'2021-02-24 14:32:16',	'Emploi du déroulé de session (4 mois avant l\'examen) ?',	4,	CONV('1', 2, 10) + 0),
(70,	1,	'2021-02-24 14:32:16',	'Emploi de l\'attestation de compétences acquises (pour TB / MS) ?',	5,	CONV('1', 2, 10) + 0),
(71,	1,	'2021-02-24 14:32:16',	'Emploi d\'un espace de dialogue final ?',	6,	CONV('1', 2, 10) + 0),
(72,	1,	'2021-02-24 14:32:16',	'Utilisation du lien vers l\'enquête finale ?',	6,	CONV('1', 2, 10) + 0),
(73,	1,	'2021-02-24 14:32:16',	'Emploi du bilan de fin de formation ?',	6,	CONV('1', 2, 10) + 0),
(74,	1,	'2021-02-24 14:32:16',	'Application des résultats et traçabilité des réclamations ?',	7,	CONV('1', 2, 10) + 0),
(77,	1,	'2021-02-25 13:13:15',	'Utilisation du compte rendu bilan interne ?',	7,	CONV('1', 2, 10) + 0);

CREATE TABLE `RaisonsNonConformitesAutoevaluation` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `User` int NOT NULL,
  `Question_ID` int NOT NULL,
  `Reason` tinytext NOT NULL,
  `Eval_Number` int NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=173 DEFAULT CHARSET=utf8;


CREATE TABLE `ResultatsAutoevaluations` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Evaluation_Number` int NOT NULL,
  `Question` int NOT NULL,
  `Answer` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `User_ID` int NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4925 DEFAULT CHARSET=utf8;

INSERT INTO `ResultatsAutoevaluations` (`ID`, `Evaluation_Number`, `Question`, `Answer`, `User_ID`) VALUES
(4853,	1,	40,	NULL,	3),
(4854,	1,	41,	NULL,	3),
(4855,	1,	42,	NULL,	3),
(4856,	1,	43,	NULL,	3),
(4857,	1,	44,	NULL,	3),
(4858,	1,	45,	NULL,	3),
(4859,	1,	46,	NULL,	3),
(4860,	1,	47,	NULL,	3),
(4861,	1,	48,	NULL,	3),
(4862,	1,	49,	NULL,	3),
(4863,	1,	50,	NULL,	3),
(4864,	1,	51,	NULL,	3),
(4865,	1,	52,	NULL,	3),
(4866,	1,	53,	NULL,	3),
(4867,	1,	54,	NULL,	3),
(4868,	1,	55,	NULL,	3),
(4869,	1,	56,	NULL,	3),
(4870,	1,	57,	NULL,	3),
(4871,	1,	58,	NULL,	3),
(4872,	1,	59,	NULL,	3),
(4873,	1,	60,	NULL,	3),
(4874,	1,	61,	NULL,	3),
(4875,	1,	62,	NULL,	3),
(4876,	1,	63,	NULL,	3),
(4877,	1,	64,	NULL,	3),
(4878,	1,	65,	NULL,	3),
(4879,	1,	66,	NULL,	3),
(4880,	1,	67,	NULL,	3),
(4881,	1,	68,	NULL,	3),
(4882,	1,	69,	NULL,	3),
(4883,	1,	70,	NULL,	3),
(4884,	1,	71,	NULL,	3),
(4885,	1,	72,	NULL,	3),
(4886,	1,	73,	NULL,	3),
(4887,	1,	74,	NULL,	3),
(4888,	1,	77,	NULL,	3),
(4889,	1,	40,	NULL,	1),
(4890,	1,	41,	NULL,	1),
(4891,	1,	42,	NULL,	1),
(4892,	1,	43,	NULL,	1),
(4893,	1,	44,	NULL,	1),
(4894,	1,	45,	NULL,	1),
(4895,	1,	46,	NULL,	1),
(4896,	1,	47,	NULL,	1),
(4897,	1,	48,	NULL,	1),
(4898,	1,	49,	NULL,	1),
(4899,	1,	50,	NULL,	1),
(4900,	1,	51,	NULL,	1),
(4901,	1,	52,	NULL,	1),
(4902,	1,	53,	NULL,	1),
(4903,	1,	54,	NULL,	1),
(4904,	1,	55,	NULL,	1),
(4905,	1,	56,	NULL,	1),
(4906,	1,	57,	NULL,	1),
(4907,	1,	58,	NULL,	1),
(4908,	1,	59,	NULL,	1),
(4909,	1,	60,	NULL,	1),
(4910,	1,	61,	NULL,	1),
(4911,	1,	62,	NULL,	1),
(4912,	1,	63,	NULL,	1),
(4913,	1,	64,	NULL,	1),
(4914,	1,	65,	NULL,	1),
(4915,	1,	66,	NULL,	1),
(4916,	1,	67,	NULL,	1),
(4917,	1,	68,	NULL,	1),
(4918,	1,	69,	NULL,	1),
(4919,	1,	70,	NULL,	1),
(4920,	1,	71,	NULL,	1),
(4921,	1,	72,	NULL,	1),
(4922,	1,	73,	NULL,	1),
(4923,	1,	74,	NULL,	1),
(4924,	1,	77,	NULL,	1);

CREATE TABLE `Users` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Localisation` int DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` int NOT NULL,
  `InvitatedBy` int DEFAULT NULL,
  `Activated` bit(1) DEFAULT NULL,
  `Avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default.png',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO `Users` (`ID`, `Name`, `FirstName`, `Email`, `Localisation`, `Password`, `Role`, `InvitatedBy`, `Activated`, `Avatar`) VALUES
(1,	'Steven',	'Durieux',	'stevenhonor@live.fr',	14,	'8d150051e155cd5a77030a8c8c2ea9a59f4a0b82e42f140be50faf79005a0f07',	4,	NULL,	CONV('1', 2, 10) + 0,	'1.png'),
(2,	'Compte',	'Formateur',	'test@test.com',	7,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	1,	NULL,	NULL,	'2.jpg'),
(3,	'Compte',	'Auditeur',	'auditeur@auditeur.com',	4,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	2,	NULL,	NULL,	'default.png'),
(4,	'Auditeur',	'Numérodeux',	'auditeur2@auditeur2.com',	2,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	2,	NULL,	NULL,	'4.png'),
(5,	'Formateur',	'Numerodeux',	'formateur2@formateur2.com',	NULL,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	1,	NULL,	NULL,	'default.png'),
(6,	'Formateur',	'Numerotrois',	'formateur3@formateur3.com',	NULL,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	1,	NULL,	NULL,	'default.png'),
(7,	'Formateur',	'Numeroquatre',	'test2@test2.com',	7,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	1,	NULL,	NULL,	'default.png'),
(8,	'Formateur',	'Numerocinq',	'test3@test3.com',	7,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	1,	NULL,	NULL,	'default.png'),
(9,	'Auditeur',	'Numérotrois',	'auditeur3@auditeur3.com',	4,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	2,	NULL,	NULL,	'default.png');

-- 2021-03-28 22:02:38
