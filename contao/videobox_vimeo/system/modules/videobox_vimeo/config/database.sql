-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************


-- --------------------------------------------------------

-- 
-- Table `tl_videobox_settings`
-- 

CREATE TABLE `tl_videobox_settings` (
  `vimeo_template` varchar(64) NOT NULL default '',
  `vimeo_size` varchar(64) NOT NULL default '',
  `vimeo_color` varchar(6) NOT NULL default '',
  `vimeo_autoplay` char(1) NOT NULL default '0',
  `vimeo_showbyline` char(1) NOT NULL default '1',
  `vimeo_showtitle` char(1) NOT NULL default '1',
  `vimeo_showportrait` char(1) NOT NULL default '1'
) ENGINE=MyISAM  CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_videobox`
-- 

CREATE TABLE `tl_videobox` (
  `vimeo_id` varchar(128) NOT NULL default ''
) ENGINE=MyISAM  CHARSET=utf8;
