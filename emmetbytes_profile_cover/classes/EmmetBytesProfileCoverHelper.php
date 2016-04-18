<?php
/**********************************************************************************************
 * Created By : EmmetBytes Software Solutions
 * Created Date : February 20, 2013
 * Email : emmetbytes@gmail.com
 *
 * Copyright : (c) EmmetBytes Software Solutions 2012
 * Product Name : Profile Cover
 * Product Version : 1.1
 * 
 * Important : This is a commercial product by EmmetBytes Software Solutions and 
 *   cannot be modified, redistributed or resold without any written permission 
 *   from EmmetBytes Software Solutions
 **********************************************************************************************/
    
bx_import('BxDolProfilesController');
bx_import('BxDolProfileFields');
bx_import('BxDolPageView');
class EmmetBytesProfileCoverHelper{
    var $boonexVersion;
    var $helperObject;

    // constructor
    function EmmetBytesProfileCoverHelper(&$oMain){
        $this->oDb = $oMain->_oDb;
        $this->boonexVersion = $GLOBALS['ebModuleBoonexVersion'] = (isset($GLOBALS['ebModuleBoonexVersion'] )) ? $GLOBALS['ebModuleBoonexVersion'] : $this->oDb->oParams->_aParams['sys_tmp_version']; 
        if($this->boonexVersion < 7.1){
            $this->helperObject = new EmmetBytesProfileCoverDefaultHelper($oMain);
        }else if($this->boonexVersion < "7.1.1"){
            $this->helperObject = new EmmetBytesProfileCover710UpHelper($oMain);
        }else{
            $this->helperObject = new EmmetBytesProfileCover711UpHelper($oMain);
        }
    }
}

class EmmetBytesProfileCoverDefaultHelper{
    var $loggedId;

    // constructor
    function EmmetBytesProfileCoverDefaultHelper(&$oMain){
        global $logged;
        // getting the aArea
        if($logged['member']){
            $this->aArea = 2;
        }else if($logged['admin']){
            $this->aArea = 3;
        }else{
            $this->aArea = 4;
        }
        $this->loggedId = getLoggedId();
        $this->thumbnailWidth = 166;
        $this->thumbnailHeight = 166;
        $this->compressionLevel = 75;
        $this->thumbnailFileSize = getParam('emmet_bytes_profile_cover_avatar_size');
        $pageView = new BxDolPageView('profile');
        $this->backgroundWidth = $pageView->aPage['Width'];
        $this->backgroundHeight = 285;
        $this->backgroundFileSize = getParam('emmet_bytes_profile_cover_background_size');
        $this->profileCoverImageUrl = BX_DOL_URL_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cover/images/profile_covers/';
        $this->oMain = $oMain;
        $this->oDb = $oMain->_oDb;
        $this->oTemplate = $oMain->_oTemplate;
        $this->oConfig = $oMain->_oConfig;
        $this->oPC = new BxDolProfilesController();
        $this->oPF = new BxDolProfileFields($this->aArea);
    }

    // BOF THE METHODS FOR THE PROFILE COVER ACTIONS
    // submitting the temporary background
    function submitTmpBackground($formParams){
        $returnVals = array();
        $this->compressionLevel = (int)getParam('emmet_bytes_profile_cover_profile_cover_background_compr_level');
        $ext = explode('.', $formParams['background_image']['name']);
        $ext = $ext[sizeof($ext) - 1];
        $imageName = $this->loggedId . '_' . time() . '.' . $ext;
        $checkBackgroundFilters = $this->checkBackgroundImage($formParams);
        $hasError = $checkBackgroundFilters['has_error'];
        $returnVals['error'] = $checkBackgroundFilters['error'];
        $profileCoverImgDir = $this->profileCoverImageDir . $imageName;
        if(!$hasError && move_uploaded_file($formParams['background_image']['tmp_name'], $profileCoverImgDir)){
            chmod($profileCoverImgDir, 0777);
            $resized = $this->imageResize($profileCoverImgDir, $this->backgroundWidth, $this->backgroundHeight);
            if($resized){
                $imagePath = $this->getProfileCoverOwnImages($profileCoverImgDir);
                $returnVals = array(
                    'error' => false,
                    'image_path' => $imagePath,
                    'image_name' => $imageName
                );
            }else{
                $returnVals['error'] = _t('_emmetbytes_profile_cover_background_image_upload_problem');
            }
        }
        echo json_encode($returnVals);
        exit;
    }

    // checking for the background image filters
    protected function checkBackgroundImage($formParams){
        $returnVals = array('has_error' => false, 'error' => '',);
        $fileSize = $formParams['background_image']['size'];
        list($width, $height) = getimagesize($formParams['background_image']['tmp_name']);
        if($width < $this->backgroundWidth || $height < $this->backgroundHeight){
            $returnVals['has_error'] = true;
            $returnVals['error'] = _t('_emmetbytes_profile_cover_background_image_size_problem', $this->backgroundWidth, $this->backgroundHeight);
        }else if($this->backgroundFileSize < $fileSize){
            $returnVals['has_error'] = true;
            $returnVals['error'] =  _t('_emmetbytes_profile_cover_background_image_file_size_problem', $this->backgroundFileSize);
        }
        return $returnVals;
    }

    // submitting the images
    function submitBackgroundImage($_POST){
        if($_POST['submit'] == 'Save'){ // save the background image datas
            $retVal = $this->saveProfileCoverBgImgDatas($_POST);
        }else{ // cancel the background image
            $retVal = $this->cancelProfileCoverBgImgDatas($_POST);
        }
        echo json_encode($retVal);
    }

    // reposition the background
    function repositionBackground(){
        $profileCoverDatas = $this->getProfileCoverDatas($this->loggedId);    
        echo json_encode($profileCoverDatas);
        exit;
    }

    // remove the background
    function removeProfileCoverBackground(){
        $profileId = $this->loggedId;
        $profileCoverImgDatas = $this->getProfileCoverDatas($profileId);
        $origImagePath = $profileCoverImgDatas['bg_image_path'];
        $croppedImagePath = $profileCoverImgDatas['bg_image_cropped_path'];
        // remove the original image
        $this->removeImage($origImagePath);
        // remove the cropped image path
        $this->removeImage($croppedImagePath);
        // alert parameters
        $alertParams = array(
            'action' => 'remove_background',
            'entry_id' => $profileCoverImgDatas['id'],
            'profile_id' => $profileId,
            'status' => 'approved',
        );
        $this->alert($alertParams);
        // remove the database data
        $removed = $this->oDb->removeBackgroundProfileCover($profileCoverImgDatas['id']);
    }

    // getting the profile cover background menu options
    function getProfileCoverBackgroundMenuOptions(){
        $profileId = $this->loggedId;
        $profileCoverDatas = $this->getProfileCoverDatas($profileId);
        echo $this->getInsertBackgroundOptionsContents($profileCoverDatas);
    }

    // submitting the temporary avatar
    function submitTmpAvatar($formParams){
        $returnVals = array();
        $ext = explode('.', $formParams['avatar_image']['name']);
        $ext = $ext[sizeof($ext) - 1];
        $imageName = 'avatar_' . $this->loggedId . '_' . time() . '.' . $ext;
        $checkBackgroundFilters = $this->checkAvatarImage($formParams);
        $hasError = $checkBackgroundFilters['has_error'];
        $returnVals['error'] = $checkBackgroundFilters['error'];
        $profileCoverImgDir = $this->profileCoverImageDir . $imageName;
        $profileCoverImgOrigDir = $this->profileCoverImageDir . 'orig_' . $imageName;
        if(!$hasError && move_uploaded_file($formParams['avatar_image']['tmp_name'], $profileCoverImgDir)){
            chmod($profileCoverImgDir, 0777);
            $profileCoverImgOrigDir = $this->profileCoverImageDir . 'orig_' . $imageName;
            copy($profileCoverImgDir, $profileCoverImgOrigDir);
            $resized = $this->imageResize($profileCoverImgDir, $this->thumbnailWidth, $this->thumbnailHeight);
            if($resized){
                $imagePath = $this->getProfileCoverOwnImages($profileCoverImgDir);
                $returnVals = array(
                    'error' => false,
                    'image_path' => $imagePath,
                    'image_name' => $imageName,
                );
            }
        }
        echo json_encode($returnVals);
        exit;
    }

    // method that checks the avatar image
    protected function checkAvatarImage($formParams){
        $returnVals = array('has_error' => false, 'error' => '',);
        $fileSize = $formParams['avatar_image']['size'];
        list($width, $height) = getimagesize($formParams['avatar_image']['tmp_name']);
        if($width < $this->thumbnailWidth || $height < $this->thumbnailHeight){
            $returnVals['has_error'] = true;
            $returnVals['error'] = _t('_emmetbytes_profile_cover_background_image_size_problem', $this->thumbnailWidth, $this->thumbnailHeight);
        }else if($this->thumbnailFileSize < $fileSize){
            $returnVals['has_error'] = true;
            $returnVals['error'] =  _t('_emmetbytes_profile_cover_background_image_file_size_problem', $this->thumbnailFileSize);
        }
        return $returnVals;

    }

    // submitting the avatar image
    function submitAvatarImage($formData){
        $this->insertAvatar($formData);
    }

    // submitting the friend request
    function friendRequest($friendId, $action){
        $memberButtons = $this->getMemberButtonsLangs();
        $class = '';
        switch($action){
            case 'add':
                $addFriend = $this->addFriend($friendId);
                $lang = $memberButtons['cancel_friend_request'];
                $class = 'ebytes_profile_cover_cancel_friend_request';
                $action = 'remove';
                break;
            case 'accept':
                $acceptFriend = $this->acceptFriendRequest($friendId);
                $lang = $memberButtons['remove_friend'];
                $class = 'ebytes_profile_cover_remove_friend';
                $action = 'remove';
                break;
            case 'remove':
                $this->removeFriend($friendId);
                $lang = $memberButtons['add_friend'];
                $class = 'ebytes_profile_cover_add_friend';
                $action = 'add';
                break;
        }
        $returnVals = array(
            'lang' => $lang,
            'action' => $action,
            'className' => $class,
        );
        echo json_encode($returnVals);
    }

    // removing the friend
    protected function removeFriend($friendId){
        $oCommunicator = $this->commonFriendRequestCommunicatorClass();
        $aParams = array($friendId);
        $execFunction = $oCommunicator->execFunction( 
            '_deleteRequest', 
            'sys_friend_list', 
            $aParams, 
            array(1, 1)
        );
    }

    // adding the friend
    protected function addFriend($iMemberId){
        $loggedId = $this->loggedId;
        $sQuery = "INSERT INTO `sys_friend_list` SET 
            `ID` = '{$loggedId}', `Profile` = '{$iMemberId}', `Check` = '0'";

        if ( db_res($sQuery, 0) ) {
            $alertParams = array(
                'unit' => 'friend',
                'action' => 'request',
                'object_id' => $iMemberId,
                'sender_id' => $loggedId,
            );
            $this->sendSystemAlerts($alertParams);
            $emailNotificationParams = array(
                'member_id' => $iMemberId,
                'logged_id' => $loggedId,
                'template_name' => 't_FriendRequest',
                'request_link' => BX_DOL_URL_ROOT  . 'communicator.php?communicator_mode=friends_requests',
            );
            $this->sendEmailNotification($emailNotificationParams);
            return true;
        } else {
            return false;
        }

    }

    // accepting the friend request
    protected function acceptFriendRequest($friendId){
        $oCommunicator = $this->commonFriendRequestCommunicatorClass();
        $aParams = array($friendId);
        $execFunction = $oCommunicator->execFunction( 
            '_acceptFriendInvite', 
            'sys_friend_list', 
            $aParams
        );
    }

    // getting the friends popup datas
    function getFriendPopupDatas($profileId){
        $nickName = getNickName($profileId);
        $profileCoverDatas = $this->oDb->getCoverDataByProfileId($profileId);
        $backgroundImage = '';
        if(sizeof($profileCoverDatas) && !empty($profileCoverDatas['background_image'])){
            $backgroundImage = $this->profileCoverImageDir . 'small_' . $profileCoverDatas['background_image'];
            $backgroundImageArray = $this->getProfileCoverOwnImages($backgroundImage);
            $backgroundImage = $backgroundImageArray[0];
        }
        $avatar = $GLOBALS['oFunctions']->getMemberAvatar($profileId);
        $profileDatas = array(
            'nickname' => $nickName,
            'avatar' => $avatar,
            'backgroundImage' => $backgroundImage,
            'link' => getProfileLink($profileId),
        );
        echo json_encode($profileDatas);
    }


    protected function sendSystemAlerts($alertParams){
        bx_import('BxDolAlerts');
        $oZ = new BxDolAlerts(
            $alertParams['unit'],
            $alertParams['action'],
            $alertParams['object_id'], 
            $alertParams['sender_id']
        );
        $oZ -> alert();
    }


    protected function sendEmailNotification($emailNotificationParams){
        $oEmailTemplate = new BxDolEmailTemplates();
        $aTemplate = $oEmailTemplate -> getTemplate($emailNotificationParams['template_name']);
        $aRecipient = getProfileInfo($emailNotificationParams['member_id']);
        $aPlus = array(
                'Recipient'     => $aRecipient['NickName'],
                'SenderLink'    => getProfileLink($emailNotificationParams['logged_id']),
                'Sender'        => getNickName($emailNotificationParams['logged_id']),
                'RequestLink'   => $emailNotificationParams['request_link'],
                );
        sendMail( $aRecipient['Email'], $aTemplate['Subject'], $aTemplate['Body'], '', $aPlus );
    }
    // EOF THE METHODS FOR THE PROFILE COVER ACTIONS

    // GETTING THE PROFILE HEADER
    function getProfileCover(){
        // displays on the profile page only
        if(!defined('BX_PROFILE_PAGE')){ return ''; }
        // the profile id 
        $profileId = getID($_GET['ID']);
        // logged id
        $loginId = $this->loggedId;
        // insert the css
        $this->oTemplate->addCss(array('main.css', 'form_adv.css'));
        // insert the javascript
        $this->oTemplate->addJs(array('jquery-ui.js', 'ui.draggable.js', 'EmmetBytesProfileCover.js', 'ui.datepicker.js'));
        $aVars = array(
            'profile_cover_top' => $this->getProfileCoverTopContents($profileId, $loginId),
            'background_menu_insert_caption' => _t('_emmetbytes_profile_cover_insert_background_caption'),
            'background_menu_change_caption' => _t('_emmetbytes_profile_cover_change_background_caption'),
            'profile_cover_bottom' => $this->getProfileCoverBottomContents($profileId, $loginId),
            'base_url' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri(),
            'background_form_url' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'submit_background_image',
            'background_menu_options_url' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'get_profile_cover_background_menu_options',
            'avatar_form_url' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'submit_avatar_image',
        );
        return $this->oTemplate->parseHTMLByName('main_ebytes_profile_cover_container', $aVars);
    }

    // BOF THE TOP CONTENTS METHODS
    // getting the profile cover top part
    protected function getProfileCoverTopContents($profileId, $loginId){
        $profileCoverDatas = $this->getProfileCoverDatas($profileId);
        $aVars = array(
            'extra_top_container_class' => (empty($profileCoverDatas['bg_image'])) ? 'ebytes_profile_cover_top_container_no_background' : '',
            'bx_if:allow_change_cover' => array(
                'condition' => $this->loggedId == $profileId ? true : false,
                'content' => array(
                    'insert_background_caption' => (sizeof($profileCoverDatas) && !empty($profileCoverDatas['bg_image'])) ? _t('_emmetbytes_profile_cover_change_background_caption') : _t('_emmetbytes_profile_cover_insert_background_caption'),
                    'insert_background_options' => $this->getInsertBackgroundOptionsContents($profileCoverDatas),
                ),
            ),
            'bx_if:show_bg_image' => array(
                'condition' => !empty($profileCoverDatas['bg_image']) ? true : false,
                'content' => array(
                    'bg_image_path' => $profileCoverDatas['bg_image_cropped'],
                ),
            ),
            'bx_if:allow_thumbnail_change' => array(
                'condition' => ($profileId == $loginId) ? true : false,
                'content' => array(
                    'thumbnail_button_caption' => _t('_emmetbytes_profile_cover_insert_avatar_caption'),
                    'form_avatar_action' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'submit_tmp_avatar',
                ),
            ),
            'member_thumbnail' => $profileCoverDatas['member_avatar'],
            'member_name' => $profileCoverDatas['member_name'],
            'member_buttons' => $this->getMemberButtons($profileId, $loginId),
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_top_container', $aVars);
    }

    // getting the profile cover datas
    protected function getProfileCoverDatas($profileId){
        $profileCoverDatas = $this->oDb->getCoverDataByProfileId($profileId);
        $dProfileCoverDatas = array(
            'id' => $profileCoverDatas['id'],
            'bg_image_name' => '',
            'bg_image' => '',
            'bg_image_cropped' => '',
            'bg_pos_y' => '', 
            'bg_pos_x' => '', 
            'thumbnail_image' => '', 
            'thumbnail_image_cropped' => '', 
            't_pos_y' => '', 
            't_pos_x' => '',
            'member_name' => $this->getMemberName($profileId),
        );
        // sets up the profile cover background datas
        if(!empty($profileCoverDatas['background_image'])){
            $backgroundImage = $profileCoverDatas['background_image'];
            $backgroundImagePath = $this->profileCoverImageDir . $backgroundImage;
            $backgroundImageUrl = $this->getProfileCoverOwnImages($backgroundImagePath);
            $backgroundImageCroppedPath = $this->profileCoverImageDir . 'crop_' . $backgroundImage;
            $backgroundImageCroppedUrl = $this->getProfileCoverOwnImages($backgroundImageCroppedPath);
            $dProfileCoverDatas['bg_image_name'] = $backgroundImage;
            $dProfileCoverDatas['bg_image'] = $backgroundImageUrl[0];
            $dProfileCoverDatas['bg_image_path'] = $backgroundImagePath;
            $dProfileCoverDatas['bg_image_cropped'] = $backgroundImageCroppedUrl[0] . '?'. time();
            $dProfileCoverDatas['bg_image_cropped_path'] = $backgroundImageCroppedPath;
            $dProfileCoverDatas['bg_pos_y'] = $profileCoverDatas['bg_pos_y'];
            $dProfileCoverDatas['bg_pos_x'] = $profileCoverDatas['bg_pos_x'];
        }
        // sets up the profile cover thumbnail datas
        $dProfileCoverDatas['member_avatar'] = $this->getMemberAvatar($profileId);
        return $dProfileCoverDatas;
    }

    // getting the insert background options
    protected function getInsertBackgroundOptionsContents($profileCoverDatas){
        $aVars = array(
            'bx_repeat:background_options' => array(
                $this->getUploadForm(), // form uploader
                $this->getRepositionForm($profileCoverDatas),// reposition button
                $this->getRemoveForm($profileCoverDatas),// remove button
            ),
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_insert_background_options', $aVars);
    }

    // getting the background uploader form
    protected function getUploadForm(){
        $aVars = array(
            'caption' => _t('_emmetbytes_profile_cover_upload_background_caption'),
            'class' => 'ebytes_profile_cover_upload_background_container',
            'form_action' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'submit_tmp_background',
        );
        return array(
            'content' => $this->oTemplate->parseHTMLByName('ebytes_profile_cover_background_uploader_container', $aVars),
        );
    }

    // getting the reposition form
    protected function getRepositionForm($profileCoverDatas){
        $content = array( 'content' => '');
        if(sizeof($profileCoverDatas) && !empty($profileCoverDatas['bg_image'])){
            $aVars = array(
                'caption' => _t('_emmetbytes_profile_cover_reposition_background_caption'),
                'class' => 'ebytes_profile_cover_reposition_background_container',
                'data_id' => $profileCoverDatas['id'],
                'form_action' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'reposition_image',
            );
            $content['content'] = $this->oTemplate->parseHTMLByName('ebytes_profile_cover_background_reposition_container', $aVars);
        }
        return $content;
    }

    // getting the remove form
    protected function getRemoveForm($profileCoverDatas){
        $content = array( 'content' => '');
        if(sizeof($profileCoverDatas) && !empty($profileCoverDatas['bg_image'])){
            $aVars = array(
                'caption' => _t('_emmetbytes_profile_cover_remove_background_caption'),
                'class' => 'ebytes_profile_cover_remove_background_container',
                'data_id' => $profileCoverDatas['id'],
                'form_action' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'remove_image',
            );
            $content['content'] = $this->oTemplate->parseHTMLByName('ebytes_profile_cover_background_remove_container', $aVars);
        }
        return $content;
    }

    // save the profile cover background image datas
    protected function saveProfileCoverBgImgDatas($datas){
        $this->compressionLevel = (int)getParam('emmet_bytes_profile_cover_profile_cover_background_compr_level');
        // initialize variables
        $imageName = $datas['image_name'];
        $srcX = abs($datas['x_pos']);
        $srcY = abs($datas['y_pos']);
        $loggedId = $this->loggedId;
        // query parameters
        $queryParams = array(
            'profile_id' => $loggedId,
            'background_image' => $imageName,
            'bg_pos_x' => $srcX,
            'bg_pos_y' => $srcY
        );
        // image parameters
        $imageParams = array(
            'image_name' => $imageName, 
            'image_width' => $this->backgroundWidth,
            'image_height' => $this->backgroundHeight,
            'src_x' => $srcX,
            'src_y' => $srcY,
        );
        $imagePath = $this->cropImage($imageParams); 
        $resizeImageParams = array(
            'image_path' => $imagePath,
            'image_name' => $imageName,
            'image_width' => $this->backgroundWidth,
            'image_height' => $this->backgroundHeight,
            'resized_image_width' => round($this->backgroundWidth / 3),
            'resized_image_height' => round($this->backgroundHeight / 3),
        );
        $this->resizeImage($resizeImageParams);
        $profileData = $this->oDb->getCoverDataByProfileId($loggedId);
        if(sizeof($profileData)){
            $this->oDb->updateBackgroundInfo($queryParams);
            $action = 'change_background';
            $entryId = $profileData['id']; 
        }else{
            $entryId = $this->oDb->insertBackgroundInfo($queryParams);
            $action = 'add_background';
        }
        if($datas['type'] == 'reposition'){
            $action = 'repositioned_background';
        }
        // alert parameters
        $alertParams = array(
            'action' => $action,
            'entry_id' => $entryId,
            'profile_id' => $loggedId,
            'status' => 'approved',
        );
        $this->alert($alertParams);
        $retVal = $this->getProfileCoverDatas($this->loggedId);
        $retVal['action'] = 'insert';
        return $retVal;
    }

    // cancel the insertion of the new profile cover background image
    protected function cancelProfileCoverBgImgDatas($datas){
        $imagePath = $this->profileCoverImageDir . $datas['image_name'];
        if($datas['fresh'] == 'true'){
            $this->removeImage($imagePath);
        }
        $profileCoverImgDatas = $this->getProfileCoverDatas($this->loggedId);
        $retVal = array(
            'hasData' => true,
            'action' => 'cancel',
        );
        if(sizeof($profileCoverImgDatas) && !empty($profileCoverImgDatas['bg_image'])){
            $retVal = array_merge($retVal, $profileCoverImgDatas);
        }else{
            $retVal['hasData'] = false;
        }
        return $retVal;
    }

    // save the avatar image
    protected function insertAvatar($formData){
        // initialize variables
        $imageName = $formData['image_name'];
        $srcX = abs($formData['x_pos']);
        $srcY = abs($formData['y_pos']);
        $loggedId = $this->loggedId;
        // query parameters
        $queryParams = array(
            'profile_id' => $loggedId,
            'thumbnail_image' => $imageName,
            't_pos_x' => $srcX,
            't_pos_y' => $srcY
        );
        // image parameters
        $imageParams = array(
            'image_name' => $imageName, 
            'image_width' => $this->thumbnailWidth,
            'image_height' => $this->thumbnailHeight,
            'src_x' => $srcX,
            'src_y' => $srcY,
        );
        $imagePath = $this->cropImage($imageParams);
        $profileData = $this->oDb->getCoverDataByProfileId($loggedId);
        if($formData['submit'] == 'Save'){
            if(sizeof($profileData)){
                $this->oDb->updateAvatarInfo($queryParams);
            }else{
                $entryId = $this->oDb->insertAvatarInfo($queryParams);
            }
            $this->createAvatar($imagePath, $loggedId, $imageName);
            $imagePath = $this->getProfileCoverOwnImages($imagePath);
            $returnVal = array(
                'action' => 'save',
                'image_path' => $imagePath
            );
        }else{
            $memberAvatarFullInfo = $this->getMemberAvatarFullInfo($loggedId);
            $returnVal = array(
                'action' => 'cancel',
                'image_path' => $memberAvatarFullInfo['member_avatar'],
            );
            $returnVal = array_merge($returnVal, $memberAvatarFullInfo);
        }
        echo json_encode($returnVal);
    }

    // getting the member buttons
    protected function getMemberButtons($profileId, $loginId){
        if($loginId <= 0){
            return false;
        }else if($profileId == $loginId){
            return $this->getMemberOwnButtons($profileId);
        }else if($this->isFriend($profileId, $loginId)){
            return $this->getFriendsOwnButtons($profileId, $loginId); 
        }else if($this->isPendingFriendRequest($loginId, $profileId)){
            return $this->getInvitedMemberButtons($profileId, $loginId);
        }else if($this->isPendingFriendRequest($profileId, $loginId)){
            return $this->getMembersWhoInviteMeButtons($profileId, $loginId);
        }else{
            return $this->getOtherMemberButtons($profileId, $loginId);
        }
    }

    // getting the users own buttons
    protected function getMemberOwnButtons($loginId){
        $aVars = array(
            'caption' => _t('_emmetbytes_profile_cover_update_info_caption'),
            'link' => BX_DOL_URL_ROOT . 'pedit.php?ID=' . $loginId,
            'function' => '',
            'eb_link' => '',
            'eb_action' => '',
            'class' => 'ebytes_profile_cover_view_profile',
        );
        return $this->createMemberButton($aVars);
    }

    // getting the users friends buttons
    protected function getFriendsOwnButtons($profileId, $loginId){
        $buttonsLang = $this->getMemberButtonsLangs();
        $aVars = array(
            'caption' => $buttonsLang['remove_friend'],
            'link' => '#',
            'function' => 'onclick="ebProfileCoverFriendButtonAction(this, \'' . $profileId . '\'); return false;"',
            'eb_link' => 'eb_link=' . BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'friend_request',
            'eb_action' => 'eb_action="remove"',
            'class' => 'ebytes_profile_cover_remove_friend',
        );
        $buttons =  $this->createMemberButton($aVars);
        $buttons .=  $this->getSendMessageButton($profileId);
        return $buttons;
    }

    // getting the invited member buttons
    protected function getInvitedMemberButtons($profileId, $loginId){
        $buttonsLang = $this->getMemberButtonsLangs();
        $aVars = array(
            'caption' => $buttonsLang['cancel_friend_request'],
            'link' => '#',
            'function' => 'onclick="ebProfileCoverFriendButtonAction(this, \'' . $profileId . '\'); return false;"',
            'eb_link' => 'eb_link=' . BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'friend_request',
            'eb_action' => 'eb_action="remove"',
            'class' => 'ebytes_profile_cover_cancel_friend_request',
        );
        $buttons =  $this->createMemberButton($aVars);
        $buttons .=  $this->getSendMessageButton($profileId);
        return $buttons;
    }

    // getting the buttons for the members who invited me
    protected function getMembersWhoInviteMeButtons($profileId, $loginId){
        $buttonsLang = $this->getMemberButtonsLangs();
        $aVars = array(
            'caption' => $buttonsLang['accept_friend_request'],
            'link' => '#',
            'function' => 'onclick="ebProfileCoverFriendButtonAction(this, \'' . $profileId . '\'); return false;"',
            'eb_link' => 'eb_link=' . BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'friend_request',
            'eb_action' => 'eb_action="accept"',
            'class' => 'ebytes_profile_cover_accept_friend_request',
        );
        $buttons =  $this->createMemberButton($aVars);
        $buttons .=  $this->getSendMessageButton($profileId);
        return $buttons;

    }

    // getting the other members buttons
    protected function getOtherMemberButtons($profileId, $loginId){
        $memberButtons = $this->getMemberButtonsLangs();
        $aVars = array(
            'caption' => $memberButtons['add_friend'],
            'link' => '#',
            'function' => 'onclick="ebProfileCoverFriendButtonAction(this, \'' . $profileId . '\'); return false;"',
            'eb_link' => 'eb_link=' . BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'friend_request',
            'eb_action' => 'eb_action="add"',
            'class' => 'ebytes_profile_cover_add_friend',
        );
        $buttons =  $this->oTemplate->parseHTMLByName('ebytes_profile_cover_member_button_container', $aVars);
        $buttons .= $this->getSendMessageButton($profileId);
        return $buttons;
    }

    // getting the send a message button
    protected function getSendMessageButton($profileId){
        $aVars = array(
            'caption' => _t('_emmetbytes_profile_cover_send_message_caption'),
            'link' => BX_DOL_URL_ROOT . 'mail.php?mode=compose&recipient_id=' . $profileId,
            'function' => '',
            'class' => 'ebytes_profile_cover_send_message',
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_member_button_container', $aVars);
    }

    // create members button
    protected function createMemberButton($aVars){
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_member_button_container', $aVars);
    }

    // getting the member buttons languages
    protected function getMemberButtonsLangs(){
        return array(
            'remove_friend' => _t('_emmetbytes_profile_cover_remove_friend_caption'),
            'cancel_friend_request' => _t('_emmetbytes_profile_cover_member_cancel_friend_request'),
            'accept_friend_request' => _t('_emmetbytes_profile_cover_accept_friend_request_caption'),
            'add_friend' => _t('_emmetbytes_profile_cover_add_friend_caption'),
        );
    }
    // EOF THE TOP CONTENTS METHODS

    // BOF THE BOTTOM CONTENTS METHODS
    // getting the bottom contents
    protected function getProfileCoverBottomContents($profileId, $loginId){
        $memberInformations = $this->getMemberInformations($profileId, $loginId);
        $memberActivities = $this->getMemberActivities($profileId, $loginId);
        $mainContainerAddonClass = $toggleContainerAddonClass = $activitiesContainerAddonClass = '';
        $displayContainerCount = $memberActivities['displayed_activities_container_count'];
        if($displayContainerCount <= 0){
            $mainContainerAddonClass = 'ebytes_profile_cover_empty_activities';
            $toggleContainerAddonClass = 'ebytes_profile_cover_one_line_activity';
            $activitiesContainerAddonClass = 'ebytes_profile_cover_no_activities';
        }else if($displayContainerCount == 1){
            $mainContainerAddonClass = $activitiesContainerAddonClass = 'ebytes_profile_cover_one_activity';
            $toggleContainerAddonClass = 'ebytes_profile_cover_one_line_activity';
        }else if($displayContainerCount == 2){
            $mainContainerAddonClass = $activitiesContainerAddonClass = 'ebytes_profile_cover_two_activity';
            $toggleContainerAddonClass = 'ebytes_profile_cover_one_line_activity';
        }else if($displayContainerCount == 3){
            $mainContainerAddonClass = $activitiesContainerAddonClass = 'ebytes_profile_cover_three_activity';
            $toggleContainerAddonClass = 'ebytes_profile_cover_one_line_activity';
        }else if($displayContainerCount == 4){
            $mainContainerAddonClass = $activitiesContainerAddonClass = 'ebytes_profile_cover_four_activity';
            $toggleContainerAddonClass = 'ebytes_profile_cover_one_line_activity';
        }
        $aVars = array(
            'member_informations' => $memberInformations,
            'member_activities' => $memberActivities['container'],
            'info_container_addon_class' => $mainContainerAddonClass,
            'toggle_container_addon_class' => $toggleContainerAddonClass,
            'activities_container_addon_class' => $activitiesContainerAddonClass,
            'base_url' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri(),
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_bottom_container', $aVars);
    }

    // bof the member informations
    // getting the member informations
    protected function getMemberInformations($profileId, $loginId){
        $profileInfo = getProfileInfo($profileId);
        // getting the location data
        $locationDatas = array(
            'caption' => _t('_emmetbytes_profile_cover_location_caption'),
            'location' => (isset($profileInfo['Country']) && !empty($profileInfo['Country'])) ? (_t($GLOBALS['aPreValues']['Country'][$profileInfo['Country']]['LKey']) . (isset($profileInfo['City']) && !empty($profileInfo['City']) ? ', ' . $profileInfo['City'] : '')) : '',
        );
        $birthdate = $profileInfo['DateOfBirth'];
        $relationshipStatus = $profileInfo['RelationshipStatus'];
        $aVars = array(
            'about_caption' => _t('_emmetbytes_profile_cover_about_caption'),
            'about_link' => BX_DOL_URL_ROOT . 'profile_info.php?ID=' . $profileId,
            // getting the location
            'bx_if:display_location' => array(
                'condition' => ($this->hasLocation($profileInfo)),
                'content' => array(
                    'location_container' => ($this->hasLocation($profileInfo)) ? $this->getMembersLocationContainer($locationDatas) : '',
                ),
            ),
            // getting the insert location
            'bx_if:display_insert_location' => array(
                'condition' => ($this->isOwner($loginId, $profileId) && (!$this->hasLocation($profileInfo))),
                'content' => array(
                    'add_your_add_location_caption' => _t('_emmetbytes_profile_cover_add_location_caption'),
                    'countries' => '',
                ),
            ),
            // getting the birthdate
            'bx_if:display_birthdate' => array(
                'condition' => ($this->hasBirthDate($profileInfo)) ? true : false,
                'content' => array(
                    'birthdate_container' => ($this->hasBirthDate($profileInfo)) ? $this->getMembersBirthdateContainer($birthdate) : '',
                ),
            ),
            // getting the insert birthdate
            'bx_if:display_insert_birthdate' => array(
                'condition' => ($this->isOwner($loginId, $profileId) && (!$this->hasBirthDate($profileInfo))) ? true : false,
                'content' => array(
                    'add_your_add_birthdate_caption' => _t('_emmetbytes_profile_cover_add_birthdate_caption'),
                ),
            ),
            // getting the gender
            'bx_if:display_gender' => array(
                'condition' => ($this->hasGender($profileInfo)),
                'content' => array(
                    'gender_container' => ($this->hasGender($profileInfo)) ? $this->getMemberGenderContainer($profileInfo['Sex']) : '',
                ),
            ),
            // getting the display insert gender
            'bx_if:display_insert_gender' => array(
                'condition' => ($this->isOwner($loginId, $profileId) && (!$this->hasGender($profileInfo))),
                'content' => array(
                    'add_your_add_gender_caption' => _t('_emmetbytes_profile_cover_add_gender_caption'),
                ),
            ),
            // getting the relationship
            'bx_if:display_relationship' => array(
                'condition' => ($this->hasRelationshipStatus($profileInfo)),
                'content' => array(
                    'relationship_container' => ($this->hasRelationshipStatus($profileInfo)) ? $this->getMemberRelationshipContainer($profileInfo['RelationshipStatus']) : '',
                ),
            ),
            // getting the display insert relationship
            'bx_if:display_insert_relationship' => array(
                'condition' => ($this->isOwner($loginId, $profileId) && (!$this->hasRelationshipStatus($profileInfo))),
                'content' => array(
                    'add_your_add_relationship_caption' => _t('_emmetbytes_profile_cover_add_relationship_caption'),
                ),
            ),
            // getting the headline
            'bx_if:display_headline' => array(
                'condition' => (
                    !$this->isOwner($loginId, $profileId) &&
                    !$this->hasLocation($profileInfo) && 
                    !$this->hasBirthDate($profileInfo) && 
                    !$this->hasGender($profileInfo) && 
                    !$this->hasRelationshipStatus($profileInfo) &&
                    $this->hasHeadline($profileInfo)
                ),
                'content' => array(
                    'headline_container' => (
                        !$this->isOwner($loginId, $profileId) &&
                        !$this->hasLocation($profileInfo) && 
                        !$this->hasBirthDate($profileInfo) && 
                        !$this->hasGender($profileInfo) && 
                        !$this->hasRelationshipStatus($profileInfo) &&
                        $this->hasHeadline($profileInfo)
                    ) ? $this->getMembersHeadlineContainer($profileInfo['Headline']) : '',
                ),
            ),
            // getting the nickname
            'bx_if:display_nickname' => array(
                'condition' => (
                    !$this->isOwner($loginId, $profileId) &&
                    !$this->hasLocation($profileInfo) && 
                    !$this->hasBirthDate($profileInfo) && 
                    !$this->hasGender($profileInfo) && 
                    !$this->hasRelationshipStatus($profileInfo) &&
                    !$this->hasHeadline($profileInfo)
                ),
                'content' => array(
                    'nickname' => "<div class='ebytes_profile_cover_nickname_container'>{$profileInfo['NickName']}</div>",
                ),
            ),
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_informations_container', $aVars);
    }

    // getting the members headline
    protected function getMembersHeadlineContainer($headline){
        $aVars = array(
            'headline' => $headline,
        ); 
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_member_headline_container', $aVars);
    }

    // getting the members location
    protected function getMembersLocationContainer($locationData){
        $aVars = array(
            'location_caption' => $locationData['caption'],
            'location' => $locationData['location'],
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_member_location_container', $aVars);
    }

    // getting the members birthdate
    protected function getMembersBirthdateContainer($birthdate){
        $aVars = array(
            'birthdate_caption' => _t('_emmetbytes_profile_cover_birthdate_caption'),
            'birthdate' => date('F d, Y', strtotime($birthdate)),
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_member_birthdate_container', $aVars);
    }

    // getting the members gender container
    protected function getMemberGenderContainer($gender){
        $aVars = array(
            'gender' => $gender,
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_member_gender_container', $aVars);
    }

    // getting the members relationship container
    protected function getMemberRelationshipContainer($relationship){
        $aVars = array(
            'relationship' => $relationship,
        ); 
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_member_relationship_container', $aVars);
    }

    // check if the logged user is the owner
    protected function isOwner($loginId, $profileId){
        return ($loginId == $profileId) ? true : false;
    }

    // check if the headline is not empty
    protected function hasHeadline($profileInfo){
        return (isset($profileInfo['Headline']) && !empty($profileInfo['Headline']));
    }

    // check if the location is not empty
    protected function hasLocation($profileInfo){
        return (isset($profileInfo['Country']) && !empty($profileInfo['Country']));
    }

    // checks if the birthdate is not empty
    protected function hasBirthDate($profileInfo){
       return ((int)$profileInfo['DateOfBirth'] > 0);
    }

    // checks if the gender is not empty
    protected function hasGender($profileInfo){
        return (isset($profileInfo['Sex']) && !empty($profileInfo['Sex']));
    }

    // checks if the relationship status is not empty
    protected function hasRelationshipStatus($profileInfo){
        return (isset($profileInfo['RelationshipStatus']) && !empty($profileInfo['RelationshipStatus']));
    }
    // eof the member informations

    // getting the member avatar
    protected function getMemberAvatar($profileId){
        $aVars = $this->getMemberAvatarFullInfo($profileId);
        return $this->oTemplate->parseHTMLByName('ebytes_member_avatar_container', $aVars);
    }

    // getting the member avatar full informations
    protected function getMemberAvatarFullInfo($profileId){
        $memberAvatarDatas = $this->getUserAvatarDatas($profileId);
        $memberAvatar = $memberAvatarDatas['member_avatar_url'];
        $memberAvatarDir = $memberAvatarDatas['member_avatar_dir'];
        list($iw, $ih) = getimagesize($memberAvatarDir);
        $leftMargin = ($this->thumbnailWidth - $iw) / 2;
        $topMargin = ($this->thumbnailHeight - $ih) / 2;
        return array(
            'member_avatar' => $memberAvatar,
            'width' => $iw,
            'height' => $ih,
            'margin_left' => $leftMargin,
            'margin_top' => $topMargin,
        );
    }

    // getting the members avatar, based on the from BxBaseFunction
    protected function getUserAvatarDatas($iId, $sType = 'emmetbytes'){
        $aProfile = getProfileInfo($iId);
        if (!$aProfile || !@include_once (BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/include.php'))
            return false;

        if(($memberThumbnailDatas = $this->getEmmetBytesMemberThumbnail($iId, $sType, $aProfile))){
            return $memberThumbnailDatas;
        }else{
            return $this->getDolphinDefaultAvatar($iId, $sType, $aProfile);
        }
    }

    // getting the emmetbytes thumbnail image
    protected function getEmmetBytesMemberThumbnail($iId, $sType='emmetbytes', $aProfile){
        if($sType == 'emmetbytes' && is_file(BX_AVA_DIR_USER_AVATARS . $aProfile['Avatar'] . 'eb' . BX_AVA_EXT)){
            return array(
                'member_avatar_url' => BX_AVA_URL_USER_AVATARS . $aProfile['Avatar'] . 'eb' . BX_AVA_EXT,
                'member_avatar_dir' => BX_AVA_DIR_USER_AVATARS . $aProfile['Avatar'] . 'eb' . BX_AVA_EXT,
            );
        }else{
            return false;
        }
    }

    // getting the dolphin default avatar
    protected function getDolphinDefaultAvatar($iId, $sType='emmetbytes', $aProfile){
        if($aProfile['Avatar']){
            return array(
                'member_avatar_url' => BX_AVA_URL_USER_AVATARS . $aProfile['Avatar'] .  BX_AVA_EXT,
                'member_avatar_dir' => BX_AVA_DIR_USER_AVATARS . $aProfile['Avatar'] .  BX_AVA_EXT,
            );
        }else{
            return $this->getThumbnailEmptySexPic($aProfile);
        }
    }

    // getting the thumbnail empty sex picture
    protected function getThumbnailEmptySexPic($aProfile){
        $sexPicUrl = $GLOBALS['oFunctions']->getSexPic($aProfile['Sex'], 'medium');
        $sexPicDir = str_replace(BX_DOL_URL_ROOT, BX_DIRECTORY_PATH_ROOT, $sexPicUrl);
        return array(
            'member_avatar_url' => $sexPicUrl,
            'member_avatar_dir' => $sexPicDir,
        );
    }

    // getting the member activities
    protected function getMemberActivities($profileId, $loginId){
        $aVars = array();
        $displayActivitiesContainerCount = 0;
        $hideEmptyContainers = getParam('emmet_bytes_profile_cover_hide_empty_containers');

        // getting the member friends
        $displayFriendsContainer = getParam('emmet_bytes_profile_cover_display_friends');
        $memberFriendsDatas = $this->getMemberFriends($profileId);
        $hasFriendsContainer = true;
        if(($hideEmptyContainers && $memberFriendsDatas['count'] <= 0) || !$displayFriendsContainer){ 
            $hasFriendsContainer = false;
        }else{
            $displayActivitiesContainerCount++; 
        }
        $aVars['bx_if:display_member_friends'] = array(
            'condition' => $hasFriendsContainer,
            'content' => array(
                'member_friends' => $memberFriendsDatas['contents'],
            ),
        );

        // getting the member photo albums
        $photoModuleExist = $this->moduleExist('photos');
        $memberPhotosDatas = $this->getMemberPhotoAlbumsDatas($profileId, $photoModuleExist);
        $displayPhotoAlbumsConainer = getParam('emmet_bytes_profile_cover_display_photo_albums');
        $hasPhotoAlbumsContainer = true;
        if(!$photoModuleExist || ($hideEmptyContainers && $memberPhotosDatas['count'] <= 0) || !$displayPhotoAlbumsConainer){ 
            $hasPhotoAlbumsContainer = false;
        }else{
            $displayActivitiesContainerCount++; 
        }
        $aVars['bx_if:display_member_photo_albums'] = array(
            'condition' => $hasPhotoAlbumsContainer,
            'content' => array(
                'member_photo_albums' => $memberPhotosDatas['content']['contents'],
            ),
        );

        // getting the members video datas
        $videoModuleExist = $this->moduleExist('videos');
        $memberVideoDatas = $this->getMemberVideoAlbumsDatas($profileId, $videoModuleExist);
        $displayVideoAlbumsContainer = getParam('emmet_bytes_profile_cover_display_video_albums');
        $hasVideAlbumsContainer = true;
        if(!$videoModuleExist || ($hideEmptyContainers && $memberVideoDatas['count'] <= 0) || !$displayVideoAlbumsContainer){ 
            $hasVideAlbumsContainer = false;
        }else{
            $displayActivitiesContainerCount++; 
        }
        $aVars['bx_if:display_member_video_albums'] = array(
            'condition' => $hasVideAlbumsContainer,
            'content' => array(
                'member_video_albums' => $memberVideoDatas['content']['contents'],
            ),
        );

        // getting the members sound datas
        $soundsModuleExist = $this->moduleExist('sounds');
        $memberSoundDatas = $this->getMemberSoundAlbumsDatas($profileId, $soundsModuleExist);
        $displaySoundAlbumsContainer = getParam('emmet_bytes_profile_cover_display_sounds_albums');
        $hasSoundsAlbumsContainer = true;
        if(!$soundsModuleExist || ($hideEmptyContainers && $memberSoundDatas['count'] <= 0) || !$displaySoundAlbumsContainer){ 
            $hasSoundsAlbumsContainer = false;
        }else{
            $displayActivitiesContainerCount++; 
        }
        $aVars['bx_if:display_member_sound_albums'] = array(
            'condition' => $hasSoundsAlbumsContainer,
            'content' => array(
                'member_sound_albums' => $memberSoundDatas['content']['contents'],
            ),
        );

        // getting the members files datas
        $filesModuleExist = $this->moduleExist('files');
        $memberFileDatas = $this->getMemberFileFoldersDatas($profileId, $filesModuleExist);
        $displayFilesFolderContainer = getParam('emmet_bytes_profile_cover_display_file_folders');
        $hasFilesContainer = true;
        if(!$filesModuleExist || ($hideEmptyContainers && $memberFileDatas['count'] <= 0) || !$displayFilesFolderContainer){ 
            $hasFilesContainer = false;
        }else{
            $displayActivitiesContainerCount++; 
        }
        $aVars['bx_if:display_member_file_folders'] = array(
            'condition' => $hasFilesContainer,
            'content' => array(
                'member_file_folders' => $memberFileDatas['content']['contents'],
            ),
        );

        // getting the members ads datas
        $adsModuleExist = $this->moduleExist('ads');
        $memberAdsDatas = $this->getMemberAdsDatas($profileId, $loginId, $adsModuleExist);
        $displayAdsContainer = getParam('emmet_bytes_profile_cover_display_ads');
        $hasAdsContainer = true;
        if(!$adsModuleExist || ($hideEmptyContainers && $memberAdsDatas['count'] <= 0) || !$displayAdsContainer){ 
            $hasAdsContainer = false;
        }else{
            $displayActivitiesContainerCount++; 
        }
        $aVars['bx_if:display_member_ads'] = array(
            'condition' => $hasAdsContainer,
            'content' => array(
                'member_ads' => $memberAdsDatas['content']['contents'],
            ),
        );

        // getting the members blogs datas
        $blogsModuleExist = $this->moduleExist('blogs');
        $memberBlogDatas = $this->getMemberBlogDatas($profileId, $blogsModuleExist);
        $displayBlogsContainer = getParam('emmet_bytes_profile_cover_display_blog_posts');
        $hasBlogContainer = true;
        if(!$blogsModuleExist || ($hideEmptyContainers && $memberBlogDatas['count'] <= 0) || !$displayBlogsContainer){ 
            $hasBlogContainer = false;
        }else{
            $displayActivitiesContainerCount++; 
        }
        $aVars['bx_if:display_member_blog'] = array(
            'condition' => $hasBlogContainer,
            'content' => array(
                'member_blogs' => $memberBlogDatas['content']['contents'],
            ),
        );

        // getting the members polls datas
        $pollModuleExist = $this->moduleExist('poll');
        $memberPollDatas = $this->getMemberPollDatas($profileId, $pollModuleExist);
        $displayPollsContainer = getParam('emmet_bytes_profile_cover_display_polls');
        $hasPollsContainer = true;
        if(!$pollModuleExist || ($hideEmptyContainers && $memberPollDatas['count'] <= 0) || !$displayPollsContainer){ 
        $hasPollsContainer = false;
        }else{
            $displayActivitiesContainerCount++; 
        }
        $aVars['bx_if:display_member_poll'] = array(
            'condition' => $hasPollsContainer,
            'content' => array(
                'member_polls' => $memberPollDatas['content']['contents'],
            ),
        );

        // getting the members sites datas
        $siteModuleExist = $this->moduleExist('sites');
        $memberSiteDatas = $this->getMemberSiteDatas($profileId, $siteModuleExist);
        $displaySiteContainer = getParam('emmet_bytes_profile_cover_dipslay_websites');
        $hasSiteContainer = true;
        if(!$siteModuleExist || ($hideEmptyContainers && $memberSiteDatas['count'] <= 0) || !$displaySiteContainer){ 
            $hasSiteContainer = false;
        }else{
            $displayActivitiesContainerCount++; 
        }
        $aVars['bx_if:display_member_site'] = array(
            'condition' => $hasSiteContainer,
            'content' => array(
                'member_sites' => $memberSiteDatas['content']['contents'],
            ),
        );

        // getting the members events datas
        $eventsModuleExist = $this->moduleExist('events');
        $memberEventDatas = $this->getMemberEventDatas($profileId, $eventsModuleExist);
        $displayEventContainer = getParam('emmet_bytes_profile_cover_display_events');
        $hasEventContainer = true;
        if(!$eventsModuleExist || ($hideEmptyContainers && $memberEventDatas['count'] <= 0) || !$displayEventContainer){ 
            $hasEventContainer = false;
        }else{
            $displayActivitiesContainerCount++; 
        }
        $aVars['bx_if:display_member_event'] = array(
            'condition' => $hasEventContainer,
            'content' => array(
                'member_events' => $memberEventDatas['content']['contents'],
            ),
        );

        // getting the members stores datas
        $storeModuleExist = $this->moduleExist('store');
        $memberStoreDatas = $this->getMemberStoreDatas($profileId, $storeModuleExist);
        $displayStoreContainer = getParam('emmet_bytes_profile_cover_display_store_products');
        $hasStoreContainer = true;
        if(!$storeModuleExist || ($hideEmptyContainers && $memberStoreDatas['count'] <= 0) || !$displayStoreContainer){ 
            $hasStoreContainer = false;
        }else{
            $displayActivitiesContainerCount++; 
        }
        $aVars['bx_if:display_member_store'] = array(
            'condition' => $hasStoreContainer,
            'content' => array(
                'member_stores' => $memberStoreDatas['content']['contents'],
            ),
        );

        // getting the members groups datas
        $groupsModuleExist = $this->moduleExist('groups');
        $memberGroupsDatas = $this->getMemberGroupsDatas($profileId, $groupsModuleExist);
        $displayGroupsContainer = getParam('emmet_bytes_profile_cover_display_groups');
        $hasGroupsContainer = true;
        if(!$groupsModuleExist || ($hideEmptyContainers && $memberGroupsDatas['count'] <= 0) || !$displayGroupsContainer){ 
            $hasGroupsContainer = false;
        }else{
            $displayActivitiesContainerCount++; 
        }
        $aVars['bx_if:display_member_groups'] = array(
            'condition' => $hasGroupsContainer,
            'content' => array(
                'member_groups' => $memberGroupsDatas['content']['contents'],
            ),
        );

        return array(
            'displayed_activities_container_count' => $displayActivitiesContainerCount,
            'container' => $this->oTemplate->parseHTMLByName('ebytes_profile_cover_member_activities_container', $aVars),
        );
    }

    // getting the empty containers
    protected function getActivitiesEmptyContainers(){
        $aVars = array(
            'class' => 'empty_container',
            'empty_class' => 'ebytes_profile_cover_member_activity_empty_contents',
            'contents' => '',
            'caption' => '',
            'link' => '', 
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_member_activity_container', $aVars);
    }

    // checking if the module exists
    protected function moduleExist($sUri){
        return $this->oDb->isModule($sUri);
    }
    // EOF THE BOTTOM CONTENTS METHODS

    // BOF GETTING THE MEMBERS ACTIVITIES CONTAINERS
    // getting the members friends
    protected function getMemberFriends($profileId){
        $whereParam = $sortParam = $friendsContainer = '';
        $limitParam = "LIMIT 0, 6";
        $memberFriendsCount = getFriendNumber($profileId);
        $memberFriends = getMyFriendsEx($profileId, $whereParam, $sortParam, $limitParam);
        if($memberFriendsCount > 0){
            $friendsContainer = $this->getFriendsAvatarContainer($memberFriends);
        }
        $aVars = array(
            'contents' => $friendsContainer,
            'empty_class' => ($memberFriendsCount > 0) ? '' : 'ebytes_profile_cover_member_activity_empty_contents',
            'class' => 'ebytes_profile_cover_member_friends_container',
            'caption' => ((int)$memberFriendsCount) ? _t('_emmetbytes_profile_cover_friends_count_caption', $memberFriendsCount) : _t('_emmetbytes_profile_cover_friends_count_caption', ''), 'link' => BX_DOL_URL_ROOT . 'viewFriends.php?iUser=' . $profileId,
        );
        return array(
            'count' => $memberFriendsCount,
            'contents' => $this->oTemplate->parseHTMLByName(    
                'ebytes_profile_cover_member_activity_container', 
                $aVars
            ), 
        );
    }

    // getting the friends avatar containers
    protected function getFriendsAvatarContainer($memberFriends){
        $friendsContainer = '';
        foreach($memberFriends as $friendId=>$friendData){
            $memberInfo = getProfileInfo($friendId);
            if($memberInfo['Couple']){
                $memberInfo = getProfileInfo($memberInfo['Couple']);
            }
            $friendsContainer .= $this->getFriendContainer($memberInfo);
        }
        return $friendsContainer;
    }

    // getting the friends avatar container
    protected function getFriendContainer($memberInfo){
        $profileId = (!$memberInfo['Couple']) ? $memberInfo['ID'] : $memberInfo['Couple'];
        $friendAvatar = $GLOBALS['oFunctions']->getMemberAvatar($profileId, 'medium');
        $aVars = array(
            'avatar' => $friendAvatar,
            'profile_id' => $profileId,
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_friend_container', $aVars);
    }

    // getting the common activity container
    protected function getCommonActivityContainer($aVars){
        $aVars = array(
            'class' => (isset($aVars['class'])) ? $aVars['class'] : '',
            'empty_class' => (isset($aVars['is_empty']) && $aVars['is_empty']) ? 'ebytes_profile_cover_member_activity_empty_contents' : '',
            'contents' => (isset($aVars['contents'])) ? $aVars['contents'] : '',
            'caption' => (isset($aVars['caption'])) ? $aVars['caption'] : '',
            'link' => (isset($aVars['link'])) ? $aVars['link'] : ''
        );
        return array(
            'contents' => $this->oTemplate->parseHTMLByName('ebytes_profile_cover_member_activity_container', $aVars), 
        );
    }

    // getting the activities common container
    protected function getActivitiesCommonContainer($datas){
        $imageContents = '';
        if(sizeof($datas['images']) > 1){
            $class = 'ebytes_profile_cover_common_activities_multi_image';
            foreach($datas['images'] as $image){
                $imageVars = array( 
                    'image_bg' => $image,
                    'image' => $GLOBALS['oSysTemplate']->getIconUrl('spacer.gif')
                );
                $imageContents .= $this->oTemplate->parseHTMLByName('ebytes_profile_cover_common_activity_icon_container', $imageVars);
            }
        }else{
            $class = 'ebytes_profile_cover_common_activities_single_image';
            $imageVars = array( 
                'image_bg' => $datas['images'][0],
                'image' => $GLOBALS['oSysTemplate']->getIconUrl('spacer.gif')
            );
            $imageContents = $this->oTemplate->parseHTMLByName('ebytes_profile_cover_common_activity_icon_container', $imageVars);
        }
        $class = (isset($datas['class']) && !empty($datas['class'])) ? $datas['class'] : $class;
        $aVars = array(
            'class' => $class,
            'is_empty' => $datas['is_empty'],
            'contents' => ($datas['is_empty']) ? '' : $imageContents,
            'caption' => $datas['caption'],
            'link' => $datas['link'],
        );
        return $this->getCommonActivityContainer($aVars);
    }

    // getting the member photo albums
    protected function getMemberPhotoAlbumsDatas($profileId, $exist){
        // getting the member photos
        $memberPhotos = array();
        $photoAlbumsCount = 0;
        if($exist){
            $memberPhotos = $this->oDb->getMemberPhotos($profileId);
            $photoAlbumsCount = $this->oDb->getMemberFileAlbumCount($profileId, 'bx_photos');
            $photoAlbumsCount = ($photoAlbumsCount['count'] > 0) ? $photoAlbumsCount['count'] : '';
        }
        $images = array();
        if(sizeof($memberPhotos) > 0){
            foreach($memberPhotos as $memberPhoto){
                $a = array(
                        'ID' => $profileId,
                        'Avatar' => $memberPhoto['ID'],
                        );
                $photo = BxDolService::call('photos', 'get_image', array($a, 'browse'), 'Search');
                if(!empty($photo['file'])){
                    $images[] = $photo['file'];
                }
            }
        }else{
            $images = $this->getProfileCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cover/images/default_photo_icon.jpg');
        }
        if(sizeof($images) <= 0){
            $images = $this->getProfileCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cover/images/default_photo_icon.jpg');
        }
        $link = BX_DOL_URL_ROOT . 'm/photos/albums/browse/owner/' . $this->getMemberUserName($profileId);
        if($this->loggedId == $profileId)
            $link = BX_DOL_URL_ROOT . 'm/photos/albums/my/main/';
        $imageData = array(
            'images' => $images,
            'is_empty' => !$photoAlbumsCount,
            'caption' => _t('_emmetbytes_profile_cover_photo_albums_count_caption', $photoAlbumsCount),
            'link' => $link,
        );
        return array(
            'content' => $this->getActivitiesCommonContainer($imageData),
            'count' => $photoAlbumsCount,
        );
    }

    // getting the member video albums
    protected function getMemberVideoAlbumsDatas($profileId, $videoModuleExist){
        $memberVideos = array();
        $videoAlbumsCount = 0;
        if($videoModuleExist){
            $memberVideos = $this->oDb->getMemberVideos($profileId);
            $videoAlbumsCount = $this->oDb->getMemberFileAlbumCount($profileId, 'bx_videos');
            $videoAlbumsCount = ($videoAlbumsCount['count'] > 0) ? $videoAlbumsCount['count'] : '';
        }
        $images = array();
        if(sizeof($memberVideos) > 0){
            foreach($memberVideos as $memberVideo){
                $images[] = $this->getMemberVideo($memberVideo['ID']);
            }
        }else{
            $images = $this->getProfileCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cover/images/default_video_icon.jpg');
        }
        $link = BX_DOL_URL_ROOT . 'm/videos/albums/browse/owner/' . $this->getMemberUserName($profileId);
        if($this->loggedId == $profileId)
            $link = BX_DOL_URL_ROOT . 'm/videos/albums/my/main/';
        $imageData = array(
            'images' => $images,
            'is_empty' => !$videoAlbumsCount,
            'caption' => _t('_emmetbytes_profile_cover_video_albums_count_caption', $videoAlbumsCount),
            'link' => $link,
        );
        return array(
            'content' => $this->getActivitiesCommonContainer($imageData),
            'count' => $videoAlbumsCount,
        );
    }

    // getting the member sounds albums
    protected function getMemberSoundAlbumsDatas($profileId, $soundsModuleExist){
        $imagePath = $this->getProfileCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cover/images/default_sound_icon.jpg');
        $soundAlbumsCount = 0;
        if($soundsModuleExist){
            $soundAlbumsCount = $this->oDb->getMemberFileAlbumCount($profileId, 'bx_sounds');
            $soundAlbumsCount = ($soundAlbumsCount['count'] > 0) ? $soundAlbumsCount['count'] : '';
        }
        $link = BX_DOL_URL_ROOT . 'm/sounds/albums/browse/owner/' . $this->getMemberUserName($profileId);
        if($this->loggedId == $profileId)
            $link = BX_DOL_URL_ROOT . 'm/sounds/albums/my/main/';
        $imageData = array(
            'images' => $imagePath,
            'is_empty' => !$soundAlbumsCount,
            'caption' => _t('_emmetbytes_profile_cover_sound_albums_count_caption', $soundAlbumsCount),
            'link' => $link,
        );
        return array(
            'count' => $soundAlbumsCount,
            'content' => $this->getActivitiesCommonContainer($imageData),
        );
    }

    // getting the member files albums folders
    protected function getMemberFileFoldersDatas($profileId, $filesModuleExist){
        $imagePath = $this->getProfileCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cover/images/default_file_icon.jpg');
        $fileAlbumsCount = 0;
        if($filesModuleExist){
            $fileAlbumsCount = $this->oDb->getMemberFileAlbumCount($profileId, 'bx_files');
            $fileAlbumsCount = ($fileAlbumsCount['count'] > 0) ? $fileAlbumsCount['count'] : '';
        }
        $link = BX_DOL_URL_ROOT . 'm/files/albums/browse/owner/' . $this->getMemberUserName($profileId);
        if($this->loggedId == $profileId)
            $link = BX_DOL_URL_ROOT . 'm/files/albums/my/main/';
        $imageData = array(
            'images' => $imagePath,
            'is_empty' => !$fileAlbumsCount,
            'caption' => _t('_emmetbytes_profile_cover_file_folders_count_caption', $fileAlbumsCount),
            'link' => $link,
        );
        return array(
            'count' => $fileAlbumsCount,
            'content' => $this->getActivitiesCommonContainer($imageData),
        );
    }

    // getting the member ads datas
    protected function getMemberAdsDatas($profileId, $loginId, $adsModuleExist){
        $link = $this->getAdsLink($profileId);
        $adsImageDatas = array();
        if($adsModuleExist){
            $adsImageDatas = $this->oDb->getMemberAdsPhotos($profileId);
        }
        if(sizeof($adsImageDatas) < 0){
            $imagePath = $this->getMemberAdsImage($adsImageDatas);
        }else{
            $imagePath = $this->getProfileCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cover/images/default_ads_icon.jpg');
        }
        $adsCount = 0;
        if($adsModuleExist){
            $adsCount = $this->oDb->getMemberAdsCount($profileId, $loginId);
            $adsCount = ($adsCount['count'] > 0) ? $adsCount['count'] : '';
        }
        $imageData = array(
            'images' => $imagePath,
            'is_empty' => !$adsCount,
            'caption' => _t('_emmetbytes_profile_cover_ads_count_caption', $adsCount),
            'link' => $link,
            'class' => '', 
        );
        return array(
            'count' => $adsCount,
            'content' => $this->getActivitiesCommonContainer($imageData),
        );
    }

    protected function getAdsLink($profileId){
        $link = BX_DOL_URL_ROOT . 'ads/my_page';
        if($this->loggedId != $profileId){
            $link = BX_DOL_URL_ROOT . 'modules/boonex/ads/classifieds.php?UsersOtherListing=1&IDProfile=' . $profileId;
        }
        return $link;
    }

    // getting the member blogs datas
    protected function getMemberBlogDatas($profileId, $blogsModuleExist){
        $blogPostsCount = 0;
        if($blogsModuleExist){
            $blogPostsCount = $this->oDb->getMemberBlogPostsCount($profileId);
            $blogPostsCount = ($blogPostsCount['count'] > 0) ? $blogPostsCount['count'] : '';
        }
        $imagePath = $this->getProfileCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cover/images/default_blog_icon.jpg');
        $link = $this->getBlogLink($profileId);
        $imageData = array(
            'images' => $imagePath,
            'is_empty' => !$blogPostsCount,
            'caption' => _t('_emmetbytes_profile_cover_blogs_count_caption', $blogPostsCount),
            'link' => $link,
        );
        return array(
            'count' => $blogPostsCount,
            'content' => $this->getActivitiesCommonContainer($imageData)
        );
    }

    protected function getBlogLink($profileId){
        $link = BX_DOL_URL_ROOT . 'blogs/my_page/';
        if($profileId != $this->loggedId){
            $link = BX_DOL_URL_ROOT . 'modules/boonex/blogs/blogs.php?action=show_member_blog&ownerID=' . $profileId;
        }
        return $link;
    }

    // getting the member polls datas
    protected function getMemberPollDatas($profileId, $pollModuleExist){
        $link = '';
        if($this->loggedId == $profileId){
            $link = BX_DOL_URL_ROOT . 'm/poll/&action=my';
        }else{
            $profileInfo = getProfileInfo($profileId);
            $link = BX_DOL_URL_ROOT . 'm/poll/&action=user&nickname=' . $profileInfo['NickName'];
        }
        $imagePath = $this->getProfileCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cover/images/default_poll_icon.jpg');
        $pollsCount = 0;
        if($pollModuleExist){
            $pollsCount = $this->oDb->getPollsCount($profileId);
            $pollsCount = ($pollsCount['count'] > 0) ? $pollsCount['count'] : '';
        }
        $imageData = array(
            'images' => $imagePath,
            'is_empty' => !$pollsCount,
            'caption' => _t('_emmetbytes_profile_cover_polls_count_caption', $pollsCount),
            'link' => $link,
        );
        return array(
            'count' => $pollsCount,
            'content' => $this->getActivitiesCommonContainer($imageData),
        );
    }

    // getting the member site datas
    protected function getMemberSiteDatas($profileId, $siteModuleExist){
        $link = '';
        $memberSitePhotos = array();
        $websiteCount = 0;
        if($siteModuleExist){
            $memberSitePhotos = $this->oDb->getMemberWebsitePhotos($profileId);
            $websiteCount = $this->oDb->getWebsiteCount($profileId);
            $websiteCount = ($websiteCount['count'] > 0) ? $websiteCount['count'] : '';
        }
        $profileInfo = getProfileInfo($profileId);
        $link = BX_DOL_URL_ROOT . 'm/sites/browse/user/' . $profileInfo['NickName'];
        if($this->loggedId == $profileId)
            $link = BX_DOL_URL_ROOT . 'm/sites/browse/my';
        if(sizeof($memberSitePhotos) > 0){
            $imagePath = $this->getMemberWebsitePhotos($memberSitePhotos);
        }else{
            $imagePath = $this->getProfileCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cover/images/default_site_icon.jpg');
        }
        $imageData = array(
            'images' => $imagePath,
            'is_empty' => !$websiteCount,
            'caption' => _t('_emmetbytes_profile_cover_websites_count_caption', $websiteCount),
            'link' => $link,
        );
        return array(
            'count' => $websiteCount,
            'content' => $this->getActivitiesCommonContainer($imageData),
        );
    }

    // getting the member event datas
    protected function getMemberEventDatas($profileId, $eventsModuleExist){
        $link = '';
        $memberEventPhotos = array();
        $eventCount = 0;
        if($eventsModuleExist){
            $memberEventPhotos = $this->oDb->getMemberEventPhotos($profileId);
            $eventCount = $this->oDb->getMemberEventCount($profileId);
            $eventCount = ($eventCount['count'] > 0) ? $eventCount['count'] : '';
        }
        $profileInfo = getProfileInfo($profileId);
        $link = BX_DOL_URL_ROOT . 'm/events/browse/user/' . $profileInfo['NickName'];
        if($profileId == $this->loggedId){
            $link = BX_DOL_URL_ROOT . 'm/events/browse/my';
        }
        if(sizeof($memberEventPhotos) > 0){
            $imagePath = $this->getMemberEventPhotos($memberEventPhotos);
        }else{
            $imagePath = $this->getProfileCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cover/images/default_event_icon.jpg');
        }
        $imageData = array(
            'images' => $imagePath,
            'is_empty' => !$eventCount,
            'caption' => _t('_emmetbytes_profile_cover_events_count_caption', $eventCount),
            'link' => $link,
        );
        return array(
            'count' => $eventCount,
            'content' => $this->getActivitiesCommonContainer($imageData),
        );
    }

    // getting the member store datas
    protected function getMemberStoreDatas($profileId, $storeModuleExist){
        $link = '';
        if($this->loggedId == $profileId){
            $link = BX_DOL_URL_ROOT . 'm/store/browse/my';
        }else{
            $profileInfo = getProfileInfo($profileId);
            $link = BX_DOL_URL_ROOT . 'm/store/browse/user/' . $profileInfo['NickName'];
        }
        $memberStorePhotos = array();
        $storeProductsCount = 0;
        if($storeModuleExist){
            $memberStorePhotos = $this->oDb->getMemberStorePhotos($profileId, 2); 
            $storeProductsCount = $this->oDb->getMemberStoreProductsCount($profileId);
            $storeProductsCount = ($storeProductsCount['count'] > 0) ? $storeProductsCount['count'] : '';
        }
        if(sizeof($memberStorePhotos) > 0){
            $imagePath = $this->getMemberStorePhotos($memberStorePhotos);
        }else{
            $imagePath = $this->getProfileCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cover/images/default_store_icon.jpg');
        }
        $imageData = array(
            'images' => $imagePath,
            'is_empty' => !$storeProductsCount,
            'caption' => _t('_emmetbytes_profile_cover_stores_count_caption', $storeProductsCount),
            'link' => $link,
            'class' => (sizeof($memberStorePhotos) > 0) ? 'ebytes_profile_cover_common_activities_multi_image' : '',
        );
        return array(
            'count' => $storeProductsCount,
            'content' => $this->getActivitiesCommonContainer($imageData),
        );
    }

    // getting the member groups datas
    protected function getMemberGroupsDatas($profileId, $groupsModuleExist){
        $link = '';
        $memberGroupsCount = 0;
        $memberGroupPhotos = array();
        if($groupsModuleExist){
            $memberGroupsCount = $this->oDb->getMemberGroupsCount($profileId);
            $memberGroupsCount = ($memberGroupsCount['count'] > 0) ? $memberGroupsCount['count'] : '';
            $memberGroupPhotos = $this->oDb->getMemberGroupPhotos($profileId);
        }
        if(sizeof($memberGroupPhotos) > 0){
            $imagePath = $this->getMemberGroupPhotos($memberGroupPhotos);
        }else{
            $imagePath = $this->getProfileCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cover/images/default_groups_icon.jpg');
        }
        if($profileId == $this->loggedId){
            $link = BX_DOL_URL_ROOT . 'm/groups/browse/my';
        }else{
            $profileInfo = getProfileInfo($profileId);
            $link = BX_DOL_URL_ROOT . 'm/groups/browse/user/' . $profileInfo['NickName'];
        }
        $imageData = array(
            'images' => $imagePath,
            'is_empty' => !$memberGroupsCount,
            'caption' => _t('_emmetbytes_profile_cover_groups_count_caption', $memberGroupsCount),
            'link' => $link,
        );
        return array(
            'count' => $memberGroupsCount,
            'content' => $this->getActivitiesCommonContainer($imageData),
        );
    }
    // EOF GETTING THE MEMBERS ACTIVITIES CONTAINERS

    // BOF THE INFORMATION POPUPS
    // getting the headline popup
    function getHeadlinePopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $profileId = getID($_GET['ID']);
            $loggedId = $this->loggedId;
            if($profileId == $loggedId){
                if(isset($_POST['headline']) && !empty($_POST['headline'])){
                    $headline = $_POST['headline'];
                    $formData = array(
                        'ID' => $profileId,
                        'headline' => $headline,
                    );
                    $formData = array(
                        'Headline' => $headline,
                    );
                    // update the headline
                    $success = $this->updateProfile($profileId, $formData);
                    if($success > 0){
                        $content = $this->getMembersHeadlineContainer($headline);
                    }
                }else{
                    $errors = array(_t('_emmetbytes_profile_cover_no_headline_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_profile_cover_not_allowed_error'));
            }
            if($errors){
                $error_container = $this->generateFormErrorContents($errors);
                $data = array(
                    'error' => 'true',
                    'error_message' => $error_container,
                );
                return json_encode($data);
            }else{
                $data = array(
                    'error' => 'false',
                    'content' => $content,
                );
                return json_encode($data);
            }
            exit;
        }else{
            $aVars = array(
                'form_action' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'get_headline_popup',
                'inputs_container' => $this->getHeadlineForm(),
                'container_id' => 'ebytes_profile_cover_insert_headline_container',
                'form_id' => 'ebytes_profile_cover_headline_form_id',
                'close_caption' => _t('_emmetbytes_profile_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_profile_cover_popup_submit_headline_caption'),
            );
            return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_popup', $aVars);
        }
    }

    // getting the headline form
    protected function getHeadlineForm(){
        $aVars = array(
            'help_caption' => _t('_emmetbytes_profile_cover_help_headline_caption'),
            'input_name' => 'headline',
            'headline_caption' => _t('_emmetbytes_profile_cover_headline_caption'),
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_headline_form', $aVars);
    }

    // getting the location popup
    function getLocationPopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = $hasErrors = false;
            $content = '';
            $profileId = getID($_GET['ID']);
            $loggedId = $this->loggedId;
            $country = $_POST['country'];
            $city = $_POST['city'];
            if($profileId == $loggedId){
                if(!isset($_POST['country']) || empty($_POST['country'])){
                    $hasErrors = true;
                    $errors[] = _t('_emmetbytes_profile_cover_no_country_problem');
                }
                if(!isset($_POST['city']) || empty($_POST['city'])){
                    $hasErrors = true;
                    $errors[] = _t('_emmetbytes_profile_cover_no_city_problem');
                }
                if(!$hasErrors){
                    $formData = array(
                        'ID' => $profileId,
                        'Country' => $country,
                        'City' => $city,
                    );
                    // update the location
                    $success = $this->updateProfile($profileId, $formData);
                    if($success > 0){
                        // getting the location data
                        $locationDatas = array(
                            'caption' => _t('_emmetbytes_profile_cover_location_caption'),
                            'location' => (_t($GLOBALS['aPreValues']['Country'][$country]['LKey']) . ', ' . $city ),
                        );
                        $content = $this->getMembersLocationContainer($locationDatas);
                    }
                }
            }else{
                $errors = array(_t('_emmetbytes_profile_cover_not_allowed_error'));
            }
            if($errors){
                $error_container = $this->generateFormErrorContents($errors);
                $data = array(
                    'error' => 'true',
                    'error_message' => $error_container,
                );
                return json_encode($data);
            }else{
                $data = array(
                    'error' => 'false',
                    'content' => $content,
                );
                return json_encode($data);
            }
            exit;
        }else{
            $aVars = array(
                'form_action' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'get_location_popup',
                'inputs_container' => $this->getLocationForm(),
                'container_id' => 'ebytes_profile_cover_insert_location_container',
                'close_caption' => _t('_emmetbytes_profile_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_profile_cover_popup_submit_location_caption'),
            );
            return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_popup', $aVars);
        }
    }

    // getting the form for the location
    protected function getLocationForm(){
        // getting the profile countries
        $countries = array('' => _t('_Country'));
        $countries = array_merge($countries, $this->oPF->convertValues4Input('#!Country'));
        $countriesSelection = $this->generateSelectContainer($countries, 'country', 'ebytes_profile_cover_country_select');
        $aVars = array(
            'help_caption' => _t('_emmetbytes_profile_cover_help_location_caption'),
            'countries_selection' => $countriesSelection,
            'city_input_name' => 'city',
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_location_form', $aVars);
    }

    // getting the birthdate popup
    function getBirthdatePopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $profileId = getID($_GET['ID']);
            $loggedId = $this->loggedId;
            if($profileId == $loggedId){
                if(isset($_POST['birthdate']) && !empty($_POST['birthdate']) && $_POST['birthdate'] != 'yy-mm-dd'){
                    $birthdate = $_POST['birthdate'];
                    $formData = array(
                        'ID' => $profileId,
                        'DateOfBirth' => $birthdate,
                    );
                    // update the birthdate
                    $success = $this->updateProfile($profileId, $formData);
                    if($success > 0){
                        // getting the birthdate data
                        $content = $this->getMembersBirthdateContainer($birthdate);
                    }
                }else{
                    $errors = array(_t('_emmetbytes_profile_cover_no_birthdate_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_profile_cover_no_headline_problem'));
            }
            if($errors){
                $error_container = $this->generateFormErrorContents($errors);
                $data = array(
                    'error' => 'true',
                    'error_message' => $error_container,
                );
                return json_encode($data);
            }else{
                $data = array(
                    'error' => 'false',
                    'content' => $content,
                );
                return json_encode($data);
            }
            exit;
        } else{
            $aVars = array(
                'form_action' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'get_birthdate_popup',
                'inputs_container' => $this->getBirthdateForm(),
                'container_id' => 'ebytes_profile_cover_insert_birthdate_container',
                'close_caption' => _t('_emmetbytes_profile_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_profile_cover_popup_submit_birthdate_caption'),
            );
            return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_popup', $aVars);
        }
    }

    // getting the birthdate form
    protected function getBirthdateForm(){
        // getting the profile countries
        $aVars = array(
            'help_caption' => _t('_emmetbytes_profile_cover_help_location_caption'),
            'birthdate_input_name' => 'birthdate',
            'birthdate_caption' => '', 
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_birthdate_form', $aVars);
    }

    // getting the gender popup
    function getGenderPopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $profileId = getID($_GET['ID']);
            $loggedId = $this->loggedId;
            $gender = $_POST['gender'];
            if($profileId == $loggedId){
                if(isset($_POST['gender']) && !empty($_POST['gender'])){
                    $formData = array(
                        'ID' => $profileId,
                        'Sex' => $gender, 
                    );
                    // update the gender
                    $success = $this->updateProfile($profileId, $formData);
                    if($success > 0){
                        // getting the gender data
                        $content = $this->getMemberGenderContainer($gender);
                    }
                }else{
                    $errors = array(_t('_emmetbytes_profile_cover_no_gender_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_profile_cover_not_allowed_error'));
            }
            if($errors){
                $error_container = $this->generateFormErrorContents($errors);
                $data = array(
                    'error' => 'true',
                    'error_message' => $error_container,
                );
                return json_encode($data);
            }else{
                $data = array(
                    'error' => 'false',
                    'content' => $content,
                );
                return json_encode($data);
            }
            exit;
        }else{
            $aVars = array(
                'form_action' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'get_gender_popup',
                'inputs_container' => $this->getGenderForm(),
                'container_id' => 'ebytes_profile_cover_insert_gender_container',
                'close_caption' => _t('_emmetbytes_profile_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_profile_cover_popup_submit_gender_caption'),
            );
            return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_popup', $aVars);
        }
    }

    // getting the gender form
    protected function getGenderForm(){
        $genders = array(
            '' => _t('_emmetbytes_profile_cover_gender_caption'),
            'male' => _t('_adm_txt_mp_male'),
            'female' => _t('_adm_txt_mp_female'),
        );
        $genderSelection = $this->generateSelectContainer($genders, 'gender', 'ebytes_profile_cover_gender_select');
        // getting the profile countries
        $aVars = array(
            'help_caption' => _t('_emmetbytes_profile_cover_help_gender_caption'),
            'genders_selection' => $genderSelection,
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_gender_form', $aVars);

    }

    // getting the relationship popup
    function getRelationshipPopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $profileId = getID($_GET['ID']);
            $loggedId = $this->loggedId;
            if($profileId == $loggedId){
                if(isset($_POST['relationship']) && !empty($_POST['relationship'])){
                    $relationshipStatus = $_POST['relationship'];
                    $formData = array(
                        'ID' => $profileId,
                        'RelationshipStatus' => $relationshipStatus,
                    );
                    $success = $this->updateProfile($profileId, $formData);
                    if($success > 0){
                        // getting the relationship data
                        $content = $this->getMemberRelationshipContainer($relationshipStatus);
                    }
                }else{
                    $errors = array(_t('_emmetbytes_profile_cover_no_relationship_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_profile_cover_not_allowed_error'));
            }
            if($errors){
                $error_container = $this->generateFormErrorContents($errors);
                $data = array(
                    'error' => 'true',
                    'error_message' => $error_container,
                );
                return json_encode($data);
            }else{
                $data = array(
                    'error' => 'false',
                    'content' => $content,
                );
                return json_encode($data);
            }
            exit;
        }else{
            $aVars = array(
                'form_action' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'get_relationship_popup',
                'inputs_container' => $this->getRelationshipForm(),
                'container_id' => 'ebytes_profile_cover_insert_relationship_container',
                'close_caption' => _t('_emmetbytes_profile_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_profile_cover_popup_submit_relationship_caption'),
            );
            return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_popup', $aVars);
        }
    }

    // gettingt the relationship form
    protected function getRelationshipForm(){
        $relationshipStatus = $this->oDb->getRelationshipStatus();
        $relationshipVals = array('' => _t('_FieldCaption_RelationshipStatus_Edit'));
        $relationshipValsTemp = explode("\n", $relationshipStatus[0]['Values']);
        foreach($relationshipValsTemp as $relationshipVal){
            $relationshipVals[$relationshipVal] = $relationshipVal;
        }
        $relationshipSelection = $this->generateSelectContainer($relationshipVals, 'relationship', 'ebytes_profile_cover_relationship_select');
        // getting the profile countries
        $aVars = array(
            'help_caption' => _t('_emmetbytes_profile_cover_help_relationship_caption'),
            'relationships_selection' => $relationshipSelection,
        );
        return $this->oTemplate->parseHTMLByName('ebytes_profile_cover_relationship_form', $aVars);
    }
    // EOF THE INFORMATION POPUPS

    // BOF THE PROFILE CONTROLLER ACTIONS
    // update the profiles
    protected function updateProfile($profileId, $formData){
        return $this->oPC->updateProfile($profileId, $formData);
    }
    // EOF THE PROFILE CONTROLLER ACTIONS

    // BOF THE FORM CONTAINER
    // generate the select container
    protected function generateSelectContainer($datas, $name, $class=''){
        $htmlSelect = '<select name="' . $name . '" class="'. $class . '">';
        foreach ($datas as $value=>$data) {
            $htmlSelect .= '<option value="' . $value . '">' . $data . '</option>';
        }
        $htmlSelect .= '</select>';
        return $htmlSelect;
    }

    // generate the form error contents
    protected function generateFormErrorContents($errors = array()){
        $contents = '';
        foreach($errors as $error){
            $aVars = array( 'error' => $error,); 
            $contents .= $this->oTemplate->parseHTMLByName('ebytes_profile_cover_form_error_container', $aVars);
        }
        return $contents;
    }
    // EOF THE FORM CONTAINER

    // BOF THE METHODS THAT MIMICKS THE SYSTEM DATAS
    // getting the video files path
    protected function getVideoFilesPath(){
        return BX_DIRECTORY_PATH_ROOT . 'flash/modules/video/files/';
    }

    // getting the video files url
    protected function getVideoFilesUrl(){
        return BX_DOL_URL_ROOT . 'flash/modules/video/files/';
    }

    // getting the members videos
    protected function getMemberVideo($id){
        return $this->getVideoFilesUrl() . $id . '_small.jpg';
    }

    // getting the members ads files path
    protected function getAdsFilesPath(){
        return BX_DOL_URL_ROOT . 'media/images/classifieds/';
    }

    // getting the members ads images
    protected function getMemberAdsImage($adsImageDatas){
        $adsImages = array();
        foreach($adsImageDatas as $adsImageData){
            $adsImages[] = $this->getAdsFilesPath() . 'thumb_' . $adsImageData;
        }
        return $adsImages;
    }

    // getting the members blog posts file path
    protected function getMembersBlogPostsFilePath(){
        return BX_DOL_URL_ROOT . 'media/images/blog/big_';
    }

    // getting the members blog post image
    protected function getMemberBlogPostsImage($blogPostsImageDatas){
        $blogPostsImages = array();
        foreach($blogPostsImageDatas as $blogPostsImageData){
            $blogPostsImages[] = $this->getMembersBlogPostsFilePath() . $blogPostsImageData['PostPhoto'];
        }
        return $blogPostsImages;
    }
    
    // getting the members website photo
    protected function getMemberWebsitePhotos($photos){
        $websitePhotos = array();
        foreach($photos as $photo){
            $aPhoto = BxDolService::call('photos', 'get_photo_array', array($photo['photo'], 'browse'), 'Search'); 
            $websitePhotos[] = $aPhoto['file'];
        }
        return $websitePhotos;
    }

    // getting the members event photos
    protected function getMemberEventPhotos($photos){
       $eventPhotos = array(); 
       foreach($photos as $photo){
            $aPhoto = BxDolService::call('photos', 'get_photo_array', array($photo['PrimPhoto'], 'browse'), 'Search'); 
            $eventPhotos[] = $aPhoto['file'];
       }
       return $eventPhotos;
    }

    // getting the members store photos
    protected function getMemberStorePhotos($photos){
        $storePhotos = array();
        foreach($photos as $photo){
            $aPhoto = BxDolService::call('photos', 'get_photo_array', array($photo['thumb'], 'browse'), 'Search');
            $storePhotos[] = $aPhoto['file'];
        }
        return $storePhotos;
    }

    // getting the members group photos
    protected function getMemberGroupPhotos($photos){
        $memberGroupPhotos = array();
        foreach($photos as $photo){
            $aPhoto = BxDolService::call('photos', 'get_photo_array', array($photo['thumb'], 'browse'), 'Search');
            $memberGroupPhotos[] = $aPhoto['file'];
        }
        return $memberGroupPhotos;
    }
    // EOF THE METHODS THAT MIMICKS THE SYSTEM DATAS

    // BOF THE METHODS THAT MIMICKS THE AVATAR MODULE
    // create the avatar
    protected function createAvatar($img, $profileId, $imageName){
        $createAvatar = BxDolService::call('avatar', 'make_avatar_from_image', array($img));
        if($createAvatar){
            if (BxDolRequest::serviceExists('photos', 'perform_photo_upload', 'Uploader')) {
                $imgPath = $this->profileCoverImageDir . 'orig_' . $imageName;
                $aFileInfo = array (
                    'medTitle' => _t('_bx_ava_avatar'),
                    'medDesc' => _t('_bx_ava_avatar'),
                    'medTags' => _t('_ProfilePhotos'),
                    'Categories' => array(_t('_ProfilePhotos')),
                    'album' => str_replace('{nickname}', getNickName($profileId), getParam('bx_photos_profile_album_name')),
                    'albumPrivacy' => BX_DOL_PG_ALL
                );
                BxDolService::call('photos', 'perform_photo_upload', array($imgPath, $aFileInfo, false), 'Uploader');
            }
        }
    }
    // EOF THE METHODS THAT MIMICKS THAT AVATAR MODULE

    // BOF THE METHODS FOR THE BxTemplCommunicator CLASS
    // creating an instance of the common friend request communicator class
    protected function commonFriendRequestCommunicatorClass(){
        bx_import('BxTemplCommunicator');
        $loggedId = $this->loggedId;
        $aCommunicatorSettings = array(
            'member_id' => $loggedId
        );
        return new BxTemplCommunicator(
            'communicator_page', 
            $aCommunicatorSettings
        );
    }
    // EOF THE METHODS FOR THE BxTemplCommunicator CLASS

    // BOF THE IMAGE METHODS
    // getting the profile cover own images
    protected function getProfileCoverOwnImages($imagePath){
        $imageUrlPath = base64_encode($imagePath);
        return array(BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'get_own_image/' . $imageUrlPath);
    }

    // getting the default image
    function getOwnImage($defaultImage){
        $image = base64_decode($defaultImage);
        $imageInfo = getimagesize($image);
        header('Content-Type: ' . $imageInfo['mime']);
        echo readfile($image);
    }

    // resize the image
    protected function imageResize($imagePath, $defaultWidth, $defaultHeight){
        $imageInfo = getimagesize($imagePath);        
        switch($imageInfo[2]){
            case IMAGETYPE_JPEG:
                $imageRsrc = imagecreatefromjpeg($imagePath);
                break;
            case IMAGETYPE_PNG:
                $imageRsrc = imagecreatefrompng($imagePath);
                break;
            case IMAGETYPE_GIF:
                $imageRsrc = imagecreatefromgif($imagePath);
                break;
        }
        $imageSrcWidth = $imageInfo[0];
        $imageSrcHeight = $imageInfo[1];
        $widthRatio = $defaultWidth / $imageSrcWidth;
        $heightRatio = $defaultHeight / $imageSrcHeight;
        if($widthRatio > $heightRatio){
            $imageWidth = $defaultWidth;
            $imageHeight = $imageSrcHeight * $widthRatio;
        }else{
            $imageWidth = $imageSrcWidth * $heightRatio;
            $imageHeight = $defaultHeight;
        }
        $newImage = imagecreatetruecolor($imageWidth, $imageHeight);
        imagecopyresampled($newImage, $imageRsrc, 0, 0, 0, 0, $imageWidth, $imageHeight, $imageSrcWidth, $imageSrcHeight);
        return (imagejpeg($newImage, $imagePath, (int)$this->compressionLevel)) ? true : false;
    }

    // crop the profile cover image
    protected function cropImage($imageParams){
        // initialize image parameter
        $imgName = $imageParams['image_name'];
        $imgWidth = $imageParams['image_width'];
        $imgHeight = $imageParams['image_height'];
        $srcX = $imageParams['src_x'];
        $srcY = $imageParams['src_y'];
        $tmpImagePath = $this->profileCoverImageDir . $imgName;
        $imagePath = $this->profileCoverImageDir . 'crop_' . $imgName;
        $imageSize = getimagesize($tmpImagePath);
        $imageRsrc = $this->getImageResource($tmpImagePath);
        $newImage = imagecreatetruecolor($imgWidth, $imgHeight);
        imagecopyresampled($newImage, $imageRsrc, 0, 0, $srcX, $srcY, $imageSize[0], $imageSize[1], $imageSize[0], $imageSize[1]);
        imagejpeg($newImage, $imagePath, (int)$this->compressionLevel);
        chmod($imagePath, 0777);
        return $imagePath;
    }

    // resize the images
    protected function resizeImage($imageParams){
        $imageName = $imageParams['image_name'];
        $imagePath = $imageParams['image_path'];
        $newImagePath = $this->profileCoverImageDir . 'small_' . $imageName;
        $imageWidth = $imageParams['image_width'];
        $imageHeight = $imageParams['image_height'];
        $rImageWidth = $imageParams['resized_image_width'];
        $rImageHeight = $imageParams['resized_image_height'];
        $imgRsrc = $this->getImageResource($imagePath);
        $newImage = imagecreatetruecolor($rImageWidth, $rImageHeight);
        imagecopyresized(
            $newImage, 
            $imgRsrc, 
            0, 
            0, 
            0, 
            0, 
            $rImageWidth, 
            $rImageHeight, 
            $imageWidth,
            $imageHeight
        );
        imagejpeg($newImage, $newImagePath);
        chmod($newImagePath, 0777);
        return $newImagePath;
    }

    // getting the image resource
    protected function getImageResource($imagePath){
        $imageSize = getimagesize($imagePath);
        switch($imageSize[2]){
            case IMAGETYPE_JPEG:
                $imageRsrc = imagecreatefromjpeg($imagePath);
                break;
            case IMAGETYPE_PNG:
                $imageRsrc = imagecreatefrompng($imagePath);
                break;
            case IMAGETYPE_GIF:
                $imageRsrc = imagecreatefromgif($imagePath);
                break;
        }
        return $imageRsrc;
    }

    // remove the image
    protected function removeImage($imagePath){
        unlink($imagePath);
    }
    // EOF THE IMAGE METHODS

    // BOF THE ALERT METHODS
    // alert
    protected function alert($alertParams){
        // initialize the alert parameters
        $action = $alertParams['action'];
        $entryId = $alertParams['entry_id'];
        $profileId = $alertParams['profile_id'];
        $status = $alertParams['status'];
        $oAlert = new BxDolAlerts($this->oMain->_sPrefix, $action, $entryId, $profileId, array('status' => $status)); 
        $oAlert->alert();
    }

    // getting the alert common languages
    protected function getAlertLanguages($type){
        return array(
            'add_background' => '_eb_profile_cover_add_' . $type . '_post_background',
            'change_background' => '_eb_profile_cover_change_' . $type . '_post_background',
            'repositioned_background' => '_eb_profile_cover_repositioned_' . $type . '_post_background',
            'remove_background' => '_eb_profile_cover_remove_' . $type . '_post_background',
            'add_thumbnail' => '_eb_profile_cover_add_' . $type . '_post_thumbnail',
            'change_thumbnail' => '_eb_profile_cover_change_' . $type . '_post_thumbnail',
        );
    }
    
    // getting the spy data
    function getSpyData(){
        return $this->commonAlertDatas('get_spy_post');
    }

    // getting the spy post
    function getSpyPost($sAction, $iObjectId, $iSenderId, $aExtraParams){
        $lang = $this->getAlertLanguages('spy');
        return $this->oMain->_serviceGetSpyPost($sAction, $iObjectId, $iSenderId, $aExtraParams, $lang);
    }

    // getting the wall data
    function getWallData(){
        return $this->commonAlertDatas('get_wall_post');
    }

    // getting the wall post
    function getWallPost($aEvent){
        $languages = $this->getAlertLanguages('wall');
        $sTextAction = _t($languages[$aEvent['action']]);
        $sTextWallObject = 'test wall object';
        // check for the ownership
        if (!($aProfile = getProfileInfo($aEvent['owner_id']))){
            return '';
        }
        // check for the correct data entry
        if (!($aDataEntry = $this->oMain->_oDb->getEntryByIdAndOwner ($aEvent['object_id'], $aEvent['owner_id'], 0))){
            return '';
        }
        $sCss = '';        
        if($aEvent['js_mode']){
            $sCss = $this->oMain->_oTemplate->addCss('wall_post.css', true);
        }else{
            $this->oMain->_oTemplate->addCss('wall_post.css');
        }
        $aVars = array(
                'cpt_user_name' => $aProfile['NickName'],
                'cpt_action' => $sTextAction,
                'cpt_object' => $sTextWallObject,
                'cpt_item_url' => BX_DOL_URL_ROOT . $this->oMain->_oConfig->getBaseUri() . 'view/' . $aDataEntry[$this->oMain->_oDb->_sFieldUri],
                'post_id' => $aEvent['id'],
        );
        return array(
            'title' => $aProfile['username'] . ' ' . $sTextAction . ' ' . $sTextWallObject,
            'description' => 'test description', // $aDataEntry[$this->oMain->_oDb->_sFieldDesc],
            'content' => $sCss . $this->oMain->_oTemplate->parseHtmlByName('wall_post', $aVars)
        );
    }

    // common alert datas
    protected function commonAlertDatas($alertMethod){
        return array(
            'handlers' => array(
                array('alert_unit' => $this->oMain->_sPrefix, 'alert_action' => 'add_background', 'module_uri' => $this->oMain->_aModule['uri'], 'module_class' => 'Module', 'module_method' => $alertMethod),
                array('alert_unit' => $this->oMain->_sPrefix, 'alert_action' => 'change_background', 'module_uri' => $this->oMain->_aModule['uri'], 'module_class' => 'Module', 'module_method' => $alertMethod),
                array('alert_unit' => $this->oMain->_sPrefix, 'alert_action' => 'repositioned_background', 'module_uri' => $this->oMain->_aModule['uri'], 'module_class' => 'Module', 'module_method' => $alertMethod),
                array('alert_unit' => $this->oMain->_sPrefix, 'alert_action' => 'remove_background', 'module_uri' => $this->oMain->_aModule['uri'], 'module_class' => 'Module', 'module_method' => $alertMethod),
                array('alert_unit' => $this->oMain->_sPrefix, 'alert_action' => 'add_thumbnail', 'module_uri' => $this->oMain->_aModule['uri'], 'module_class' => 'Module', 'module_method' => $alertMethod),
                array('alert_unit' => $this->oMain->_sPrefix, 'alert_action' => 'change_thumbnail', 'module_uri' => $this->oMain->_aModule['uri'], 'module_class' => 'Module', 'module_method' => $alertMethod),
            ),
            'alerts' => array(
                array('unit' => $this->oMain->_sPrefix, 'action' => 'add_background'),
                array('unit' => $this->oMain->_sPrefix, 'action' => 'change_background'),
                array('unit' => $this->oMain->_sPrefix, 'action' => 'repositioned_background'),
                array('unit' => $this->oMain->_sPrefix, 'action' => 'remove_background'),
                array('unit' => $this->oMain->_sPrefix, 'action' => 'add_thumbnail'),
                array('unit' => $this->oMain->_sPrefix, 'action' => 'change_thumbnail'),
            )
        );
    }
    // EOF THE ALERT METHODS

    // getting the member name
    protected function getMemberName($profileId){
        $profileInfo = getProfileInfo($profileId);
        return (!empty($profileInfo['FirstName']) || !empty($profileInfo['LastName'])) 
            ? $profileInfo['FirstName'] . ' ' . $profileInfo['LastName'] 
            : $profileInfo['NickName'];
    }

    // checks if the member is a friend
    protected function isFriend($profileId, $loginId){
        $isFriends = is_friends($profileId, $loginId);
        return $isFriends;
    }

    // checks if the logged id has already sent an invite
    protected function memberIsInvited($loggedId, $profileId){
        return $this->isPendingFriendRequest($loggedId, $profileId);
    }

    // checks if the member was already added or has added the logged member as a friend
    protected function isPendingFriendRequest($memberId1, $memberId2){
        $checkMemberConnection = $this->oDb->checkMemberConnection($memberId1, $memberId2);
        if(isset($checkMemberConnection['Check']) && !$checkMemberConnection['Check']){
            return true;
        }else{
            return false;
        }
    }

    protected function getMemberUsername($profileId){
        return getNickname($profileId);
    }
}

// for dolphin 7.1 and above
class EmmetBytesProfileCover710UpHelper extends EmmetBytesProfileCoverDefaultHelper{
    var $loggedId;

    // constructor
    function EmmetBytesProfileCover710UpHelper($oMain){
        parent::EmmetBytesProfileCoverDefaultHelper($oMain);
    }

    // getting the profile cover
    function getProfileCover(){
        // displays on the profile page only
        if(!defined('BX_PROFILE_PAGE')){ return ''; }
        // the profile id 
        $profileId = getID($_GET['ID']);
        // logged id
        $loginId = $this->loggedId;
        // insert the css
        $this->oTemplate->addCss(array('main.css', 'form_adv.css'));
        // insert the javascript
        $this->oTemplate->addJs(array('EmmetBytesProfileCover.js', 'jquery.ui.datepicker.min.js','jquery.ui.all.min.js', 'jquery.ui.draggable.min.js'));
        $aVars = array(
            'profile_cover_top' => $this->getProfileCoverTopContents($profileId, $loginId),
            'background_menu_insert_caption' => _t('_emmetbytes_profile_cover_insert_background_caption'),
            'background_menu_change_caption' => _t('_emmetbytes_profile_cover_change_background_caption'),
            'profile_cover_bottom' => $this->getProfileCoverBottomContents($profileId, $loginId),
            'base_url' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri(),
            'background_form_url' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'submit_background_image',
            'background_menu_options_url' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'get_profile_cover_background_menu_options',
            'avatar_form_url' => BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'submit_avatar_image',
        );
        return $this->oTemplate->parseHTMLByName('main_ebytes_profile_cover_container', $aVars);

    }

    // getting the common friends request communicator class
    protected function commonFriendRequestCommunicatorClass(){
        bx_import('BxTemplCommunicator');
        $loggedId = $this->loggedId;
        $aCommunicatorSettings = array(
            'member_id' => $loggedId
        );
        return new BxTemplCommunicator(
            $aCommunicatorSettings
        );
    }

    // create the avatar
    protected function createAvatar($img, $profileId, $imageName){
        $createAvatar = BxDolService::call('avatar', 'make_avatar_from_image', array($img));
        if($createAvatar){
            if (BxDolRequest::serviceExists('photos', 'perform_photo_upload', 'Uploader')) {
                $imgPath = $this->profileCoverImageDir . 'orig_' . $imageName;
                $aFileInfo = array (
                    'medTitle' => _t('_bx_ava_avatar'),
                    'medDesc' => _t('_bx_ava_avatar'),
                    'medTags' => _t('_ProfilePhotos'),
                    'Categories' => array(_t('_ProfilePhotos')),
                    'album' => str_replace('{nickname}', getUsername($profileId), getParam('bx_photos_profile_album_name')),
                    'albumPrivacy' => BX_DOL_PG_ALL
                );
                BxDolService::call('photos', 'perform_photo_upload', array($imgPath, $aFileInfo, false, 0, true, $img), 'Uploader');
            }
        }
    }

    // getting the dolphin default avatar
    protected function getUserAvatarDatas($iId, $sType = 'emmetbytes'){
        $thumbType = getParam('sys_member_info_thumb');
        if($thumbType == 'bx_photos_thumb'){
            return $this->getDolphinDefaultProfilePhoto($iId, $sType);
        }else{
            return parent::getUserAvatarDatas($iId, $sType);
        }
    }

    // getting the default dolphin profile photo
    protected function getDolphinDefaultProfilePhoto($iId, $sType){
        $aProfile = getProfileInfo($iId);
        $ebProfilePhotoUrl = BxDolService::call('photos', 'profile_photo', array($aProfile['ID'], 'eb'), 'Search');
        $ebProfilePhotoDir = $this->getDolphinProfilePhotoDir($ebProfilePhotoUrl, 'eb');
        if($ebProfilePhotoDir){
            return array(
                'member_avatar_url' => $ebProfilePhotoUrl,
                'member_avatar_dir' => $ebProfilePhotoDir,
            );
        }else{
            $profilePhotoUrl = BxDolService::call('photos', 'profile_photo', array($aProfile['ID'], 'browse'), 'Search');
            $profilePhotoDir = $this->getDolphinProfilePhotoDir($profilePhotoUrl, 'browse');
            if($profilePhotoDir){
                return array(
                    'member_avatar_url' => $profilePhotoUrl,
                    'member_avatar_dir' => $profilePhotoDir,
                );
            }else{
                return $this->getThumbnailEmptySexPic($aProfile);
            }
        }
    }

    // getting the default dolphin profile photo directory
    protected function getDolphinProfilePhotoDir($profilePhotoUrl, $sType = 'eb'){
        $photoModule = BxDolModule::getInstance('BxPhotosModule');
        $profilePhotoUrlList = split('/', $profilePhotoUrl);
        $profilePhotoName = $profilePhotoUrlList[sizeof($profilePhotoUrlList) - 1];
        $sPointPos = strrpos($profilePhotoName, '.');
        $sKey = substr($profilePhotoName, 0, $sPointPos);
        $sExt = substr($profilePhotoName, $sPointPos + 1);
        $iId = $photoModule->_oDb->getIdByHash($sKey);
        $path = $photoModule->_oConfig->getFilesPath() . $iId . str_replace('{ext}', $sExt, $photoModule->_oConfig->aFilePostfix[$sType]);
        if(!is_file($path))
            return '';
        return $path;
    }

    protected function getMemberUsername($profileId){
        return getUsername($profileId);
    }
}

class EmmetBytesProfileCover711UpHelper extends EmmetBytesProfileCover710UpHelper{

    function EmmetBytesProfileCover712UpHelper($oMain){
        parent::EmmetBytesProfileCover710UpHelper($oMain);
    }

    protected function getAdsLink($profileId){
        $link = BX_DOL_URL_ROOT . 'ads/my_page';
        if($this->loggedId != $profileId){
            $link = BX_DOL_URL_ROOT . 'ads/member_ads/' . $profileId;
        }
        return $link;
    }

    protected function getBlogLink($profileId){
        $link = BX_DOL_URL_ROOT . 'blogs/my_page/';
        if($profileId != $this->loggedId){
            $link = BX_DOL_URL_ROOT . 'blogs/member_posts/' . $profileId;
        }
        return $link;
    }
}
?>
