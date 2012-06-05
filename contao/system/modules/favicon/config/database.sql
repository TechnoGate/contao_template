CREATE TABLE `tl_page` (
  `faviconPath` varchar(255) NOT NULL default '',
  `faviconExternal` char(100) NOT NULL default '',
  `appleTouchIconPath` varchar(255) NOT NULL default '',
  `appleTouchIconExternal` char(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
