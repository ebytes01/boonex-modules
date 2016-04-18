<?php
/**********************************************************************************************
 * Created By : EmmetBytes Software Solutions
 * Created Date : January 10, 2013
 * Email : emmetbytes@gmail.com
 *
 * Copyright : (c) EmmetBytes Software Solutions 2012
 * Product Name : Boonex Contents Slider
 * Product Version : 1.0
 * 
 * Important : This is a commercial product by EmmetBytes Software Solutions and 
 *   cannot be modified, redistributed or resold without any written permission 
 *   from EmmetBytes Software Solutions
 **********************************************************************************************/

function emmetbytes_boonex_contents_slider_import ($sClassPostfix, $aModuleOverwright = array()) {
    global $aModule;
    $a = $aModuleOverwright ? $aModuleOverwright : $aModule;
    if (!$a || $a['uri'] != 'ebBoonexContentsSlider') {
        $oMain = BxDolModule::getInstance('EmmetBytesBoonexContentsSliderModule');
        $a = $oMain->_aModule;
    }
    bx_import ($sClassPostfix, $a);
}

// bx_imports
bx_import('BxDolTwigModule');

class EmmetBytesBoonexContentsSliderModule extends BxDolTwigModule {
    // attributes
    var $ebHelper, $ebAdminHelper;

    // CONSTRUCTOR
    function EmmetBytesBoonexContentsSliderModule(&$aModule) {
        parent::BxDolTwigModule($aModule);
        $this->_sFilterName = 'emmet_bytes_boonex_contents_slider_filter';
        $this->_sPrefix = 'emmet_bytes_boonex_contents_slider';
    }
    
    // ACTIONS
    // gets the information block main image
    function actionGetInfoImage($imageID, $width, $height){
        $this->getHelper()->getImage($imageID, $width, $height);
    }

    // gets the blogs information image
    function actionGetInfoBlogImage($imageName, $width, $height){
        $this->getHelper()->getBlogImage($imageName, $width, $height);
    }

    // gets the file information image
    function actionGetInfoFileImage($imageName, $width, $height){
        $this->getHelper()->getFileImage($imageName, $width, $height);
    }

    // gets the file information image two
    function actionGetInfoFileImageTwo($imageName, $width, $height){
        $this->getHelper()->getFileImage($imageName, $width, $height);
    }

    // gets the information block container
    function actionGetInformationContainer(){
        echo $this->getHelper()->getInformationContainerResponse();        
    }

    // gets the next batch of entries
    function actionGetMoreEntries(){
        echo $this->getHelper()->getMoreEntriesResponse();
    }
    // EOF THE ACTIONS
    
    // ADMINISTRATION ACTIONS
    // main administration page
    function actionAdministration ($sUrl = '') {

        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }        
        $this->_oTemplate->pageStart();
        $aMenu = array(
            'homepage' => array(
                'title' => _t('emmetbytes_boonex_contents_slider_administration_homepage_settings'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/homepage',
                '_func' => array ('name' => 'actionAdministrationHomepageSettings', 'params' => array()),
            ),
            'profile' => array(
                'title' => _t('emmetbytes_boonex_contents_slider_administration_profile_settings'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/profile',
                '_func' => array ('name' => 'actionAdministrationProfileSettings', 'params' => array()),
            ),
            'module_pages' => array(
                'title' => _t('emmetbytes_boonex_contents_slider_administration_module_pages_settings'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/module_pages',
                '_func' => array ('name' => 'actionAdministrationModulePagesSettings', 'params' => array()),
            ),
        );

        if (empty($aMenu[$sUrl])){ $sUrl = 'homepage'; }

        $aMenu[$sUrl]['active'] = 1;
        $sContent = call_user_func_array(array($this, $aMenu[$sUrl]['_func']['name']), $aMenu[$sUrl]['_func']['params']);
        echo $this->_oTemplate->adminBlock ($sContent, _t('emmetbytes_boonex_contents_slider_administration'), $aMenu);
        $this->_oTemplate->addCssAdmin ('admin.css');
        $this->_oTemplate->addCssAdmin ('unit.css');
        $this->_oTemplate->addCssAdmin ('main.css');
        $this->_oTemplate->addCssAdmin ('forms_extra.css'); 
        $this->_oTemplate->addCssAdmin ('forms_adv.css');        
        $this->_oTemplate->pageCodeAdmin (_t('emmetbytes_boonex_contents_slider_administration'));
    }

    // administration settings
    function actionAdministrationSettings () {
        return parent::_actionAdministrationSettings ('BoonexContentsSlider');
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
    // EOF THE ADMINISTRATION ACTIONS

    // HOMEPAGE SERVICE BLOCKS
    // homepage events block
    function serviceHomepageEventsBlock($iBlockID){
        return $this->getHelper()->homepageEventsBlockHelper($iBlockID);
    }

    // homepage groups block
    function serviceHomepageGroupsBlock($iBlockID){
        return $this->getHelper()->homepageGroupsBlockHelper($iBlockID);
    }

    // homepage sites block
    function serviceHomepageSitesBlock($iBlockID){
        return $this->getHelper()->homepageSitesBlockHelper($iBlockID);
    }

    // homepage blogs block
    function serviceHomepageBlogsBlock($iBlockID){
        return $this->getHelper()->homepageBlogsBlockHelper($iBlockID);
    }

    // homepage photos block
    function serviceHomepagePhotosBlock($iBlockID){
        return $this->getHelper()->homepagePhotosBlockHelper($iBlockID);
    }

    // homepage videos block
    function serviceHomepageVideosBlock($iBlockID){
        return $this->getHelper()->homepageVideosBlockHelper($iBlockID);
    }

    // homepage sounds block
    function serviceHomepageSoundsBlock($iBlockID){
        return $this->getHelper()->homepageSoundsBlockHelper($iBlockID);
    }

    // homepage files block
    function serviceHomepageFilesBlock($iBlockID){
        return $this->getHelper()->homepageFilesBlockHelper($iBlockID);
    }

    // homepage ads block
    function serviceHomepageAdsBlock($iBlockID){
        return $this->getHelper()->homepageAdsBlockHelper($iBlockID);
    }
    // EOF THE HOMEPAGE SERVICE BLOCKS 

    // PROFILE SERVICE BLOCKS
    // events
    // user events
    function serviceProfileOwnEvents($iBlockID, $profileID){
        return $this->getHelper()->profileOwnEventsBlockHelper($iBlockID, $profileID);
    }

    // profile joined events
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

    // ads
    function serviceProfileOwnAds($iBlockID, $profileID){
        return $this->getHelper()->profileOwnAdsBlockHelper($iBlockID, $profileID);
    }
    // EOF THE PROFILE SERVICE BLOCKS

    // EVENTS SERVICE BLOCKS
    // bof the main events page
    // upcoming events
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

    // bof the my events page
    function serviceMyEvents($iBlockID){
        return $this->getHelper()->myEvents($iBlockID, $this->_iProfileId);
    }
    // EOF THE THE EVENTS SERVICE BLOCKS

    // GROUPS SERVICE BLOCKS
    // bof the main groups page
    function serviceMainRecentGroups($iBlockID){
        return $this->getHelper()->mainRecentGroups($iBlockID);
    }

    // bof the my groups page
    function serviceMyGroups($iBlockID){
        return $this->getHelper()->myGroups($iBlockID, $this->_iProfileId);
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

    // BOF the sites my page blocks
    function serviceUsersSites($iBlockID){
        return $this->getHelper()->usersOwnSites($iBlockID, $this->_iProfileId);
    }
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
    // EOF THE PHOTOS SERVICE BLOCKS

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
    // EOF THE VIDEOS SERVICE BLOCKS

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

    // ADS SERVICE BLOCKS
    function serviceMainLastAds($iBlockID){
        return $this->getHelper()->mainLastAds($iBlockID);
    }
    // EOF THE ADS SERVICE BLOCKS

    // GETTERS
    function getHelper(){
        if(!isset($GLOBALS['oEBBoonexContentsSliderHelper'])){
            emmetbytes_boonex_contents_slider_import('Helper', $this->_aModule);
            $helperObjContainers = new EmmetBytesBoonexContentsSliderHelper($this); 
            $GLOBALS['oEBBoonexContentsSliderHelper'] = $helperObjContainers->helperObj;
        }
        return $GLOBALS['oEBBoonexContentsSliderHelper'];
    }


    function getAdministrationHelper(){
        if(!isset($GLOBALS['oEBBoonexContentsSliderAdministrationHelper'])){
            emmetbytes_boonex_contents_slider_import('AdministrationHelper', $this->_aModule);
            $adminHelperObjContainer = new EmmetBytesBoonexContentsSliderAdministrationsHelper($this); 
            $GLOBALS['oEBBoonexContentsSliderAdministrationHelper'] = $adminHelperObjContainer->helperObj;
        }
        return $GLOBALS['oEBBoonexContentsSliderAdministrationHelper'];
    }
    // EOF THE GETTERS

    // remove me later
    private function startProfiling(){
        $memUsage = memory_get_usage();
        $starttime = microtime(); // remove me later, start profiling
        $startarray = explode(" ", $starttime); // remove me later, start profiling
        $starttime = $startarray[1] + $startarray[0]; // remove me later, start profiling
        return array('memUsage' => $memUsage, 'startTime' => $starttime,);
    }


    private function endProfiling($name, $starttime, $startmem){
        $memusage = memory_get_usage() - $startmem;
        $endtime = microtime(); // remove me later, end profiling
        $endarray = explode(" ", $endtime); // remove me later, end profiling
        $endtime = $endarray[1] + $endarray[0]; // remove me later, end profiling
        $totaltime = $endtime - $starttime; // remove me later, end profiling
        $totaltime = round($totaltime,5); // remove me later, end profiling
        $filename = '/var/log/module_log/overall.log';
        if(file_exists($filename)){
           $fh = fopen($filename, 'a'); 
        }else{
           $fh = fopen($filename, 'w'); 
        }
        $mem_usage_mb =  $memusage / 1048576;
        $text = $name . ' - MEMORY USAGE : ' . $memusage . ' bytes,  ' . $mem_usage_mb . ' mb, TOTAL TIME : ' . $totaltime . "\n";
        fwrite($fh, $text);
        fclose($fh);
    }
}

?>
