-- creating the settings table
CREATE TABLE IF NOT EXISTS `emmet_bytes_bon_con_in_realtime_settings` (
        `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(80) NOT NULL,
        `default_tab` varchar(100) NOT NULL,
        `maximum_numbers_of_datas` int(5) NOT NULL,
        `fetch_type` varchar(200) NOT NULL,
        `display_sites_url` int(2) NOT NULL,
        `display_author` int(2) NOT NULL,
        `display_date` int(2) NOT NULL,
        `display_location` int(2) NOT NULL,
        `display_fans_count` int(2) NOT NULL,
        `display_rating` int(2) NOT NULL,
        `display_size` int(2) NOT NULL,
        `display_length` int(2) NOT NULL,
        `display_view` int(2) NOT NULL,
        `display_categories` int(2) NOT NULL,
        `display_tags` int(2) NOT NULL,
        `display_comments_count` int(2) NOT NULL,
        `display_description` int(2) NOT NULL,
        `max_description_chars` int(10) NOT NULL,
        `display_contents` int(2) NOT NULL,
        `max_contents_chars` int(10) NOT NULL,
        PRIMARY KEY (`ID`),
        KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;
INSERT INTO `emmet_bytes_bon_con_in_realtime_settings` (`ID`, `name`, `default_tab`, `maximum_numbers_of_datas`, `fetch_type`, `display_sites_url`, `display_author`, `display_date`, `display_location`, `display_fans_count`, `display_rating`, `display_size`, `display_length`, `display_view`, `display_categories`, `display_tags`, `display_comments_count`, `display_description`, `max_description_chars`, `display_contents`, `max_contents_chars`) VALUES
(1, 'module_blocks_main_upcoming_event_block_settings', '', 6, 'automatic', 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 'module_blocks_main_past_event_block_settings', '', 6, 'automatic', 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 'module_blocks_main_recent_event_block_settings', '', 6, 'automatic', 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 'homepage_event_block_settings', 'top', 6, 'automatic', 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 'homepage_group_block_settings', 'featured', 6, 'automatic', 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 'homepage_site_block_settings', '', 6, 'automatic', 1, 1, 1, 0, 0, 1, 0, 0, 0, 1, 1, 1, 1, 200, 0, 0),
(7, 'homepage_blogs_block_settings', 'last', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 0, 0, 1, 1, 1, 0, 0, 1, 300),
(8, 'homepage_photos_block_settings', 'top', 6, 'automatic', 0, 1, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(9, 'homepage_videos_block_settings', 'last', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(10, 'homepage_sounds_block_settings', 'last', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(11, 'homepage_files_block_settings', 'last', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(12, 'profile_my_event_block_settings', '', 6, 'automatic', 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(13, 'profile_joined_event_block_settings', '', 6, 'automatic', 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(14, 'profile_my_group_block_settings', '', 6, 'automatic', 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(15, 'profile_joined_group_block_settings', '', 6, 'automatic', 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(16, 'profile_my_site_block_settings', '', 6, 'automatic', 1, 1, 1, 0, 0, 1, 0, 0, 0, 1, 1, 1, 1, 300, 0, 0),
(17, 'profile_my_blogs_block_settings', '', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 0, 0, 1, 1, 1, 0, 0, 1, 300),
(18, 'profile_my_photos_block_settings', '', 6, 'automatic', 0, 1, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(19, 'profile_my_videos_block_settings', '', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(20, 'profile_my_sounds_block_settings', '', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(21, 'module_blocks_users_event_block_settings', '', 6, '', 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(22, 'module_blocks_main_recent_group_block_settings', '', 6, 'automatic', 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(23, 'module_blocks_users_group_block_settings', '', 6, '', 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(24, 'module_blocks_main_featured_site_block_settings', '', 6, 'automatic', 1, 1, 1, 0, 0, 1, 0, 0, 0, 1, 1, 1, 1, 300, 0, 0),
(25, 'module_blocks_main_recent_site_block_settings', '', 6, 'automatic', 1, 1, 1, 0, 0, 1, 0, 0, 0, 1, 1, 1, 1, 300, 0, 0),
(26, 'module_blocks_main_latest_blogs_block_settings', '', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 0, 0, 1, 1, 1, 0, 0, 1, 200),
(27, 'module_blocks_main_public_photos_block_settings', 'last', 6, 'automatic', 0, 1, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(28, 'module_blocks_main_favorite_photos_block_settings', '', 6, 'automatic', 0, 1, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(29, 'module_blocks_main_featured_photos_block_settings', '', 6, 'automatic', 0, 1, 1, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(30, 'module_blocks_main_public_videos_block_settings', 'last', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(31, 'module_blocks_main_favorite_videos_block_settings', '', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(32, 'module_blocks_main_featured_videos_block_settings', '', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(33, 'module_blocks_main_public_sounds_block_settings', 'last', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(34, 'module_blocks_main_favorite_sounds_block_settings', '', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(35, 'module_blocks_main_featured_sounds_block_settings', '', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0),
(36, 'module_blocks_main_public_files_block_settings', 'last', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(37, 'module_blocks_main_top_files_block_settings', '', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(38, 'module_blocks_main_favorite_files_block_settings', '', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(39, 'module_blocks_main_featured_files_block_settings', '', 6, 'automatic', 0, 1, 1, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `sys_permalinks` VALUES (NULL, 'modules/?r=ebBonConInRealtime/', 'm/ebBonConInRealtime/', 'emmet_bytes_bon_con_in_realtime_permalinks');
SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('BonConInRealtime', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES 
('emmet_bytes_bon_con_in_realtime_permalinks', 'on', 26, 'Enable friendly permalinks in bonConInRealtime', 'checkbox', '', '', '0', '');
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES 
-- bof the index blocks
('index', '998px', 'Homepage Photo Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_homepage_photo', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''homepage_photos_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Sites Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_homepage_sites', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''homepage_sites_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Events Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_homepage_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''homepage_events_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Blogs Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_homepage_blogs', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''homepage_blogs_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Sounds Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_homepage_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''homepage_sounds_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Videos Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_homepage_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''homepage_videos_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Files Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_homepage_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''homepage_files_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Groups Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_homepage_groups', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''homepage_groups_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the index blocks
-- bof the profile page blocks
('profile', '998px', 'Own Events Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_profile_own_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''profile_own_events'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Joined Events Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_profile_joined_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''profile_joined_events'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Groups Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_profile_own_groups', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''profile_own_groups'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Joined Groups Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_profile_joined_groups', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''profile_joined_groups'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Sites Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_profile_own_sites', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''profile_own_sites'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Blogs Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_profile_own_blogs', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''profile_own_blogs'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Photos Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_profile_own_photos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''profile_own_photos'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Videos Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_profile_own_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''profile_own_videos'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Sounds Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_profile_own_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''profile_own_sounds'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
-- eof the profile page blocks
-- bof the main events page blocks
('bx_events_main', '998px', 'Upcoming Events Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_upcoming_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_upcoming_events'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_events_main', '998px', 'Past Events Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_past_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_past_events'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_events_main', '998px', 'Recent Events Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_recent_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_recent_events'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the main events page blocks
-- bof the groups main page blocks
('bx_groups_main', '998px', 'Recent Groups Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_recent_groups', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_recent_groups'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the groups main page blocks
-- bof the main sites page blocks
('bx_sites_main', '998px', 'Recent Sites Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_recent_sites', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_recent_sites'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_sites_main', '998px', 'Feautred Sites Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_featured_sites', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_featured_sites'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the main sites page blocks
-- bof the main blogs page blocks
('bx_blogs_home', '998px', 'Latest Blog Post Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_latest_blog_post', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''latest_blog_post'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the main blogs page blocks
-- bof the photos main page blocks
('bx_photos_home', '998px', 'Main Public Photos Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_public_photos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_public_photos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_photos_home', '998px', 'Main Featured Photos Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_featured_photos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_featured_photos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_photos_home', '998px', 'Main Favorite Photos Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_favorite_photos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_favorite_photos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the photos main page blocks
-- bof the videos main page blocks
('bx_videos_home', '998px', 'Main Public Videos Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_public_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_public_videos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_videos_home', '998px', 'Main Featured Videos Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_featured_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_featured_videos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_videos_home', '998px', 'Main Favorite Videos Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_favorite_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_favorite_videos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the videos main page blocks
-- bof the sounds main page blocks
('bx_sounds_home', '998px', 'Main Public Sounds Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_public_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_public_sounds'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_sounds_home', '998px', 'Main Featured Sounds Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_featured_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_featured_sounds'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_sounds_home', '998px', 'Main Favorite Sounds Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_favorite_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_favorite_sounds'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the sounds main page blocks
-- bof the files main page blocks
('bx_files_home', '998px', 'Main Public Files Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_public_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_public_files'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_files_home', '998px', 'Main Top Files Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_top_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_top_files'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_files_home', '998px', 'Main Featured Files Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_featured_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_featured_files'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_files_home', '998px', 'Main Favorite Files Bon Con In Realtime', '_emmetbytes_bon_con_in_realtime_main_favorite_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBonConInRealtime'', ''main_favorite_files'', array($iBlockID));', 1, 66, 'non,memb', 0);
-- eof the files main page blocks
SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id` = '2');
INSERT IGNORE INTO `sys_menu_admin` (`parent_id`, `name`, `title`, `url`, `description`, `icon`, `order`) VALUES 
(2, 'emmet_bytes_bon_con_in_realtime', 'emmetbytes_bon_con_in_realtime', '{siteUrl}modules/?r=ebBonConInRealtime/administration/', 'BonConInRealtime module by EmmetBytes', 'modules/EmmetBytes/emmetbytes_bon_con_in_realtime/|icon.png', @iMax+1);

