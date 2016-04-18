-- profile cover main table
CREATE TABLE `eb_profile_cover_main`(
    `id` int not null auto_increment primary key,
    `profile_id` int not null,
    `background_image` varchar(200),
    `bg_pos_x` int not null,
    `bg_pos_y` int not null,
    `thumbnail_image` varchar(200),
    `t_pos_x` int not null,
    `t_pos_y` int not null
);

-- sys_injections
INSERT INTO `sys_injections` (`id`, `name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES (NULL, 'eb_profile_cover', '0', 'injection_eb_profile_cover', 'php', "return BxDolService::call('ebProfileCover', 'get_profile_cover');", '0', '1');

-- permalink
INSERT INTO `sys_permalinks` VALUES (NULL, 'modules/?r=ebProfileCover/', 'm/ebProfileCover/', 'emmet_bytes_profile_cover_permalinks');

-- settings
SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('ProfileCover', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
('emmet_bytes_profile_cover_permalinks', 'on', 26, 'Enable friendly permalinks in profileCover', 'checkbox', '', '', '0', ''),
('emmet_bytes_profile_cover_hide_empty_containers', '', @iCategId, 'Hide Empty Containers', 'checkbox', '', '', 1, ''),
('emmet_bytes_profile_cover_display_friends', 'on', @iCategId, 'Display Friends Container', 'checkbox', '', '', 2, ''),
('emmet_bytes_profile_cover_display_photo_albums', 'on', @iCategId, 'Display Photo Albums Container ( If Installed )', 'checkbox', '', '', 3, ''),
('emmet_bytes_profile_cover_display_video_albums', 'on', @iCategId, 'Display Video Albums Container ( If Installed )', 'checkbox', '', '', 4, ''),
('emmet_bytes_profile_cover_display_sounds_albums', 'on', @iCategId, 'Display Sounds Albums Container ( If Installed )', 'checkbox', '', '', 5, ''),
('emmet_bytes_profile_cover_display_file_folders', 'on', @iCategId, 'Display File Folders Container ( If Installed )', 'checkbox', '', '', 6, ''),
('emmet_bytes_profile_cover_display_ads', 'on', @iCategId, 'Display Ads Container ( If Installed )', 'checkbox', '', '', 7, ''),
('emmet_bytes_profile_cover_display_blog_posts', 'on', @iCategId, 'Display Blog Posts Container ( If Installed )', 'checkbox', '', '', 8, ''),
('emmet_bytes_profile_cover_display_polls', 'on', @iCategId, 'Display Polls Container ( If Installed )', 'checkbox', '', '', 9, ''),
('emmet_bytes_profile_cover_dipslay_websites', 'on', @iCategId, 'Display Websites Container ( If Installed )', 'checkbox', '', '', 10, ''),
('emmet_bytes_profile_cover_display_events', 'on', @iCategId, 'Display Events Container ( If Installed )', 'checkbox', '', '', 11, ''),
('emmet_bytes_profile_cover_display_store_products', 'on', @iCategId, 'Display Store Products Container ( If Installed )', 'checkbox', '', '', 12, ''),
('emmet_bytes_profile_cover_display_groups', 'on', @iCategId, 'Display Groups Container ( If Installed )', 'checkbox', '', '', 13, ''),
('emmet_bytes_profile_cover_profile_cover_background_compr_level', '75', @iCategId, 'Profile Cover Background Compression Level (0-Worst Quality, 100-Best Quality)', 'digit', '', '', 14, ''),
('emmet_bytes_profile_cover_background_size', '512000', @iCategId, 'Maximum number of bytes for the profile cover background image', 'digit', '', '', 15, ''),
('emmet_bytes_profile_cover_avatar_size', '512000', @iCategId, 'Maximum number of bytes for the profile cover avatar image', 'digit', '', '', 16, '');

-- admin menu
SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id` = '2');
INSERT IGNORE INTO `sys_menu_admin` (`parent_id`, `name`, `title`, `url`, `description`, `icon`, `order`) VALUES
(2, 'emmet_bytes_profile_cover', '_emmet_bytes_profile_cover', '{siteUrl}modules/?r=ebProfileCover/administration/', 'Profile Cover module by EmmetBytes', 'modules/EmmetBytes/emmetbytes_profile_cover/|profileCover.png', @iMax+1);

-- alert handlers
INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_profile_cover_add_background', '', '', 'BxDolService::call(''ebProfileCover'', ''response_add_background'', array($this));');
INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_profile_cover_change_background', '', '', 'BxDolService::call(''ebProfileCover'', ''response_change_background'', array($this));');
INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_profile_cover_remove_background', '', '', 'BxDolService::call(''ebProfileCover'', ''response_remove_background'', array($this));');
INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_profile_cover_add_thumbnail', '', '', 'BxDolService::call(''ebProfileCover'', ''response_add_thumbnail'', array($this));');
INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_profile_cover_change_thumbnail', '', '', 'BxDolService::call(''ebProfileCover'', ''response_change_thumbnail'', array($this));');

