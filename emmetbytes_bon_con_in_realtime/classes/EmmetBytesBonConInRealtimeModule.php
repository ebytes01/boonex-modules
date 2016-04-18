<?php
/**********************************************************************************************
 * Created By : EmmetBytes Software Solutions
 * Created Date : June 10, 2012
 * Email : emmetbytes@gmail.com
 *
 * Copyright : (c) EmmetBytes Software Solutions 2012
 * Product Name : Boonex Contents in Realtime
 * Product Version : 1.0
 * 
 * Important : This is a commercial product by EmmetBytes Software Solutions and 
 *   cannot be modified, redistributed or resold without any written permission 
 *   from EmmetBytes Software Solutions
 **********************************************************************************************/

function emmetbytes_bon_con_in_realtime_import ($sClassPostfix, $aModuleOverwright = array()) {
    global $aModule;
    $a = $aModuleOverwright ? $aModuleOverwright : $aModule;
    if (!$a || $a['uri'] != 'ebBonConInRealtime') {
        $oMain = BxDolModule::getInstance('EmmetBytesBonConInRealtimeModule');
        $a = $oMain->_aModule;
    }
    bx_import ($sClassPostfix, $a);
}

//bx_imports
bx_import('BxDolTwigModule');

class EmmetBytesBonConInRealtimeModule extends BxDolTwigModule {
    //attributes
    var $ebHelper, $ebAdminHelper;

    function EmmetBytesBonConInRealtimeModule(&$aModule) {
        parent::BxDolTwigModule($aModule);
        $this->_sFilterName = 'emmet_bytes_bon_con_in_realtime_filter';
        $this->_sPrefix = 'emmet_bytes_bon_con_in_realtime';
    }
    
    // ACTIONS
    // gets the next batch of entries
    function actionGetNewEntries(){
        echo $this->getHelper()->getNewEntriesResponse();
    }

    // getting the main thumbnail image
    function actionGetThumbImage($imagePath){
        $this->getHelper()->getThumbImage($imagePath);    
    }
    // EOF THE ACTIONS
    
    //ADMINISTRATION ACTIONS
    //main administration page
    function actionAdministration ($sUrl = '') {

        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }        
        $this->_oTemplate->pageStart();
        $aMenu = array(
            'homepage' => array(
                'title' => _t('emmetbytes_bon_con_in_realtime_administration_homepage_settings'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/homepage',
                '_func' => array ('name' => 'actionAdministrationHomepageSettings', 'params' => array()),
            ),
            'profile' => array(
                'title' => _t('emmetbytes_bon_con_in_realtime_administration_profile_settings'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/profile',
                '_func' => array ('name' => 'actionAdministrationProfileSettings', 'params' => array()),
            ),
            'module_pages' => array(
                'title' => _t('emmetbytes_bon_con_in_realtime_administration_module_pages_settings'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/module_pages',
                '_func' => array ('name' => 'actionAdministrationModulePagesSettings', 'params' => array()),
            ),
        );

        if (empty($aMenu[$sUrl])){ $sUrl = 'homepage'; }

        $aMenu[$sUrl]['active'] = 1;
        $sContent = call_user_func_array(array($this, $aMenu[$sUrl]['_func']['name']), $aMenu[$sUrl]['_func']['params']);
        echo $this->_oTemplate->adminBlock ($sContent, _t('emmetbytes_bon_con_in_realtime_administration'), $aMenu);
        $this->_oTemplate->addCssAdmin ('admin.css');
        $this->_oTemplate->addCssAdmin ('unit.css');
        $this->_oTemplate->addCssAdmin ('main.css');
        $this->_oTemplate->addCssAdmin ('forms_extra.css'); 
        $this->_oTemplate->addCssAdmin ('forms_adv.css');        
        $this->_oTemplate->pageCodeAdmin (_t('emmetbytes_bon_con_in_realtime_administration'));
    }

    // administration settings
    function actionAdministrationSettings () {
        return parent::_actionAdministrationSettings ('BonConInRealtime');
    }

    // administration homepage settings
    function actionAdministrationHomepageSettings(){
        return $this->getAdministrationHelper()->getHomepageSettings();
    }

    // administration profile settings
    function actionAdministrationProfileSettings(){
        return $this->getAdministrationHelper()->getProfileSettings();
    }

    // administration module pages settings
    function actionAdministrationModulePagesSettings(){
        return $this->getAdministrationHelper()->getModuleBlocksSettings();
    }
    //EOF THE ADMINISTRATION ACTIONS

    // HOMEPAGE SERVICE BLOCKS
    //homepage events block
    function serviceHomepageEventsBlock($iBlockID){
        $container = $this->getHelper()->homepageEventsBlockHelper($iBlockID);
        return $container;
    }

    // homepage groups block
    function serviceHomepageGroupsBlock($iBlockID){
        $container = $this->getHelper()->homepageGroupsBlockHelper($iBlockID);
        return $container;
    }

    //homepage sites block
    function serviceHomepageSitesBlock($iBlockID){
        $container = $this->getHelper()->homepageSitesBlockHelper($iBlockID);
        return $container;
    }

    // homepage blogs block
    function serviceHomepageBlogsBlock($iBlockID){
        $container = $this->getHelper()->homepageBlogsBlockHelper($iBlockID);
        return $container;
    }

    // homepage photos block
    function serviceHomepagePhotosBlock($iBlockID){
        $container = $this->getHelper()->homepagePhotosBlockHelper($iBlockID);
        return $container;
    }

    // homepage videos block
    function serviceHomepageVideosBlock($iBlockID){
        $container = $this->getHelper()->homepageVideosBlockHelper($iBlockID);
        return $container;
    }

    // homepage sounds block
    function serviceHomepageSoundsBlock($iBlockID){
        $container = $this->getHelper()->homepageSoundsBlockHelper($iBlockID);
        return $container;
    }

    // homepage files block
    function serviceHomepageFilesBlock($iBlockID){
        $container = $this->getHelper()->homepageFilesBlockHelper($iBlockID);
        return $container;
    }
    // EOF THE HOMEPAGE SERVICE BLOCKS 

    // PROFILE SERVICE BLOCKS
    // events
    // user events
    function serviceProfileOwnEvents($iBlockID, $profileID){
        return $this->getHelper()->profileOwnEventsBlockHelper($iBlockID, $profileID);
    }

    //profile joined events
    function serviceProfileJoinedEvents($iBlockID, $profileID){
        return $this->getHelper()->profileJoinedEventsBlockHelper($iBlockID, $profileID);
    }
    // eof the events

    // groups
    // user groups
    function serviceProfileOwnGroups($iBlockID, $profileID){
        return $this->getHelper()->profileOwnGroupsBlockHelper($iBlockID, $profileID);
    }

    // joined groups
    function serviceProfileJoinedGroups($iBlockID, $profileID){
        return $this->getHelper()->profileJoinedGroupsBlockHelper($iBlockID, $profileID);
    }
    // eof the groups

    // sites
    function serviceProfileOwnSites($iBlockID, $profileID){
        return $this->getHelper()->profileOwnSitesBlockHelper($iBlockID, $profileID);
    }

    // blogs
    function serviceProfileOwnBlogs($iBlockID, $profileID){
        return $this->getHelper()->profileOwnBlogsBlockHelper($iBlockID, $profileID);
    }

    // photos
    function serviceProfileOwnPhotos($iBlockID, $profileID){
        return $this->getHelper()->profileOwnPhotosBlockHelper($iBlockID, $profileID);
    }

    // videos
    function serviceProfileOwnVideos($iBlockID, $profileID){
        return $this->getHelper()->profileOwnVideosBlockHelper($iBlockID, $profileID);
    }

    // sounds
    function serviceProfileOwnSounds($iBlockID, $profileID){
        return $this->getHelper()->profileOwnSoundsBlockHelper($iBlockID, $profileID);
    }
    // EOF THE PROFILE SERVICE BLOCKS

    // EVENTS SERVICE BLOCKS
    // bof the main events page
    //upcoming events
    function serviceMainUpcomingEvents($iBlockID){
        return $this->getHelper()->mainEventsUpcoming($iBlockID);
    }

    // past events
    function serviceMainPastEvents($iBlockID){
        return $this->getHelper()->mainEventsPast($iBlockID);
    }

    // recent events
    function serviceMainRecentEvents($iBlockID){
        return $this->getHelper()->mainEventsRecent($iBlockID);
    }
    // oef the main events page
    // EOF THE THE EVENTS SERVICE BLOCKS

    // GROUPS SERVICE BLOCKS
    // bof the main groups page
    function serviceMainRecentGroups($iBlockID){
        return $this->getHelper()->mainRecentGroups($iBlockID);
    }
    // EOF THE GROUPS SERVICE BLOCKS

    // SITES SERVICE BLOCKS
    // BOF the sites main page blocks
    // main featured sites
    function serviceMainFeaturedSites($iBlockID){
        return $this->getHelper()->mainFeaturedSites($iBlockID);
    }

    // main recent sites
    function serviceMainRecentSites($iBlockID){
        return $this->getHelper()->mainRecentSites($iBlockID);
    }
    // EOF the sites main page blocks
    // EOF THE SITES SERVICE BLOCKS

    // BLOGS SERVICE BLOCKS
    // bof the blogs main page
    function serviceLatestBlogPost($iBlockID){
        return $this->getHelper()->latestBlogPost($iBlockID);
    } 
    // EOF THE BLOGS SERVICE BLOCKS 

    // PHOTOS SERVICE BLOCKS
    // bof the main photos page service blocks
    function serviceMainPublicPhotos($iBlockID){
        return $this->getHelper()->mainPublicPhotos($iBlockID);
    }

    function serviceMainFavoritePhotos($iBlockID){
        return $this->getHelper()->mainFavoritePhotos($iBlockID, $this->_iProfileId);
    }

    function serviceMainFeaturedPhotos($iBlockID){
        return $this->getHelper()->mainFeaturedPhotos($iBlockID);
    }
    // eof the main photos page service blocks

    // VIDEOS SERVICE BLOCKS
    // main page videos service blocks
    function serviceMainPublicVideos($iBlockID){
        return $this->getHelper()->mainPublicVideos($iBlockID);
    }

    function serviceMainFavoriteVideos($iBlockID){
        return $this->getHelper()->mainFavoriteVideos($iBlockID, $this->_iProfileId);
    }

    function serviceMainFeaturedVideos($iBlockID){
        return $this->getHelper()->mainFeaturedVideos($iBlockID);
    }
    // eof the main page videos service blocks

    // SOUNDS SERVICE BLOCKS
    // main page sounds service blocks
    function serviceMainPublicSounds($iBlockID){
        return $this->getHelper()->mainPublicSounds($iBlockID);
    }

    function serviceMainFavoriteSounds($iBlockID){
        return $this->getHelper()->mainFavoriteSounds($iBlockID, $this->_iProfileId);
    }

    function serviceMainFeaturedSounds($iBlockID){
        return $this->getHelper()->mainFeaturedSounds($iBlockID);
    }
    // EOF THE SOUNSD SERVICE BLOCKS

    // FILES SERVICE BLOCKS
    // main page files service blocks
    function serviceMainPublicFiles($iBlockID){
        return $this->getHelper()->mainPublicFiles($iBlockID);
    }

    function serviceMainTopFiles($iBlockID){
        return $this->getHelper()->mainTopFiles($iBlockID);
    }

    function serviceMainFavoriteFiles($iBlockID){
        return $this->getHelper()->mainFavoriteFiles($iBlockID, $this->_iProfileId);
    }

    function serviceMainFeaturedFiles($iBlockID){
        return $this->getHelper()->mainFeaturedFiles($iBlockID);
    }
    // EOF THE FILES SERVICE BLOCKS

    // GETTERS
    function getHelper(){
        if(!isset($GLOBALS['oEBBonConInRealtimeHelper'])){
            emmetbytes_bon_con_in_realtime_import('Helper', $this->_aModule);
            $helperObjContainers = new EmmetBytesBonConInRealtimeHelper($this); 
            $GLOBALS['oEBBonConInRealtimeHelper'] = $helperObjContainers->helperObj;
        }
        return $GLOBALS['oEBBonConInRealtimeHelper'];
    }


    function getAdministrationHelper(){
        if(!isset($GLOBALS['oEBBonConInRealtimeAdministrationHelper'])){
            emmetbytes_bon_con_in_realtime_import('AdministrationHelper', $this->_aModule);
            $helperObjContainers = new EmmetBytesBonConInRealtimeAdministrationsHelper($this); 
            $GLOBALS['oEBBonConInRealtimeAdministrationHelper'] = $helperObjContainers->helperObj;
        }
        return $GLOBALS['oEBBonConInRealtimeAdministrationHelper'];
    }
    //EOF THE GETTERS
}

?>
