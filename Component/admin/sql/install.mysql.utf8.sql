CREATE TABLE IF NOT EXISTS `#__smfaq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_by_email` varchar(100) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `answer_created_by_id` int(11) NOT NULL,
  `answer_created` datetime NOT NULL,
  `answer_state` tinyint(1) NOT NULL,
  `answer_email` tinyint(1) NOT NULL,
  `access` int(11) NOT NULL,
  `metadesc` text NOT NULL,
  `metakey` text NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS `#__smfaq_comments` (
  `question_id` int(11) NOT NULL,
  `comment` mediumtext NOT NULL,
  `created` datetime NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS `#__smfaq_votes` (
  `question_id` int(11) unsigned NOT NULL,
  `vote_yes` int(10) NOT NULL DEFAULT '0',
  `vote_no` int(10) NOT NULL DEFAULT '0',
  `lastip` varchar(20) NOT NULL
) DEFAULT CHARSET=utf8;

