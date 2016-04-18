-- creating the settings table
CREATE TABLE IF NOT EXISTS `[db_prefix]settings` ( 
    `ID` int(10) unsigned NOT NULL auto_increment, 
    `name` varchar(80) not null, 
    `default_tab` varchar(100) not null, 
    `maximum_datas` varchar(100) not null,
    `maximum_title_characters` varchar(100) not null,
    `display_date_start` int not null,
    `display_created_date` int not null,
    `display_views` int not null,
    `display_size` int not null,
    `display_categories` int not null,
    `display_tags` int not null,
    `display_comments_count` int not null,
    `display_author` int not null,
    `display_rate` int not null,
    `display_fans_count` int not null,
    `display_location` int not null,
    `display_site_url` int not null,
    `display_ads_price` int not null,
    `display_description` int not null,
    `maximum_description_characters` varchar(100) not null,
    INDEX(`name`), 
    PRIMARY KEY (`ID`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- insert settings datas
INSERT INTO `emmet_bytes_boonex_contents_slider_settings` (`ID`, `name`, `default_tab`, `maximum_datas`, `maximum_title_characters`, `display_date_start`, `display_created_date`, `display_views`, `display_size`, `display_categories`, `display_tags`, `display_comments_count`, `display_author`, `display_rate`, `display_fans_count`, `display_location`, `display_site_url`, `display_ads_price`, `display_description`, `maximum_description_characters`) VALUES
(1, 'homepage_photos_block_settings', 'last', '8', '40', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(2, 'profile_my_photos_block_settings', '', '8', '40', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(3, 'profile_my_videos_block_settings', '', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(4, 'homepage_videos_block_settings', 'last', '8', '40', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(5, 'homepage_sounds_block_settings', 'last', '8', '40', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(6, 'homepage_files_block_settings', 'last', '8', '40', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 1, '10'),
(12, 'profile_joined_event_block_settings', '', '8', '40', 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, ''),
(7, 'profile_my_event_block_settings', '', '8', '40', 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, ''),
(8, 'homepage_event_block_settings', 'recent', '8', '40', 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 1, '300'),
(9, 'homepage_group_block_settings', 'featured', '8', '40', 0, 1, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 1, '300'),
(10, 'homepage_site_block_settings', '', '4', '40', 0, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 1, 0, 1, '200'),
(11, 'homepage_blogs_block_settings', 'last', '8', '40', 0, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 1, '400'),
(13, 'profile_my_group_block_settings', '', '8', '40', 0, 1, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 1, '300'),
(14, 'profile_joined_group_block_settings', '', '8', '40', 0, 1, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, ''),
(15, 'profile_my_site_block_settings', '', '8', '40', 0, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 1, 0, 1, '300'),
(16, 'profile_my_blogs_block_settings', '', '8', '40', 0, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 1, '400'),
(17, 'profile_my_sounds_block_settings', '', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(18, 'module_blocks_main_upcoming_event_block_settings', '', '8', '30', 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 1, '100'),
(19, 'module_blocks_main_past_event_block_settings', '', '8', '30', 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 1, '100'),
(20, 'module_blocks_main_recent_event_block_settings', '', '8', '30', 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 1, '100'),
(21, 'module_blocks_main_recent_group_block_settings', '', '8', '30', 0, 1, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 1, '100'),
(22, 'module_blocks_main_featured_site_block_settings', '', '8', '30', 0, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 1, 0, 1, '300'),
(23, 'module_blocks_main_recent_site_block_settings', '', '8', '30', 0, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 1, 0, 1, '300'),
(24, 'module_blocks_main_latest_blogs_block_settings', 'last', '8', '40', 0, 1, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 1, '300'),
(25, 'module_blocks_main_featured_files_block_settings', '', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 1, '400'),
(26, 'module_blocks_main_public_photos_block_settings', 'last', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(27, 'module_blocks_main_favorite_photos_block_settings', '', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(28, 'module_blocks_main_featured_photos_block_settings', '', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(29, 'module_blocks_main_public_videos_block_settings', 'last', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(30, 'module_blocks_main_favorite_videos_block_settings', '', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(31, 'module_blocks_main_featured_videos_block_settings', '', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(32, 'module_blocks_main_public_sounds_block_settings', 'last', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(33, 'module_blocks_main_favorite_sounds_block_settings', '', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(34, 'module_blocks_main_featured_sounds_block_settings', '', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, ''),
(35, 'module_blocks_main_public_files_block_settings', 'last', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 1, '400'),
(36, 'module_blocks_main_top_files_block_settings', '', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 1, '400'),
(37, 'module_blocks_main_favorite_files_block_settings', '', '8', '30', 0, 1, 1, 1, 0, 0, 0, 1, 1, 0, 0, 0, 0, 1, '400'),
(39, 'homepage_ads_block_settings', '', '8', '40', 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, 1, 1, '10'),
(40, 'profile_my_ads_block_settings', '', '8', '40', 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, 1, 1, '10'),
(41, 'module_blocks_main_last_ads_block_settings', '', '8', '40', 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, 1, 1, '10');
-- eof the main files block settings
INSERT INTO `sys_permalinks` VALUES (NULL, 'modules/?r=ebBoonexContentsSlider/', 'm/ebBoonexContentsSlider/', 'emmet_bytes_boonex_contents_slider_permalinks');
SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('BoonexContentsSlider', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES 
('emmet_bytes_boonex_contents_slider_permalinks', 'on', 26, 'Enable friendly permalinks in boonexContentsSlider', 'checkbox', '', '', '0', '');
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES 
-- bof the index blocks
('index', '998px', 'Homepage Photo Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_homepage_photo', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''homepage_photos_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Sites Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_homepage_sites', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''homepage_sites_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Events Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_homepage_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''homepage_events_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Blogs Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_homepage_blogs', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''homepage_blogs_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Sounds Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_homepage_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''homepage_sounds_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Videos Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_homepage_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''homepage_videos_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Files Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_homepage_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''homepage_files_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Groups Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_homepage_groups', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''homepage_groups_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('index', '998px', 'Homepage Ads Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_homepage_ads', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''homepage_ads_block'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the index blocks
-- bof the profile page blocks
('profile', '998px', 'Own Events Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_profile_own_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''profile_own_events'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Joined Events Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_profile_joined_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''profile_joined_events'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Groups Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_profile_own_groups', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''profile_own_groups'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Joined Groups Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_profile_joined_groups', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''profile_joined_groups'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Sites Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_profile_own_sites', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''profile_own_sites'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Blogs Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_profile_own_blogs', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''profile_own_blogs'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Photos Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_profile_own_photos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''profile_own_photos'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Videos Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_profile_own_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''profile_own_videos'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Sounds Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_profile_own_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''profile_own_sounds'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
('profile', '998px', 'Own Ads Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_profile_own_ads', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''profile_own_ads'', array($iBlockID, $this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0), 
-- eof the profile page blocks
-- bof the main events page blocks
('bx_events_main', '998px', 'Upcoming Events Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_upcoming_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_upcoming_events'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_events_main', '998px', 'Past Events Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_past_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_past_events'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_events_main', '998px', 'Recent Events Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_recent_events', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_recent_events'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the main events page blocks
-- bof the groups main page blocks
('bx_groups_main', '998px', 'Recent Groups Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_recent_groups', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_recent_groups'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the groups main page blocks
-- bof the main sites page blocks
('bx_sites_main', '998px', 'Recent Sites Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_recent_sites', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_recent_sites'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_sites_main', '998px', 'Feautred Sites Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_featured_sites', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_featured_sites'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the main sites page blocks
-- bof the main blogs page blocks
('bx_blogs_home', '998px', 'Latest Blog Post Datas Slider', '_emmetbytes_boonex_contents_slider_latest_blog_post', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''latest_blog_post'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the main blogs page blocks
-- bof the photos main page blocks
('bx_photos_home', '998px', 'Main Public Photos Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_public_photos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_public_photos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_photos_home', '998px', 'Main Featured Photos Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_featured_photos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_featured_photos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_photos_home', '998px', 'Main Favorite Photos Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_favorite_photos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_favorite_photos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the photos main page blocks
-- bof the videos main page blocks
('bx_videos_home', '998px', 'Main Public Videos Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_public_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_public_videos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_videos_home', '998px', 'Main Featured Videos Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_featured_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_featured_videos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_videos_home', '998px', 'Main Favorite Videos Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_favorite_videos', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_favorite_videos'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the videos main page blocks
-- bof the sounds main page blocks
('bx_sounds_home', '998px', 'Main Public Sounds Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_public_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_public_sounds'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_sounds_home', '998px', 'Main Featured Sounds Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_featured_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_featured_sounds'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_sounds_home', '998px', 'Main Favorite Sounds Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_favorite_sounds', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_favorite_sounds'', array($iBlockID));', 1, 66, 'non,memb', 0), 
-- eof the sounds main page blocks
-- bof the files main page blocks
('bx_files_home', '998px', 'Main Public Files Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_public_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_public_files'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_files_home', '998px', 'Main Top Files Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_top_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_top_files'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_files_home', '998px', 'Main Featured Files Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_featured_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_featured_files'', array($iBlockID));', 1, 66, 'non,memb', 0), 
('bx_files_home', '998px', 'Main Favorite Files Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_favorite_files', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_favorite_files'', array($iBlockID));', 1, 66, 'non,memb', 0),
-- eof the files main page blocks
-- bof the ads main page blocks
('ads_home', '998px', 'Main Last Ads Boonex Contents Slider', '_emmetbytes_boonex_contents_slider_main_last_ads', 0, 1, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''ebBoonexContentsSlider'', ''main_last_ads'', array($iBlockID));', 1, 66, 'non,memb', 0);
-- eof the ads main page blocks
SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id` = '2');
INSERT IGNORE INTO `sys_menu_admin` (`parent_id`, `name`, `title`, `url`, `description`, `icon`, `order`) VALUES 
(2, 'emmetbytes_contents_slider', 'emmetbytes_boonex_contents_slider', '{siteUrl}modules/?r=ebBoonexContentsSlider/administration/', 'BoonexContentsSlider module by EmmetBytes', 'modules/EmmetBytes/emmetbytes_boonex_contents_slider/|icon.png', @iMax+1);

