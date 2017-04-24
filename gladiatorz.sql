-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 28. November 2011 um 10:12
-- Server Version: 5.1.54
-- PHP-Version: 5.3.5-1ubuntu7.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `gladiatorz`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Admin_log`
--

CREATE TABLE IF NOT EXISTS `Admin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `seite` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `info` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `Admin_log`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `aktivierung`
--

CREATE TABLE IF NOT EXISTS `aktivierung` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Aktivierungscode` varchar(10) NOT NULL DEFAULT '',
  `Erstellt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `EMail` varchar(255) NOT NULL DEFAULT '',
  `Aktiviert` enum('Ja','Nein') NOT NULL DEFAULT 'Ja',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `aktivierung`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ally_gebet`
--

CREATE TABLE IF NOT EXISTS `ally_gebet` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `schule` int(10) unsigned NOT NULL,
  `effekt` int(10) unsigned NOT NULL,
  `time` int(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ally_gebet`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ally_schule`
--

CREATE TABLE IF NOT EXISTS `ally_schule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `kuerzel` varchar(5) COLLATE latin1_general_ci NOT NULL,
  `pic` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `descr` longtext COLLATE latin1_general_ci NOT NULL,
  `vize` int(10) unsigned NOT NULL,
  `boss` int(10) unsigned NOT NULL,
  `diplo` int(10) unsigned NOT NULL,
  `gold` int(10) unsigned NOT NULL,
  `medallien` int(10) unsigned NOT NULL,
  `area` varchar(20) COLLATE latin1_general_ci NOT NULL DEFAULT '0|0|0|0|0|0',
  `taxpercent` int(2) unsigned NOT NULL DEFAULT '5',
  `taxprotect` int(10) unsigned NOT NULL DEFAULT '100',
  `minpoints` int(10) unsigned NOT NULL DEFAULT '10',
  `maxmember` int(5) unsigned NOT NULL,
  `tactic` int(1) unsigned NOT NULL DEFAULT '0',
  `war` text COLLATE latin1_general_ci NOT NULL,
  `public` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ally_schule`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `appendix`
--

CREATE TABLE IF NOT EXISTS `appendix` (
  `name` varchar(25) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `appendix`
--

INSERT INTO `appendix` (`name`) VALUES
('der Habgier'),
('des Hasses'),
('der Dummheit'),
('der Armen'),
('der Mächtigen'),
('der Schwachen'),
('der Fürchterlichen'),
('der Dummen'),
('der Bettler'),
('der Hoffnung'),
('des Wüters'),
('der Ahnen'),
('der Lehrmeister'),
('der Bedeutungslosigkeit'),
('der Gnade'),
('der Ehrfurcht'),
('der Legenden'),
('der Sklaven'),
('der Wut'),
('der Macht'),
('der Unbesiegbarkeit'),
('der Unbestechligkeit'),
('der Unbarmherzigkeit'),
('der Nacht'),
('des Kampfes'),
('der Dunkelheit'),
('der Besiegbarkeit'),
('der Barmherzigkeit'),
('der Weisheit'),
('der Unverfrorenheit'),
('der Enthauptung'),
('der Beschaulichkeit'),
('des Grauens'),
('des Wahnsinns'),
('des Todes'),
('der Vernichtung'),
('der Rache'),
('der Finsternis'),
('der Verdammnis'),
('des Verfalls'),
('des Unbezähmbaren'),
('der Erleuchtung'),
('des Glanzes'),
('des Meisters'),
('des Schutzes'),
('des Herzlosen'),
('der Könige'),
('des Geschicks'),
('der Vergeltung'),
('der Verzweiflung'),
('des Schmerzes'),
('des Untergangs'),
('des Unbezwingbaren'),
('des Friedens'),
('der Verderbnis'),
('des Feuers'),
('der Furcht'),
('des Glaubens'),
('der Vergebung'),
('der Grausamkeit'),
('des Schicksals'),
('des Blutes'),
('des Glücks'),
('des Fluches'),
('des Mutes'),
('der Diebe'),
('der Unbesiegbaren'),
('der Zerstreuung'),
('der Blutrünstigkeit'),
('der Apokalypse'),
('der Anmut'),
('der Zerstörung'),
('der Liebe'),
('des Ruins'),
('der Todgeweihten'),
('des Zerstörers'),
('der Ehre'),
('der Gerechtigkeit'),
('des Lebens'),
('des Blitzes'),
('des Erhabenen'),
('des Verfluchten'),
('der Perfektion'),
('des Sieges'),
('des Donners'),
('des Heldenmutes'),
('der Elite'),
('der Unterwelt'),
('des Reichtums'),
('des Größenwahns'),
('des Leidens'),
('des Gütigen'),
('der Knechtschaft'),
('der Reinheit'),
('des Ruhmes'),
('des Drachens'),
('der Allmächtigkeit'),
('der Dämmerung'),
('der Helden'),
('der Intelligenz'),
('der Feuersbrunst'),
('des Nebels'),
('des Ruhmreichen'),
('des Fleisches'),
('der Flammen'),
('der Stärke'),
('des Chaos'),
('der Sünde'),
('der Geweihten'),
('der Schatten'),
('der Garde'),
('der Götter'),
('aus Blut und Ehre'),
('der Unschuld'),
('der Unsterblichkeit');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ausruestung`
--

CREATE TABLE IF NOT EXISTS `ausruestung` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `art` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `off` int(10) unsigned NOT NULL,
  `deff` int(10) unsigned NOT NULL,
  `minStaerke` int(10) unsigned NOT NULL,
  `minGeschick` int(10) unsigned NOT NULL,
  `minKondition` int(10) unsigned NOT NULL,
  `minCarisma` int(10) unsigned NOT NULL,
  `minIntelligenz` int(10) unsigned NOT NULL,
  `rang` int(10) NOT NULL DEFAULT '1',
  `typ` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `ort` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `wert` int(10) unsigned NOT NULL,
  `schule` int(10) unsigned NOT NULL,
  `pic` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `inv_pos` int(3) unsigned NOT NULL DEFAULT '0',
  `klasse` int(1) unsigned NOT NULL,
  `ZHW` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'nur bei zweihandwaffen',
  `save` int(10) unsigned NOT NULL DEFAULT '0',
  `dauer` int(50) NOT NULL DEFAULT '0',
  `preis` int(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`art`,`ort`),
  KEY `user_id_2` (`user_id`),
  KEY `art` (`art`),
  KEY `ort` (`ort`),
  KEY `art_2` (`art`,`ort`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=170379 ;

--
-- Daten für Tabelle `ausruestung`
--

INSERT INTO `ausruestung` (`id`, `user_id`, `name`, `art`, `off`, `deff`, `minStaerke`, `minGeschick`, `minKondition`, `minCarisma`, `minIntelligenz`, `rang`, `typ`, `ort`, `wert`, `schule`, `pic`, `inv_pos`, `klasse`, `ZHW`, `save`, `dauer`, `preis`) VALUES
(120, 0, 'Stahlplattenstiefel', 'foots', 10, 50, 40, 0, 0, 0, 40, 7, 'schmiede', 'schmiede', 30000, 0, 'stahlplattenstiefel.gif', 0, 6, 0, 0, 0, 0),
(119, 0, 'Kampfstiefel', 'foots', 20, 35, 28, 0, 0, 0, 28, 6, 'schmiede', 'schmiede', 20000, 0, 'kampfstiefel.gif', 0, 5, 0, 0, 0, 0),
(118, 0, 'Klingenschuhe', 'foots', 15, 25, 20, 0, 0, 0, 20, 5, 'schmiede', 'schmiede', 13000, 0, 'klingenschuhe.gif', 0, 4, 0, 0, 0, 0),
(117, 0, 'Plattenstiefel', 'foots', 10, 15, 14, 0, 0, 0, 14, 4, 'schmiede', 'schmiede', 7000, 0, 'plattenstiefel.gif', 0, 3, 0, 0, 0, 0),
(116, 0, 'Eisenschuhe', 'foots', 6, 10, 7, 0, 0, 0, 7, 3, 'schmiede', 'schmiede', 3000, 0, 'eisenschuhe.gif', 0, 3, 0, 0, 0, 0),
(115, 0, 'Lederstiefel', 'foots', 3, 3, 4, 0, 0, 0, 4, 2, 'schmiede', 'schmiede', 1200, 0, 'lederstiefel.gif', 0, 2, 0, 0, 0, 0),
(114, 0, 'Latschen', 'foots', 0, 2, 2, 0, 0, 0, 2, 1, 'schmiede', 'schmiede', 200, 0, 'latschen.gif', 0, 1, 0, 0, 0, 0),
(113, 0, 'Dornenhandschuhe', 'gloves', 10, 15, 0, 0, 0, 0, 30, 5, 'schmiede', 'schmiede', 20000, 0, 'dornenhandschuhe.gif', 0, 6, 0, 0, 0, 0),
(112, 0, 'gepolsterte Handschuhe', 'gloves', 0, 20, 0, 0, 0, 0, 23, 6, 'schmiede', 'schmiede', 13000, 0, 'gepolsterte_handschuhe.gif', 0, 5, 0, 0, 0, 0),
(111, 0, 'Schlagring', 'gloves', 5, 8, 0, 0, 0, 0, 16, 4, 'schmiede', 'schmiede', 7000, 0, 'schlagring.gif', 0, 4, 0, 0, 0, 0),
(110, 0, 'Kettenhandschuhe', 'gloves', 0, 7, 0, 0, 0, 0, 9, 3, 'schmiede', 'schmiede', 3000, 0, 'kettenhandschuhe.gif', 0, 3, 0, 0, 0, 0),
(109, 0, 'Lederhandschuhe', 'gloves', 0, 4, 0, 0, 0, 0, 5, 2, 'schmiede', 'schmiede', 1500, 0, 'lederhandschuhe.gif', 0, 2, 0, 0, 0, 0),
(108, 0, 'Stoffhandschuhe', 'gloves', 0, 2, 0, 0, 0, 0, 2, 1, 'schmiede', 'schmiede', 200, 0, 'stoffhandschuhe.gif', 0, 1, 0, 0, 0, 0),
(107, 0, 'verzierter Panzergürtel', 'belt', 0, 30, 36, 0, 0, 18, 0, 6, 'schmiede', 'schmiede', 15000, 0, 'verzierter_panzerguertel.gif', 0, 6, 0, 0, 0, 0),
(106, 0, 'Panzergürtel', 'belt', 0, 22, 24, 0, 0, 12, 0, 5, 'schmiede', 'schmiede', 8500, 0, 'panzerguertel.gif', 0, 5, 0, 0, 0, 0),
(105, 0, 'Schuppengürtel', 'belt', 0, 15, 15, 0, 0, 8, 0, 4, 'schmiede', 'schmiede', 5000, 0, 'schupperguertel.gif', 0, 4, 0, 0, 0, 0),
(104, 0, 'verzierter Gürtel', 'belt', 0, 10, 8, 0, 0, 4, 0, 3, 'schmiede', 'schmiede', 2500, 0, 'verzierter_guertel.gif', 0, 3, 0, 0, 0, 0),
(103, 0, 'Ledergürtel', 'belt', 0, 6, 4, 0, 0, 2, 0, 2, 'schmiede', 'schmiede', 1000, 0, 'lederguertel.gif', 0, 2, 0, 0, 0, 0),
(102, 0, 'Strick', 'belt', 0, 2, 2, 0, 0, 1, 0, 1, 'schmiede', 'schmiede', 100, 0, 'strick.gif', 0, 1, 0, 0, 0, 0),
(101, 0, 'Panzerkettenumhang', 'cape', 5, 20, 0, 0, 0, 25, 0, 5, 'schmiede', 'schmiede', 15000, 0, 'panzerkettenumhang.gif', 0, 6, 0, 0, 0, 0),
(100, 0, 'Kettenumhang', 'cape', 2, 15, 0, 0, 0, 20, 0, 4, 'schmiede', 'schmiede', 10000, 0, 'kettenumhang.gif', 0, 5, 0, 0, 0, 0),
(98, 0, 'Kute', 'cape', 1, 5, 0, 0, 0, 10, 0, 2, 'schmiede', 'schmiede', 2500, 0, 'kute.gif', 0, 2, 0, 0, 0, 0),
(99, 0, 'Lederumhang', 'cape', 0, 10, 0, 0, 0, 15, 0, 3, 'schmiede', 'schmiede', 5000, 0, 'lederumhang.gif', 0, 4, 0, 0, 0, 0),
(97, 0, 'Stoffumhang', 'cape', 0, 2, 0, 0, 0, 5, 0, 1, 'schmiede', 'schmiede', 1000, 0, 'stoffumhang.gif', 0, 1, 0, 0, 0, 0),
(96, 0, 'eiserne Beinplatte', 'lowbody', 0, 40, 0, 25, 20, 0, 0, 7, 'schmiede', 'schmiede', 15000, 0, 'eiserne_beinplatte.gif', 0, 6, 0, 0, 0, 0),
(95, 0, 'vergoldete Beinschürze', 'lowbody', 0, 32, 0, 20, 17, 0, 0, 6, 'schmiede', 'schmiede', 10000, 0, 'vergoldete_beinschuerze.gif', 0, 5, 0, 0, 0, 0),
(94, 0, 'Beinplatten', 'lowbody', 0, 25, 0, 16, 14, 0, 0, 5, 'schmiede', 'schmiede', 6500, 0, 'beinplatten.gif', 0, 4, 0, 0, 0, 0),
(93, 0, 'verzierte Beinschienen', 'lowbody', 0, 19, 0, 10, 9, 0, 0, 4, 'schmiede', 'schmiede', 4000, 0, 'verzierte_beinschienen.gif', 0, 4, 0, 0, 0, 0),
(92, 0, 'einfache Beinschürze', 'lowbody', 0, 14, 0, 8, 6, 0, 0, 3, 'schmiede', 'schmiede', 2500, 0, 'einfache_beinschuerze.gif', 0, 3, 0, 0, 0, 0),
(91, 0, 'einfache Beinschienen', 'lowbody', 0, 9, 0, 5, 3, 0, 0, 2, 'schmiede', 'schmiede', 1000, 0, 'einfache_beinschienen.gif', 0, 2, 0, 0, 0, 0),
(90, 0, 'Lederhose', 'lowbody', 0, 5, 0, 2, 1, 0, 0, 1, 'schmiede', 'schmiede', 200, 0, 'lederhose.gif', 0, 2, 0, 0, 0, 0),
(89, 0, 'Stoffhose', 'lowbody', 0, 1, 0, 0, 0, 0, 0, 1, 'schmiede', 'schmiede', 50, 0, 'stoffhose.gif', 0, 1, 0, 0, 0, 0),
(88, 0, 'Stahlplattenharnisch', 'armor', 0, 100, 0, 50, 0, 0, 0, 8, 'schmiede', 'schmiede', 75000, 0, 'stahlplattenharnisch.gif', 0, 6, 0, 0, 0, 0),
(87, 0, 'Goldrüstung', 'armor', 0, 75, 0, 44, 0, 0, 0, 7, 'schmiede', 'schmiede', 50000, 0, 'goldruestung.gif', 0, 5, 0, 0, 0, 0),
(86, 0, 'Eisenplattenharnisch', 'armor', 0, 60, 0, 38, 0, 0, 0, 6, 'schmiede', 'schmiede', 25000, 0, 'eisenplattenharnisch.gif', 0, 5, 0, 0, 0, 0),
(85, 0, 'Kriegerrüstung', 'armor', 0, 48, 0, 32, 0, 0, 0, 5, 'schmiede', 'schmiede', 15000, 0, 'kriegerruestung.gif', 0, 4, 0, 0, 0, 0),
(84, 0, 'Adelsrüstung', 'armor', 0, 33, 0, 26, 0, 0, 0, 4, 'schmiede', 'schmiede', 9000, 0, 'adelsruestung.gif', 0, 4, 0, 0, 0, 0),
(83, 0, 'Plattenharnisch', 'armor', 0, 23, 0, 20, 0, 0, 0, 3, 'schmiede', 'schmiede', 5000, 0, 'plattenharnisch.gif', 0, 3, 0, 0, 0, 0),
(82, 0, 'Kettenhemd', 'armor', 0, 15, 0, 14, 0, 0, 0, 2, 'schmiede', 'schmiede', 2500, 0, 'kettenhemd.gif', 0, 3, 0, 0, 0, 0),
(81, 0, 'Lederrüstung', 'armor', 0, 9, 0, 9, 0, 0, 0, 1, 'schmiede', 'schmiede', 1000, 0, 'lederruestung.gif', 0, 2, 0, 0, 0, 0),
(80, 0, 'Tunika', 'armor', 0, 5, 0, 4, 0, 0, 0, 1, 'schmiede', 'schmiede', 200, 0, 'tunika.gif', 0, 2, 0, 0, 0, 0),
(79, 0, 'Stoffrüstung', 'armor', 0, 2, 0, 1, 0, 0, 0, 1, 'schmiede', 'schmiede', 50, 0, 'stoffruestung.gif', 0, 1, 0, 0, 0, 0),
(78, 0, 'Stahlschulterplatte', 'shoulder', 0, 30, 0, 0, 25, 0, 0, 5, 'schmiede', 'schmiede', 15000, 0, 'stahlschulterplatte.gif', 0, 6, 0, 0, 0, 0),
(77, 0, 'Goldschulterplatte', 'shoulder', 0, 23, 0, 0, 19, 0, 0, 4, 'schmiede', 'schmiede', 10000, 0, 'goldschulterplatte.gif', 0, 5, 0, 0, 0, 0),
(76, 0, 'Eisenschulterplatte', 'shoulder', 0, 15, 0, 0, 15, 0, 0, 3, 'schmiede', 'schmiede', 6500, 0, 'eisenschulterplatte.gif', 0, 5, 0, 0, 0, 0),
(75, 0, 'Stachelschultern', 'shoulder', 3, 7, 0, 0, 11, 0, 0, 3, 'schmiede', 'schmiede', 4000, 0, 'stachelschultern.gif', 0, 4, 0, 0, 0, 0),
(74, 0, 'Bronzeschulterplatte', 'shoulder', 0, 7, 0, 0, 8, 0, 0, 2, 'schmiede', 'schmiede', 2500, 0, 'bronzeschulterplatte.gif', 0, 3, 0, 0, 0, 0),
(73, 0, 'Lederlappen', 'shoulder', 0, 4, 0, 0, 5, 0, 0, 2, 'schmiede', 'schmiede', 1000, 0, 'lederlappen.gif', 0, 2, 0, 0, 0, 0),
(72, 0, 'Felllappen', 'shoulder', 0, 2, 0, 0, 3, 0, 0, 1, 'schmiede', 'schmiede', 200, 0, 'felllappen.gif', 0, 2, 0, 0, 0, 0),
(71, 0, 'Stofflappen', 'shoulder', 0, 1, 0, 0, 1, 0, 0, 1, 'schmiede', 'schmiede', 50, 0, 'stofflappen.gif', 0, 1, 0, 0, 0, 0),
(70, 0, 'Offiziershelm', 'head', 10, 55, 0, 0, 40, 0, 0, 6, 'schmiede', 'schmiede', 50000, 0, 'offiziershelm.gif', 0, 6, 0, 0, 0, 0),
(69, 0, 'Galea', 'head', 0, 49, 0, 0, 36, 0, 0, 6, 'schmiede', 'schmiede', 30000, 0, 'galea.gif', 0, 6, 0, 0, 0, 0),
(68, 0, 'Thrakerhelm', 'head', 0, 40, 0, 0, 32, 0, 0, 5, 'schmiede', 'schmiede', 22000, 0, 'thrakerhelm.gif', 0, 5, 0, 0, 0, 0),
(67, 0, 'Vollhelm', 'head', 0, 32, 0, 0, 27, 0, 0, 5, 'schmiede', 'schmiede', 15500, 0, 'vollhelm.gif', 0, 5, 0, 0, 0, 0),
(66, 0, 'Eisenhelm', 'head', 0, 25, 0, 0, 22, 0, 0, 4, 'schmiede', 'schmiede', 11000, 0, 'eisenhelm.gif', 0, 4, 0, 0, 0, 0),
(65, 0, 'Wikingerhelm', 'head', 6, 15, 0, 0, 17, 0, 0, 4, 'schmiede', 'schmiede', 7000, 0, 'wikingerhelm.gif', 0, 4, 0, 0, 0, 0),
(64, 0, 'Erzkrone', 'head', 0, 14, 0, 0, 13, 0, 0, 3, 'schmiede', 'schmiede', 5000, 0, 'erzkrone.gif', 0, 3, 0, 0, 0, 0),
(63, 0, 'Hörner', 'head', 3, 7, 0, 0, 9, 0, 0, 3, 'schmiede', 'schmiede', 3000, 0, 'hoerner.gif', 0, 3, 0, 0, 0, 0),
(62, 0, 'Bronzehelm', 'head', 0, 7, 0, 0, 6, 0, 0, 2, 'schmiede', 'schmiede', 1500, 0, 'bronzehelm.gif', 0, 2, 0, 0, 0, 0),
(61, 0, 'Topfhelm', 'head', 0, 4, 0, 0, 3, 0, 0, 2, 'schmiede', 'schmiede', 500, 0, 'topfhelm.gif', 0, 2, 0, 0, 0, 0),
(60, 0, 'Lederkappe', 'head', 0, 2, 0, 0, 1, 0, 0, 1, 'schmiede', 'schmiede', 100, 0, 'lederkappe.gif', 0, 1, 0, 0, 0, 0),
(58, 0, 'Scutum', 'shield', 0, 140, 0, 50, 0, 0, 0, 7, 'schmiede', 'schmiede', 110000, 0, 'scutum.gif', 0, 6, 0, 0, 0, 0),
(59, 0, 'Kopftuch', 'head', 0, 1, 0, 0, 0, 0, 0, 1, 'schmiede', 'schmiede', 20, 0, 'kopftuch.gif', 0, 1, 0, 0, 0, 0),
(57, 0, 'Flügelschild', 'shield', 0, 120, 0, 44, 0, 0, 0, 6, 'schmiede', 'schmiede', 80000, 0, 'fluegelschild.gif', 0, 6, 0, 0, 0, 0),
(56, 0, 'Klingenschild', 'shield', 20, 80, 0, 38, 0, 0, 0, 5, 'schmiede', 'schmiede', 60000, 0, 'klingenschild.gif', 0, 5, 0, 0, 0, 0),
(55, 0, 'Eisenschild', 'shield', 0, 100, 0, 33, 0, 0, 0, 6, 'schmiede', 'schmiede', 43000, 0, 'eisenschild.gif', 0, 5, 0, 0, 0, 0),
(54, 0, 'Paradeschild', 'shield', 0, 80, 0, 28, 0, 0, 0, 5, 'schmiede', 'schmiede', 25000, 0, 'paradeschild.gif', 0, 4, 0, 0, 0, 0),
(53, 0, 'Buckler', 'shield', 0, 65, 0, 24, 0, 0, 0, 4, 'schmiede', 'schmiede', 15500, 0, 'buckler.gif', 0, 4, 0, 0, 0, 0),
(52, 0, 'Stachelschild', 'shield', 12, 50, 0, 20, 0, 0, 0, 3, 'schmiede', 'schmiede', 10000, 0, 'stachelschild.gif', 0, 3, 0, 0, 0, 0),
(51, 0, 'Bronzeschild', 'shield', 0, 55, 0, 16, 0, 0, 0, 4, 'schmiede', 'schmiede', 6000, 0, 'bronzeschild.gif', 0, 3, 0, 0, 0, 0),
(50, 0, 'Rundschild', 'shield', 0, 40, 0, 12, 0, 0, 0, 3, 'schmiede', 'schmiede', 2500, 0, 'rundschild.gif', 0, 3, 0, 0, 0, 0),
(49, 0, 'Dornenschild', 'shield', 5, 15, 0, 9, 0, 0, 0, 2, 'schmiede', 'schmiede', 1200, 0, 'dornenschild.gif', 0, 2, 0, 0, 0, 0),
(48, 0, 'einfacher Buckler', 'shield', 0, 9, 0, 6, 0, 0, 0, 2, 'schmiede', 'schmiede', 500, 0, 'einfacher_buckler.gif', 0, 2, 0, 0, 0, 0),
(47, 0, 'Holzschild', 'shield', 0, 4, 0, 3, 0, 0, 0, 1, 'schmiede', 'schmiede', 100, 0, 'holzschild.gif', 0, 1, 0, 0, 0, 0),
(46, 0, 'Holzplatte', 'shield', 0, 2, 0, 1, 0, 0, 0, 1, 'schmiede', 'schmiede', 20, 0, 'holzplatte.gif', 0, 1, 0, 0, 0, 0),
(45, 0, 'Flamberge (ZHW)', 'weapon', 400, 0, 50, 0, 0, 0, 0, 10, 'schmiede', 'schmiede', 400000, 0, 'flamberge.gif', 0, 6, 1, 0, 0, 0),
(44, 0, 'Claymore (ZHW)', 'weapon', 350, 0, 46, 0, 0, 0, 0, 9, 'schmiede', 'schmiede', 300000, 0, 'claymore.gif', 0, 6, 1, 0, 0, 0),
(43, 0, 'Bastardschwert (ZHW)', 'weapon', 315, 0, 44, 0, 0, 0, 0, 8, 'schmiede', 'schmiede', 250000, 0, 'bastardschwert.gif', 0, 6, 1, 0, 0, 0),
(42, 0, 'Kriegshammer (ZHW)', 'weapon', 280, 0, 40, 0, 0, 0, 0, 8, 'schmiede', 'schmiede', 190000, 0, 'kriegshammer.gif', 0, 5, 1, 0, 0, 0),
(41, 0, 'Henkersbeil (ZHW)', 'weapon', 250, 0, 36, 0, 0, 0, 0, 7, 'schmiede', 'schmiede', 145000, 0, 'henkersbeil.gif', 0, 5, 1, 0, 0, 0),
(40, 0, 'Hellebarde (ZHW)', 'weapon', 225, 0, 32, 0, 0, 0, 0, 7, 'schmiede', 'schmiede', 115000, 0, 'hellbarde.gif', 0, 5, 1, 0, 0, 0),
(39, 0, 'Doppelaxt (ZHW)', 'weapon', 200, 0, 28, 0, 0, 0, 0, 6, 'schmiede', 'schmiede', 90000, 0, 'doppelaxt.gif', 0, 4, 1, 0, 0, 0),
(38, 0, 'Breitschwert (ZHW)', 'weapon', 170, 0, 25, 0, 0, 0, 0, 6, 'schmiede', 'schmiede', 66000, 0, 'breitschwert.gif', 0, 4, 1, 0, 0, 0),
(37, 0, 'Streitaxt (ZHW)', 'weapon', 145, 0, 22, 0, 0, 0, 0, 5, 'schmiede', 'schmiede', 50000, 0, 'streitaxt.gif', 0, 4, 1, 0, 0, 0),
(36, 0, 'Lanze (ZHW)', 'weapon', 120, 0, 19, 0, 0, 0, 0, 5, 'schmiede', 'schmiede', 35000, 0, 'lanze.gif', 0, 4, 1, 0, 0, 0),
(35, 0, 'Langschwert (ZHW)', 'weapon', 100, 0, 16, 0, 0, 0, 0, 4, 'schmiede', 'schmiede', 25000, 0, 'Langschwert.gif', 0, 3, 1, 0, 0, 0),
(34, 0, 'Dreschflegel (ZHW)', 'weapon', 85, 0, 13, 0, 0, 0, 0, 4, 'schmiede', 'schmiede', 14000, 0, 'dreschflegel.gif', 0, 3, 1, 0, 0, 0),
(33, 0, 'Dreizack (ZHW)', 'weapon', 60, 0, 11, 0, 0, 0, 0, 3, 'schmiede', 'schmiede', 7000, 0, 'dreizack.gif', 0, 3, 1, 0, 0, 0),
(32, 0, 'große Axt (ZHW)', 'weapon', 40, 0, 9, 0, 0, 0, 0, 3, 'schmiede', 'schmiede', 3000, 0, 'grosse_axt.gif', 0, 2, 1, 0, 0, 0),
(31, 0, 'Speer (ZHW)', 'weapon', 25, 0, 7, 0, 0, 0, 0, 2, 'schmiede', 'schmiede', 1500, 0, 'sperr.gif', 0, 2, 1, 0, 0, 0),
(30, 0, 'Sense (ZHW)', 'weapon', 15, 0, 5, 0, 0, 0, 0, 2, 'schmiede', 'schmiede', 500, 0, 'sense.gif', 0, 2, 1, 0, 0, 0),
(29, 0, 'Mistgabel (ZHW)', 'weapon', 10, 0, 3, 0, 0, 0, 0, 1, 'schmiede', 'schmiede', 150, 0, 'mistgabel.gif', 0, 1, 1, 0, 0, 0),
(28, 0, 'Holzstab (ZHW)', 'weapon', 6, 0, 2, 0, 0, 0, 0, 1, 'schmiede', 'schmiede', 50, 0, 'holzstab.gif', 0, 1, 1, 0, 0, 0),
(27, 0, 'Ast (ZHW)', 'weapon', 2, 0, 1, 0, 0, 0, 0, 1, 'schmiede', 'schmiede', 20, 0, 'ast.gif', 0, 1, 1, 0, 0, 0),
(26, 0, 'Kurzschwert (ZW)', 'shield', 115, 0, 33, 33, 0, 0, 0, 5, 'schmiede', 'schmiede', 75000, 0, 'kurzschwert.gif', 0, 6, 0, 0, 0, 0),
(25, 0, 'Beil (ZW)', 'shield', 95, 0, 30, 30, 0, 0, 0, 5, 'schmiede', 'schmiede', 40000, 0, 'beil.gif', 0, 5, 0, 0, 0, 0),
(24, 0, 'Langdolch (ZW)', 'shield', 75, 0, 27, 28, 0, 0, 0, 4, 'schmiede', 'schmiede', 25000, 0, 'langdolch.gif', 0, 5, 0, 0, 0, 0),
(22, 0, 'Netz (ZW)', 'shield', 45, 0, 18, 20, 0, 0, 0, 3, 'schmiede', 'schmiede', 8000, 0, 'netz.gif', 0, 4, 0, 0, 0, 0),
(23, 0, 'Klingenbrecher (ZW)', 'shield', 60, 0, 24, 22, 0, 0, 0, 4, 'schmiede', 'schmiede', 15000, 0, 'klingenbrecher.gif', 0, 4, 0, 0, 0, 0),
(21, 0, 'Spitzdolch (ZW)', 'shield', 30, 0, 15, 13, 0, 0, 0, 3, 'schmiede', 'schmiede', 5000, 0, 'spitzdolch.gif', 0, 3, 0, 0, 0, 0),
(20, 0, 'Eisenfaust (ZW)', 'shield', 20, 0, 8, 9, 0, 0, 0, 2, 'schmiede', 'schmiede', 2000, 0, 'eisenfaust.gif', 0, 3, 0, 0, 0, 0),
(19, 0, 'Dolch (ZW)', 'shield', 12, 0, 6, 5, 0, 0, 0, 2, 'schmiede', 'schmiede', 750, 0, 'dolch.gif', 0, 2, 0, 0, 0, 0),
(18, 0, 'Schlagring (ZW)', 'shield', 5, 0, 2, 3, 0, 0, 0, 1, 'schmiede', 'schmiede', 250, 0, 'schlagring.gif', 0, 2, 0, 0, 0, 0),
(17, 0, 'Messer (ZW)', 'shield', 2, 0, 1, 1, 0, 0, 0, 1, 'schmiede', 'schmiede', 100, 0, 'messer.gif', 0, 1, 0, 0, 0, 0),
(16, 0, 'Stahlschwert (EHW)', 'weapon', 260, 0, 50, 0, 0, 0, 0, 8, 'schmiede', 'schmiede', 350000, 0, 'stahlschwert.gif', 0, 6, 0, 0, 0, 0),
(15, 0, 'Streitkolben (EHW)', 'weapon', 220, 0, 45, 0, 0, 0, 0, 7, 'schmiede', 'schmiede', 250000, 0, 'streitkolben.gif', 0, 6, 0, 0, 0, 0),
(14, 0, 'Eisenschwert (EHW)', 'weapon', 180, 0, 40, 0, 0, 0, 0, 7, 'schmiede', 'schmiede', 180000, 0, 'eisenschwert.gif', 0, 5, 0, 0, 0, 0),
(13, 0, 'Kriegsbeil (EHW)', 'weapon', 140, 0, 36, 0, 0, 0, 0, 6, 'schmiede', 'schmiede', 130000, 0, 'kriegsbeil.gif', 0, 5, 0, 0, 0, 0),
(12, 0, 'Morgenstern (EHW)', 'weapon', 115, 0, 32, 0, 0, 0, 0, 6, 'schmiede', 'schmiede', 90000, 0, 'morgenstern.gif', 0, 5, 0, 0, 0, 0),
(11, 0, 'Krummschwert (EHW)', 'weapon', 90, 0, 28, 0, 0, 0, 0, 5, 'schmiede', 'schmiede', 66000, 0, 'krummschwert.gif', 0, 4, 0, 0, 0, 0),
(9, 0, 'Säbel (EHW)', 'weapon', 55, 0, 20, 0, 0, 0, 0, 4, 'schmiede', 'schmiede', 25000, 0, 'saebel.gif', 0, 4, 0, 0, 0, 0),
(10, 0, 'Gladius (EHW)', 'weapon', 70, 0, 24, 0, 0, 0, 0, 5, 'schmiede', 'schmiede', 40000, 0, 'gladius.gif', 0, 4, 0, 0, 0, 0),
(8, 0, 'Kurzschwert (EHW)', 'weapon', 40, 0, 16, 0, 0, 0, 0, 4, 'schmiede', 'schmiede', 15000, 0, 'kurzschwert.gif', 0, 3, 0, 0, 0, 0),
(7, 0, 'Handbeil (EHW)', 'weapon', 30, 0, 12, 0, 0, 0, 0, 3, 'schmiede', 'schmiede', 9000, 0, 'handbeil.gif', 0, 3, 0, 0, 0, 0),
(6, 0, 'Holzhammer (EHW)', 'weapon', 22, 0, 9, 0, 0, 0, 0, 3, 'schmiede', 'schmiede', 4000, 0, 'holzhammer.gif', 0, 3, 0, 0, 0, 0),
(5, 0, 'Knüppel (EHW)', 'weapon', 15, 0, 6, 0, 0, 0, 0, 2, 'schmiede', 'schmiede', 2000, 0, 'knueppel.gif', 0, 2, 0, 0, 0, 0),
(4, 0, 'Eisenfaust (EHW)', 'weapon', 10, 0, 4, 0, 0, 0, 0, 2, 'schmiede', 'schmiede', 500, 0, 'eisenfaust.gif', 0, 2, 0, 0, 0, 0),
(3, 0, 'Sichel (EHW)', 'weapon', 6, 0, 2, 0, 0, 0, 0, 1, 'schmiede', 'schmiede', 150, 0, 'sichel.gif', 0, 2, 0, 0, 0, 0),
(2, 0, 'Holzschwert (EHW)', 'weapon', 3, 0, 1, 0, 0, 0, 0, 1, 'schmiede', 'schmiede', 50, 0, 'holzschwert.gif', 0, 1, 0, 0, 0, 0),
(1, 0, 'Stein (EHW)', 'weapon', 1, 0, 0, 0, 0, 0, 0, 1, 'schmiede', 'schmiede', 10, 0, 'stein.gif', 0, 1, 0, 0, 0, 0),
(131, 0, 'freier Item Slot', 'itemall', 0, 0, 0, 0, 0, 0, 0, 1, 'standard', 'standard', 0, 0, '', 0, 0, 0, 0, 0, 0),
(130, 0, 'Nichts', 'foots', 0, 1, 0, 0, 0, 0, 0, 1, 'standard', 'standard', 0, 0, 'keins.gif', 0, 0, 0, 0, 0, 0),
(129, 0, 'Nichts', 'gloves', 0, 1, 0, 0, 0, 0, 0, 1, 'standard', 'standard', 0, 0, 'keins.gif', 0, 0, 0, 0, 0, 0),
(128, 0, 'Nichts', 'belt', 0, 1, 0, 0, 0, 0, 0, 1, 'standard', 'standard', 0, 0, 'keins.gif', 0, 0, 0, 0, 0, 0),
(127, 0, 'Nichts', 'cape', 0, 1, 0, 0, 0, 0, 0, 1, 'standard', 'standard', 0, 0, 'keins.gif', 0, 0, 0, 0, 0, 0),
(125, 0, 'Nichts', 'shoulder', 0, 1, 0, 0, 0, 0, 0, 1, 'standard', 'standard', 0, 0, 'keins.gif', 0, 0, 0, 0, 0, 0),
(126, 0, 'Haare', 'lowbody', 0, 1, 0, 0, 0, 0, 0, 1, 'standard', 'standard', 0, 0, 'haare.gif', 0, 0, 0, 0, 0, 0),
(124, 0, 'Haare', 'head', 0, 1, 0, 0, 0, 0, 0, 1, 'standard', 'standard', 0, 0, 'haare.gif', 0, 0, 0, 0, 0, 0),
(123, 0, 'nackte Haut', 'armor', 0, 1, 0, 0, 0, 0, 0, 1, 'standard', 'standard', 0, 0, 'nackt.gif', 0, 0, 0, 0, 0, 0),
(122, 0, 'Nichts', 'shield', 0, 0, 0, 0, 0, 0, 0, 1, 'standard', 'standard', 0, 0, 'keins.gif', 0, 0, 0, 0, 0, 0),
(121, 0, 'Faust', 'weapon', 1, 0, 0, 0, 0, 0, 0, 1, 'standard', 'standard', 0, 0, 'faeuste.gif', 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `auszeichnung`
--

CREATE TABLE IF NOT EXISTS `auszeichnung` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `UID` int(10) unsigned NOT NULL,
  `Text` varchar(10000) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `img` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `auszeichnung`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `collected`
--

CREATE TABLE IF NOT EXISTS `collected` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `collected_name_1` int(10) NOT NULL,
  `collected_name_2` int(10) NOT NULL,
  `collected_name_3` int(10) NOT NULL,
  `collected_name_4` int(10) NOT NULL,
  `collected_name_5` int(10) NOT NULL,
  `collected_name_6` int(10) NOT NULL,
  `collected_name_7` int(10) NOT NULL,
  `collected_name_8` int(10) NOT NULL,
  `collected_name_9` int(10) NOT NULL,
  `collected_name_10` int(10) NOT NULL,
  `collected_name_11` int(10) NOT NULL,
  `collected_name_12` int(10) NOT NULL,
  `collected_name_13` int(10) NOT NULL,
  PRIMARY KEY (`id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `collected`
--

INSERT INTO `collected` (`id`, `user_id`, `collected_name_1`, `collected_name_2`, `collected_name_3`, `collected_name_4`, `collected_name_5`, `collected_name_6`, `collected_name_7`, `collected_name_8`, `collected_name_9`, `collected_name_10`, `collected_name_11`, `collected_name_12`, `collected_name_13`) VALUES
(1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `contest`
--

CREATE TABLE IF NOT EXISTS `contest` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `votes` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `contest`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `duelle`
--

CREATE TABLE IF NOT EXISTS `duelle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `duellant_1` int(10) unsigned NOT NULL,
  `duellant_2` int(10) unsigned NOT NULL,
  `einsatz` bigint(10) unsigned NOT NULL,
  `wann` int(10) unsigned NOT NULL,
  `turnier` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `duelle`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `duelle_report`
--

CREATE TABLE IF NOT EXISTS `duelle_report` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `winner` int(10) NOT NULL DEFAULT '0',
  `loser` int(10) NOT NULL DEFAULT '0',
  `report` text NOT NULL,
  `winner_level` int(10) NOT NULL DEFAULT '0',
  `loser_level` int(10) NOT NULL DEFAULT '0',
  `einsatz` int(50) NOT NULL DEFAULT '0',
  `schule` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `duelle_report`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `duelle_schule`
--

CREATE TABLE IF NOT EXISTS `duelle_schule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `duellant_1` int(10) unsigned NOT NULL,
  `duellant_2` int(10) unsigned NOT NULL,
  `wann` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `duelle_schule`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `duell_stats`
--

CREATE TABLE IF NOT EXISTS `duell_stats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `d1` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `d2` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `winner` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `wonlvl` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `loslvl` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `einsatz` int(10) unsigned NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `schule` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `duell_stats`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event` int(10) unsigned NOT NULL,
  `dauer` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `event`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `forum_answers`
--

CREATE TABLE IF NOT EXISTS `forum_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topics_id` int(10) unsigned NOT NULL,
  `user` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `message` mediumtext COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `forum_answers`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `forum_forums`
--

CREATE TABLE IF NOT EXISTS `forum_forums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `type` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT 'normal',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=100 ;

--
-- Daten für Tabelle `forum_forums`
--

INSERT INTO `forum_forums` (`id`, `name`, `type`) VALUES
(1, 'News', 'normal'),
(5, 'Fragen', 'normal'),
(6, 'Vorschl&auml;ge', 'normal'),
(7, 'OffTopic', 'normal'),
(9, 'Bugmeldungen', 'normal'),
(46, 'Archiv', 'normal'),
(8, 'Nachrichten aus aller Welt', 'normal'),
(11, 'Turniere', 'normal'),
(10, 'Teamforum', 'allyin');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `forum_read`
--

CREATE TABLE IF NOT EXISTS `forum_read` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `topic` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `forum_read`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `forum_topics`
--

CREATE TABLE IF NOT EXISTS `forum_topics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `forums_id` int(10) unsigned NOT NULL,
  `creator` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `name` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `hits` int(10) unsigned NOT NULL,
  `important` varchar(1) COLLATE latin1_general_ci NOT NULL DEFAULT 'n',
  `close` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `forum_topics`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gebote`
--

CREATE TABLE IF NOT EXISTS `gebote` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` int(10) unsigned NOT NULL,
  `bieter` int(10) unsigned NOT NULL,
  `preis` int(10) unsigned NOT NULL,
  `repay` varchar(1) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=410 ;

--
-- Daten für Tabelle `gebote`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `highscore`
--

CREATE TABLE IF NOT EXISTS `highscore` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `user_id` int(10) NOT NULL,
  `points` int(10) NOT NULL,
  `brutality` int(10) NOT NULL,
  `prestige` int(10) NOT NULL,
  `meds` int(10) NOT NULL,
  `exp` int(10) NOT NULL,
  `gold` int(10) NOT NULL,
  `pm` int(10) NOT NULL,
  `schule` int(10) NOT NULL,
  `regstamp` int(10) NOT NULL,
  `lonline` int(10) NOT NULL,
  `level` int(10) NOT NULL,
  `arenarang` int(10) NOT NULL,
  `Quest` int(10) NOT NULL,
  `char_1` int(10) NOT NULL,
  `char_2` int(10) NOT NULL,
  `char_3` int(10) NOT NULL,
  `char_4` int(10) NOT NULL,
  `char_5` int(10) NOT NULL,
  `char_all` int(10) NOT NULL,
  `char2_1` int(10) NOT NULL,
  `char2_2` int(10) NOT NULL,
  `char2_3` int(10) NOT NULL,
  `char2_4` int(10) NOT NULL,
  `char2_5` int(10) NOT NULL,
  `char2_all` int(10) NOT NULL,
  `trainer_1` int(10) NOT NULL,
  `trainer_2` int(10) NOT NULL,
  `trainer_3` int(10) NOT NULL,
  `trainer_4` int(10) NOT NULL,
  `trainer_5` int(10) NOT NULL,
  `trainer_6` int(10) NOT NULL,
  `trainer_all` int(10) NOT NULL,
  `move_1` int(10) NOT NULL,
  `move_2` int(10) NOT NULL,
  `move_3` int(10) NOT NULL,
  `move_4` int(10) NOT NULL,
  `move_5` int(10) NOT NULL,
  `move_6` int(10) NOT NULL,
  `move_7` int(10) NOT NULL,
  `move_8` int(10) NOT NULL,
  `move_9` int(10) NOT NULL,
  `move_10` int(10) NOT NULL,
  `move_11` int(10) NOT NULL,
  `move_12` int(10) NOT NULL,
  `move_13` int(10) NOT NULL,
  `move_14` int(10) NOT NULL,
  `move_15` int(10) NOT NULL,
  `move_all` int(10) NOT NULL,
  `tier_win` int(10) NOT NULL,
  `tier_lost` int(10) NOT NULL,
  `tier_points` int(10) NOT NULL,
  `arena_win` int(10) NOT NULL,
  `arena_lost` int(10) NOT NULL,
  `arena_points` int(10) NOT NULL,
  `prestige_win` int(10) NOT NULL,
  `prestige_lost` int(10) NOT NULL,
  `prestige_points` int(10) NOT NULL,
  `duell_win` int(10) NOT NULL,
  `duell_lost` int(10) NOT NULL,
  `duell_points` int(10) NOT NULL,
  `rang_win` int(10) NOT NULL,
  `rang_lost` int(10) NOT NULL,
  `rang_points` int(10) NOT NULL,
  `off` int(10) NOT NULL,
  `deff` int(10) NOT NULL,
  `turnierpoints` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `highscore`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `highscore30`
--

CREATE TABLE IF NOT EXISTS `highscore30` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `user_id` int(10) NOT NULL,
  `points` int(10) NOT NULL,
  `brutality` int(10) NOT NULL,
  `prestige` int(10) NOT NULL,
  `meds` int(10) NOT NULL,
  `exp` int(10) NOT NULL,
  `gold` int(10) NOT NULL,
  `pm` int(10) NOT NULL,
  `schule` int(10) NOT NULL,
  `regstamp` int(10) NOT NULL,
  `lonline` int(10) NOT NULL,
  `level` int(10) NOT NULL,
  `arenarang` int(10) NOT NULL,
  `Quest` int(10) NOT NULL,
  `char_1` int(10) NOT NULL,
  `char_2` int(10) NOT NULL,
  `char_3` int(10) NOT NULL,
  `char_4` int(10) NOT NULL,
  `char_5` int(10) NOT NULL,
  `char_all` int(10) NOT NULL,
  `char2_1` int(10) NOT NULL,
  `char2_2` int(10) NOT NULL,
  `char2_3` int(10) NOT NULL,
  `char2_4` int(10) NOT NULL,
  `char2_5` int(10) NOT NULL,
  `char2_all` int(10) NOT NULL,
  `trainer_1` int(10) NOT NULL,
  `trainer_2` int(10) NOT NULL,
  `trainer_3` int(10) NOT NULL,
  `trainer_4` int(10) NOT NULL,
  `trainer_5` int(10) NOT NULL,
  `trainer_6` int(10) NOT NULL,
  `trainer_all` int(10) NOT NULL,
  `move_1` int(10) NOT NULL,
  `move_2` int(10) NOT NULL,
  `move_3` int(10) NOT NULL,
  `move_4` int(10) NOT NULL,
  `move_5` int(10) NOT NULL,
  `move_6` int(10) NOT NULL,
  `move_7` int(10) NOT NULL,
  `move_8` int(10) NOT NULL,
  `move_9` int(10) NOT NULL,
  `move_10` int(10) NOT NULL,
  `move_11` int(10) NOT NULL,
  `move_12` int(10) NOT NULL,
  `move_13` int(10) NOT NULL,
  `move_14` int(10) NOT NULL,
  `move_15` int(10) NOT NULL,
  `move_all` int(10) NOT NULL,
  `tier_win` int(10) NOT NULL,
  `tier_lost` int(10) NOT NULL,
  `tier_points` int(10) NOT NULL,
  `arena_win` int(10) NOT NULL,
  `arena_lost` int(10) NOT NULL,
  `arena_points` int(10) NOT NULL,
  `prestige_win` int(10) NOT NULL,
  `prestige_lost` int(10) NOT NULL,
  `prestige_points` int(10) NOT NULL,
  `duell_win` int(10) NOT NULL,
  `duell_lost` int(10) NOT NULL,
  `duell_points` int(10) NOT NULL,
  `rang_win` int(10) NOT NULL,
  `rang_lost` int(10) NOT NULL,
  `rang_points` int(10) NOT NULL,
  `off` int(10) NOT NULL,
  `deff` int(10) NOT NULL,
  `turnierpoints` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `highscore30`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ips`
--

CREATE TABLE IF NOT EXISTS `ips` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `user` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Datum` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `ips`
--

INSERT INTO `ips` (`id`, `ip`, `user`, `Datum`) VALUES
(1, '::1', 'admin', '2011-11-28 10:10:52');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `level`
--

CREATE TABLE IF NOT EXISTS `level` (
  `Level` int(5) NOT NULL,
  `exp` int(10) NOT NULL,
  PRIMARY KEY (`Level`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `level`
--

INSERT INTO `level` (`Level`, `exp`) VALUES
(1, 28),
(2, 32),
(3, 36),
(4, 40),
(5, 48),
(6, 52),
(7, 60),
(8, 68),
(9, 80),
(10, 88),
(11, 100),
(12, 116),
(13, 132),
(14, 148),
(15, 168),
(16, 188),
(17, 212),
(18, 240),
(19, 268),
(20, 304),
(21, 340),
(22, 380),
(23, 424),
(24, 472),
(25, 524),
(26, 584),
(27, 652),
(28, 720),
(29, 800),
(30, 884),
(31, 976),
(32, 1076),
(33, 1188),
(34, 1304),
(35, 1432),
(36, 1572),
(37, 1720),
(38, 1884),
(39, 2056),
(40, 2244),
(41, 2440),
(42, 2656),
(43, 2884),
(44, 3124),
(45, 3384),
(46, 3656),
(47, 3948),
(48, 4256),
(49, 4580),
(50, 4924),
(51, 5284),
(52, 5660),
(53, 6056),
(54, 6472),
(55, 6904),
(56, 7356),
(57, 7824),
(58, 8312),
(59, 8816),
(60, 9336),
(61, 9872),
(62, 10424),
(63, 10988),
(64, 11568),
(65, 12164),
(66, 12768),
(67, 13380),
(68, 14004),
(69, 14632),
(70, 15268),
(71, 15908),
(72, 16548),
(73, 17192),
(74, 17840),
(75, 18500),
(76, 19180),
(77, 19876),
(78, 20580),
(79, 21300),
(80, 22032),
(81, 22780),
(82, 23540),
(83, 24316),
(84, 25116),
(85, 25940),
(86, 26796),
(87, 27676),
(88, 28588),
(89, 29480),
(90, 30400),
(91, 31348),
(92, 32316),
(93, 33316),
(94, 34332),
(95, 35372),
(96, 36416),
(97, 37488),
(98, 38580),
(99, 39688),
(100, 40000),
(101, 41120),
(102, 42256),
(103, 43412),
(104, 44604),
(105, 45844),
(106, 47140),
(107, 48500),
(108, 49948),
(109, 51492),
(110, 53092),
(111, 54752),
(112, 56504),
(113, 58304),
(114, 60208),
(115, 62296),
(116, 64512),
(117, 66912),
(118, 69576),
(119, 72424),
(120, 75496),
(121, 78740),
(122, 81940),
(123, 85140),
(124, 88340),
(125, 91540),
(126, 94740),
(127, 97940),
(128, 101140),
(129, 104340),
(130, 107540),
(131, 110740),
(132, 113940),
(133, 117140),
(134, 120340),
(135, 123540),
(136, 126740),
(137, 129940),
(139, 136340),
(140, 139540),
(141, 142740),
(142, 145940),
(138, 133140),
(163, 221940),
(162, 217940),
(143, 149140),
(144, 152340),
(145, 155540),
(146, 158740),
(147, 161940),
(148, 165140),
(149, 168340),
(150, 171540),
(151, 174740),
(152, 177940),
(153, 181940),
(154, 185940),
(155, 189940),
(156, 193940),
(157, 197940),
(158, 201940),
(159, 205940),
(160, 209940),
(161, 213940),
(164, 225940),
(165, 229940),
(166, 233940),
(167, 237940),
(168, 241940),
(169, 245940),
(170, 249940),
(171, 253940),
(172, 4000000);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `markt`
--

CREATE TABLE IF NOT EXISTS `markt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Item` int(10) unsigned NOT NULL,
  `Verkaeufer` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Kaeufer` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Preis` int(10) unsigned NOT NULL,
  `Datum` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `markt`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `moves`
--

CREATE TABLE IF NOT EXISTS `moves` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `kraftschlag` int(10) unsigned NOT NULL DEFAULT '0',
  `wutschrei` int(10) unsigned NOT NULL DEFAULT '0',
  `armor` int(10) unsigned NOT NULL DEFAULT '0',
  `kritischer_schlag` int(10) unsigned NOT NULL DEFAULT '0',
  `wundhieb` int(10) unsigned NOT NULL DEFAULT '0',
  `kraftschrei` int(10) unsigned NOT NULL DEFAULT '0',
  `block` int(10) unsigned NOT NULL DEFAULT '0',
  `taeuschen` int(10) unsigned NOT NULL DEFAULT '0',
  `koerperteil_abschlagen` int(10) unsigned NOT NULL DEFAULT '0',
  `sand_werfen` int(10) unsigned NOT NULL DEFAULT '0',
  `ausweichen` int(10) unsigned NOT NULL DEFAULT '0',
  `todesschlag` int(10) unsigned NOT NULL DEFAULT '0',
  `konter` int(10) unsigned NOT NULL DEFAULT '0',
  `berserker` int(10) unsigned NOT NULL DEFAULT '0',
  `anti_def` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `moves`
--

INSERT INTO `moves` (`id`, `uid`, `kraftschlag`, `wutschrei`, `armor`, `kritischer_schlag`, `wundhieb`, `kraftschrei`, `block`, `taeuschen`, `koerperteil_abschlagen`, `sand_werfen`, `ausweichen`, `todesschlag`, `konter`, `berserker`, `anti_def`) VALUES
(1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `multiverdacht`
--

CREATE TABLE IF NOT EXISTS `multiverdacht` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `ipfromuser` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `multiverdacht`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nachrichten`
--

CREATE TABLE IF NOT EXISTS `nachrichten` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `empfaenger` varchar(100) NOT NULL,
  `absender` varchar(100) NOT NULL,
  `titel` varchar(50) NOT NULL,
  `text` text NOT NULL,
  `datum` int(10) unsigned NOT NULL,
  `gelesen` varchar(1) NOT NULL,
  `del_empfaenger` varchar(1) NOT NULL DEFAULT 'n',
  `del_absender` varchar(1) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`),
  KEY `empfaenger` (`empfaenger`),
  KEY `empfaenger_2` (`empfaenger`,`gelesen`,`del_empfaenger`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `nachrichten`
--

INSERT INTO `nachrichten` (`id`, `empfaenger`, `absender`, `titel`, `text`, `datum`, `gelesen`, `del_empfaenger`, `del_absender`) VALUES
(1, 'admin', 'admin', 'Willkommen bei Gladiatorz!', '\r\nGuten Tag, admin!\r\n\r\nWillkommen bei Gladiatorz.\r\nUm dir den Einstieg etwas zu erleichtern möchten wir dir kurz das Wichtigste im Spiel erklären.\r\n\r\nPowMod (PM):\r\nMithilfe von PM kannst du hier kämpfen und dein Charakter skillen. Jeder siegreiche Kampf verbraucht 0.1 PM. Bei einer Niederlage kann es vorkommen, dass du mehr verbrauchst. Wenn deine PM leer sind musst du bis zur nächsten vollen Stunde warten, dann bekommst du neue PM.\r\nIm Gasthaus kannst du dich auch für wenig Gold schlafen legen und somit die Regeneration deiner PM erhöhen.\r\n\r\nSpezial Moves:\r\nFür jedes neu erreichte Level bekommst du 2 Spezial Moves Punkte, diese kannst du benutzen um deine Spezial Moves auszubauen. Mit Level 30, 60 und 90 werden neue Spezial Moves freigeschalten. Viele von den Spezial Moves benötigen aber eine bestimmte Mindestmenge eines anderen Spezial Moves um trainiert zu werden.\r\n\r\nDie Kämpfe:\r\nBei Gladiatorz gibt es viele Arten zu kämpfen. Die Wichtigste ist der Kampf in der Tiergrube. Dort bekommst du Gold, Erfahrung und mit etwas Glück neue Items.\r\nGegen andere Gladiatoren kannst du in der Arena und bei Duellen kämpfen. Das Besondere bei Duellen ist das du dort keine PM verbrauchst.\r\nWenn dir die Tiergrube zu lahm ist, kannst du auch Prestigekämpfe machen. Dort kämpfst du gegen mehrere Tiere und kannst am ende des Tages Medaillen gewinnen.\r\n\r\nDie Schulen (Clans):\r\nIn Schulen treffen sich Gladiatoren die einen ähnlichen Kampfstil haben. Durch die jeweiligen Schulentaktiken wird man dann im Kampf verstärkt. Außerdem bringen Schulen auch einen Bonus außerhalb von Kämpfen, wie zum Beispiel eine höhere Regeneration deiner PM während des Schlafens.\r\n\r\nFalls du noch Fragen hast stehen dir jederzeit User in der Shoutbox zur Verfügung und auch unser Wiki kann einige weitere Fragen beantworten.\r\n\r\nmfg\r\ndein Gladiatorzteam', 1322470835, 'n', 'n', 'n'),
(2, 'Basarleitung', 'admin', 'Willkommen bei Gladiatorz!', '\r\nGuten Tag, Basarleitung!\r\n\r\nWillkommen bei Gladiatorz.\r\nUm dir den Einstieg etwas zu erleichtern möchten wir dir kurz das Wichtigste im Spiel erklären.\r\n\r\nPowMod (PM):\r\nMithilfe von PM kannst du hier kämpfen und dein Charakter skillen. Jeder siegreiche Kampf verbraucht 0.1 PM. Bei einer Niederlage kann es vorkommen, dass du mehr verbrauchst. Wenn deine PM leer sind musst du bis zur nächsten vollen Stunde warten, dann bekommst du neue PM.\r\nIm Gasthaus kannst du dich auch für wenig Gold schlafen legen und somit die Regeneration deiner PM erhöhen.\r\n\r\nSpezial Moves:\r\nFür jedes neu erreichte Level bekommst du 2 Spezial Moves Punkte, diese kannst du benutzen um deine Spezial Moves auszubauen. Mit Level 30, 60 und 90 werden neue Spezial Moves freigeschalten. Viele von den Spezial Moves benötigen aber eine bestimmte Mindestmenge eines anderen Spezial Moves um trainiert zu werden.\r\n\r\nDie Kämpfe:\r\nBei Gladiatorz gibt es viele Arten zu kämpfen. Die Wichtigste ist der Kampf in der Tiergrube. Dort bekommst du Gold, Erfahrung und mit etwas Glück neue Items.\r\nGegen andere Gladiatoren kannst du in der Arena und bei Duellen kämpfen. Das Besondere bei Duellen ist das du dort keine PM verbrauchst.\r\nWenn dir die Tiergrube zu lahm ist, kannst du auch Prestigekämpfe machen. Dort kämpfst du gegen mehrere Tiere und kannst am ende des Tages Medaillen gewinnen.\r\n\r\nDie Schulen (Clans):\r\nIn Schulen treffen sich Gladiatoren die einen ähnlichen Kampfstil haben. Durch die jeweiligen Schulentaktiken wird man dann im Kampf verstärkt. Außerdem bringen Schulen auch einen Bonus außerhalb von Kämpfen, wie zum Beispiel eine höhere Regeneration deiner PM während des Schlafens.\r\n\r\nFalls du noch Fragen hast stehen dir jederzeit User in der Shoutbox zur Verfügung und auch unser Wiki kann einige weitere Fragen beantworten.\r\n\r\nmfg\r\ndein Gladiatorzteam', 1322470896, 'n', 'n', 'n');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `open_fights`
--

CREATE TABLE IF NOT EXISTS `open_fights` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user1` int(10) NOT NULL,
  `user2` int(10) NOT NULL,
  `status` int(5) NOT NULL,
  `time` int(10) NOT NULL,
  `winner` int(10) NOT NULL DEFAULT '0',
  `report` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `open_fights`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `open_fights_report`
--

CREATE TABLE IF NOT EXISTS `open_fights_report` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `winner` int(10) NOT NULL DEFAULT '0',
  `loser` int(10) NOT NULL DEFAULT '0',
  `report` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `open_fights_report`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `quest_all`
--

CREATE TABLE IF NOT EXISTS `quest_all` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `short` varchar(2000) NOT NULL,
  `aktiv` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `quest_all`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `quest_all_what`
--

CREATE TABLE IF NOT EXISTS `quest_all_what` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `quest_id` int(10) DEFAULT NULL,
  `what` enum('1','2','3','4','5','6','7','8','9','10','11','12','13') NOT NULL,
  `count` int(10) NOT NULL,
  `max` int(10) NOT NULL,
  `gold` int(10) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `quest_all_what`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `quest_today`
--

CREATE TABLE IF NOT EXISTS `quest_today` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `count` int(10) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `quest_today`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `quest_what`
--

CREATE TABLE IF NOT EXISTS `quest_what` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `typ` varchar(20) NOT NULL,
  `animal_id` int(10) NOT NULL,
  `maxcount` int(10) NOT NULL,
  `count` int(10) NOT NULL,
  `win` varchar(20) NOT NULL,
  `wincount` float NOT NULL,
  `dif` int(10) NOT NULL,
  `aktiv` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `quest_what`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rangnpcs`
--

CREATE TABLE IF NOT EXISTS `rangnpcs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rang` int(2) NOT NULL DEFAULT '1',
  `place` int(10) unsigned NOT NULL,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `off` int(10) unsigned NOT NULL,
  `def` int(10) unsigned NOT NULL,
  `hp` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `rangnpcs`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `multfights` int(10) unsigned NOT NULL DEFAULT '0',
  `werbung` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `settings`
--

INSERT INTO `settings` (`id`, `user_id`, `multfights`, `werbung`) VALUES
(1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_ally_geleande`
--

CREATE TABLE IF NOT EXISTS `settings_ally_geleande` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `second` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `descrfirst` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `descrsecond` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `cost` varchar(20) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `settings_ally_geleande`
--

INSERT INTO `settings_ally_geleande` (`id`, `first`, `second`, `descrfirst`, `descrsecond`, `cost`) VALUES
(5, 'Unterkunft', 'große Unterkunft', 'In der normalen Unterkunft haben 25 Gladiatoren Platz.', 'Die große Unterkunft ragt über das halbe Gelände deiner Schule und bietet Platz für 60 Gladiatoren.', '60000|60'),
(4, 'Schmiede', 'große Schmiede', 'Durch eine kleine Spende an den örtlichen Schmied veringern sich die Preise ein wenig.', 'Eine Schmiede auf deinem eigenen Gelände! Du musst nur noch die Produktionskosten bezahlen, das gibt also einen ordentlichen Preisnachlas gegenüber der örtlichen Schmiede.', '50000|20'),
(3, 'Schenke', 'Wirtshaus', 'Endlich ordentlich was zu essen! Hier kannst du deinen Hunger weitaus effektiver stillen als im Gasthaus.', 'Das große Wirtshaus bietet alles was man essen kann.', '50000|20'),
(2, 'Schlafraum', 'Schlafhaus', 'Ziemlich klein, aber wenigstens gemütlicher als die öffentlichen Schlafhallen (+0.5 PM/h zusätzlich).', 'Geradezu luxuriös eingerichtet bietet dieses Haus allen wünschenswerten Komfort (+1 PM/h zusätzlich).', '100000|25'),
(1, 'Trainingshalle', 'große Trainingshalle', 'Hier kannst du deine Fähigkeiten bis insgesamt 100 Punkte trainieren. Das Training kostet dich nur noch 0.5 PM.', 'Hier kannst du deine Fähigkeiten bis insgesamt 250 Punkte trainieren. Das Training kostet dich bis 125 keinen PM mehr.', '50000|50'),
(6, 'Tierkäfig', 'Tierkäfig deluxe', 'In der Tiergrube bekommt Ihr + 1EXP und + 10% Gold.', 'In der Tiergrube bekommt Ihr + 2EXP und + 20% Gold.', '100000|80');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `settings_untereigenschaften`
--

CREATE TABLE IF NOT EXISTS `settings_untereigenschaften` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `Grundkosten` int(10) unsigned NOT NULL,
  `GrundPM` float unsigned NOT NULL,
  `KostenSteigerung` float unsigned NOT NULL,
  `PMSteigerung` float unsigned NOT NULL,
  `Auswirkung` float unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `settings_untereigenschaften`
--

INSERT INTO `settings_untereigenschaften` (`id`, `name`, `Grundkosten`, `GrundPM`, `KostenSteigerung`, `PMSteigerung`, `Auswirkung`) VALUES
(1, 'Schlagkraft', 3000000, 12.2, 0.07, 0.3, 5),
(2, 'Einstecken', 3000000, 12.2, 0.07, 0.3, 4),
(3, 'Kraftprotz', 3000000, 12.2, 0.07, 0.3, 50),
(4, 'Glueck', 3000000, 12.2, 0.07, 0.3, 0.5),
(5, 'Sammler', 3000000, 12.2, 0.07, 0.3, 0.4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `shoutbox`
--

CREATE TABLE IF NOT EXISTS `shoutbox` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` int(10) unsigned NOT NULL,
  `from` int(10) unsigned NOT NULL,
  `fromname` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `msg` varchar(400) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `shoutbox`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sitting`
--

CREATE TABLE IF NOT EXISTS `sitting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user` varchar(100) NOT NULL,
  `sitter` varchar(100) NOT NULL,
  `start` date NOT NULL,
  `ende` date NOT NULL,
  `text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `sitting`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tiergrube`
--

CREATE TABLE IF NOT EXISTS `tiergrube` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL COMMENT 'Name des Tieres',
  `health` int(10) unsigned NOT NULL COMMENT 'Kraft des Tieres',
  `exp` int(10) unsigned NOT NULL COMMENT 'EXP des Teires',
  `attack` int(10) unsigned NOT NULL COMMENT 'Off des Tieres',
  `armor` int(10) unsigned NOT NULL COMMENT 'Def des Tieres',
  `gold` int(10) unsigned NOT NULL COMMENT 'Gold des Tieres',
  `drop` int(10) unsigned NOT NULL COMMENT 'keine Ahnung',
  `prestige` int(10) NOT NULL DEFAULT '0' COMMENT 'Für berechnung des Prestiges',
  `Klasse` int(10) unsigned NOT NULL COMMENT 'Klasse des Tieres, wichtig für TG anzeige',
  `arti1` int(10) unsigned NOT NULL COMMENT 'wahrscheinlichkeit1',
  `arti2` int(10) unsigned NOT NULL COMMENT 'wahrscheinlichkeit2',
  `klasse1` int(10) unsigned NOT NULL COMMENT 'itemklasse1',
  `klasse2` int(10) unsigned NOT NULL COMMENT 'itemklasse2',
  `klasse3` int(10) unsigned NOT NULL COMMENT 'itemklasse3',
  `move1` int(10) unsigned NOT NULL COMMENT 'Der erste Move',
  `move2` int(10) unsigned NOT NULL COMMENT 'Der zweite Move',
  `move3` int(10) unsigned NOT NULL COMMENT 'Der dritte Move',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=51 ;

--
-- Daten für Tabelle `tiergrube`
--

INSERT INTO `tiergrube` (`id`, `name`, `health`, `exp`, `attack`, `armor`, `gold`, `drop`, `prestige`, `Klasse`, `arti1`, `arti2`, `klasse1`, `klasse2`, `klasse3`, `move1`, `move2`, `move3`) VALUES
(1, 'Bisamratte', 10, 1, 1, 0, 2, 0, 1, 1, 96, 100, 1, 2, 3, 9, 0, 0),
(2, 'Reh', 20, 1, 3, 0, 5, 0, 1, 1, 95, 99, 1, 2, 3, 9, 0, 0),
(3, 'Antilope', 45, 1, 6, 4, 8, 0, 1, 1, 90, 98, 1, 2, 3, 9, 0, 0),
(4, 'Kudu', 80, 1, 9, 7, 13, 0, 1, 1, 81, 94, 1, 2, 3, 10, 11, 0),
(5, 'Hirsch', 130, 2, 14, 10, 22, 0, 1, 1, 73, 91, 1, 2, 3, 10, 12, 0),
(6, 'Steinbock', 180, 2, 20, 15, 40, 0, 2, 1, 60, 87, 1, 2, 3, 9, 13, 0),
(7, 'Fuchs', 350, 3, 40, 25, 65, 0, 2, 1, 45, 83, 1, 2, 3, 11, 32, 0),
(8, 'Schakal', 500, 3, 70, 35, 90, 0, 2, 1, 30, 78, 1, 2, 3, 11, 0, 0),
(9, 'Warzenschwein', 510, 3, 65, 43, 100, 0, 3, 2, 15, 76, 1, 2, 3, 13, 15, 0),
(10, 'Wildschwein', 625, 4, 80, 60, 140, 0, 3, 2, 75, 98, 2, 3, 4, 11, 14, 0),
(11, 'Hund', 750, 4, 85, 50, 130, 0, 3, 2, 70, 97, 2, 3, 4, 11, 14, 0),
(12, 'Strauß', 950, 4, 100, 65, 150, 0, 3, 2, 65, 95, 2, 3, 4, 16, 17, 0),
(13, 'Pferd', 1200, 4, 130, 90, 185, 0, 4, 2, 55, 91, 2, 3, 4, 12, 13, 0),
(14, 'Steinadler', 1050, 5, 160, 70, 195, 0, 4, 2, 45, 87, 2, 3, 4, 16, 18, 0),
(15, 'Andenkondor', 1150, 5, 160, 100, 210, 0, 4, 2, 40, 86, 2, 3, 4, 16, 18, 0),
(16, 'Panda', 1300, 5, 160, 120, 220, 0, 4, 2, 35, 84, 2, 3, 4, 11, 19, 0),
(17, 'Luchs', 1500, 5, 190, 140, 260, 0, 4, 2, 25, 82, 2, 3, 4, 20, 21, 22),
(18, 'Gorilla', 1800, 5, 230, 160, 300, 0, 4, 3, 15, 78, 2, 3, 4, 20, 21, 22),
(19, 'Wildhundrudel', 2200, 6, 270, 190, 350, 0, 4, 3, 5, 75, 2, 3, 4, 11, 24, 0),
(20, 'Keiler', 2500, 6, 320, 240, 400, 0, 5, 3, 65, 96, 3, 4, 5, 11, 13, 0),
(21, 'Hyäne', 2750, 7, 370, 220, 450, 0, 5, 3, 58, 94, 3, 4, 5, 11, 0, 0),
(22, 'Gaur', 3000, 7, 400, 250, 500, 0, 5, 3, 50, 92, 3, 4, 5, 13, 25, 0),
(23, 'Wolf', 2800, 7, 410, 240, 510, 0, 6, 3, 40, 90, 3, 4, 5, 11, 26, 0),
(24, 'Kaiman', 3000, 8, 440, 280, 540, 0, 6, 3, 30, 88, 3, 4, 5, 11, 27, 0),
(25, 'Stier', 3500, 8, 500, 320, 600, 0, 6, 3, 20, 85, 3, 4, 5, 13, 25, 29),
(26, 'Leopard', 4100, 8, 580, 370, 700, 0, 7, 4, 10, 82, 3, 4, 5, 11, 29, 0),
(27, 'Gepard', 4600, 9, 620, 400, 750, 0, 7, 4, 80, 98, 4, 5, 6, 13, 25, 0),
(28, 'Wisent', 4900, 9, 650, 420, 820, 0, 7, 4, 77, 97, 4, 5, 6, 13, 25, 0),
(29, 'Schwarzbär', 5600, 9, 720, 500, 880, 0, 8, 4, 74, 96, 4, 5, 6, 11, 19, 0),
(30, 'Panzernashorn', 6000, 9, 720, 500, 920, 0, 8, 4, 70, 94, 4, 5, 6, 13, 0, 0),
(31, 'Büffel', 6666, 9, 800, 620, 970, 0, 8, 4, 65, 91, 4, 5, 6, 13, 25, 0),
(32, 'Komodowaran', 7000, 10, 900, 600, 1050, 0, 9, 4, 60, 88, 4, 5, 6, 11, 30, 0),
(33, 'Löwe', 7600, 10, 1050, 670, 1250, 0, 9, 4, 55, 86, 4, 5, 6, 11, 29, 0),
(34, 'Braunbär', 8500, 11, 1200, 700, 1600, 0, 9, 5, 48, 83, 4, 5, 6, 11, 19, 29),
(35, 'Nil-Krokodil', 9500, 11, 1400, 850, 2000, 0, 10, 5, 42, 81, 4, 5, 6, 11, 27, 0),
(36, 'Nashorn', 10400, 12, 1500, 1100, 2300, 0, 10, 5, 36, 78, 4, 5, 6, 13, 25, 0),
(37, 'Nilpferd', 11000, 12, 1300, 1300, 2500, 0, 10, 5, 30, 74, 4, 5, 6, 11, 13, 0),
(38, 'Elefant', 13000, 13, 1600, 1500, 3000, 0, 11, 5, 24, 66, 4, 5, 6, 11, 25, 0),
(39, 'Wolfsrudel', 12000, 13, 1800, 1400, 3800, 0, 11, 5, 18, 55, 4, 5, 6, 24, 33, 0),
(40, 'Stierherde', 14000, 14, 1900, 1500, 4500, 0, 11, 5, 12, 42, 4, 5, 6, 13, 25, 0),
(41, 'Löwenrudel', 15000, 15, 2000, 1600, 5555, 0, 11, 5, 6, 31, 4, 5, 6, 24, 33, 0),
(42, 'hungrige Eisbären', 16000, 16, 2200, 1800, 5800, 0, 12, 5, 11, 25, 4, 5, 6, 33, 25, 29),
(43, 'Geierschwarm', 17700, 18, 2400, 1850, 6500, 0, 4, 5, 0, 10, 4, 5, 6, 16, 18, 0),
(44, 'Krokodilbecken', 18000, 18, 2500, 1950, 7000, 0, 0, 5, 0, 8, 4, 5, 6, 11, 27, 0),
(45, 'Gorillarudel', 20000, 19, 2600, 2050, 7300, 0, 0, 5, 0, 5, 4, 5, 6, 20, 22, 29),
(46, 'Grizzly', 22000, 20, 3000, 2150, 7500, 0, 13, 5, 0, 5, 4, 5, 6, 22, 25, 35),
(47, 'Anakonda', 24000, 21, 3200, 2250, 7700, 0, 14, 5, 0, 5, 4, 5, 6, 27, 42, 35),
(48, 'Schlangengrube', 26000, 22, 3600, 2350, 8000, 0, 15, 5, 0, 5, 4, 5, 6, 27, 33, 35),
(49, 'Tiger', 28000, 23, 4200, 2450, 8300, 0, 16, 5, 0, 5, 4, 5, 6, 29, 32, 35),
(50, 'Kriegselefant', 30000, 24, 5000, 2550, 10000, 0, 17, 5, 0, 0, 4, 5, 6, 35, 36, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tiergrube_moves`
--

CREATE TABLE IF NOT EXISTS `tiergrube_moves` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `kraftschlag` int(10) unsigned NOT NULL DEFAULT '0',
  `wutschrei` int(10) unsigned NOT NULL DEFAULT '0',
  `armor` int(10) unsigned NOT NULL DEFAULT '0',
  `kritischer_schlag` int(10) unsigned NOT NULL DEFAULT '0',
  `wundhieb` int(10) unsigned NOT NULL DEFAULT '0',
  `kraftschrei` int(10) unsigned NOT NULL DEFAULT '0',
  `block` int(10) unsigned NOT NULL DEFAULT '0',
  `taeuschen` int(10) unsigned NOT NULL DEFAULT '0',
  `koerperteil_abschlagen` int(10) unsigned NOT NULL DEFAULT '0',
  `sand_werfen` int(10) unsigned NOT NULL DEFAULT '0',
  `ausweichen` int(10) unsigned NOT NULL DEFAULT '0',
  `todesschlag` int(10) unsigned NOT NULL DEFAULT '0',
  `konter` int(10) unsigned NOT NULL DEFAULT '0',
  `berserker` int(10) unsigned NOT NULL DEFAULT '0',
  `anti_def` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Daten für Tabelle `tiergrube_moves`
--

INSERT INTO `tiergrube_moves` (`id`, `uid`, `kraftschlag`, `wutschrei`, `armor`, `kritischer_schlag`, `wundhieb`, `kraftschrei`, `block`, `taeuschen`, `koerperteil_abschlagen`, `sand_werfen`, `ausweichen`, `todesschlag`, `konter`, `berserker`, `anti_def`) VALUES
(1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 7, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, 9, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(10, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(11, 11, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(12, 12, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(13, 13, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(14, 14, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(15, 15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(16, 16, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(17, 17, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(18, 18, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(19, 19, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(20, 20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(21, 21, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(22, 22, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(23, 23, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(24, 24, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(25, 25, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(26, 26, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(27, 27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(28, 28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(29, 29, 100, 100, 0, 100, 0, 100, 0, 0, 100, 0, 0, 100, 0, 0, 0),
(30, 30, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(31, 31, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(32, 32, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(33, 33, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(34, 34, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(35, 35, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(36, 36, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(37, 37, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(38, 38, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(39, 39, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tipps`
--

CREATE TABLE IF NOT EXISTS `tipps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipp` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `autor` varchar(200) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=78 ;

--
-- Daten für Tabelle `tipps`
--

INSERT INTO `tipps` (`id`, `tipp`, `autor`) VALUES
(1, 'Auf dem Basar kann man oft gute Items erwerben. Beachte aber die  Mindestvoraussetzungen, vor allem den nötigen Mindestrang', 'donnergott88'),
(2, 'Die meisten Punkte bekommt man in der Arena.', 'donnergott88'),
(3, 'Mit Hilfe von Prestige bekommt man Medaillen.', 'donnergott88'),
(4, 'Bei Wetten kann man mit etwas Glück  Gold verdienen.', 'donnergott88'),
(5, 'Starke Tiere droppen stärkere Artefakte.', 'donnergott88'),
(6, 'Schaut täglich im Forum nach neuen News.', 'donnergott88'),
(7, 'Auch für Prestigekämpfe gibt es EXP.', 'donnergott88'),
(8, 'Bei Fragen wendet euch an die Admins, Mods, Gamemasters  eure Schulenleitung, schaut auf die Pinnwand eurer Schule oder schreibt sie ins Forum.', 'wildthings'),
(9, 'Durch Charisma erhöht sich das Prestige bei Prestigekämpfen.', 'donnergott88'),
(10, 'Kondition, Stärke, Heilkunde, Geschick, Kraftprotz und euer Level erhöhen die Kraft.', 'donnergott88'),
(11, 'Seinen Rang kann man durch alles was EXP gibt erhöhen.', 'donnergott88'),
(13, 'Habt auf die Tastatur schnell eure Pfoten, ihr müsst noch für Gladi voten', 'Endaril'),
(14, 'Wenn ihr einen Spieler sucht, dann nutzt die Spielersuche.', 'donnergott88'),
(15, 'Der Powmod regeneriert sich um 0,5 PM/h; in ausgebauten Schulen gibt es zusätzlich noch 1 PM/h beim schlafen und 0,5 PM/h vom Gasthaus.', 'donnergott88'),
(17, 'Ab Level 90 kannst du jeden, der ein höheres Level hat, zum Duell herausfordern.', 'donnergott88'),
(20, 'Bei unregelmäßig stattfindenden Turnieren kann man Gold und Medaillen gewinnen.', 'donnergott88'),
(21, 'Wenn ihr bestimmte Sachen auf dem Basar sucht, nutzt die Sortierfunktion.', 'donnergott88'),
(22, 'Auf dem Basar sind deine eigenen Angebote rot und Artikel auf die du geboten hast blau unterlegt.', 'donnergott88'),
(23, 'Niemanden beleidigen, dafür gibt`s VWs und bei 3 von denen werdet ihr für 1 Woche gesperrt.', 'donnergott88'),
(24, 'Im Inventar siehst du deine Besitztümer.', 'Seiji'),
(25, 'Im Inventar siehst du deine eigenen Waffen und Rüstungen mit den jeweiligen Offensiv- und Defensivwerten.', 'LAURIN'),
(34, 'Wenn ihr die besten Items im Spiel in der Tiergrube finden wollt, müsst ihr einen Platz in der Rangkampfliga innehaben!', 'admin'),
(27, 'In der Schmiede lassen sich Waffen, Zweitwaffen, Schilde und Rüstungsteile käuflich erwerben.', 'Seiji'),
(28, 'Achtet in der Tiergrube auf den Text. Mit etwas Glück findet ihr nützliche Items.', 'Seiji'),
(29, 'Die Highscore lässt sich in viele Kategorien sortieren.', 'Seiji'),
(30, 'Wenn du zu erschöpft bist um zu kämpfen, musst du im Gasthaus etwas essen und so deinen Kraftvorrat wieder auffüllen.', 'Seiji'),
(31, 'Mit 100.000 Gold und ein paar Medaillen könnt ihr eure eigene Gladiatorenschule gründen.', 'Seiji'),
(33, 'Bei der Pinnwand gibt es eine Blätterfunktion, so kann man auch ältere Beiträge nachlesen.', 'donnergott88'),
(32, 'Messt euch mit anderen Spielern in Duellen und erntet Erfolg und Erfahrungspunkte. Oder wettet auf bestehende Duelle und verdoppelt euren Einsatz.', 'Seiji'),
(35, 'Ab Level 20 kann man bei der Arenaleitung interessante Aufgaben erfüllen.', 'Polo™'),
(36, 'Durch benutzen der F5 Taste kann man in der Tiergrube viel Zeit sparen.', 'Dragon'),
(37, 'Benutzt die Shoutbox, um mit anderen Spielern zu reden.', 'ki//ermaschine'),
(38, 'Die Shoutbox kann man in den Optionen abstellen.', 'ki//ermaschine'),
(39, 'Wenn ihr gleichstarke Gegner sucht, schaut mal in der Arena vorbei.', 'odin_the_killer'),
(40, 'Wenn euch die Tiere in der Tiergrube zu schwach sind, geht zu den Prestigekämpfen und kämpft gegen mehrere Tiere aufeinmal.', 'odin_the_killer'),
(41, 'Du kannst auch Artefakte mit deinem Namen suchen lassen.', 'donnergott88'),
(42, 'Die Gegnersuche zeigt dir gleichstarke Gegner an.', 'donnergott88'),
(43, 'Im Boxlog kannst du alles nachlesen, was in der Shoutbox geschrieben wurde.', 'donnergott88'),
(44, 'Für 50 Medaillen kannst du dir deinen eigenen Forumtitel erstellen lassen.', 'donnergott88'),
(45, 'Hast du Fragen zum Spiel? Dann drück auf Hilfe und du kommst auf unsere Wiki-Seite.', 'donnergott88'),
(46, 'Schulen können dir verschiedene Vorteile bringen.', 'donnergott88'),
(47, 'Für 35 Medaillen kannst du eine Woche lang 3 Kämpfe auf einmal machen.', 'Junker'),
(48, 'Gegen Multis wird schnell und hart verfahren, also denkt erst gar nicht dran es zu versuchen!', 'st rob'),
(49, 'Wenn du Gold übrig hast, schau doch mal beim Trainer vorbei und erweitere deine Fähigkeiten.', 'Bodom'),
(50, 'Die Sparfunktion unter Charakter ist nur für das Steigern der Eigenschaften gedacht. Gesparte PowMod können nicht wieder abgehoben werden.', 'TitusPullo'),
(51, 'Beachte: Nachrichten ohne Betreff werden nicht versendet.', 'donnergott88'),
(52, 'Nutze für deinen Account ein sicheres Passwort, das nicht einfach erraten werden kann.', 'Dragon'),
(54, 'Bitte lies die Regeln sorgfältig, Unwissenheit schützt vor Strafe nicht.', 'donnergott88'),
(12, 'Beim Glücksspiel im Gasthaus kannst du schnell das große Geld gewinnen oder dein letztes Hemd verzocken.', 'donnergott88'),
(16, 'Um ein Duell zu erstellen ist ein Mindesteinsatz von 1.000 Gold nötig.', 'donnergott88'),
(19, 'Die in der Shoutbox gestellten Fragen werden schneller beantwortet als durch In-Game-Nachrichten.', 'Camel'),
(53, 'Es wird vor schweren RL Problemen gewarnt, Gladi kann süchtig machen!', 'donnergott88'),
(55, 'Um vergessene Passwörter wieder herzustellen, musst du deine E-Mail Adresse oder ICQ-Nummer im Profil angeben.', 'donnergott88'),
(56, 'Für 100 Medaillen kannst du deine Spezial Moves neu verteilen.', 'donnergott88'),
(57, 'Waffen mit dem Bonus (ZHW) können nicht mit einem Schild oder einer Zweitwaffe getragen werden.', 'donnergott88'),
(58, 'In der Shoutbox kannst du viele bekannte BBCodes benutzen.', 'donnergott88'),
(59, 'Auf dem Markt kannst du Items sofort von anderen Spielern kaufen oder selbst welche anbieten.', 'donnergott88'),
(60, 'In der Hall of Fame findest du die besten Spieler der vergangenen Runde.', 'donnergott88'),
(61, 'Die Shoutbox ist keine Smileparade, sondern eine Möglichkeit mit anderen Gladiatoren zu kommunizieren.', 'Heroista'),
(62, 'Du kannst bei einer Duellwette maximal 10.000 Gold setzen....<br>viel Erfolg!', 'Heroista'),
(63, 'Jeder gewonnene Kampf gegen eine feindliche Schule in der Arena, bringt eine Medaille für das Schulenkonto.', 'Heroista'),
(64, 'Gewonnene Medaillen sowie eingenommes Gold deiner Schule, können für Erweiterungen der Schulengebäude oder Gebete genutzt werden.', 'Heroista'),
(65, 'Gebete erhöhen für eine bestimmte Zeit die Eigenschaften aller Schulenmitglieder (je nach Auswahl der Gottheit).', 'Heroista'),
(66, 'Die Arenaleitung hat hin und wieder ein paar Quests zu vergeben, die natürlich gut bezahlt werden.', 'Heroista'),
(67, 'Die Abrechnung bei den Prestigekämpfen erfolgt täglich um 21:55 Uhr.', 'Heroista'),
(68, 'Mit Hilfe der Coolnesspunkte wird dein Goldgewinn in der Arena berechnet.', 'Heroista'),
(69, 'Beginnt erst mit schwachen Tieren und kämpft euch nur langsam hoch.', 'Odins Zorn'),
(70, 'Spiel mit Spaß & Freude und nichts anderem, denn bedenke: Gladiatorz ist nur ein Browsergame und nicht das RL.', 'Odins Zorn'),
(71, 'Bei Duellen bekommt der Sieger 30 EXP und der Verlierer 15 EXP.', 'LAURIN'),
(72, 'Wenn du keine PowMod mehr hast, kannst du entweder bis zur nächsten halben Stunde warten, oder im Gasthaus mindestens 1 Stunde schlafen gehen.', 'LAURIN'),
(73, 'Neulinge wenden sich bitte bei Fragen zunächst an die Gamemasters!', 'wildthings'),
(74, 'Die Gamemaster geben Spieltipps und sorgen für Ordnung in unserer Community.', 'Heroista'),
(75, 'Im Inventar kannst du Items sperren, um sie vor dem Verkauf über die Funktion "Alle nicht getragenen Items verkaufen" zu schützen.', 'Heroista'),
(76, 'Bei einem Gebet in deiner Schule, wirst du mit Hilfe einer Nachricht darüber informiert.', 'Heroista'),
(77, 'Auf dem Namens-Markt kannst du Items mit deinem Namen sofort kaufen (Menü "Händler").', 'Heroista'),
(18, 'Mit Level 45 und 70 kommen neue Tiere in die Tiergrube', 'donnergott88'),
(26, 'Für Duellsiege gegen Gladiatoren aus verfeindeten Schulen bekommt deine Schule Medaillien', 'donnergott88');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `turnier`
--

CREATE TABLE IF NOT EXISTS `turnier` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `startgold` int(10) NOT NULL,
  `gold` int(10) NOT NULL DEFAULT '0',
  `teilnehmer` int(10) NOT NULL,
  `minlvl` int(10) NOT NULL,
  `maxlvl` int(10) NOT NULL,
  `date` datetime NOT NULL,
  `round` int(1) NOT NULL DEFAULT '0',
  `place` int(10) NOT NULL DEFAULT '0',
  `winner` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `turnier`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `turnier_report`
--

CREATE TABLE IF NOT EXISTS `turnier_report` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `winner` int(10) NOT NULL DEFAULT '0',
  `loser` int(10) NOT NULL DEFAULT '0',
  `report` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `turnier_report`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `turnier_run`
--

CREATE TABLE IF NOT EXISTS `turnier_run` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `turnierid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `place` int(10) NOT NULL,
  `report_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `turnier_run`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `umfrage`
--

CREATE TABLE IF NOT EXISTS `umfrage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `frage` longtext CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `antwort1` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `antwort2` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `antwort3` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `antwort4` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `antwort5` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `stimmen1` int(10) unsigned NOT NULL,
  `stimmen2` int(10) unsigned NOT NULL,
  `stimmen3` int(10) unsigned NOT NULL,
  `stimmen4` int(10) unsigned NOT NULL,
  `stimmen5` int(10) unsigned NOT NULL,
  `zeit` int(10) unsigned NOT NULL COMMENT 'Enddatum der Umfrage',
  `antworten` int(10) unsigned NOT NULL COMMENT 'anzahl der Antortmöglichkeiten. min 2',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `umfrage`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `pw` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `avatar` varchar(400) COLLATE latin1_general_ci NOT NULL,
  `prestige` int(10) unsigned NOT NULL DEFAULT '0',
  `gold` int(10) unsigned NOT NULL DEFAULT '20',
  `goldwin` int(10) unsigned NOT NULL,
  `goldlose` int(10) unsigned NOT NULL,
  `kraft` int(10) unsigned NOT NULL DEFAULT '0',
  `kraftupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `staerke` int(10) unsigned NOT NULL DEFAULT '0',
  `geschick` int(10) unsigned NOT NULL DEFAULT '0',
  `kondition` int(10) unsigned NOT NULL DEFAULT '0',
  `charisma` int(10) unsigned NOT NULL DEFAULT '0',
  `inteligenz` int(10) unsigned NOT NULL DEFAULT '0',
  `waffenkunde` int(10) unsigned NOT NULL DEFAULT '0',
  `ausweichen` int(10) unsigned NOT NULL DEFAULT '0',
  `taktik` int(10) unsigned NOT NULL DEFAULT '0',
  `zweiwaffenkampf` int(10) unsigned NOT NULL DEFAULT '0',
  `heilkunde` int(10) unsigned NOT NULL DEFAULT '0',
  `schildkunde` int(10) unsigned NOT NULL DEFAULT '0',
  `Schlagkraft` int(10) unsigned NOT NULL,
  `Einstecken` int(10) unsigned NOT NULL,
  `Kraftprotz` int(10) unsigned NOT NULL,
  `Glueck` int(10) unsigned NOT NULL,
  `Sammler` int(10) unsigned NOT NULL,
  `regstamp` int(10) unsigned NOT NULL DEFAULT '0',
  `lonline` int(10) unsigned NOT NULL DEFAULT '0',
  `lgasthaus` int(10) unsigned NOT NULL DEFAULT '0',
  `powmod` float NOT NULL DEFAULT '0',
  `exp` int(10) unsigned NOT NULL DEFAULT '0',
  `arena` int(10) unsigned NOT NULL DEFAULT '1230764400',
  `arenarang` int(10) unsigned NOT NULL DEFAULT '10',
  `schaden` int(10) unsigned NOT NULL DEFAULT '0',
  `schlafen` int(10) unsigned NOT NULL DEFAULT '0',
  `tier_kills_win` int(10) unsigned NOT NULL DEFAULT '0',
  `tier_kills_lost` int(10) unsigned NOT NULL DEFAULT '0',
  `ppl_kills_win` int(10) unsigned NOT NULL DEFAULT '0',
  `ppl_kills_lost` int(10) unsigned NOT NULL DEFAULT '0',
  `prest_kills_win` int(10) unsigned NOT NULL DEFAULT '0',
  `prest_kills_lost` int(10) unsigned NOT NULL DEFAULT '0',
  `duell_kills_win` int(10) unsigned NOT NULL DEFAULT '0',
  `duell_kills_lost` int(10) unsigned NOT NULL DEFAULT '0',
  `rang_kills_win` int(10) unsigned NOT NULL DEFAULT '0',
  `rang_kills_lost` int(10) unsigned NOT NULL DEFAULT '0',
  `play` int(10) unsigned NOT NULL DEFAULT '0',
  `quest` int(10) unsigned NOT NULL DEFAULT '0',
  `berichte` varchar(1) COLLATE latin1_general_ci NOT NULL DEFAULT 'j',
  `schule` varchar(20) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `medallien` int(10) unsigned NOT NULL DEFAULT '0',
  `bann` int(10) unsigned NOT NULL DEFAULT '0',
  `werbung` int(10) unsigned NOT NULL DEFAULT '0',
  `status` varchar(100) COLLATE latin1_general_ci NOT NULL DEFAULT 'user',
  `descr` longtext COLLATE latin1_general_ci NOT NULL,
  `title` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `lforum` int(10) unsigned NOT NULL DEFAULT '0',
  `rang` int(11) NOT NULL DEFAULT '1',
  `rankplace` int(10) unsigned NOT NULL DEFAULT '21',
  `level` int(5) unsigned NOT NULL DEFAULT '1',
  `lattack` int(10) unsigned NOT NULL DEFAULT '0',
  `ldefend` int(10) unsigned NOT NULL DEFAULT '0',
  `pmspar` float unsigned NOT NULL DEFAULT '0',
  `couponspar` int(10) unsigned NOT NULL DEFAULT '0',
  `couponeinlos` int(10) unsigned NOT NULL,
  `trainerpunkte` int(10) unsigned NOT NULL,
  `trainerzeit` int(10) unsigned NOT NULL,
  `multi` int(10) NOT NULL,
  `contest` tinyint(1) NOT NULL DEFAULT '0',
  `mail` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT 'Wichtig',
  `rlname` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT 'Dein Name',
  `sex` varchar(1) COLLATE latin1_general_ci NOT NULL DEFAULT '0',
  `icq` varchar(12) COLLATE latin1_general_ci NOT NULL DEFAULT 'xxx-xxx-xxx',
  `website` varchar(30) COLLATE latin1_general_ci NOT NULL DEFAULT 'www',
  `aktiv` smallint(1) NOT NULL,
  `turnier` int(1) NOT NULL DEFAULT '0',
  `turnierpoints` int(10) NOT NULL DEFAULT '0',
  `ref` int(10) NOT NULL DEFAULT '0',
  `QIC` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `name`, `pw`, `avatar`, `prestige`, `gold`, `goldwin`, `goldlose`, `kraft`, `kraftupdate`, `staerke`, `geschick`, `kondition`, `charisma`, `inteligenz`, `waffenkunde`, `ausweichen`, `taktik`, `zweiwaffenkampf`, `heilkunde`, `schildkunde`, `Schlagkraft`, `Einstecken`, `Kraftprotz`, `Glueck`, `Sammler`, `regstamp`, `lonline`, `lgasthaus`, `powmod`, `exp`, `arena`, `arenarang`, `schaden`, `schlafen`, `tier_kills_win`, `tier_kills_lost`, `ppl_kills_win`, `ppl_kills_lost`, `prest_kills_win`, `prest_kills_lost`, `duell_kills_win`, `duell_kills_lost`, `rang_kills_win`, `rang_kills_lost`, `play`, `quest`, `berichte`, `schule`, `medallien`, `bann`, `werbung`, `status`, `descr`, `title`, `lforum`, `rang`, `rankplace`, `level`, `lattack`, `ldefend`, `pmspar`, `couponspar`, `couponeinlos`, `trainerpunkte`, `trainerzeit`, `multi`, `contest`, `mail`, `rlname`, `sex`, `icq`, `website`, `aktiv`, `turnier`, `turnierpoints`, `ref`, `QIC`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '', 0, 0, 0, 0, 110, 1322471452, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1322470835, 1322471452, 0, 9.9, 1, 1230764400, 10, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'j', '0', 0, 0, 0, 'admin', '', '', 0, 1, 21, 1, 0, 0, 19.5, 0, 0, 0, 0, 0, 0, 'info@gladiatorgame.de', 'Dein Name', '0', 'xxx-xxx-xxx', 'www', 1, 0, 0, 0, 0),
(4, 'Basarleitung', 'e10adc3949ba59abbe56e057f20f883e', '', 0, 250, 0, 0, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1322470896, 1322470896, 0, 10, 0, 1230764400, 10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'j', '0', 0, 0, 0, 'admin', '', '', 0, 1, 21, 1, 0, 0, 20, 0, 0, 0, 0, 0, 0, 'support@gladiatorgame.de', 'Dein Name', '0', 'xxx-xxx-xxx', 'www', 1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wetten`
--

CREATE TABLE IF NOT EXISTS `wetten` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wer` int(10) unsigned NOT NULL,
  `wieviel` int(10) unsigned NOT NULL,
  `duell` int(10) unsigned NOT NULL,
  `duellant` varchar(2) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=569 ;

--
-- Daten für Tabelle `wetten`
--

