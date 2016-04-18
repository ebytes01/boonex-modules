-- creating the settings table
CREATE TABLE IF NOT EXISTS `[db_prefix]settings` ( 
    `ID` int(10) unsigned NOT NULL auto_increment, 
    `name` varchar(80) not null, 
    `default_tab` varchar(100) not null, 
    `info_image_height` int not null, 
    `info_image_width` int not null, 
    `display_sites_url` int(2) not null, 
    `display_author` int(2) not null, 
    `display_album` int(2) not null, 
    `display_rating` int(2) not null, 
    `display_location` int(2) not null, 
    `display_tags` int(2) not null, 
    `display_categories` int(2) not null, 
    `display_date_start` int(2) not null, 
    `display_date_end` int(2) not null, 
    `display_description` int(2) not null, 
    `max_description_chars` int(10) not null, 
    INDEX(`name`), 
    PRIMARY KEY (`ID`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
INSERT INTO `emmet_bytes_bon_con_special_info_settings` (`ID`, `name`, `default_tab`, `info_image_height`, `info_image_width`, `display_sites_url`, `display_author`, `display_album`, `display_rating`, `display_location`, `display_tags`, `display_categories`, `display_date_start`, `display_date_end`, `display_description`, `max_description_chars`) VALUES 
-- bof the homepage block settings
(1, 'homepage_event_block_settings', 'featured', 300, 300, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 400), 
(2, 'homepage_group_block_settings', 'featured', 301, 301, 0, 1, 0, 1, 1, 1, 1, 0, 0, 1, 401), 
(3, 'homepage_site_block_settings', '', 302, 302, 1, 1, 0, 1, 0, 1, 1, 0, 0, 1, 402), 
(4, 'homepage_blogs_block_settings', 'last', 303, 303, 0, 1, 0, 1, 0, 1, 1, 0, 0, 1, 403), 
(5, 'homepage_photos_block_settings', 'last', 305, 305, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 405), 
(6, 'homepage_videos_block_settings', 'last', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 150), 
(7, 'homepage_sounds_block_settings', 'last', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 200), 
(8, 'homepage_files_block_settings', 'last', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 300), 
-- eof the homepage block settings
-- bof the profile block settings
(9, 'profile_my_event_block_settings', '', 300, 300, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 400), 
(10, 'profile_joined_event_block_settings', '', 301, 301, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 401), 
(11, 'profile_my_group_block_settings', '', 302, 302, 0, 1, 0, 1, 1, 1, 1, 0, 0, 1, 402), 
(12, 'profile_joined_group_block_settings', '', 303, 303, 0, 1, 0, 1, 1, 1, 1, 0, 0, 1, 403), 
(13, 'profile_my_site_block_settings', '', 304, 304, 1, 1, 0, 1, 0, 1, 1, 0, 0, 1, 404), 
(14, 'profile_my_blogs_block_settings', '', 306, 306, 0, 1, 0, 1, 0, 1, 1, 0, 0, 1, 406), 
(15, 'profile_my_photos_block_settings', '', 307, 307, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 407), 
(16, 'profile_my_videos_block_settings', '', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 402), 
(17, 'profile_my_sounds_block_settings', '', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 400), 
-- eof the profile block settings
-- bof the main events blocks settings
(18, 'module_blocks_main_upcoming_event_block_settings', '', 300, 300, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 400), 
(19, 'module_blocks_main_past_event_block_settings', '', 301, 301, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 401), 
(20, 'module_blocks_main_recent_event_block_settings', '', 302, 302, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 402), 
-- eof the main events blocks settings
-- bof the my events block settings
(21, 'module_blocks_users_event_block_settings', '', 303, 303, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 403), 
-- eof the my events block settings
-- bof the main groups block settings
(22, 'module_blocks_main_recent_group_block_settings', '', 304, 304, 0, 1, 0, 1, 1, 1, 1, 0, 0, 1, 404), 
-- eof the main groups block settings
-- bof the users group block settings
(23, 'module_blocks_users_group_block_settings', '', 305, 305, 0, 1, 0, 1, 1, 1, 1, 0, 0, 1, 405), 
-- eof the users group block settings
-- bof the main sites block settings
(24, 'module_blocks_main_featured_site_block_settings', '', 306, 306, 1, 1, 0, 1, 0, 1, 1, 0, 0, 1, 406), 
(25, 'module_blocks_main_recent_site_block_settings', '', 307, 307, 1, 1, 0, 1, 0, 1, 1, 0, 0, 1, 407), 
-- eof the main sites block settings
-- bof the users site block settings
(26, 'module_blocks_users_site_block_settings', '', 308, 308, 1, 1, 0, 1, 0, 1, 1, 0, 0, 1, 408), 
-- eof the users site block settings
-- bof the main blogs block settings
(27, 'module_blocks_main_latest_blogs_block_settings', '', 309, 309, 0, 1, 0, 1, 0, 1, 1, 0, 0, 1, 409), 
-- eof the main blogs block settings
-- bof the main photos block settings
(28, 'module_blocks_main_public_photos_block_settings', 'last', 310, 310, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 410), 
(29, 'module_blocks_main_favorite_photos_block_settings', '', 311, 311, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 411), 
(30, 'module_blocks_main_featured_photos_block_settings', '', 312, 312, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 412), 
-- eof the main photos block settings
-- bof the main videos block settings
(31, 'module_blocks_main_public_videos_block_settings', 'last', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 413), 
(32, 'module_blocks_main_favorite_videos_block_settings', '', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 413), 
(33, 'module_blocks_main_featured_videos_block_settings', '', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 413), 
-- eof the main videos block settings
-- bof the main sounds block settings
(34, 'module_blocks_main_public_sounds_block_settings', 'last', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 414), 
(35, 'module_blocks_main_favorite_sounds_block_settings', '', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 415), 
(36, 'module_blocks_main_featured_sounds_block_settings', '', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 416), 
-- eof the main sounds block settings
-- bof the main files block settings
(37, 'module_blocks_main_public_files_block_settings', 'last', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 417), 
(38, 'module_blocks_main_top_files_block_settings', '', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 418), 
(39, 'module_blocks_main_favorite_files_block_settings', '', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 419), 
(40, 'module_blocks_main_featured_files_block_settings', '', 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1, 420);
-- eof the main files block settings
INSERT INTO `sys_permalinks` VALUES (NULL, 'modules/?r=ebBonConSpecialInfo/', 'm/ebBonConSpecialInfo/', 'emmet_bytes_bon_con_special_info_permalinks');
SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('BonConSpecialInfo', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES 
('emmet_bytes_bon_con_special_info_permalinks', 'on', 26, 'Enable friendly permalinks in bonConSpecialInfo', 'checkbox', '', '', '0', '');
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES 
-- bof the index blocks
('index', '998px', 'Homepage Photo Bon Con Special Info', '_emmetbytes_bon_con_special_info_homepage_photo', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''homepage_photos_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Sites Bon Con Special Info', '_emmetbytes_bon_con_special_info_homepage_sites', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''homepage_sites_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Events Bon Con Special Info', '_emmetbytes_bon_con_special_info_homepage_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''homepage_events_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Blogs Bon Con Special Info', '_emmetbytes_bon_con_special_info_homepage_blogs', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''homepage_blogs_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Sounds Bon Con Special Info', '_emmetbytes_bon_con_special_info_homepage_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''homepage_sounds_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Videos Bon Con Special Info', '_emmetbytes_bon_con_special_info_homepage_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''homepage_videos_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Files Bon Con Special Info', '_emmetbytes_bon_con_special_info_homepage_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''homepage_files_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Groups Bon Con Special Info', '_emmetbytes_bon_con_special_info_homepage_groups', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''homepage_groups_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the index blocks
-- bof the profile page blocks
('profile', '998px', 'Own Events Bon Con Special Info', '_emmetbytes_bon_con_special_info_profile_own_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''profile_own_events'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Joined Events Bon Con Special Info', '_emmetbytes_bon_con_special_info_profile_joined_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''profile_joined_events'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Groups Bon Con Special Info', '_emmetbytes_bon_con_special_info_profile_own_groups', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''profile_own_groups'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Joined Groups Bon Con Special Info', '_emmetbytes_bon_con_special_info_profile_joined_groups', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''profile_joined_groups'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Sites Bon Con Special Info', '_emmetbytes_bon_con_special_info_profile_own_sites', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''profile_own_sites'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Blogs Bon Con Special Info', '_emmetbytes_bon_con_special_info_profile_own_blogs', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''profile_own_blogs'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Photos Bon Con Special Info', '_emmetbytes_bon_con_special_info_profile_own_photos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''profile_own_photos'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Videos Bon Con Special Info', '_emmetbytes_bon_con_special_info_profile_own_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''profile_own_videos'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Sounds Bon Con Special Info', '_emmetbytes_bon_con_special_info_profile_own_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''profile_own_sounds'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
-- eof the profile page blocks
-- bof the main events page blocks
('bx_events_main', '998px', 'Upcoming Events Bon Con Special Info', '_emmetbytes_bon_con_special_info_upcoming_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_upcoming_events'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_events_main', '998px', 'Past Events Bon Con Special Info', '_emmetbytes_bon_con_special_info_past_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_past_events'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_events_main', '998px', 'Recent Events Bon Con Special Info', '_emmetbytes_bon_con_special_info_recent_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_recent_events'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the main events page blocks
-- bof the users events page blocks
('bx_events_my', '998px', 'My Events Bon Con Special Info', '_emmetbytes_bon_con_special_info_my_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''my_events'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the users events page blocks
-- bof the groups main page blocks
('bx_groups_main', '998px', 'Recent Groups Bon Con Special Info', '_emmetbytes_bon_con_special_info_recent_groups', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_recent_groups'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the groups main page blocks
-- bof the users groups page blocks
('bx_groups_my', '998px', 'My Groups Bon Con Special Info', '_emmetbytes_bon_con_special_info_my_groups', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''my_groups'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the users groups page blocks
-- bof the main sites page blocks
('bx_sites_main', '998px', 'Recent Sites Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_recent_sites', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_recent_sites'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_sites_main', '998px', 'Feautred Sites Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_featured_sites', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_featured_sites'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the main sites page blocks
-- bof the users sites page blocks
('bx_sites_profile', '998px', 'Users Own Sites Bon Con Special Info', '_emmetbytes_bon_con_special_info_my_own_sites', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''users_sites'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the users sites page blocks
-- bof the main blogs page blocks
('bx_blogs_home', '998px', 'Latest Blog Post Bon Con Special Info', '_emmetbytes_bon_con_special_info_latest_blog_post', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''latest_blog_post'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the main blogs page blocks
-- bof the photos main page blocks
('bx_photos_home', '998px', 'Main Public Photos Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_public_photos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_public_photos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_photos_home', '998px', 'Main Featured Photos Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_featured_photos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_featured_photos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_photos_home', '998px', 'Main Favorite Photos Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_favorite_photos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_favorite_photos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the photos main page blocks
-- bof the videos main page blocks
('bx_videos_home', '998px', 'Main Public Videos Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_public_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_public_videos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_videos_home', '998px', 'Main Featured Videos Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_featured_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_featured_videos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_videos_home', '998px', 'Main Favorite Videos Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_favorite_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_favorite_videos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the videos main page blocks
-- bof the sounds main page blocks
('bx_sounds_home', '998px', 'Main Public Sounds Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_public_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_public_sounds'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_sounds_home', '998px', 'Main Featured Sounds Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_featured_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_featured_sounds'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_sounds_home', '998px', 'Main Favorite Sounds Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_favorite_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_favorite_sounds'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the sounds main page blocks
-- bof the files main page blocks
('bx_files_home', '998px', 'Main Public Files Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_public_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_public_files'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_files_home', '998px', 'Main Top Files Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_top_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_top_files'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_files_home', '998px', 'Main Featured Files Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_featured_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_featured_files'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_files_home', '998px', 'Main Favorite Files Bon Con Special Info', '_emmetbytes_bon_con_special_info_main_favorite_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConSpecialInfo'', ''main_favorite_files'', array($iBlockID));', 1, 66, 'non,memb', 0);
-- eof the files main page blocks
SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id` = '2');
INSERT IGNORE INTO `sys_menu_admin` (`parent_id`, `name`, `title`, `url`, `description`, `icon`, `order`) VALUES 
(2, 'emmet_bytes_bon_con_special_info', 'emmetbytes_bon_con_special_info', '{siteUrl}modules/?r=ebBonConSpecialInfo/administration/', 'BonConSpecialInfo module by EmmetBytes', 'modules/EmmetBytes/emmetbytes_bon_con_special_info/|icon.png', @iMax+1);

