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
-- Table `tl_form`
--

CREATE TABLE `tl_form` (
  `sendFormattedMail` char(1) NOT NULL default '',
  `formattedMailRecipient` text NULL,
  `formattedMailSubject` varchar(255) NOT NULL default '',
  `sendConfirmationMail` char(1) NOT NULL default '',
  `confirmationMailRecipientField` varchar(64) NOT NULL default '',
  `confirmationMailRecipient` varchar(255) NOT NULL default '',
  `confirmationMailSender` varchar(255) NOT NULL default '',
  `confirmationMailReplyto` varchar(255) NOT NULL default '',
  `confirmationMailSubject` varchar(255) NOT NULL default '',
  `confirmationMailText` text NULL,
  `confirmationMailTemplate` varchar(255) NOT NULL default '',
  `confirmationMailSkipEmpty` char(1) NOT NULL default '',
  `addConfirmationMailAttachments` char(1) NOT NULL default '',
  `confirmationMailAttachments` blob NULL,
  `formattedMailText` text NULL,
  `formattedMailTemplate` varchar(255) NOT NULL default '',
  `formattedMailSkipEmpty` char(1) NOT NULL default '',
  `addFormattedMailAttachments` char(1) NOT NULL default '',
  `formattedMailAttachments` blob NULL,
  `storeFormdata` char(1) NOT NULL default '',
  `efgStoreValues` char(1) NOT NULL default '',
  `efgAliasField` varchar(64) NOT NULL default '',
  `useFormValues` char(1) NOT NULL default '',
  `useFieldNames` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table `tl_form_field`
--

CREATE TABLE `tl_form_field` (
  `efgLookupOptions` text NULL,
  `efgMultiSRC` text NULL,
  `efgImageMultiple` char(1) NOT NULL default '',
  `efgImageUseHomeDir` char(1) NOT NULL default '',
  `efgImageSortBy` varchar(32) NOT NULL default '',
  `efgImagePerRow` smallint(5) unsigned NOT NULL default '0',
  `efgImageSize` varchar(255) NOT NULL default '',
  `efgImageFullsize` char(1) NOT NULL default '',
  `efgImageMargin` varchar(255) NOT NULL default '',
  `efgAddBackButton` char(1) NOT NULL default '',
  `efgBackSlabel` varchar(255) NOT NULL default '',
  `efgBackImageSubmit` char(1) NOT NULL default '',
  `efgBackSingleSRC` varchar(255) NOT NULL default '',
  `efgBackStoreSessionValues` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table `tl_module`
--

CREATE TABLE `tl_module` (
  `list_formdata` varchar(64) NOT NULL default '',
  `list_fields` text NULL,
  `list_where` varchar(255) NOT NULL default '',
  `list_sort` varchar(255) NOT NULL default '',
  `list_search` varchar(255) NOT NULL default '',
  `list_info` text NULL,
  `list_layout` varchar(64) NOT NULL default '',
  `list_info_layout` varchar(64) NOT NULL default '',
  `efg_list_access` varchar(32) NOT NULL default '',
  `efg_fe_edit_access` varchar(32) NOT NULL default '',
  `efg_fe_delete_access` varchar(32) NOT NULL default '',
  `efg_fe_export_access` varchar(32) NOT NULL default '',
  `efg_fe_keep_id` char(1) NOT NULL default '',
  `efg_fe_no_formatted_mail` char(1) NOT NULL default '',
  `efg_fe_no_confirmation_mail` char(1) NOT NULL default '',
  `efg_iconfolder` varchar(255) NOT NULL default '',
  `efg_DetailsKey` varchar(64) NOT NULL default '',
  `efg_list_searchtype` varchar(32) NOT NULL default '',
  `efg_com_allow_comments` char(1) NOT NULL default '',
  `efg_com_notify` varchar(32) NOT NULL default '',
  `efg_com_per_page` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table `tl_formdata`
--

CREATE TABLE `tl_formdata` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `fd_member` int(10) unsigned NOT NULL default '0',
  `fd_user` int(10) unsigned NOT NULL default '0',
  `fd_member_group` int(10) unsigned NOT NULL default '0',
  `fd_user_group` int(10) unsigned NOT NULL default '0',
  `form` varchar(64) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  `date` int(10) unsigned NOT NULL default '0',
  `confirmationSent` char(1) NOT NULL default '',
  `confirmationDate` varchar(10) NOT NULL default '',
  `published` char(1) NOT NULL default '',
  `alias` varchar(64) NOT NULL default '',
  `be_notes` text NULL,
  PRIMARY KEY  (`id`),
  KEY `form` (`form`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table `tl_formdata_details`
--

CREATE TABLE `tl_formdata_details` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `ff_id` int(10) unsigned NOT NULL default '0',
  `ff_type` varchar(32) NOT NULL default '',
  `ff_name` varchar(64) NOT NULL default '',
  `ff_label` varchar(255) NOT NULL default '',
  `value` text NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `ff_name` (`ff_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

