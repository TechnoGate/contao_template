CREATE TABLE `tl_page` (
  `ga_analyticsid` varchar(20) NOT NULL default ''
  `ga_anonymizeip` char(1) NOT NULL default '',
  `ga_measurespeed` char(1) NOT NULL default '',
  `ga_eventtracking` char(1) NOT NULL default '',
  `ga_externaltracking` char(1) NOT NULL default '',
  `ga_addlinktracking` char(1) NOT NULL default '',
  `ga_setdomainname` char(150) NOT NULL default '',
  `ga_titlelinktracking` char(150) NOT NULL default '',
  `ga_bounceseconds` int(10) unsigned NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

