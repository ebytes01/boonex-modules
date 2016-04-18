<?
/**********************************************************************************************
 * Created By : EmmetBytes Software Solutions
 * Created Date : February 20, 2013
 * Email : emmetbytes@gmail.com
 *
 * Copyright : (c) EmmetBytes Software Solutions 2012
 * Product Name : Profile Cover Mnmal
 * Product Version : 1.1
 * 
 * Important : This is a commercial product by EmmetBytes Software Solutions and 
 *   cannot be modified, redistributed or resold without any written permission 
 *   from EmmetBytes Software Solutions
 **********************************************************************************************/

bx_import('BxDolTwigModuleDb');

/*
 * ProfileCvr module Data
 */
class EmmetBytesProfileCvrDb extends BxDolTwigModuleDb {	

	function EmmetBytesProfileCvrDb(&$oConfig) {
		parent::BxDolTwigModuleDb($oConfig);
        $this->_sTableMain = '_main';
        $this->_sPrefix = 'eb_profile_cvr';
        $this->_sTableId = 'id';
        $this->_sFieldAuthorId = 'profile_id';
        $this->_sTableProfile = 'Profiles';
        $this->_sTableProfileFields = 'sys_profile_fields';
        $this->_sTableProfileColHeadline = 'Headline';
        $this->_sTableProfileColId = 'ID';

	}

    // check if the member has a profile cvr data
    function getCoverDataByProfileId($profileId){
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
                `{$this->_sPrefix}{$this->_sTableMain}`
            WHERE
                `profile_id` = '$profileId'
        ");
    }

    // inserting the background info
    function insertBackgroundInfo($params){
        $this->query("
            INSERT `{$this->_sPrefix}{$this->_sTableMain}`(
                `profile_id`,
                `background_image`,
                `bg_pos_x`,
                `bg_pos_y`
            )VALUES(
                '{$params["profile_id"]}',
                '{$params["background_image"]}',
                '{$params["bg_pos_x"]}',
                '{$params["bg_pos_y"]}'
            )
        ");
        return $this->lastId();
    }

    // update the background info
    function updateBackgroundInfo($params){
        $this->query("
            UPDATE
                `{$this->_sPrefix}{$this->_sTableMain}`
            SET
                `background_image` = '{$params["background_image"]}',
                `bg_pos_x` = '{$params["bg_pos_x"]}',
                `bg_pos_y` = '{$params["bg_pos_y"]}'
            WHERE
                `profile_id` = '{$params["profile_id"]}'
        ");
    }

    // remove the background profile cvr
    function removeBackgroundProfileCvr($id){
        return $this->query("
            DELETE FROM
                `{$this->_sPrefix}{$this->_sTableMain}`
            WHERE
                `id` = '$id'
        ");
    }

    // inserting the avatar info
    function insertAvatarInfo($params){
        $this->query("
            INSERT `{$this->_sPrefix}{$this->_sTableMain}`(
                `profile_id`,
                `thumbnail_image`,
                `t_pos_x`,
                `t_pos_y`
            )VALUES(
                '{$params['profile_id']}',
                '{$params['thumbnail_image']}',
                '{$params['t_pos_x']}',
                '{$params['t_pos_y']}'
            )

        ");
        return $this->lastId();
    }

    // updating the avatar info
    function updateAvatarInfo($params){
        $this->query("
            UPDATE
                `{$this->_sPrefix}{$this->_sTableMain}`
            SET
                `thumbnail_image` = '{$params['thumbnail_image']}',
                `t_pos_x` = '{$params['t_pos_x']}',
                `t_pos_y` = '{$params['t_pos_y']}'
            WHERE
                `profile_id` = '{$params['profile_id']}'
        "); 
    }

    // checking if the member is a friend
    function isFriend($profileId, $loginId){
        return $this->getRow("
            SELECT
                `ID`
            FROM
                `sys_friend_list`
            WHERE
                `Profile` = '$profileId'
            AND
                `ID` = '$loginId'
        ");
    }

    // getting the relationship status
    function getRelationshipStatus(){
        return $this->getAll("
            SELECT
                `Values`
            FROM
                `sys_profile_fields`
            WHERE
                `Name` = 'RelationshipStatus'
        ");
    }

    // getting the member photos
    function getMemberPhotos($profileId, $limit=1){
        return $this->getAll("
            SELECT
                `ID`
            FROM
                `bx_photos_main`
            WHERE
                `Owner` = '$profileId'
            LIMIT 0, $limit
        "); 
    } 

    // getting teh member videos
    function getMemberVideos($profileId, $limit=1){
       return $this->getAll("
            SELECT
                `ID`
            FROM
                `RayVideoFiles`
            WHERE
                `Owner` = '$profileId'
            LIMIT 0, $limit
       "); 
    }

    // getting the files album count
    function getMemberFileAlbumCount($profileId, $fileType){
        return $this->getRow("
            SELECT
                count(`ID`) as `count`
            FROM
                `sys_albums`
            WHERE
                `Status` = 'active'
            AND
                `Owner` = '$profileId'
            AND 
                `Type` = '$fileType'
        ");
    }

    // getting the ads photos
    function getMemberAdsPhotos($profileId){
        return $this->getColumn("
            SELECT
                `mm`.`MediaFile`
            FROM    
                `bx_ads_main` as m
            LEFT JOIN   
                `bx_ads_main_media` as mm       
            ON
                m.`media` = mm.`MediaID`
            WHERE
                m.`IDProfile` = '$profileId'
            AND
                mm.`MediaFile` != ''
        "); 
    }

    // getting the ads count
    function getMemberAdsCount($profileId, $loginId){
        $hasTimestamp = '';
        if($profileId != $loginId){
            $hasTimestamp = "AND UNIX_TIMESTAMP() - `bx_ads_main`.`LifeTime`*24*60*60 < `bx_ads_main`.`DateTime`";
        }
        return $this->getRow("
            SELECT
                count(`ID`) as `count`
            FROM
                `bx_ads_main`
            WHERE
                `IDProfile` = '$profileId'
            $hasTimestamp
        ");
    }

    // getting the blogs photos
    function getMembersBlogsPhotos($profileId, $limit = 1){
        return $this->getAll("
            SELECT 
                `PostID` as `id`, 
                `PostPhoto`
            FROM 
                `bx_blogs_posts` 
            WHERE 
                `OwnerID` = '$profileId'
            AND
                `PostPhoto` != ''
            LIMIT 0, $limit
        ");
    }

    // getting the blogs posts count
    function getMemberBlogPostsCount($profileId){
        return $this->getRow("
            SELECT
                count(`PostID`) as `count`
            FROM
                `bx_blogs_posts`
            WHERE
                `OwnerID` = '$profileId'
        ");
    }

    // getting the polls count
    function getPollsCount($profileId){
        return $this->getRow("
            SELECT
                count(`id_poll`) as count
            FROM
                `bx_poll_data`
            WHERE
                `id_profile` = $profileId
            AND
                `poll_approval` = 1
        ");
    }

    // getting the website images
    function getMemberWebsitePhotos($profileId, $limit = 1){
        return $this->getAll("
            SELECT
                `photo`
            FROM
                `bx_sites_main`
            WHERE
                `ownerId` = '$profileId'  
            AND
                `photo` != 0
            ORDER BY
                `date` DESC
            LIMIT 0, $limit
        ");
    }

    // getting the website count
    function getWebsiteCount($profileId){
        return $this->getRow("
            SELECT
                count(`id`) as count
            FROM
                `bx_sites_main`
            WHERE
                `ownerId` = '$profileId'
        ");
    }

    // getting the events images
    function getMemberEventPhotos($profileId, $limit = 1){
        return $this->getAll("
            SELECT
               `PrimPhoto`
            FROM
                `bx_events_main`
            WHERE
                `ResponsibleID` = '$profileId' 
            AND
                `PrimPhoto` != ''
            ORDER BY
                `Date` DESC
            LIMIT 0, $limit;
        ");
    }

    // getting the member event count
    function getMemberEventCount($profileId){
        return $this->getRow("
            SELECT
                count(`ID`) as count
            from
                `bx_events_main`
            WHERE
                `ResponsibleID` = '$profileId'
        ");
    }

    // getting the member store photos
    function getMemberStorePhotos($profileId, $limit = 1){
        return $this->getAll("
            SELECT
                `thumb`
            FROM
                `bx_store_products`
            WHERE
                `author_id` = '$profileId'
            AND
                `status` = 'approved'
            AND
                `thumb` != ''
            LIMIT 0, $limit
        ");
    }

    // getting the member store products count
    function getMemberStoreProductsCount($profileId){
        return $this->getRow("
            SELECT
                count(`id`) as count
            FROM
                `bx_store_products`
            WHERE
                `author_id` = '$profileId'
            AND
                `status` = 'approved'
        ");
    }

    // getting the member groups photos
    function getMemberGroupPhotos($profileId, $limit = 1){
        return $this->getAll("
            SELECT
               `thumb`
            FROM
                `bx_groups_main`
            WHERE
               `author_id` = '$profileId'  
            AND
               `thumb` != 0 
            LIMIT 0, $limit
        ");
    }

    // getting the member groups count
    function getMemberGroupsCount($profileId){
        return $this->getRow("
            SELECT
                count(`id`) as count
            FROM
                `bx_groups_main`
            WHERE
                `author_id` = '$profileId'
        ");
    }

    function checkMemberConnection($memberId1, $memberId2){
        return $this->getRow("
            SELECT
                `Check`
            FROM
                `sys_friend_list`
            WHERE
                `ID` = '$memberId1'
            AND
                `Profile` = '$memberId2'
        ");
    }
}

?>
