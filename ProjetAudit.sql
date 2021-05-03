-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `ProjetAudit` /*!40100 DEFAULT CHARACTER SET utf8 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ProjetAudit`;

CREATE TABLE `Audits` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Audit_Number` int NOT NULL,
  `User_ID` int NOT NULL,
  `DateAndHour` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Completed` bit(1) NOT NULL,
  `Auditor` int NOT NULL,
  `Assistant1` int DEFAULT NULL,
  `Assistant2` int DEFAULT NULL,
  `Facility` int NOT NULL,
  `Type` int NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=utf8;

INSERT INTO `Audits` (`ID`, `Audit_Number`, `User_ID`, `DateAndHour`, `Completed`, `Auditor`, `Assistant1`, `Assistant2`, `Facility`, `Type`) VALUES
(150,	1,	5,	'2021-05-03 20:23:49',	CONV('1', 2, 10) + 0,	1,	NULL,	NULL,	13,	2),
(151,	1,	7,	'2021-05-03 20:28:40',	CONV('1', 2, 10) + 0,	1,	NULL,	NULL,	14,	2),
(152,	1,	6,	'2021-05-03 20:30:30',	CONV('0', 2, 10) + 0,	1,	NULL,	NULL,	14,	2),
(153,	2,	7,	'2021-05-03 20:37:56',	CONV('0', 2, 10) + 0,	1,	NULL,	NULL,	14,	1),
(154,	2,	5,	'2021-05-03 20:43:00',	CONV('0', 2, 10) + 0,	1,	4,	NULL,	8,	2);

CREATE TABLE `AuditsReports` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Audit_Number` int NOT NULL,
  `Audit_Type` int NOT NULL,
  `Question` int NOT NULL,
  `Report` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Observation` tinytext CHARACTER SET utf8 COLLATE utf8_general_ci,
  `User_ID` int NOT NULL,
  `Auditor` int NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11378 DEFAULT CHARSET=utf8;

INSERT INTO `AuditsReports` (`ID`, `Audit_Number`, `Audit_Type`, `Question`, `Report`, `Observation`, `User_ID`, `Auditor`) VALUES
(11314,	1,	2,	1,	'Conforme',	NULL,	5,	1),
(11315,	1,	2,	2,	'NCmineure',	'Pas eu le temps 1',	5,	1),
(11316,	1,	2,	3,	'NCmineure',	'Pas eu le temps 2',	5,	1),
(11317,	1,	2,	4,	'NCmajeure',	'Pas eu envie 1',	5,	1),
(11318,	1,	2,	5,	'NCmajeure',	'Pas eu envie 2',	5,	1),
(11319,	1,	2,	6,	'Conforme',	NULL,	5,	1),
(11320,	1,	2,	7,	'Conforme',	NULL,	5,	1),
(11321,	1,	2,	8,	'Conforme',	NULL,	5,	1),
(11322,	1,	2,	9,	'Conforme',	NULL,	5,	1),
(11323,	1,	2,	10,	'Conforme',	NULL,	5,	1),
(11324,	1,	2,	11,	'Conforme',	NULL,	5,	1),
(11325,	1,	2,	12,	'Conforme',	NULL,	5,	1),
(11326,	1,	2,	13,	'Conforme',	NULL,	5,	1),
(11327,	1,	2,	14,	'Conforme',	NULL,	5,	1),
(11328,	1,	2,	15,	'Conforme',	NULL,	5,	1),
(11329,	1,	2,	16,	'Conforme',	NULL,	5,	1),
(11330,	1,	2,	17,	'Conforme',	NULL,	5,	1),
(11331,	1,	2,	18,	'Conforme',	NULL,	5,	1),
(11332,	1,	2,	19,	'Conforme',	NULL,	5,	1),
(11333,	1,	2,	20,	'Conforme',	NULL,	5,	1),
(11334,	1,	2,	21,	'Conforme',	NULL,	5,	1),
(11335,	1,	2,	22,	'Conforme',	NULL,	5,	1),
(11336,	1,	2,	23,	'Conforme',	NULL,	5,	1),
(11337,	1,	2,	24,	'Conforme',	NULL,	5,	1),
(11338,	1,	2,	25,	'Conforme',	NULL,	5,	1),
(11339,	1,	2,	26,	'Conforme',	NULL,	5,	1),
(11340,	1,	2,	27,	'Conforme',	NULL,	5,	1),
(11341,	1,	2,	28,	'Conforme',	NULL,	5,	1),
(11342,	1,	2,	29,	'Conforme',	NULL,	5,	1),
(11343,	1,	2,	30,	'Conforme',	NULL,	5,	1),
(11344,	1,	2,	31,	'Conforme',	NULL,	5,	1),
(11345,	1,	2,	32,	'Conforme',	NULL,	5,	1),
(11346,	1,	2,	1,	'Conforme',	NULL,	7,	1),
(11347,	1,	2,	2,	'Conforme',	NULL,	7,	1),
(11348,	1,	2,	3,	'Conforme',	NULL,	7,	1),
(11349,	1,	2,	4,	'NCmineure',	'sfdsfdsfsdf',	7,	1),
(11350,	1,	2,	5,	'NCmineure',	'fdgfgdfgdfg',	7,	1),
(11351,	1,	2,	6,	'NCmajeure',	'dfsfdsfsdf',	7,	1),
(11352,	1,	2,	7,	'Conforme',	NULL,	7,	1),
(11353,	1,	2,	8,	'Conforme',	NULL,	7,	1),
(11354,	1,	2,	9,	'Conforme',	NULL,	7,	1),
(11355,	1,	2,	10,	'Conforme',	NULL,	7,	1),
(11356,	1,	2,	11,	'Conforme',	NULL,	7,	1),
(11357,	1,	2,	12,	'Conforme',	NULL,	7,	1),
(11358,	1,	2,	13,	'Conforme',	NULL,	7,	1),
(11359,	1,	2,	14,	'Conforme',	NULL,	7,	1),
(11360,	1,	2,	15,	'Conforme',	NULL,	7,	1),
(11361,	1,	2,	16,	'Conforme',	NULL,	7,	1),
(11362,	1,	2,	17,	'Conforme',	NULL,	7,	1),
(11363,	1,	2,	18,	'Conforme',	NULL,	7,	1),
(11364,	1,	2,	19,	'Conforme',	NULL,	7,	1),
(11365,	1,	2,	20,	'Conforme',	NULL,	7,	1),
(11366,	1,	2,	21,	'Conforme',	NULL,	7,	1),
(11367,	1,	2,	22,	'Conforme',	NULL,	7,	1),
(11368,	1,	2,	23,	'Conforme',	NULL,	7,	1),
(11369,	1,	2,	24,	'Conforme',	NULL,	7,	1),
(11370,	1,	2,	25,	'Conforme',	NULL,	7,	1),
(11371,	1,	2,	26,	'Conforme',	NULL,	7,	1),
(11372,	1,	2,	27,	'Conforme',	NULL,	7,	1),
(11373,	1,	2,	28,	'Conforme',	NULL,	7,	1),
(11374,	1,	2,	29,	'Conforme',	NULL,	7,	1),
(11375,	1,	2,	30,	'Conforme',	NULL,	7,	1),
(11376,	1,	2,	31,	'Conforme',	NULL,	7,	1),
(11377,	1,	2,	32,	'Conforme',	NULL,	7,	1);

CREATE TABLE `Autoevaluations` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Evaluation_Number` int NOT NULL,
  `User_ID` int NOT NULL,
  `DateAndHour` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Completed` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8;

INSERT INTO `Autoevaluations` (`ID`, `Evaluation_Number`, `User_ID`, `DateAndHour`, `Completed`) VALUES
(161,	1,	7,	'2021-05-01 11:56:36',	CONV('1', 2, 10) + 0),
(162,	1,	8,	'2021-05-01 12:04:19',	CONV('1', 2, 10) + 0),
(163,	1,	1,	'2021-05-03 20:38:11',	CONV('0', 2, 10) + 0);

CREATE TABLE `BugReports` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Bug` tinytext NOT NULL,
  `ErrorCode` tinytext,
  `DateAndHour` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SubmittedBy` int NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;


CREATE TABLE `CategoriesQuestionsAuditFormateur` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

INSERT INTO `CategoriesQuestionsAuditFormateur` (`ID`, `Name`) VALUES
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

CREATE TABLE `CriteriaQualiopi` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

INSERT INTO `CriteriaQualiopi` (`ID`, `Name`) VALUES
(1,	'Les conditions d\'information du public sur les prestations'),
(2,	'L’identification précise des objectifs des prestations proposées et l’adaptation de ces prestations aux publics bénéficiaires lors de la conception des prestations'),
(3,	'L’adaptation aux publics bénéficiaires des prestations et des modalités d’accueil, d’accompagnement, de suivi et d’évaluation mises en œuvre'),
(4,	'L’adéquation des moyens pédagogiques, techniques et d’encadrement aux prestations mises en œuvre'),
(5,	'La qualification et le développement des connaissances et compétences'),
(6,	'L’inscription et l’investissement du prestataire dans son environnement professionnel'),
(7,	'Le recueil et la prise en compte des appréciations et des réclamations formulées par les parties prenantes aux prestations délivrées');

CREATE TABLE `Documents` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Document` varchar(255) NOT NULL,
  `Link` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

INSERT INTO `Documents` (`ID`, `Document`, `Link`) VALUES
(41,	'Audit formateur',	'Audit_formateur.pdf'),
(42,	'Autoevaluation formateur',	'Autoevaluation_formateur.pdf'),
(43,	'Audit Qualiopi',	'Audit_Qualiopi.pdf');

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

CREATE TABLE `IndicatorsQualiopi` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `CreatedBy` int NOT NULL,
  `DateAndHour` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Indicator` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Criteria` int NOT NULL,
  `Evidences` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Active` bit(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

INSERT INTO `IndicatorsQualiopi` (`ID`, `CreatedBy`, `DateAndHour`, `Indicator`, `Criteria`, `Evidences`, `Active`) VALUES
(1,	1,	'2021-05-03 18:38:30',	'Indicateur 1: Diffusion d\'informations générales',	1,	'Pages WEB\r\nFiche AFPA sur le site C2RP\r\nCatalogue AFPA\r\nFiches d\'identité du titre professionnel du Ministère du Travail\r\nPowerpoint de présentation en RIC\r\nFiche produit Décret Qualité\r\nRéseaux sociaux : LinkedIn, Facebook',	CONV('1', 2, 10) + 0),
(2,	1,	'2021-05-03 18:38:30',	'Indicateur 2: Information sur les résultats',	1,	'Pages WEB\r\nFiche AFPA sur le site C2RP\r\nPowerpoint de présentation en RIC',	CONV('1', 2, 10) + 0),
(3,	1,	'2021-05-03 18:38:30',	'Indicateur 3: Information sur les taux d\'obtention',	1,	'Pages WEB\r\nFiche AFPA sur le site C2RP\r\nPowerpoint de présentation en RIC\r\nPlanification des offres',	CONV('1', 2, 10) + 0),
(4,	1,	'2021-05-03 18:38:30',	'Indicateur 4: Analyse du besoin',	2,	'Formalisation des échanges avec les clients (minimal échange de courriels)\r\nSaisie dans SIRC\r\nSaisie dans CRM\r\nDossiers de conception\r\nOutils d\'évaluation\r\nContrat d\'accompagnement VAE',	CONV('1', 2, 10) + 0),
(5,	1,	'2021-05-03 18:38:30',	'Indicateur 5: Objectifs opérationnels et évaluables',	2,	'Fiche programme\r\nFiche de positionnement\r\nContrat d\'accompagnement VAE\r\nDossiers de conception\r\nDossiers Transition PRO',	CONV('1', 2, 10) + 0),
(6,	1,	'2021-05-03 18:38:30',	'Indicateur 6: Élaboration des contenus et modalités de mise en œuvre',	2,	'Fiche programme\r\nDossiers de conception\r\nPropositions commerciales',	CONV('1', 2, 10) + 0),
(7,	1,	'2021-05-03 18:38:30',	'Indicateur 7: Adéquation des contenus aux exigences de la certification professionnelle',	2,	'Fiche Titre professionnel\r\nREAC\r\nRC ou RE',	CONV('1', 2, 10) + 0),
(8,	1,	'2021-05-03 18:38:30',	'Indicateur 8: Diagnostic et positionnement',	2,	'RAP\r\nGDP\r\nECAP\r\nÉvaluations à l\'entrée',	CONV('1', 2, 10) + 0),
(9,	1,	'2021-05-03 18:38:30',	'Indicateur 9: Accueil et conditions de déroulement de la prestation',	3,	'Présentation des actions en RIC\r\nListe des informations données lors de l\'accueil\r\nLivret d\'accueil\r\nListe des informations transmises aux stagiaires\r\nListe des personnes ressources handicap\r\nRèglement intérieur\r\nListe des documents nécessaires pour le dossier de rémunération',	CONV('1', 2, 10) + 0),
(10,	1,	'2021-05-03 18:38:30',	'Indicateur 10: Mise en œuvre et adaptation de la prestation',	3,	'Livret de suivi stagiaire\r\nCalendrier de formation\r\nÉtat d\'avancement groupe\r\nÉtat d\'avancement individuel\r\nSuivi des parcours via Métis ou SDA',	CONV('1', 2, 10) + 0),
(11,	1,	'2021-05-03 18:38:30',	'Indicateur 11: Évaluation de l\'atteinte des objectifs opérationnels',	3,	'Exemples évaluations formatives\r\nSuivi des apprentissages\r\nÉvaluation liée à la certification : ECF\r\nÉvaluation liée à la certification : DP\r\nHabilitations électriques\r\nBilans pédagogiques',	CONV('1', 2, 10) + 0),
(12,	1,	'2021-05-03 18:38:30',	'Indicateur 12: Engagement des bénéficiaires et prévention des abandons',	3,	'Enregistrements des entretiens stagiaires\r\nMesures disciplinaires\r\nEngagement individuel\r\nPlanification des rdv individuels\r\nCompte rendu des rdv individuels\r\nTraçabilité d\'échanges avec les stagiaires\r\nPositionnement suite à la bascule de parcours',	CONV('1', 2, 10) + 0),
(13,	1,	'2021-05-03 18:38:30',	'Indicateur 13: Coordination et progressivité des apprentissages',	3,	'Convention de PE avec les objectifs spécifiques\r\nSuivi PE avec évaluation des objectifs spécifiques',	CONV('1', 2, 10) + 0),
(14,	1,	'2021-05-03 18:38:30',	'Indicateur 14: Accompagnement socio-professionnel et éducatif',	3,	'Module RSE dans métis pour chaque formation\r\nListe des associations au alentour\r\nASE\r\nMoniteur éducateur promo 16/18',	CONV('1', 2, 10) + 0),
(15,	1,	'2021-05-03 18:38:30',	'Indicateur 15: Droits, devoirs et règles en matière de santé et sécurité au travail',	3,	'Livret d\'accueil entreprise\r\nDUERP\r\nRèglement intérieur',	CONV('1', 2, 10) + 0),
(16,	1,	'2021-05-03 18:38:30',	'Indicateur 16: Conditions de présentation aux examens',	3,	'Règlement de session\r\nListe de contrôle pour l\'organisation des sessions\r\nCheck lis',	CONV('1', 2, 10) + 0),
(17,	1,	'2021-05-03 18:38:30',	'Indicateur 17: Moyens humains et techniques (infrastructures)',	4,	'Revue de lancement\r\nOutils de suivis des contrôles réglementaires\r\nDUERP\r\nPAPE\r\nListe des compétences formateurs\r\nAfpa Talents\r\nCV des formateurs\r\nDescription des plateaux techniques',	CONV('1', 2, 10) + 0),
(18,	1,	'2021-05-03 18:38:30',	'Indicateur 18: Organisation fonctionnelle, responsabilités et autorités',	4,	'Organigramme\r\nFiches de postes\r\nListe des compétences formateurs\r\nRevue de lancement\r\nSIHA pour les sous traitance\r\nMagister\r\nAfpa Talents',	CONV('1', 2, 10) + 0),
(19,	1,	'2021-05-03 18:38:30',	'Indicateur 19: Ressources pédagogiques mises à disposition',	4,	'Exemple de ressources pédagogiques\r\nPrésentation de Métis\r\nBanque de l\'ingénierie\r\nSDA\r\nAfpa idée métiers',	CONV('1', 2, 10) + 0),
(20,	1,	'2021-05-03 18:38:30',	'Indicateur 20: Personnel en charge de la mobilité et de l\'handicap',	4,	'Référent Handicap\r\nRéférent mobilité',	CONV('1', 2, 10) + 0),
(21,	1,	'2021-05-03 18:38:30',	'Indicateur 21: Détermination et mise à disposition des compétences des intervenants',	5,	'Essais professionnels pour les CDI\r\nAfpa Talents\r\nMagister\r\nStart formateur\r\nEnquête de satisfaction stagiaires\r\nÉvaluation fonds de salle',	CONV('1', 2, 10) + 0),
(22,	1,	'2021-05-03 18:38:30',	'Indicateur 22: Développement des compétences des personnels',	5,	'Plan de développement des compétences\r\nTalent soft\r\nStart formateur\r\nPlanification des entretiens annuels\r\nCarrefours pédagogiques et techniques\r\nPlan de développement des compétences sur Métis\r\nDéveloppement des compétences sur habilitations\r\nréglementaires',	CONV('1', 2, 10) + 0),
(23,	1,	'2021-05-03 18:38:30',	'Indicateur 23: Veille légale et réglementaire et exploitation',	6,	'Lettre de veille fournie par la DII \"la vie des titres professionnels\"\r\nL\'actualité des régions\r\nLettre veille Afpa\r\nLettres AEF, centre inffo\r\nCarrefours métiers\r\nLettre emploi formation\r\nÉvolutions réglementaires transmises par le national\r\n',	CONV('1', 2, 10) + 0),
(24,	1,	'2021-05-03 18:38:30',	'Indicateur 24: Veille sur l\'évolution des compétences, métiers et emplois et exploitation',	6,	'Lettre de veille fournie par la DII \"la vie des titres professionnels\"\r\nL\'actualité des régions\r\nLettre veille Afpa\r\nLettres AEF, centre inffo\r\nDECIDenCO\r\nCarrefours métiers\r\nCR de Spels\r\nBMO',	CONV('1', 2, 10) + 0),
(25,	1,	'2021-05-03 18:38:30',	'Indicateur 25: Veille sur les innovations pédagogiques et technologiques et exploitation',	6,	'Carrefours techniques\r\nParticipation salons\r\nWebinaires techniques\r\nCR de Spels\r\nNews letters centres infos et C2RP\r\nJournées études formateurs',	CONV('1', 2, 10) + 0),
(26,	1,	'2021-05-03 18:38:30',	'Indicateur 26: Accueil, accompagnement ou orientation du handicap',	6,	'Liste des partenaires experts Handicap\r\nRéseau Cap emploi\r\nAppui médecin du travail Afpa\r\nListe associations ou livret d\'accompagnement',	CONV('1', 2, 10) + 0),
(27,	1,	'2021-05-03 18:38:30',	'Indicateur 27: Maitrise des prestataires externes',	6,	'Fiches évaluation des prestataires\r\nSIHA\r\nMagister',	CONV('1', 2, 10) + 0),
(28,	1,	'2021-05-03 18:38:30',	'Indicateur 28: Mobilisation du réseau de partenaires socio-économiques',	6,	'Liste des entreprises partenaires pour diffuser et accompagner les stagiaires\r\nRéseaux d\'anciens stagiaires\r\nClub d\'entreprise\r\nRéunion ANDRH\r\nConseil d\'administration mission locale\r\nConseil départementale et locale\r\nAccompagnements CV + LM',	CONV('1', 2, 10) + 0),
(29,	1,	'2021-05-03 18:38:30',	'Indicateur 29: Insertion professionnelle ou poursuite d\'études',	6,	'Liste des entreprises partenaires\r\nERE\r\nRéseaux d\'anciens stagiaires',	CONV('1', 2, 10) + 0),
(30,	1,	'2021-05-03 18:38:30',	'Indicateur 30: Appréciations des parties prenantes',	7,	'Tableau de bord Qualité régional\r\nMode opératoire gestion des enquêtes\r\nRésultats enquêtes de satisfaction\r\nCR revue de direction régionale et locales\r\nSuivi PAE\r\nRésultats de contrôles',	CONV('1', 2, 10) + 0),
(31,	1,	'2021-05-03 18:38:30',	'Indicateur 31: Gestion et traitement des aléas et des réclamations',	7,	'Mode opératoire Amélioration continue\r\nPGAC basé sur le workflow avec analyse de causes',	CONV('1', 2, 10) + 0),
(32,	1,	'2021-05-03 18:38:30',	'Indicateur 32: Démarche d\'amélioration continue et plan d\'action',	7,	'Tableau de bord Qualité régional\r\nCR revue de direction régionale et locales\r\nBilans internes de formation\r\nSuivi des plans d\'actions\r\nRapports audits internes\r\nRevues qualité locales\r\nPGAC basé sur le workflow avec analyse de causes',	CONV('1', 2, 10) + 0);

CREATE TABLE `News` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `User` int NOT NULL,
  `Actualite` tinytext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Audit_Type` int DEFAULT NULL,
  `Audit_Number` int DEFAULT NULL,
  `Eval_Number` int DEFAULT NULL,
  `Facility` int DEFAULT NULL,
  `Auditor` int DEFAULT NULL,
  `Assistant1` int DEFAULT NULL,
  `Assistant2` int DEFAULT NULL,
  `DateAndHour` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=182 DEFAULT CHARSET=utf8;

INSERT INTO `News` (`ID`, `User`, `Actualite`, `Audit_Type`, `Audit_Number`, `Eval_Number`, `Facility`, `Auditor`, `Assistant1`, `Assistant2`, `DateAndHour`) VALUES
(180,	5,	'a été audité !',	2,	1,	NULL,	13,	1,	NULL,	NULL,	'2021-05-03 20:23:49'),
(181,	7,	'a été audité !',	2,	1,	NULL,	14,	1,	NULL,	NULL,	'2021-05-03 20:28:40');

CREATE TABLE `QuestionsAuditFormateur` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `CreatedBy` int NOT NULL,
  `DateAndHour` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Question` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Evidence` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Active` bit(1) NOT NULL DEFAULT b'1',
  `Category` int NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8;

INSERT INTO `QuestionsAuditFormateur` (`ID`, `CreatedBy`, `DateAndHour`, `Question`, `Evidence`, `Active`, `Category`) VALUES
(1,	1,	'2021-05-02 15:30:42',	'Connaissance des engagements contractuels pris avec l\'AFPA',	'Cahier des charges PRF',	CONV('1', 2, 10) + 0,	1),
(2,	1,	'2021-03-29 18:28:02',	'Ressources accessibles de la formation ?',	'Kit banques de données ingénieries (BNRA BNI)',	CONV('1', 2, 10) + 0,	1),
(3,	1,	'2021-03-10 08:29:46',	'Planification des moyens ?',	'Revue de lancement',	CONV('1', 2, 10) + 0,	1),
(4,	1,	'2021-03-10 08:29:46',	'Comment est réalisé la planification des activités ?',	'Revue de lancement',	CONV('1', 2, 10) + 0,	1),
(5,	1,	'2021-03-10 08:29:46',	'Pouvez vous me montrer la planification des activités ?',	'Calendrier de la formation',	CONV('1', 2, 10) + 0,	1),
(6,	1,	'2021-03-10 08:29:46',	'Les dates de PAE sont elles définies ?',	'Calendrier de la formation',	CONV('1', 2, 10) + 0,	1),
(7,	1,	'2021-03-10 08:34:37',	'Existe-t-il un rituel d\'accueil ?',	'Présentation formation et métier , REAC, RE, formalités administratives',	CONV('1', 2, 10) + 0,	2),
(8,	1,	'2021-03-10 08:35:00',	'Présentation du livret d\'accueil ?',	'Livret d\'accueil',	CONV('1', 2, 10) + 0,	2),
(9,	1,	'2021-03-10 08:35:16',	'Présentation du règlement intérieur ?',	'Règlement intérieur',	CONV('1', 2, 10) + 0,	2),
(10,	1,	'2021-03-10 08:35:58',	'Avez-vous une liste des associations pour les aides (hébergement, transport) ?',	'Liste des associations',	CONV('1', 2, 10) + 0,	2),
(11,	1,	'2021-03-29 18:16:15',	'La première semaine, l\'élection des délégués stagiaires est elle faite ?',	'PV élections délégués stagiaires',	CONV('1', 2, 10) + 0,	2),
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
(47,	1,	'2021-03-31 09:16:38',	'Gérez vous les convocations ?',	'Convocation de certification',	CONV('1', 2, 10) + 0,	4),
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
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8;

INSERT INTO `RaisonsNonConformitesAutoevaluation` (`ID`, `User`, `Question_ID`, `Reason`, `Eval_Number`) VALUES
(195,	7,	42,	'Pas eu le temps pour appliquer le référentiel plateau technique',	1),
(196,	7,	52,	'Pas eu le temps d\'utiliser la fiche comportement',	1),
(197,	8,	44,	'Je n\'utilise pas la liste des epi',	1),
(198,	8,	58,	'Je n\'utilise pas la lsite formalisée des entreprises',	1),
(199,	8,	61,	'Je n\'emploi pas l\'évaluation tuteur',	1),
(200,	8,	52,	'Je n\'utilise pas la fiche comportement car ma formation n\'en a pas',	1),
(201,	8,	73,	'Je n\'emploi pas le bilan de fin de formation',	1);

CREATE TABLE `ResultatsAutoevaluations` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Evaluation_Number` int NOT NULL,
  `Question` int NOT NULL,
  `Answer` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `User_ID` int NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5361 DEFAULT CHARSET=utf8;

INSERT INTO `ResultatsAutoevaluations` (`ID`, `Evaluation_Number`, `Question`, `Answer`, `User_ID`) VALUES
(5289,	1,	41,	'Oui',	7),
(5290,	1,	42,	'Non',	7),
(5291,	1,	40,	'Oui',	7),
(5292,	1,	48,	'Oui',	7),
(5293,	1,	51,	'Oui',	7),
(5294,	1,	50,	'Oui',	7),
(5295,	1,	49,	'Oui',	7),
(5296,	1,	47,	'Oui',	7),
(5297,	1,	46,	'Oui',	7),
(5298,	1,	45,	'Oui',	7),
(5299,	1,	44,	'Oui',	7),
(5300,	1,	43,	'Oui',	7),
(5301,	1,	58,	'Oui',	7),
(5302,	1,	61,	'Oui',	7),
(5303,	1,	60,	'Oui',	7),
(5304,	1,	59,	'Oui',	7),
(5305,	1,	57,	'Oui',	7),
(5306,	1,	55,	'Oui',	7),
(5307,	1,	54,	'Oui',	7),
(5308,	1,	53,	'Oui',	7),
(5309,	1,	56,	'Oui',	7),
(5310,	1,	66,	'Oui',	7),
(5311,	1,	69,	'Oui',	7),
(5312,	1,	68,	'Oui',	7),
(5313,	1,	67,	'Oui',	7),
(5314,	1,	65,	'Oui',	7),
(5315,	1,	64,	'Oui',	7),
(5316,	1,	63,	'Oui',	7),
(5317,	1,	62,	'Oui',	7),
(5318,	1,	70,	'Oui',	7),
(5319,	1,	71,	'Oui',	7),
(5320,	1,	72,	'Oui',	7),
(5321,	1,	73,	'Oui',	7),
(5322,	1,	74,	'Oui',	7),
(5323,	1,	77,	'Oui',	7),
(5324,	1,	52,	'Non',	7),
(5325,	1,	41,	'Oui',	8),
(5326,	1,	42,	'Oui',	8),
(5327,	1,	40,	'Oui',	8),
(5328,	1,	48,	'Oui',	8),
(5329,	1,	51,	'Oui',	8),
(5330,	1,	50,	'Oui',	8),
(5331,	1,	49,	'Oui',	8),
(5332,	1,	47,	'Oui',	8),
(5333,	1,	46,	'Oui',	8),
(5334,	1,	45,	'Oui',	8),
(5335,	1,	44,	'Non',	8),
(5336,	1,	43,	'Oui',	8),
(5337,	1,	58,	'Non',	8),
(5338,	1,	61,	'Non',	8),
(5339,	1,	60,	'Oui',	8),
(5340,	1,	59,	'Oui',	8),
(5341,	1,	57,	'Oui',	8),
(5342,	1,	55,	'Oui',	8),
(5343,	1,	54,	'Oui',	8),
(5344,	1,	53,	'Oui',	8),
(5345,	1,	52,	'Non',	8),
(5346,	1,	56,	'Oui',	8),
(5347,	1,	66,	'Oui',	8),
(5348,	1,	69,	'Oui',	8),
(5349,	1,	68,	'Oui',	8),
(5350,	1,	67,	'Oui',	8),
(5351,	1,	65,	'Oui',	8),
(5352,	1,	64,	'Oui',	8),
(5353,	1,	62,	'Oui',	8),
(5354,	1,	63,	'Oui',	8),
(5355,	1,	70,	'Oui',	8),
(5356,	1,	72,	'Oui',	8),
(5357,	1,	73,	'Non',	8),
(5358,	1,	74,	'Oui',	8),
(5359,	1,	77,	'Oui',	8),
(5360,	1,	71,	'Oui',	8);

CREATE TABLE `TypesAudits` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `TypesAudits` (`ID`, `Name`) VALUES
(1,	'Audit formateur'),
(2,	'Audit Qualiopi');

CREATE TABLE `Users` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Localisation` int DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` int NOT NULL,
  `InvitedBy` int DEFAULT NULL,
  `Activated` bit(1) NOT NULL,
  `Avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'default.png',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

INSERT INTO `Users` (`ID`, `Name`, `FirstName`, `Email`, `Localisation`, `Password`, `Role`, `InvitedBy`, `Activated`, `Avatar`) VALUES
(1,	'Steven',	'Durieux',	'stevenhonor@live.fr',	13,	'8d150051e155cd5a77030a8c8c2ea9a59f4a0b82e42f140be50faf79005a0f07',	14,	NULL,	CONV('1', 2, 10) + 0,	'TheEvilFoxLogo.png'),
(2,	'Jennifer',	'Couturier',	'test@test.com',	7,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	4,	NULL,	CONV('0', 2, 10) + 0,	'default.png'),
(3,	'Compte',	'Auditeur',	'auditeur@auditeur.com',	4,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	2,	NULL,	CONV('1', 2, 10) + 0,	'21.gif'),
(4,	'Auditeur',	'Numérodeux',	'auditeur2@auditeur2.com',	2,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	2,	NULL,	CONV('1', 2, 10) + 0,	'default.png'),
(5,	'Formateur',	'Numerodeux',	'formateur2@formateur2.com',	7,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	1,	NULL,	CONV('1', 2, 10) + 0,	'default.png'),
(6,	'Sarah',	'Chuquet',	'formateur3@formateur3.com',	14,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	1,	NULL,	CONV('1', 2, 10) + 0,	'default.png'),
(7,	'Formateur',	'Numeroquatre',	'test2@test2.com',	7,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	1,	NULL,	CONV('1', 2, 10) + 0,	'default.png'),
(8,	'Dylan',	'Lebas',	'test3@test3.com',	13,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	2,	NULL,	CONV('1', 2, 10) + 0,	'default.png'),
(9,	'Auditeur',	'Numérotrois',	'auditeur3@auditeur3.com',	4,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	2,	NULL,	CONV('1', 2, 10) + 0,	'default.png'),
(10,	'Amelie',	'Boulesteix',	'amelie.boulesteix@afpa.fr',	14,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	2,	NULL,	CONV('1', 2, 10) + 0,	'default.png'),
(11,	'Mektar',	'Outgda',	'mektar.outgda@test.fr',	14,	'9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08',	1,	NULL,	CONV('1', 2, 10) + 0,	'default.png');

-- 2021-05-03 18:59:27
