<?
/**********************************************************************************************
 * Created By : EmmetBytes Software Solutions
 * Created Date : February 20, 2013
 * Email : emmetbytes@gmail.com
 *
 * Copyright : (c) EmmetBytes Software Solutions 2012
 * Product Name : Club Cover
 * Product Version : 1.1
 * 
 * Important : This is a commercial product by EmmetBytes Software Solutions and 
 *   cannot be modified, redistributed or resold without any written permission 
 *   from EmmetBytes Software Solutions
 **********************************************************************************************/

bx_import('BxDolTwigModuleDb');

/*
 * ClubCover module Data
 */
class EmmetBytesClubCoverDb extends BxDolTwigModuleDb {	

	function EmmetBytesClubCoverDb(&$oConfig) {
		parent::BxDolTwigModuleDb($oConfig);
        $this->_sTableMain = 'main';
        $this->_sPrefix = 'modzzz_club_';
        $this->_sClubPrefix = 'eb_club_cover';
        $this->_sClubTableMain = '_main';
        $this->_sTableId = 'id';
        $this->_sFieldAuthorId = 'profile_id';
        $this->_sTableProfile = 'Profiles';
        $this->_sProfileCoverTableMain = '_main';
        $this->_sProfileCoverPrefix = 'eb_profile_cover';
        $this->_sTableProfileFields = 'sys_profile_fields';
        $this->_sTableProfileColHeadline = 'Headline';
        $this->_sTableProfileColId = 'ID';

	}

    function getClubCoverDatasByClubId($clubId){
        return $this->getRow("
            SELECT
                `id`,
                `club_id`,
                `background_image`,
                `bg_pos_x`,
                `bg_pos_y`,
                `club_logo`,
                `t_pos_x`,
                `t_pos_y`
            FROM
                `{$this->_sClubPrefix}{$this->_sClubTableMain}`
            WHERE
                `club_id` = '$clubId'
        ");
    }

    function insertBackgroundInfo($params){
        $this->query("
            INSERT `{$this->_sClubPrefix}{$this->_sClubTableMain}`(
                `club_id`,
                `background_image`,
                `bg_pos_x`,
                `bg_pos_y`
            )VALUES(
                '{$params["club_id"]}',
                '{$params["background_image"]}',
                '{$params["bg_pos_x"]}',
                '{$params["bg_pos_y"]}'
            )
        ");
        return $this->lastId();

    }

    function updateBackgroundInfo($params){
        $this->query("
            UPDATE
                `{$this->_sClubPrefix}{$this->_sClubTableMain}`
            SET
                `background_image` = '{$params["background_image"]}',
                `bg_pos_x` = '{$params["bg_pos_x"]}',
                `bg_pos_y` = '{$params["bg_pos_y"]}'
            WHERE
                `club_id` = '{$params["club_id"]}'
        ");
    }

    // remove the background profile cover
    function removeBackgroundClubCoverByClubId($id){
        return $this->query("
            DELETE FROM
                `{$this->_sClubPrefix}{$this->_sClubTableMain}`
            WHERE
                `club_id` = '$id'
        ");
    }

    // updating the logo info
    function updateLogoInfo($params){
        $this->query("
            UPDATE
                `{$this->_sClubPrefix}{$this->_sClubTableMain}`
            SET
                `club_logo` = '{$params['club_logo']}',
                `t_pos_x` = '{$params['t_pos_x']}',
                `t_pos_y` = '{$params['t_pos_y']}'
            WHERE
                `club_id` = '{$params['club_id']}'
        "); 
    }

    // inserting the avatar info
    function insertLogoInfo($params){
        $this->query("
            INSERT `{$this->_sPrefix}{$this->_sTableMain}`(
                `club_id`,
                `club_logo`,
                `t_pos_x`,
                `t_pos_y`
            )VALUES(
                '{$params['profile_id']}',
                '{$params['club_logo']}',
                '{$params['t_pos_x']}',
                '{$params['t_pos_y']}'
            )

        ");
        return $this->lastId();
    }

    // updating the clubs module logo
    function updateClubModuleLogo($params){
        $this->query("
            UPDATE
                `{$this->_sPrefix}{$this->_sTableMain}`
            SET 
                `icon` = '{$params['logo']}'
            WHERE
                `id` = '{$params['club_id']}'
        ");
    }

    // check if the member has a profile cover data
    function getProfileCoverDataByProfileId($profileId){
        return $this->getRow("
            SELECT
                `id`,
                `profile_id`,
                `background_image`,
                `bg_pos_x`,
                `bg_pos_y`,
                `thumbnail_image`,
                `t_pos_x`,
                `t_pos_y`
            FROM
                `{$this->_sProfileCoverPrefix}{$this->_sProfileCoverTableMain}`
            WHERE
                `profile_id` = '$profileId'
        ");
    }

    // update the club datas
    function updateClubData($params){
        return $this->query("
            UPDATE
                `{$this->_sPrefix}{$this->_sTableMain}`
            SET 
                `{$params['column_name']}` = '{$params['value']}'
            WHERE
                `id` = '{$params['club_id']}'
        ");
    }
}

?>
