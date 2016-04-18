<?php
    ini_set('display_errors', 1);
    require_once ('inc/header.inc.php');
    require_once(BX_DIRECTORY_PATH_INC . 'utils.inc.php');
    require_once(BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
    require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolDb.php');

    $oDb = new BxDolDb();
    echo "updating the options : <br/>";
    $catId = $oDb->getRow("SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'ClubCover'");
    $catId = $catId['ID'];
    $oDb->query("DELETE FROM `sys_options` WHERE `kateg` = '$catId'");
    $oDb->query("
            INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
            ('emmet_bytes_club_cover_hide_empty_containers', '', $catId, 'Hide Empty Containers', 'checkbox', '', '', 1, ''),
            ('emmet_bytes_club_cover_display_friends', 'on', $catId, 'Display Friends Container', 'checkbox', '', '', 2, ''),
            ('emmet_bytes_club_cover_display_photo_albums', 'on', $catId, 'Display Photo Albums Container ( If Installed )', 'checkbox', '', '', 3, ''),
            ('emmet_bytes_club_cover_display_video_albums', 'on', $catId, 'Display Video Albums Container ( If Installed )', 'checkbox', '', '', 4, ''),
            ('emmet_bytes_club_cover_display_sounds_albums', 'on', $catId, 'Display Sounds Albums Container ( If Installed )', 'checkbox', '', '', 5, ''),
            ('emmet_bytes_club_cover_display_file_folders', 'on', $catId, 'Display File Folders Container ( If Installed )', 'checkbox', '', '', 6, ''),
            ('emmet_bytes_club_cover_display_ads', 'on', $catId, 'Display Ads Container ( If Installed )', 'checkbox', '', '', 7, ''),
            ('emmet_bytes_club_cover_display_blog_posts', 'on', $catId, 'Display Blog Posts Container ( If Installed )', 'checkbox', '', '', 8, ''),
            ('emmet_bytes_club_cover_display_polls', 'on', $catId, 'Display Polls Container ( If Installed )', 'checkbox', '', '', 9, ''),
            ('emmet_bytes_club_cover_dipslay_websites', 'on', $catId, 'Display Websites Container ( If Installed )', 'checkbox', '', '', 10, ''),
            ('emmet_bytes_club_cover_display_events', 'on', $catId, 'Display Events Container ( If Installed )', 'checkbox', '', '', 11, ''),
            ('emmet_bytes_club_cover_display_store_products', 'on', $catId, 'Display Store Products Container ( If Installed )', 'checkbox', '', '', 12, ''),
            ('emmet_bytes_club_cover_display_groups', 'on', $catId, 'Display Groups Container ( If Installed )', 'checkbox', '', '', 13, ''),
            ('emmet_bytes_club_cover_club_cover_background_compr_level', '75', $catId, 'Club Cover Background Compression Level (0-Worst Quality, 100-Best Quality)', 'digit', '', '', 14, ''),
            ('emmet_bytes_club_cover_background_size', '512000', $catId, 'Maximum number of bytes for the club cover background image', 'digit', '', '', 15, ''),
            ('emmet_bytes_club_cover_avatar_size', '512000', $catId, 'Maximum number of bytes for the club cover avatar image', 'digit', '', '', 16, '')
    ");
    $success = $oDb->getAffectedRows();
    if($success){
        echo "CONGRATULATIONS, YOU HAVE SUCCESSFULLY UPGRADED THE PRODUCT<br/>";
    }

?>

