SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE TABLE IF NOT EXISTS `author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `is_translator` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `chapter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `sid` int(11) NOT NULL COMMENT 'Section Id',
  `priority` int(11) NOT NULL DEFAULT '0' COMMENT 'Base Zero',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `sid` int(11) NOT NULL COMMENT 'Source id',
  `priority` int(11) NOT NULL DEFAULT '0' COMMENT 'Base Zero',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `source` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(512) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `version` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `display` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `verse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `cid` int(11) NOT NULL COMMENT 'Chapter Id',
  `priority` int(11) NOT NULL DEFAULT '0' COMMENT 'Zero Based',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


INSERT INTO `source` (`name`, `description`, `version`, `display`, `link`) VALUES
('The Hidden Words', '&ldquo;A work consisting of short passages revealed by Bahá’u’lláh in Persian and Arabic in 1857/58 during His exile in Baghdad, translated by Shoghi Effendi.&rdquo;<br/><br/>Copyright © Bahá''í International Community', 'Official Translation', '{"page":{"section":true,"chapter":false,"verse":false}}', 'http://www.bahai.org/library/authoritative-texts/bahaullah/hidden-words/'),
('The Holy Quran', '&ldquo;During the seventh century A.D. a man by the name of ''Mohammed,'' who was from the lineage of Abraham, was given the overwhelming task of being God''s messenger to deliver the words of the Almighty to mankind.\n<br/><br/>\nThe message that was given to this prophet represented a culmination of all previous teachings/laws, as well as a recording of the most accurate human history in relation to God.&rdquo;', 'Progressive Muslims Organization', '{"page":{"section":false,"chapter":true,"verse":false}}', 'http://www.free-minds.org/quran/'),
('Bhagavad Gita', 'Arnold', '', '{"page":{"section":false,"chapter":true,"verse":false}}', 'https://en.wikisource.org/wiki/The_Bhagavad_Gita_%28Arnold_translation%29');


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;