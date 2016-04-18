<?php
/**********************************************************************************************
 * Created By : EmmetBytes Software Solutions
 * Created Date : February 20, 2013
 * Email : emmetbytes@gmail.com
 *
 * Copyright : (c) EmmetBytes Software Solutions 2012
 * Product Name : Group Cover
 * Product Version : 1.1
 * 
 * Important : This is a commercial product by EmmetBytes Software Solutions and 
 *   cannot be modified, redistributed or resold without any written permission 
 *   from EmmetBytes Software Solutions
 **********************************************************************************************/

function emmetbytes_group_cover_import ($sClassPostfix, $aModuleOverwright = array()) {
    global $aModule;
    $a = $aModuleOverwright ? $aModuleOverwright : $aModule;
    if (!$a || $a['uri'] != 'groupCover') {
        $oMain = BxDolModule::getInstance('EmmetBytesGroupCoverModule');
        $a = $oMain->_aModule;
    }
    bx_import ($sClassPostfix, $a);
}

bx_import('BxDolTwigModule');
bx_import('BxDolPaginate');
bx_import('BxDolAlerts');

class EmmetBytesGroupCoverModule extends BxDolTwigModule {
    var $_iProfileId, $oHelper;

    // constructor
    function EmmetBytesGroupCoverModule(&$aModule) {
        parent::BxDolTwigModule($aModule);
        $this->_sFilterName = 'emmet_bytes_group_cover_filter';
        $this->_sPrefix = 'emmet_bytes_group_cover';
        $GLOBALS['oEmmetBytesGroupCoverModule'] = &$this;
    }

    // BOF THE ACTIONS
    // action home
    function actionHome () {
        Redirect(BX_DOL_URL_ROOT);
    }

    // submitting the temporary group cover background
    function actionSubmitTmpBackground(){
        $this->getHelper()->submitTmpBackground($_FILES);
    }

    // submitting the background image
    function actionSubmitBackgroundImage(){
        $this->getHelper()->submitBackgroundImage($_POST);
    }

    // reposition the image
    function actionRepositionImage(){
        $this->getHelper()->repositionBackground();
    }

    // remove the group cover background
    function actionRemoveImage(){
        $this->getHelper()->removeGroupCoverBackground();
    }

    // getting the group cover background menu
    function actionGetGroupCoverBackgroundMenuOptions(){
        $groupId = $_POST['group_id'];
        $this->getHelper()->getGroupCoverBackgroundMenuOptions($groupId);
    }

    // submitting the temporary avatar
    function actionSubmitTmpAvatar(){
        $this->getHelper()->submitTmpAvatar($_FILES);
    }

    // submitting the avatar image
    function actionSubmitAvatarImage(){
        $this->getHelper()->submitAvatarImage($_POST);
    }

    // submitting the friend request
    function actionFriendRequest(){
        $friendId = $_POST['profile_id'];
        $action = $_POST['action'];
        $this->getHelper()->friendRequest($friendId, $action);
    }

    // action get headline popup
    function actionGetHeadlinePopup(){
        echo $this->getHelper()->getHeadlinePopup();
    }

    // action get location popup
    function actionGetLocationPopup(){
        echo $this->getHelper()->getLocationPopup();
    }

    // action get birthdate popup
    function actionGetBirthdatePopup(){
        echo $this->getHelper()->getBirthdatePopup();
    }

    // action get gender popup
    function actionGetGenderPopup(){
        echo $this->getHelper()->getGenderPopup();
    }

    // action get relationship popup
    function actionGetRelationshipPopup(){
        echo $this->getHelper()->getRelationshipPopup();
    }   

    // administration action
    function actionAdministration ($sUrl = '') {

        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }        

        $this->_oTemplate->pageStart();

        $aMenu = array(
            'settings' => array(
                'title' => _t('_emmet_bytes_group_cover_administration_settings'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/settings',
                '_func' => array ('name' => 'actionAdministrationSettings', 'params' => array()),
            ),
        );

        if (empty($aMenu[$sUrl]))
            $sUrl = 'settings';

        $aMenu[$sUrl]['active'] = 1;
        $sContent = call_user_func_array (array($this, $aMenu[$sUrl]['_func']['name']), $aMenu[$sUrl]['_func']['params']);

        echo $this->_oTemplate->adminBlock ($sContent, _t('_emmet_bytes_group_cover_administration'), $aMenu);
        $this->_oTemplate->addCssAdmin ('admin.css');
        $this->_oTemplate->addCssAdmin ('unit.css');
        $this->_oTemplate->addCssAdmin ('main.css');
        $this->_oTemplate->addCssAdmin ('forms_extra.css'); 
        $this->_oTemplate->addCssAdmin ('forms_adv.css');        
        $this->_oTemplate->pageCodeAdmin (_t('_emmet_bytes_group_cover_administration'));
    }

    // administration main settings
    function actionAdministrationSettings () {
        return parent::_actionAdministrationSettings ('GroupCover');
    }

    // getting the own images
    function actionGetOwnImage($path){
        return $this->getHelper()->getOwnImage($path);
    }

    // getting the popup datas
    function actionGetFriendPopupDatas(){
        $profileId = $_POST['profile_id'];
        $this->getHelper()->getFriendPopupDatas($profileId);
    }
    // EOF THE ACTIONS

    // BOF THE SERVICES
    // getting the group cover
    function serviceGetGroupCover($iBlockID, $sContent){
        if(defined('BX_GROUPS_VIEW_PAGE') && isset($_GET['r']) && !empty($_GET['r'])){
            $request = explode('/', $_GET['r']);
            $entryName = $request[sizeof($request) - 1];
            return $this->getHelper()->getGroupCover($entryName);
        }
    }

    // setting up the spy data
    function serviceGetSpyData(){
        return $this->getHelper()->getSpyData();
    }

    // setting up the spy post
    function serviceGetSpyPost($sAction, $iObjectId, $iSenderId, $aExtraParams){
        return $this->getHelper()->getSpyPost($sAction, $iObjectId, $iSenderId, $aExtraParams);
    }

    // setting up the wall data
    function serviceGetWallData(){
        return $this->getHelper()->getWallData();
    }

    // setting up the wall post
    function serviceGetWallPost($aEvent){
        return $this->getHelper()->getWallPost($aEvent);
    } 
    // EOF THE SERVICES

    // BOF THE GETTERS
    // getting the helper object
    function getHelper(){
        if(!isset($GLOBALS['oEBModuleInfoBlocksHelper'])){
            emmetbytes_group_cover_import('Helper', $this->_aModule);
            $helper = new EmmetBytesGroupCoverHelper($this); 
            $GLOBALS['oEBGroupCoverHelper'] = $helper->helperObject;
        }
        // $GLOBALS['oEBGroupCoverHelper']->groupCoverImageDir = BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_group_cover/images/group_covers/';
        $GLOBALS['oEBGroupCoverHelper']->groupCoverImageDir = '/media/now/datas/projects/EmmetBytes/boonex_projects/boonex_modules/emmetbytes_group_cover/images/group_covers/';
        return $GLOBALS['oEBGroupCoverHelper'];
    }
    // EOF THE GETTERS
}

?>
