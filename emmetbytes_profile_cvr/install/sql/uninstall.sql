-- tables
DROP TABLE IF EXISTS `eb_profile_cvr_main`;

-- sys injections
DELETE FROM `sys_injections` WHERE `name` = 'eb_profile_cvr';

-- system objects
DELETE FROM `sys_permalinks` WHERE `standard` = 'modules/?r=ebProfileCvr/';

-- admin menu
DELETE FROM `sys_menu_admin` WHERE `name` = 'emmet_bytes_profile_cvr';

-- settings
SET @iCategId = (SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'ProfileCvr' LIMIT 1);
DELETE FROM `sys_options` WHERE `kateg` = @iCategId;
DELETE FROM `sys_options_cats` WHERE `ID` = @iCategId;
DELETE FROM `sys_options` WHERE `Name` = 'emmet_bytes_profile_cvr_permalinks';

-- alert handlers
DELETE FROM `sys_alerts_handlers` WHERE `name` = 'eb_profile_cvr_add_background';
DELETE FROM `sys_alerts_handlers` WHERE `name` = 'eb_profile_cvr_change_background';
DELETE FROM `sys_alerts_handlers` WHERE `name` = 'eb_profile_cvr_remove_background';
DELETE FROM `sys_alerts_handlers` WHERE `name` = 'eb_profile_cvr_add_thumbnail';
DELETE FROM `sys_alerts_handlers` WHERE `name` = 'eb_profile_cvr_change_thumbnail';
