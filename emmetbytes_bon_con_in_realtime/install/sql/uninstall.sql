DROP TABLE IF EXISTS `[db_prefix]settings`;
DELETE FROM `sys_permalinks` WHERE `standard` = 'modules/?r=ebBonConInRealtime/';
SET @iCategId = (SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'BonConInRealtime' LIMIT 1);
DELETE FROM `sys_options` WHERE `kateg` = @iCategId;
DELETE FROM `sys_options_cats` WHERE `ID` = @iCategId;
DELETE FROM `sys_options` WHERE `Name` = 'emmet_bytes_bon_con_in_realtime_permalinks';
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Homepage Photo Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Homepage Sites Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Homepage Events Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Homepage Blogs Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Homepage Blogs Articles Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Homepage Sounds Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Homepage Videos Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Homepage Files Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'index' AND `Desc` = 'Homepage Groups Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Desc` = 'Own Events Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Desc` = 'Joined Events Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Desc` = 'Own Groups Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Desc` = 'Joined Groups Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Desc` = 'Own Sites Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Desc` = 'Own Blogs Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Desc` = 'Own Photos Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Desc` = 'Own Videos Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'profile' AND `Desc` = 'Own Sounds Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_events_main' AND `Desc` = 'Upcoming Events Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_events_main' AND `Desc` = 'Past Events Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_events_main' AND `Desc` = 'Recent Events Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_events_my' AND `Desc` = 'My Events Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_groups_main' AND `Desc` = 'Recent Groups Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_groups_my' AND `Desc` = 'My Groups Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_sites_main' AND `Desc` = 'Recent Sites Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_sites_main' AND `Desc` = 'Feautred Sites Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_sites_profile' AND `Desc` = 'Users Own Sites';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_blogs_home' AND `Desc` = 'Latest Blog Post';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_photos_home' AND `Desc` = 'Main Public Photos Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_photos_home' AND `Desc` = 'Main Featured Photos Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_photos_home' AND `Desc` = 'Main Favorite Photos Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_videos_home' AND `Desc` = 'Main Public Videos Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_videos_home' AND `Desc` = 'Main Featured Videos Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_videos_home' AND `Desc` = 'Main Favorite Videos Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_sounds_home' AND `Desc` = 'Main Public Sounds Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_sounds_home' AND `Desc` = 'Main Featured Sounds Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_sounds_home' AND `Desc` = 'Main Favorite Sounds Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_files_home' AND `Desc` = 'Main Public Files Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_files_home' AND `Desc` = 'Main Top Files Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_files_home' AND `Desc` = 'Main Featured Files Bon Con In Realtime';
DELETE FROM `sys_page_compose` WHERE `Page` = 'bx_files_home' AND `Desc` = 'Main Favorite Files Bon Con In Realtime';
DELETE FROM `sys_menu_admin` WHERE `name` = 'emmet_bytes_bon_con_in_realtime';

