<?php
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

function emmetbytes_profile_cvr_import ($sClassPostfix, $aModuleOverwright = array()) {
    global $aModule;
    $a = $aModuleOverwright ? $aModuleOverwright : $aModule;
    if (!$a || $a['uri'] != 'profileCvr') {
        $oMain = BxDolModule::getInstance('EmmetBytesProfileCvrModule');
        $a = $oMain->_aModule;
    }
    bx_import ($sClassPostfix, $a);
}

bx_import('BxDolTwigModule');
bx_import('BxDolPaginate');
bx_import('BxDolAlerts');

class EmmetBytesProfileCvrModule extends BxDolTwigModule {
    var $_iProfileId, $oHelper;

    // constructor
    function EmmetBytesProfileCvrModule(&$aModule) {
        parent::BxDolTwigModule($aModule);
        $this->_sFilterName = 'emmet_bytes_profile_cvr_filter';
        $this->_sPrefix = 'emmet_bytes_profile_cvr';
        $GLOBALS['oEmmetBytesProfileCvrModule'] = &$this;
    }

    // BOF THE ACTIONS
    // action home
    function actionHome () {
        Redirect(BX_DOL_URL_ROOT);
    }

    // submitting the temporary profile cvr background
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

    // remove the profile cvr background
    function actionRemoveImage(){
        $this->getHelper()->removeProfileCvrBackground();
    }

    // getting the profile cvr background menu
    function actionGetProfileCvrBackgroundMenuOptions(){
       $this->getHelper()->getProfileCvrBackgroundMenuOptions();
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
                'title' => _t('_emmet_bytes_profile_cvr_administration_settings'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/settings',
                '_func' => array ('name' => 'actionAdministrationSettings', 'params' => array()),
            ),
        );

        if (empty($aMenu[$sUrl]))
            $sUrl = 'settings';

        $aMenu[$sUrl]['active'] = 1;
        $sContent = call_user_func_array (array($this, $aMenu[$sUrl]['_func']['name']), $aMenu[$sUrl]['_func']['params']);

        echo $this->_oTemplate->adminBlock ($sContent, _t('_emmet_bytes_profile_cvr_administration'), $aMenu);
        $this->_oTemplate->addCssAdmin ('admin.css');
        $this->_oTemplate->addCssAdmin ('unit.css');
        $this->_oTemplate->addCssAdmin ('main.css');
        $this->_oTemplate->addCssAdmin ('forms_extra.css'); 
        $this->_oTemplate->addCssAdmin ('forms_adv.css');        
        $this->_oTemplate->pageCodeAdmin (_t('_emmet_bytes_profile_cvr_administration'));
    }

    // administration main settings
    function actionAdministrationSettings () {
        return parent::_actionAdministrationSettings ('ProfileCvr');
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
    // getting the profile cvr
    function serviceGetProfileCvr(){
        if(getID($_GET['ID'])){
            return $this->getHelper()->getProfileCvr();
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
            emmetbytes_profile_cvr_import('Helper', $this->_aModule);
            $GLOBALS['oEBProfileCvrHelper'] = new EmmetBytesProfileCvrHelper($this); 
        }
        // $GLOBALS['oEBProfileCvrHelper']->profileCvrImageDir = BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cvr/images/profile_covers/';
        $GLOBALS['oEBProfileCoverHelper']->profileCoverImageDir = '/media/now/datas/Projects/web/my_works/boonex_projects/boonex_modules/emmetbytes_profile_cvr/images/profile_covers/';
        return $GLOBALS['oEBProfileCvrHelper'];
    }
    // EOF THE GETTERS
}

?>
