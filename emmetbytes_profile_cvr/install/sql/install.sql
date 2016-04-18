-- profile cvr main table
CREATE TABLE `eb_profile_cvr_main`(
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
INSERT INTO `sys_injections` (`id`, `name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES (NULL, 'eb_profile_cvr', '0', 'injection_eb_profile_cvr', 'php', "return BxDolService::call('ebProfileCvr', 'get_profile_cvr');", '0', '1');

-- permalink
INSERT INTO `sys_permalinks` VALUES (NULL, 'modules/?r=ebProfileCvr/', 'm/ebProfileCvr/', 'emmet_bytes_profile_cvr_permalinks');

-- settings
SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('ProfileCvr', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
('emmet_bytes_profile_cvr_permalinks', 'on', 26, 'Enable friendly permalinks in profileCvr', 'checkbox', '', '', '0', ''),
('emmet_bytes_profile_cvr_profile_cvr_background_compr_level', '75', @iCategId, 'Profile Cover Background Compression Level (0-Worst Quality, 100-Best Quality)', 'digit', '', '', 2, ''),
('emmet_bytes_profile_cvr_background_size', '512000', @iCategId, 'Maximum number of bytes for the profile cvr background image', 'digit', '', '', 3, ''),
('emmet_bytes_profile_cvr_avatar_size', '512000', @iCategId, 'Maximum number of bytes for the profile cvr avatar image', 'digit', '', '', 4, ''),
('emmet_bytes_profile_cvr_max_menu_count', '14', @iCategId, 'Maximum numbers of menus to be displayed', 'digit', '', '', 5, '');

-- admin menu
SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id` = '2');
INSERT IGNORE INTO `sys_menu_admin` (`parent_id`, `name`, `title`, `url`, `description`, `icon`, `order`) VALUES
(2, 'emmet_bytes_profile_cvr', '_emmet_bytes_profile_cvr', '{siteUrl}modules/?r=ebProfileCvr/administration/', 'Profile Cover Mnmal module by EmmetBytes', 'modules/EmmetBytes/emmetbytes_profile_cvr/|profileCvr.png', @iMax+1);

-- alert handlers
INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_profile_cvr_add_background', '', '', 'BxDolService::call(''ebProfileCvr'', ''response_add_background'', array($this));');

INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_profile_cvr_change_background', '', '', 'BxDolService::call(''ebProfileCvr'', ''response_change_background'', array($this));');

INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_profile_cvr_remove_background', '', '', 'BxDolService::call(''ebProfileCvr'', ''response_remove_background'', array($this));');

INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_profile_cvr_add_thumbnail', '', '', 'BxDolService::call(''ebProfileCvr'', ''response_add_thumbnail'', array($this));');

INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'eb_profile_cvr_change_thumbnail', '', '', 'BxDolService::call(''ebProfileCvr'', ''response_change_thumbnail'', array($this));');

