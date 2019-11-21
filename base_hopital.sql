--
-- Base de données :  `hopital_php`
--

CREATE DATABASE hopital_php;

-- --------------------------------------------------------

--
-- Privilèges de la base de données
--

CREATE USER 'user1'@'localhost' IDENTIFIED BY 'hcetylop';
GRANT ALL PRIVILEGES ON *.* TO 'user1'@'localhost' WITH GRANT OPTION;

USE hopital_php;

-- --------------------------------------------------------

--
-- Structure de la table `document`
--

DROP TABLE IF EXISTS `document`;
CREATE TABLE IF NOT EXISTS `document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_patient` int(11) NOT NULL,
  `nom_fichier` varchar(250) NOT NULL,
  `clef` varchar(250) NOT NULL,
  `type` varchar(100) NOT NULL,
  `format` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `contenu` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clef` (`clef`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `motif`
--

DROP TABLE IF EXISTS `motif`;
CREATE TABLE IF NOT EXISTS `motif` (
  `code` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(250) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `motif`
--

INSERT INTO `motif` (`code`, `libelle`) VALUES
(1, 'Consultation libre'),
(2, 'Urgence'),
(3, 'Prescription');

-- --------------------------------------------------------

--
-- Structure de la table `patient`
--

DROP TABLE IF EXISTS `patient`;
CREATE TABLE IF NOT EXISTS `patient` (
  `code` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) NOT NULL,
  `prenom` varchar(250) NOT NULL,
  `sexe` varchar(250) NOT NULL,
  `date_naissance` date NOT NULL,
  `num_secu` varchar(250) DEFAULT NULL,
  `code_pays` varchar(250) NOT NULL,
  `date_prem_entree` date NOT NULL,
  `code_motif` int(11) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `patient`
--

INSERT INTO `patient` (`code`, `nom`, `prenom`, `sexe`, `date_naissance`, `num_secu`, `code_pays`, `date_prem_entree`, `code_motif`) VALUES
(1, 'MAALOUL', 'Ali', 'M', '1979-01-12', '', 'TN', '2018-02-01', 1),
(2, 'DUPONT', 'Véronique', 'F', '1938-12-27', '238277502900442', 'FR', '2018-04-05', 2),
(3, 'DUPONT', 'Jean', 'M', '1985-04-01', '185045903800855', 'FR', '2018-06-12', 3),
(4, 'EL GUERROUJ', 'Hicham', 'M', '1980-06-10', '', 'MA', '2018-08-18', 1),
(5, 'BELMADI', 'Djamel', 'M', '1982-12-27', '', 'DZ', '2018-09-26', 1);

-- --------------------------------------------------------

--
-- Structure de la table `pays`
--

DROP TABLE IF EXISTS `pays`;
CREATE TABLE IF NOT EXISTS `pays` (
  `code` varchar(250) NOT NULL,
  `libelle` varchar(250) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `pays`
--

INSERT INTO `pays` (`code`, `libelle`) VALUES
('FR', 'France'),
('BE', 'Belgique'),
('MA', 'Maroc'),
('TN', 'Tunisie'),
('DZ', 'Algérie');

-- --------------------------------------------------------

--
-- Structure de la table `sexe`
--

DROP TABLE IF EXISTS `sexe`;
CREATE TABLE IF NOT EXISTS `sexe` (
  `code` varchar(250) NOT NULL,
  `libelle` varchar(250) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `sexe`
--

INSERT INTO `sexe` (`code`, `libelle`) VALUES
('F', 'Féminin'),
('M', 'Masculin');
COMMIT;
