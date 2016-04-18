-- club cover main table
CREATE TABLE `eb_club_cover_main`(
    `id` int not null auto_increment primary key,
    `club_id` int not null,
    `background_image` varchar(200),
    `bg_pos_x` int not null,
    `bg_pos_y` int not null,
    `club_logo` varchar(200),
    `t_pos_x` int not null,
    `t_pos_y` int not null
);

-- sys_injections
INSERT INTO `sys_injections` (`id`, `name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES (NULL, 'eb_club_cover', '0', 'injection_eb_club_cover', 'php', "return BxDolService::call('ebClubCover', 'get_club_cover');", '0', '1');

-- permalink
INSERT INTO `sys_permalinks` VALUES (NULL, 'modules/?r=ebClubCover/', 'm/ebClubCover/', 'emmet_bytes_club_cover_permalinks');

-- settings
SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('ClubCover', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
('emmet_bytes_club_cover_permalinks', 'on', 26, 'Enable friendly permalinks in clubCover', 'checkbox', '', '', '0', ''),
('emmet_bytes_club_cover_hide_empty_containers', '', @iCategId, 'Hide Empty Containers', 'checkbox', '', '', 1, ''),
('emmet_bytes_club_cover_display_fans', 'on', @iCategId, 'Display Fans Container', 'checkbox', '', '', 2, ''),
('emmet_bytes_club_cover_display_photo_albums', 'on', @iCategId, 'Display Photo Albums Container', 'checkbox', '', '', 3, ''),
('emmet_bytes_club_cover_display_video_albums', 'on', @iCategId, 'Display Video Albums Container', 'checkbox', '', '', 4, ''),
('emmet_bytes_club_cover_display_sounds_albums', 'on', @iCategId, 'Display Sounds Albums Container', 'checkbox', '', '', 5, ''),
('emmet_bytes_club_cover_display_file_folders', 'on', @iCategId, 'Display File Folders Container', 'checkbox', '', '', 6, ''),
('emmet_bytes_club_cover_display_news', 'on', @iCategId, 'Display News Container', 'checkbox', '', '', 7, ''),
('emmet_bytes_club_cover_display_websites', 'on', @iCategId, 'Display Websites Container', 'checkbox', '', '', 8, ''),
('emmet_bytes_club_cover_display_events', 'on', @iCategId, 'Display Events Container', 'checkbox', '', '', 9, ''),
('emmet_bytes_club_cover_club_cover_background_compr_level', '75', @iCategId, 'Club Cover Background Compression Level (0-Worst Quality, 100-Best Quality)', 'digit', '', '', 10, ''),
('emmet_bytes_club_cover_background_size', '512000', @iCategId, 'Maximum number of bytes for the club cover background image', 'digit', '', '', 11, ''),
('emmet_bytes_club_cover_logo_size', '512000', @iCategId, 'Maximum number of bytes for the club cover logo image', 'digit', '', '', 12, '');

-- admin menu
SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id` = '2');
INSERT IGNORE INTO `sys_menu_admin` (`parent_id`, `name`, `title`, `url`, `description`, `icon`, `order`) VALUES
(2, 'emmet_bytes_club_cover', '_emmet_bytes_club_cover', '{siteUrl}modules/?r=ebClubCover/administration/', 'Club Cover module by EmmetBytes', 'modules/EmmetBytes/emmetbytes_club_cover/|clubCover.png', @iMax+1);

-- alert handlers
INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_club_cover_add_background', '', '', 'BxDolService::call(''ebClubCover'', ''response_add_background'', array($this));');
INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_club_cover_change_background', '', '', 'BxDolService::call(''ebClubCover'', ''response_change_background'', array($this));');
INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_club_cover_remove_background', '', '', 'BxDolService::call(''ebClubCover'', ''response_remove_background'', array($this));');
INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_club_cover_add_thumbnail', '', '', 'BxDolService::call(''ebClubCover'', ''response_add_thumbnail'', array($this));');
INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_club_cover_change_thumbnail', '', '', 'BxDolService::call(''ebClubCover'', ''response_change_thumbnail'', array($this));');

