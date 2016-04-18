<?php
    ini_set('display_errors', 1);
    require_once ('inc/header.inc.php');
    require_once(BX_DIRECTORY_PATH_INC . 'utils.inc.php');
    require_once(BX_DIRECTORY_PATH_INC . 'profiles.inc.php' );
    require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolDb.php');

    $oDb = new BxDolDb();
    $oDb->query("
            UPDATE  
               `sys_menu_admin` 
            SET  
                `icon` = 'modules/EmmetBytes/emmetbytes_club_cover/|clubCover.png'
            WHERE
                `name` = 'emmet_bytes_club_cover'
    ");
    $menuIconUpdated = $oDb->getAffectedRows();
    echo "Sorry for this problem.<br/>Menu Icon Successfully updated, please clear off your websites cache and check if the update has been done : "; 
    echo "updated columns : " . $menuIconUpdated;

?>

