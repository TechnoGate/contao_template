-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_page`
-- 

CREATE TABLE `tl_page` (
  `rootId` int(10) unsigned NOT NULL default '0',
  `updatechilds` int(1) unsigned NOT NULL default '0',
  `parents` varchar(64) NOT NULL default '',
  `childrens` blob NULL,
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
