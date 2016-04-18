<?php
/**********************************************************************************************
 * Created By : EmmetBytes Software Solutions
 * Created Date : February 20, 2013
 * Email : emmetbytes@gmail.com
 *
 * Copyright : (c) EmmetBytes Software Solutions 2012
 * Product Name : Club Cover
 * Product Version : 1.0
 * 
 * Important : This is a commercial product by EmmetBytes Software Solutions and 
 *   cannot be modified, redistributed or resold without any written permission 
 *   from EmmetBytes Software Solutions
 **********************************************************************************************/

function emmetbytes_club_cover_import ($sClassPostfix, $aModuleOverwright = array()) {
    global $aModule;
    $a = $aModuleOverwright ? $aModuleOverwright : $aModule;
    if (!$a || $a['uri'] != 'clubCover') {
        $oMain = BxDolModule::getInstance('EmmetBytesClubCoverModule');
        $a = $oMain->_aModule;
    }
    bx_import ($sClassPostfix, $a);
}

bx_import('BxDolTwigModule');
bx_import('BxDolPaginate');
bx_import('BxDolAlerts');
bx_import('BxDolPageView');

class EmmetBytesClubCoverModule extends BxDolTwigModule {
    var $logginId, $clubDatas, $clubCoverDatas;

    // constructor
    function EmmetBytesClubCoverModule(&$aModule) {
        parent::BxDolTwigModule($aModule);
        $this->_sFilterName = 'emmet_bytes_club_cover_filter';
        $this->_sPrefix = 'emmet_bytes_club_cover';

        bx_import ('Privacy', $aModule);
        $this->_oPrivacy = new EmmetBytesClubCoverPrivacy($this);

        $pageView = new BxDolPageView('modzzz_club_view');
        $this->backgroundWidth = $pageView->aPage['Width'];
        $this->backgroundHeight = 285;
        $this->backgroundFileSize = getParam('emmet_bytes_club_cover_background_size');

        $this->logoWidth = 166;
        $this->logoHeight = 166;
        $this->logoFileSize = getParam('emmet_bytes_club_cover_logo_size');
        $this->compressionLevel = 75;
        $this->clubCoverImageDir = '/media/now/datas/Projects/web/my_works/boonex_projects/boonex_modules/emmetbytes_club_cover/images/club_covers/';
        // $this->clubCoverImageDir = BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_club_cover/images/club_covers/';
        $this->profileCoverImageDir = '/media/now/datas/Projects/web/my_works/boonex_projects/boonex_modules/emmetbytes_profile_cover/images/profile_covers/';
        // $this->profileCoverImageDir = BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_profile_cover/images/profile_covers/';
        $GLOBALS['oEmmetBytesClubCoverModule'] = &$this;
    }

    // BOF THE ACTIONS
    // action home
    function actionHome () {
        Redirect(BX_DOL_URL_ROOT);
    }

    // submitting the temporary club cover background
    function actionSubmitTmpBackground(){
        $hasErrors = false;
        $returnVals = array();
        if(isset($_POST['club_id'])){
            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
            if($this->clubDatas['author_id'] != getLoggedId()){
                $returnVals['error'] = _t('_emmetbytes_club_cover_not_allowed_error');
                $hasErrors = true;
            }
        }else{
            $returnVals['error'] = _t('_emmetbytes_club_cover_background_image_upload_problem');
            $hasErrors = true;
        }
        if($hasErrors){
            echo json_encode($returnVals);
            exit;
        }
        $this->submitTmpBackground($_FILES);
    }

    // submitting the background image
    function actionSubmitBackgroundImage(){
        $this->submitBackgroundImage($_POST);
    }

    // reposition the image
    function actionRepositionImage(){
        $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
        $this->repositionBackground();
    }

    // remove the club cover background
    function actionRemoveImage(){
        $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
        $this->removeClubCoverBackground();
    }

    // getting the club cover background menu
    function actionGetClubCoverBackgroundMenuOptions(){
        if(isset($_POST['club_id'])){
            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
            $this->getClubCoverBackgroundMenuOptions();
        }
    }

    // submitting the temporary logo
    function actionSubmitTmpLogo(){
        $this->submitTmpLogo($_FILES);
    }

    // submitting the logo image
    function actionSubmitLogoImage(){
        $this->clubDatas = $this->_oDb->getEntryById($_REQUEST['club_id']);
        $this->submitLogoImage($_REQUEST);
    }

    // action get business website popup
    function actionGetBusinessWebsitePopup(){
        echo $this->getBusinessWebsitePopup();
    }

    // action get business email popup
    function actionGetBusinessEmailPopup(){
        echo $this->getBusinessEmailPopup();
    }

    // action get business telephone popup
    function actionGetBusinessTelephonePopup(){
        echo $this->getBusinessTelephonePopup();
    }

    // action get business fax popup
    function actionGetBusinessFaxPopup(){
        echo $this->getBusinessFaxPopup();
    }

    // action get club capacity popup
    function actionGetClubCapacityPopup(){
        echo $this->getClubCapacityPopup();
    }

    // action get club charge popup
    function actionGetClubChargePopup(){
        echo $this->getClubChargePopup();
    }

    // action get club entry age popup
    function actionGetClubEntryAgePopup(){
        echo $this->getClubEntryAgePopup();
    }

    // action get club hours popup
    function actionGetClubHoursPopup(){
        echo $this->getClubHoursPopup();
    }

    // action get club vip area popup
    function actionGetClubVIPAreaPopup(){
        echo $this->getClubVIPAreaPopup();
    }

    // action get club bar type popup
    function actionGetClubBarTypePopup(){
        echo $this->getClubBarTypePopup();
    }

    // action get club food service popup
    function actionGetClubFoodServicePopup(){
        echo $this->getClubFoodServicePopup();
    }

    // action to submit the stream url
    function actionUpdateStreamUrl(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
            $authorId = $this->clubDatas['author_id'];
            $loggedId = getLoggedId();
            if($authorId == $loggedId){
                if(isset($_POST['stream_url']) && !empty($_POST['stream_url'])){
                    $urlPattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
                    $streamUrl = $_POST['stream_url'];
                    if(preg_match($urlPattern, $streamUrl)){
                        $clubId = $this->clubDatas['id'];
                        $formData = array(
                            'club_id' => $clubId,
                            'column_name' => 'stream_url',
                            'value' => $streamUrl,
                        );
                        // update the headline
                        $success = $this->_oDb->updateClubData($formData);
                        if($success > 0){
                            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
                            $content = $this->getClubStreamPlayerContents();
                        }
                    }else{
                        $errors = _t('_emmetbytes_club_cover_stream_player_url_invalid_caption');
                    }
                }else{
                    $errors = _t('_emmetbytes_club_cover_stream_player_url_missing_caption');
                }
            }else{
                $errors = _t('_emmetbytes_club_cover_not_allowed_error');
            }

            if($errors){
                $data = array(
                    'has_errors' => 'true',
                    'error' => $errors,
                );
                $returnVal = json_encode($data);
            }else{
                $data = array(
                    'has_errors' => 'false',
                    'content' => $content,
                );
                $returnVal = json_encode($data);
            }

        }
        echo $returnVal;
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
                'title' => _t('_emmet_bytes_club_cover_administration_settings'), 
                'href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'administration/settings',
                '_func' => array ('name' => 'actionAdministrationSettings', 'params' => array()),
            ),
        );

        if (empty($aMenu[$sUrl]))
            $sUrl = 'settings';

        $aMenu[$sUrl]['active'] = 1;
        $sContent = call_user_func_array (array($this, $aMenu[$sUrl]['_func']['name']), $aMenu[$sUrl]['_func']['params']);

        echo $this->_oTemplate->adminBlock ($sContent, _t('_emmet_bytes_club_cover_administration'), $aMenu);
        $this->_oTemplate->addCssAdmin ('admin.css');
        $this->_oTemplate->addCssAdmin ('unit.css');
        $this->_oTemplate->addCssAdmin ('main.css');
        $this->_oTemplate->addCssAdmin ('forms_extra.css'); 
        $this->_oTemplate->addCssAdmin ('forms_adv.css');        
        $this->_oTemplate->pageCodeAdmin (_t('_emmet_bytes_club_cover_administration'));
    }

    // administration main settings
    function actionAdministrationSettings () {
        return parent::_actionAdministrationSettings ('ClubCover');
    }

    // getting the own images
    function actionGetOwnImage($path){
        return $this->getOwnImage($path);
    }

    // getting the popup datas
    function actionGetFanPopupDatas(){
        $profileId = $_POST['profile_id'];
        $this->getFanPopupDatas($profileId);
    }
    // EOF THE ACTIONS

    // BOF THE SERVICES
    // getting the club cover
    function serviceGetClubCover(){
        $uri = explode('/', $_GET['r']);
        if($uri[0] != 'club' || $uri[1] != 'view' || $uri[2] == '')
            return false;
        $GLOBALS['oSysTemplate']->addCss(array('plugins/jquery/themes/|jquery-ui.css'));

        $this->clubDatas = $this->_oDb->getEntryByUri($uri[2]);
        return $this->getClubCover();
    }

    // setting up the spy data
    function serviceGetSpyData(){
        // return $this->getHelper()->getSpyData();
    }

    // setting up the spy post
    function serviceGetSpyPost($sAction, $iObjectId, $iSenderId, $aExtraParams){
        // return $this->getHelper()->getSpyPost($sAction, $iObjectId, $iSenderId, $aExtraParams);
    }

    // setting up the wall data
    function serviceGetWallData(){
        // return $this->getHelper()->getWallData();
    }

    // setting up the wall post
    function serviceGetWallPost($aEvent){
        // return $this->getHelper()->getWallPost($aEvent);
    } 
    // EOF THE SERVICES

    // BOF THE GETTERS
    // getting the club cover
    private function getClubCover(){
        $this->loginId = getLoggedId();
        $this->_oTemplate->addCss(array('main.css', 'form_adv.css'));
        $this->_oTemplate->addJs(array('EmmetBytesClubCover.js', 'jquery.ui.datepicker.min.js','jquery.ui.all.min.js', 'jquery.ui.draggable.min.js'));

        $aVars = array(
            'club_id' => $this->clubDatas['id'],
            'club_cover_top' => $this->getClubCoverTopContents(),
            'background_menu_insert_caption' => _t('_emmetbytes_club_cover_insert_background_caption'),
            'background_menu_change_caption' => _t('_emmetbytes_club_cover_change_background_caption'),
            'club_cover_bottom' => $this->getClubCoverBottomContents(),
            'base_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(),
            'background_form_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'submit_background_image',
            'background_menu_options_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'get_club_cover_background_menu_options',
            'logo_form_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'submit_logo_image',
            'show_more_information_caption' => _t('_emmetbytes_club_cover_show_more_information_caption'),
            'show_less_information_caption' => _t('_emmetbytes_club_cover_show_less_information_caption'),
        );
        return $this->_oTemplate->parseHTMLByName('main_ebytes_club_cover_container', $aVars);

    }

    // getting the club cover top part
    private  function getClubCoverTopContents(){
        $clubCoverDatas = $this->getClubCoverDatas();
        $sAuthorLink = getProfileLink($this->clubDatas['author_id']);
        $sAuthorNick = getNickName($this->clubDatas['author_id']);
        $rating =  $this->getClubRate();
        $aVars = array(
            'extra_top_container_class' => (empty($clubCoverDatas['bg_image'])) ? 'ebytes_club_cover_top_container_no_background' : '',
            'bx_if:allow_change_cover' => array(
                'condition' => getLoggedId() == $this->clubDatas['author_id'] ? true : false,
                'content' => array(
                    'insert_background_caption' => (sizeof($clubCoverDatas) && !empty($clubCoverDatas['bg_image'])) ? _t('_emmetbytes_club_cover_change_background_caption') : _t('_emmetbytes_club_cover_insert_background_caption'),
                ),
            ),
            'bx_if:show_bg_image' => array(
                'condition' => !empty($clubCoverDatas['bg_image']) ? true : false,
                'content' => array(
                    'bg_image_path' => $clubCoverDatas['bg_image_cropped'],
                ),
            ),
            'bx_if:allow_logo_change' => array(
                'condition' => ($this->clubDatas['author_id'] == getLoggedId()) ? true : false,
                'content' => array(
                    'logo_button_caption' => _t('_emmetbytes_club_cover_insert_logo_caption'),
                    'form_logo_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'submit_tmp_logo',
                ),
            ),
            'club_logo' => $clubCoverDatas['club_logo'],
            'club_rate' => $rating[0],
            'club_views' => _t('_emmetbytes_club_cover_views_counter_caption', $this->clubDatas['views']),
            'club_name' => $this->clubDatas['title'],
            'author_info' => _t('_emmetbytes_club_cover_author_info', $sAuthorLink, $sAuthorNick),
            'member_buttons' => $this->getClubButtons(),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_top_container', $aVars);
    }

    // getting the club cover datas
    private function getClubCoverDatas(){
        $this->clubCoverDatas = $this->_oDb->getClubCoverDatasByClubId($this->clubDatas['id']);
        $dClubCoverDatas = array(
            'id' => $this->clubDatas['id'],
            'bg_image_name' => '',
            'bg_image' => '',
            'bg_image_cropped' => '',
            'bg_pos_y' => '', 
            'bg_pos_x' => '', 
            'logo_image' => '', 
            'logo_image_cropped' => '', 
            't_pos_y' => '', 
            't_pos_x' => '',
            'club_title' => $this->clubDatas['title'],
        );
        // sets up the club cover background datas
        if(!empty($this->clubCoverDatas['background_image'])){
            $backgroundImage = $this->clubCoverDatas['background_image'];
            $backgroundImagePath = $this->clubCoverImageDir . $backgroundImage;
            $backgroundImageUrl = $this->getClubCoverOwnImages($backgroundImagePath);
            $backgroundImageCroppedPath = $this->clubCoverImageDir . 'crop_' . $backgroundImage;
            $backgroundImageCroppedUrl = $this->getClubCoverOwnImages($backgroundImageCroppedPath);
            $dClubCoverDatas['bg_image_name'] = $backgroundImage;
            $dClubCoverDatas['bg_image'] = $backgroundImageUrl[0];
            $dClubCoverDatas['bg_image_path'] = $backgroundImagePath;
            $dClubCoverDatas['bg_image_cropped'] = $backgroundImageCroppedUrl[0] . '?'. time();
            $dClubCoverDatas['bg_image_cropped_path'] = $backgroundImageCroppedPath;
            $dClubCoverDatas['bg_pos_y'] = $this->clubCoverDatas['bg_pos_y'];
            $dClubCoverDatas['bg_pos_x'] = $this->clubCoverDatas['bg_pos_x'];
        }

        // sets up the club cover logo datas
        $dClubCoverDatas['club_logo'] = $this->getClubLogo();
        return $dClubCoverDatas;
    }

    // getting the club logo
    private function getClubLogo(){
        $aVars = $this->getClubLogoFullInfo();
        return $this->_oTemplate->parseHTMLByName('ebytes_club_logo_container', $aVars);
    }

    // getting the club logo full info
    private function getClubLogoFullInfo(){
        $clubLogo = $clubLogoDir = '';
        if($this->clubDatas['icon']){
            if(file_exists($this->_oConfig->getClubModuleMediaDir() . 'eb_' . $this->clubDatas['icon'])){
                $clubLogo = $this->_oConfig->getClubModuleMediaUrl() . 'eb_' . $this->clubDatas['icon'];
                $clubLogoDir = $this->_oConfig->getClubModuleMediaDir() . 'eb_' . $this->clubDatas['icon'];
            }else{
                $clubLogo = $this->_oConfig->getClubModuleMediaUrl() . $this->clubDatas['icon'];
                $clubLogoDir = $this->_oConfig->getClubModuleMediaDir() . $this->clubDatas['icon'];
            }
        }else if($this->clubDatas['thumb']){
            $aImage = BxDolService::call(
                'photos', 
                'get_entry', 
                array($this->clubDatas['thumb'], 'browse'),
                'Search');
            $clubLogo = $aImage['file'];
            $clubLogoDir = $aImage['path'];
        }else{
            $clubLogo = $GLOBALS['oSysTemplate']->getImageUrl('no-image-thumb.png');
            $clubLogoDir = $GLOBALS['oSysTemplate']->getImagePath('no-image-thumb.png');
        }

        list($iw, $ih) = getimagesize($clubLogoDir);
        $leftMargin = ($this->logoWidth - $iw) / 2;
        $topMargin = ($this->logoHeight - $ih) / 2;
        return array(
            'club_logo' => $clubLogo,
            'width' => $iw,
            'height' => $ih,
            'margin_left' => $leftMargin,
            'margin_top' => $topMargin,
        );
    }

    // getting the background menu options
    private function getClubCoverBackgroundMenuOptions(){
        echo $this->getInsertBackgroundOptionsContents();
    }

    // getting the club rate
    function getClubRate(){
        bx_import('BxClubVoting');
        $o = new BxClubVoting ('modzzz_club', (int)$this->clubDatas['id']);
        if (!$o->isEnabled()) return '';
        return array($o->getBigVoting(false));
    }

    // getting the insert background options
    private function getInsertBackgroundOptionsContents(){
        $clubCoverDatas = $this->getClubCoverDatas();
        $aVars = array(
            'bx_repeat:background_options' => array(
                $this->getUploadForm(), 
                $this->getRepositionForm($clubCoverDatas),
                $this->getRemoveForm($clubCoverDatas),
            ),
        );
        return $this->_oTemplate->parseHTMLByName(
            'ebytes_club_cover_insert_background_options', 
            $aVars
        );
    }

    // getting the background uploader form
    private function getUploadForm(){
        $aVars = array(
            'caption' => _t('_emmetbytes_club_cover_upload_background_caption'),
            'class' => 'ebytes_club_cover_upload_background_container',
            'club_id' => $this->clubDatas['id'],
            'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'submit_tmp_background',
        );
        return array(
            'content' => $this->_oTemplate->parseHTMLByName( 'ebytes_club_cover_background_uploader_container', $aVars),
        );
    }

    // getting the reposition form
    private function getRepositionForm($clubCoverDatas){
        $content = array( 'content' => '');
        if(sizeof($clubCoverDatas) && !empty($clubCoverDatas['bg_image'])){
            $aVars = array(
                'caption' => _t('_emmetbytes_club_cover_reposition_background_caption'),
                'class' => 'ebytes_club_cover_reposition_background_container',
                'data_id' => $clubCoverDatas['id'],
                'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'reposition_image',
            );
            $content['content'] = $this->_oTemplate->parseHTMLByName('ebytes_club_cover_background_reposition_container', $aVars);
        }
        return $content;
    }

    // getting the remove form
    private function getRemoveForm($clubCoverDatas){
        $content = array( 'content' => '');
        if(sizeof($clubCoverDatas) && !empty($clubCoverDatas['bg_image'])){
            $aVars = array(
                'caption' => _t('_emmetbytes_club_cover_remove_background_caption'),
                'class' => 'ebytes_club_cover_remove_background_container',
                'data_id' => $clubCoverDatas['id'],
                'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'remove_image',
            );
            $content['content'] = $this->_oTemplate->parseHTMLByName('ebytes_club_cover_background_remove_container', $aVars);
        }
        return $content;
    }

    // submitting the temporary background
    private function submitTmpBackground($formParams){
        $returnVals = array();
        $this->compressionLevel = (int)getParam('emmet_bytes_club_cover_club_cover_background_compr_level');
        $ext = explode('.', $formParams['background_image']['name']);
        $ext = $ext[sizeof($ext) - 1];
        $imageName = getLoggedId() . '_' . time() . '.' . $ext;
        $checkBackgroundFilters = $this->checkBackgroundImage($formParams);
        $hasError = $checkBackgroundFilters['has_error'];
        $returnVals['error'] = $checkBackgroundFilters['error'];
        $clubCoverImgDir = $this->clubCoverImageDir . $imageName;
        if(!$hasError && move_uploaded_file($formParams['background_image']['tmp_name'], $clubCoverImgDir)){
            chmod($clubCoverImgDir, 0777);
            $resized = $this->imageResize($clubCoverImgDir, $this->backgroundWidth, $this->backgroundHeight);
            if($resized){
                $imagePath = $this->getClubCoverOwnImages($clubCoverImgDir);
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
    private function checkBackgroundImage($formParams){
        $returnVals = array('has_error' => false, 'error' => '',);
        $fileSize = $formParams['background_image']['size'];
        list($width, $height) = getimagesize($formParams['background_image']['tmp_name']);
        if($width < $this->backgroundWidth || $height < $this->backgroundHeight){
            $returnVals['has_error'] = true;
            $returnVals['error'] = _t('_emmetbytes_club_cover_background_image_size_problem', $this->backgroundWidth, $this->backgroundHeight);
        }else if($this->backgroundFileSize < $fileSize){
            $returnVals['has_error'] = true;
            $returnVals['error'] =  _t('_emmetbytes_club_cover_background_image_file_size_problem', $this->backgroundFileSize);
        }
        return $returnVals;
    }

    // resize the image
    private function imageResize($imagePath, $defaultWidth, $defaultHeight){
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

    // getting the club cover own images
    private function getClubCoverOwnImages($imagePath){
        $imageUrlPath = base64_encode($imagePath);
        return array(BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'get_own_image/' . $imageUrlPath);
    }

    // getting the default image
    function getOwnImage($defaultImage){
        $image = base64_decode($defaultImage);
        $imageInfo = getimagesize($image);
        header('Content-Type: ' . $imageInfo['mime']);
        echo readfile($image);
    }

    // submitting the images
    private function submitBackgroundImage(){
        $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
        if($this->clubDatas['author_id'] == getLoggedId() && $_POST['submit'] == 'Save'){ 
            $retVal = $this->saveClubCoverBgImgDatas($_POST);
        }else{ 
            $retVal = $this->cancelClubCoverBgImgDatas($_POST);
        }
        echo json_encode($retVal);
    }

    private function saveClubCoverBgImgDatas($datas){
        $this->compressionLevel = (int)getParam('emmet_bytes_club_cover_club_cover_background_compr_level');
        // initialize variables
        $club_id = $datas['club_id'];
        $imageName = $datas['image_name'];
        $srcX = abs($datas['x_pos']);
        $srcY = abs($datas['y_pos']);
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
        $clubData = $this->_oDb->getClubCoverDatasByClubId($club_id);
        $queryParams = array(
            'club_id' => $club_id,
            'background_image' => $imageName,
            'bg_pos_x' => $srcX,
            'bg_pos_y' => $srcY
        );
        if(sizeof($clubData)){
            $this->_oDb->updateBackgroundInfo($queryParams);
            $action = 'change_background';
            $entryId = $clubData['id']; 
        }else{
            $entryId = $this->_oDb->insertBackgroundInfo($queryParams);
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
        // $this->alert($alertParams);
        $retVal = $this->getClubCoverDatas();
        $retVal['action'] = 'insert';
        return $retVal;
    }

    // cancel the insertion of the new club cover background image
    private function cancelClubCoverBgImgDatas($datas){
        $imagePath = $this->clubCoverImageDir . $datas['image_name'];
        if($datas['fresh'] == 'true'){
            $this->removeImage($imagePath);
        }
        $clubCoverImgDatas = $this->getClubCoverDatas();
        $retVal = array(
            'hasData' => true,
            'action' => 'cancel',
        );
        if(sizeof($clubCoverImgDatas) && !empty($clubCoverImgDatas['bg_image'])){
            $retVal = array_merge($retVal, $clubCoverImgDatas);
        }else{
            $retVal['hasData'] = false;
        }
        return $retVal;
    }
    // crop the club cover image
    private function cropImage($imageParams){
        // initialize image parameter
        $imgName = $imageParams['image_name'];
        $imgWidth = $imageParams['image_width'];
        $imgHeight = $imageParams['image_height'];
        $srcX = $imageParams['src_x'];
        $srcY = $imageParams['src_y'];
        $tmpImagePath = $this->clubCoverImageDir . $imgName;
        $imagePath = $this->clubCoverImageDir . 'crop_' . $imgName;
        $imageSize = getimagesize($tmpImagePath);
        $imageRsrc = $this->getImageResource($tmpImagePath);
        $newImage = imagecreatetruecolor($imgWidth, $imgHeight);
        imagecopyresampled($newImage, $imageRsrc, 0, 0, $srcX, $srcY, $imageSize[0], $imageSize[1], $imageSize[0], $imageSize[1]);
        imagejpeg($newImage, $imagePath, (int)$this->compressionLevel);
        chmod($imagePath, 0777);
        return $imagePath;
    }

    // getting the image resource
    private function getImageResource($imagePath){
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

    // resize the images
    private function resizeImage($imageParams){
        $imageName = $imageParams['image_name'];
        $imagePath = $imageParams['image_path'];
        $newImagePath = $this->clubCoverImageDir . 'small_' . $imageName;
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

    private function removeImage($imagePath){
        unlink($imagePath);
    }

    // reposition the background
    function repositionBackground(){
        $clubCoverDatas = $this->getClubCoverDatas();    
        echo json_encode($clubCoverDatas);
        exit;
    }

    function removeClubCoverBackground(){
        $clubCoverImgDatas = $this->getClubCoverDatas();
        $origImagePath = $clubCoverImgDatas['bg_image_path'];
        $croppedImagePath = $clubCoverImgDatas['bg_image_cropped_path'];
        // remove the original image
        $this->removeImage($origImagePath);
        // remove the cropped image path
        $this->removeImage($croppedImagePath);
        // alert parameters
        $alertParams = array(
            'action' => 'remove_background',
            'entry_id' => $clubCoverImgDatas['id'],
            'profile_id' => $profileId,
            'status' => 'approved',
        );
        // $this->alert($alertParams);
        // remove the database data
        $removed = $this->_oDb->removeBackgroundClubCoverByClubId($clubCoverImgDatas['id']);
    }

    // submitting the temporary logo
    function submitTmpLogo($formParams){
        $returnVals = array();
        $ext = explode('.', $formParams['logo_image']['name']);
        $ext = $ext[sizeof($ext) - 1];
        $imageName = 'logo_' . getLoggedId() . '_' . time() . '.' . $ext;
        $checkBackgroundFilters = $this->checkLogoImage($formParams);
        $hasError = $checkBackgroundFilters['has_error'];
        $returnVals['error'] = $checkBackgroundFilters['error'];
        $clubCoverImgDir = $this->clubCoverImageDir . $imageName;
        $clubCoverImgOrigDir = $this->clubCoverImageDir . 'orig_' . $imageName;
        if(!$hasError && move_uploaded_file($formParams['logo_image']['tmp_name'], $clubCoverImgDir)){
            chmod($clubCoverImgDir, 0777);
            $clubCoverImgOrigDir = $this->clubCoverImageDir . 'orig_' . $imageName;
            copy($clubCoverImgDir, $clubCoverImgOrigDir);
            $resized = $this->imageResize($clubCoverImgDir, $this->logoWidth, $this->logoHeight);
            if($resized){
                $imagePath = $this->getClubCoverOwnImages($clubCoverImgDir);
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

    // method that checks the logo image
    protected function checkLogoImage($formParams){
        $returnVals = array('has_error' => false, 'error' => '',);
        $fileSize = $formParams['logo_image']['size'];
        list($width, $height) = getimagesize($formParams['logo_image']['tmp_name']);
        if($width < $this->logoWidth || $height < $this->logoHeight){
            $returnVals['has_error'] = true;
            $returnVals['error'] = _t('_emmetbytes_club_cover_background_image_size_problem', $this->logoWidth, $this->logoHeight);
        }else if($this->logoFileSize < $fileSize){
            $returnVals['has_error'] = true;
            $returnVals['error'] =  _t('_emmetbytes_club_cover_background_image_file_size_problem', $this->logoFileSize);
        }
        return $returnVals;

    }

    // submitting the avatar image
    private function submitLogoImage($formData){
        $this->insertLogo($formData);
    }

    protected function insertLogo($formData){
        // initialize variables
        $clubId = $formData['club_id'];
        $imageName = $formData['image_name'];
        $srcX = abs($formData['x_pos']);
        $srcY = abs($formData['y_pos']);
        $loggedId = getLoggedId();
        // image parameters
        $imageParams = array(
            'image_name' => $imageName, 
            'image_width' => $this->logoWidth,
            'image_height' => $this->logoHeight,
            'src_x' => $srcX,
            'src_y' => $srcY,
        );
        $imagePath = $this->cropImage($imageParams);
        $clubData = $this->_oDb->getClubCoverDatasByClubId($formData['club_id']);
        // query parameters
        $queryParams = array(
            'club_id' => $clubId,
            'club_logo' => $imageName,
            't_pos_x' => $srcX,
            't_pos_y' => $srcY
        );
        if($formData['submit'] == 'Save' && $this->createClubLogo($imagePath, $clubId, $imageName)){
            if(sizeof($clubData)){
                $this->_oDb->updateLogoInfo($queryParams);
            }else{
                $entryId = $this->_oDb->insertLogoInfo($queryParams);
            }
            $imagePath = $this->getClubCoverOwnImages($imagePath);
            $returnVal = array(
                'action' => 'save',
                'image_path' => $imagePath
            );
        }else{
            $clubCoverDatas = $this->getClubLogoFullInfo();
            $returnVal = array(
                'action' => 'cancel',
                'image_path' => $clubCoverDatas['club_logo'],
            );
            $returnVal = array_merge($returnVal, $clubCoverDatas);
        }
        echo json_encode($returnVal);
    }

    protected function createClubLogo($img, $clubId, $imageName){
        $ebImagePath = $this->_oConfig->getClubModuleMediaDir() . 'eb_' . $imageName;
        if(copy($img, $ebImagePath)){
            $imagePath = $this->_oConfig->getClubModuleMediaDir() . $imageName;
            $iIconWidth = (int)getParam("modzzz_club_icon_width");
            $iIconHeight = (int)getParam("modzzz_club_icon_height"); 
            imageResize($ebImagePath, $imagePath, $iIconWidth, $iIconHeight);
            $params = array(
                'club_id' => $clubId,
                'logo' => $imageName
            );
            $this->_oDb->updateClubModuleLogo($params);
            return true;
        }
        return false;
    }

    // getting the club buttons
    protected function getClubButtons(){
        $buttons = $this->getClubTeleportNowButton();
        $buttons .= $this->getClubBecomeAFanButton();
        $buttons .= $this->getClubSubscribeButton();
        return $buttons;
    }

    // teleport now button
    private function getClubTeleportNowButton(){
        $url = $this->clubDatas['url'];
        $aVars = array(
            'caption' => _t('_emmetbytes_club_cover_teleport_now'),
            'target' => '_blank',
            'link' => $url,
            'function' => '',
            'eb_link' => '',
            'eb_action' => '',
            'class' => 'ebytes_club_cover_teleport_now',
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_member_button_container', $aVars);
    }

    // become a fan button
    private function getClubBecomeAFanButton(){
        if(!$this->isAllowedJoin())
            return false;

        $isFan = $this->_oDb->isFan((int)$this->clubDatas['id'], getLoggedId(), 0) || $this->_oDb->isFan((int)$this->clubDatas['id'], getLoggedId(), 1);
        $leaveText = _t('_modzzz_club_action_title_leave');
        $joinText = _t('_modzzz_club_action_title_join');
        $aVars = array(
            'caption' => $isFan ? $leaveText : $joinText,
            'link' => '#',
            'target' => '_self',
            'function' => 'onclick="ebClubCoverBecomeAFanButton(this, \'' . $leaveText . '\', \'' . $joinText . '\'); return false;"',
            'eb_link' => 'eb_link=' . $this->getClubBaseUrl() . 'join/' . $this->clubDatas['id'] . '/' . getLoggedId(),
            'eb_action' => 'eb_action="add"',
            'class' => 'ebytes_club_cover_add_friend',
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_member_button_container', $aVars);
    }

    // subscribe button
    private function getClubSubscribeButton(){

        $oSubscription = new BxDolSubscription();
        $aSubscribeButton = $oSubscription->getButton(getLoggedId(), 'modzzz_club', '', (int)$this->clubDatas['id']);
        $unSubText = _t('_sys_btn_sbs_unsubscribe');
        $subText = _t('_sys_btn_sbs_subscribe');
        $aVars = array(
            'caption' => $aSubscribeButton['title'],
            'link' => '#',
            'target' => '_self',
            'function' => 'onclick="ebClubCoverSubscriptionButton(this, \'' . $subText . '\', \'' . $unSubText .'\', \'' . getLoggedId() . '\', \'' . $this->clubDatas['id'] . '\'); return false;"',
            'eb_link' => '',
            'eb_action' => '',
            'class' => 'club_cover_subscription_button',
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_member_button_container', $aVars);
    }

    private function isAllowedJoin(){
        if(getLoggedId() <= 0)
            return false;
        return $this->_oPrivacy->check('join', $this->clubDatas['id'], getLoggedId());
    }

    // getting the bottom contents
    private function getClubCoverBottomContents(){
        $clubInformations = $this->getClubInformations();
        $clubAddonDatas = $this->getClubAddonDatas();
        $streamPlayer = $this->getClubStreamPlayer();
        $streamPlayerAddonClass = $mainContainerAddonClass = $toggleContainerAddonClass = $addon_datasContainerAddonClass = '';
        $displayContainerCount = $clubAddonDatas['displayed_addon_datas_container_count'];
        if($displayContainerCount <= 0){
            $mainContainerAddonClass = 'ebytes_club_cover_empty_addon_datas';
            $toggleContainerAddonClass = 'ebytes_club_cover_one_line_addon_data';
            $addon_datasContainerAddonClass = 'ebytes_club_cover_no_addon_datas';
        }else if($displayContainerCount == 1){
            $mainContainerAddonClass = $addon_datasContainerAddonClass = 'ebytes_club_cover_one_addon_data';
            $toggleContainerAddonClass = 'ebytes_club_cover_one_line_addon_data';
        }else if($displayContainerCount == 2){
            $mainContainerAddonClass = $addon_datasContainerAddonClass = 'ebytes_club_cover_two_addon_data';
            $toggleContainerAddonClass = 'ebytes_club_cover_one_line_addon_data';
        }else if($displayContainerCount == 3){
            $mainContainerAddonClass = $addon_datasContainerAddonClass = 'ebytes_club_cover_three_addon_data';
            $toggleContainerAddonClass = 'ebytes_club_cover_one_line_addon_data';
        }else if($displayContainerCount == 4){
            $mainContainerAddonClass = $addon_datasContainerAddonClass = 'ebytes_club_cover_four_addon_data';
            $toggleContainerAddonClass = 'ebytes_club_cover_one_line_addon_data';
        }
        $aVars = array(
            'club_informations' => $clubInformations,
            'club_addon_datas' => $clubAddonDatas['container'],
            'bx_if:display_club_stream_player_container' => array(
                'condition' => ($streamPlayer != ''),
                'content' => array(
                    'club_stream_player_container' => $streamPlayer,
                ),
            ),
            'info_container_addon_class' => $mainContainerAddonClass,
            'toggle_container_addon_class' => $toggleContainerAddonClass,
            'stream_player_addon_class' => $streamPlayerAddonClass,
            'addon_datas_container_addon_class' => $addon_datasContainerAddonClass,
            'base_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_bottom_container', $aVars);
    }

    // getting the member informations
    private function getClubInformations(){
        $aVars = array(
            // display the business website
            'bx_if:display_business_website' => array(
                'condition' => $this->displayBusinessWebsite(),
                'content' => array(
                    'business_website_container' => $this->getBusinessWebsiteContainer(), 
                ),
            ),
            // display the insert busines website
            'bx_if:display_insert_business_website' => array(
                'condition' => (!$this->hasBusinessWebsite() && $this->isOwner()),
                'content' => array(
                    'insert_business_website' => _t('_emmetbytes_club_cover_insert_business_website_caption'),
                ),
            ),
            // display the business email
            'bx_if:display_business_email' => array(
                'condition' => $this->displayBusinessEmail(),
                'content' => array(
                    'business_email_container' => $this->getBusinessEmailContainer(),
                ),
            ),
            // display the insert busines email
            'bx_if:display_insert_business_email' => array(
                'condition' => (!$this->hasBusinessEmail() && $this->isOwner()),
                'content' => array(
                    'insert_business_email' => _t('_emmetbytes_club_cover_business_insert_email_caption'),
                ),
            ),
            // display the business telephone
            'bx_if:display_business_telephone' => array(
                'condition' => $this->displayBusinessPhoneNumber(),
                'content' => array(
                    'business_telephone_container' => $this->getBusinessTelephoneContainer(),
                ),
            ),
            // display the insert busines telephone
            'bx_if:display_insert_business_telephone' => array(
                'condition' => (!$this->hasBusinessPhoneNumber() && $this->isOwner()),
                'content' => array(
                    'insert_business_telephone' => _t('_emmetbytes_club_cover_business_insert_telephone_caption'),
                ),
            ),
            // display the business fax
            'bx_if:display_business_fax' => array(
                'condition' => $this->displayBusinessFaxNumber(),
                'content' => array(
                    'business_fax_container' => $this->getBusinessFaxContainer(),
                ),
            ),
            // display the insert busines fax
            'bx_if:display_insert_business_fax' => array(
                'condition' => (!$this->hasBusinessFaxNumber() && $this->isOwner()),
                'content' => array(
                    'insert_business_fax' => _t('_emmetbytes_club_cover_insert_business_fax_caption'),
                ),
            ),
            // display the club capacity
            'bx_if:display_club_capacity' => array(
                'condition' => $this->displayClubCapacity(),
                'content' => array(
                    'club_capacity_container' => $this->getClubCapacityContainer(),
                ),
            ),
            // display the insert club capacity
            'bx_if:display_insert_club_capacity' => array(
                'condition' => (!$this->hasClubCapacity() && $this->isOwner()),
                'content' => array(
                    'insert_club_capacity' => _t('_emmetbytes_club_cover_insert_club_capacity_caption'),
                ),
            ),
            // display the club cover_charge
            'bx_if:display_club_cover_charge' => array(
                'condition' => $this->displayClubCoverCharge(),
                'content' => array(
                    'club_cover_charge_container' => $this->getClubCoverChargeContainer(),
                ),
            ),
            // display the insert club cover_charge
            'bx_if:display_insert_club_cover_charge' => array(
                'condition' => (!$this->hasClubCoverCharge() && $this->isOwner()),
                'content' => array(
                    'insert_club_cover_charge' => _t('_emmetbytes_club_cover_insert_club_charge_caption'),
                ),
            ),
            // display the club entry_age
            'bx_if:display_club_entry_age' => array(
                'condition' => $this->displayClubEntryAge(),
                'content' => array(
                    'club_entry_age_container' => $this->getClubEntryAgeContainer(),
                ),
            ),
            // display the insert club entry_age
            'bx_if:display_insert_club_entry_age' => array(
                'condition' => (!$this->hasClubEntryAge() && $this->isOwner()),
                'content' => array(
                    'insert_club_entry_age' => _t('_emmetbytes_club_cover_insert_club_entry_age_caption'),
                ),
            ),
            // display the club hours
            'bx_if:display_club_hours' => array(
                'condition' => $this->displayClubHours(),
                'content' => array(
                    'club_hours_container' => $this->getClubHoursContainer(),
                ),
            ),
            // display the insert club hours
            'bx_if:display_insert_club_hours' => array(
                'condition' => (!$this->hasClubHours() && $this->isOwner()),
                'content' => array(
                    'insert_club_hours' => _t('_emmetbytes_club_cover_insert_club_hours_caption'),
                ),
            ),
            // display the club vip_area
            'bx_if:display_club_vip_area' => array(
                'condition' => $this->displayClubVIPArea(),
                'content' => array(
                    'club_vip_area_container' => $this->getClubVIPAreaContainer(),
                ),
            ),
            // display the insert club vip_area
            'bx_if:display_insert_club_vip_area' => array(
                'condition' => (!$this->hasClubVIPArea() && $this->isOwner()),
                'content' => array(
                    'insert_club_vip_area' => _t('_emmetbytes_club_cover_insert_club_vip_area_caption'),
                ),
            ),
            // display the club bar_type
            'bx_if:display_club_bar_type' => array(
                'condition' => $this->displayClubBarType(),
                'content' => array(
                    'club_bar_type_container' => $this->getClubBarTypeContainer(),
                ),
            ),
            // display the insert club bar_type
            'bx_if:display_insert_club_bar_type' => array(
                'condition' => (!$this->hasClubBarType() && $this->isOwner()),
                'content' => array(
                    'insert_club_bar_type' => _t('_emmetbytes_club_cover_insert_club_bar_type_caption'),
                ),
            ),
            // display the club food_service
            'bx_if:display_club_food_service' => array(
                'condition' => $this->displayClubFoodService(),
                'content' => array(
                    'club_food_service_container' => $this->getClubFoodServiceContainer(),
                ),
            ),
            // display the insert club food_service
            'bx_if:display_insert_club_food_service' => array(
                'condition' => (!$this->hasClubFoodService() && $this->isOwner()),
                'content' => array(
                    'insert_club_food_service' => _t('_emmetbytes_club_cover_insert_club_food_service_caption'),
                ),
            ),
            'show_more_caption' => _t('_emmetbytes_club_cover_show_more_information_caption'),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_informations_container', $aVars);
    }
    // EOF THE GETTERS

    // container for the business website
    private function getBusinessWebsiteContainer(){
        $aVars = array(
            'business_website' => $this->hasBusinessWebsite() ? 
                _t('_emmetbytes_club_cover_business_website_caption', $this->createAnchorTag($this->clubDatas['businesswebsite']))
                : _t('_emmetbytes_club_cover_business_website_caption', _t('_emmetbytes_club_cover_not_available_caption'))
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_business_website_container', $aVars);
    }

    // container for the business email
    private function getBusinessEmailContainer(){
        $aVars = array(
                'business_email' => $this->hasBusinessEmail() ? 
                   _t('_emmetbytes_club_cover_business_email_caption', $this->clubDatas['businessemail'])
                   : _t('_emmetbytes_club_cover_business_email_caption', _t('_emmetbytes_club_cover_not_available_caption')),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_business_email_container', $aVars);
    }

    // container for the business telephone
    private function getBusinessTelephoneContainer(){
        $aVars = array(
                'business_telephone' => $this->hasBusinessPhoneNumber() ? 
                    _t('_emmetbytes_club_cover_business_telephone_caption', $this->clubDatas['businesstelephone'])
                    : _t('_emmetbytes_club_cover_business_telephone_caption', _t('_emmetbytes_club_cover_not_available_caption')),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_business_telephone_container', $aVars);
    }

    // container for the business fax
    private function getBusinessFaxContainer(){
        $aVars = array(
            'business_fax' => $this->hasBusinessFaxNumber() ? 
               _t('_emmetbytes_club_cover_business_fax_caption', $this->clubDatas['businessfax'])
               : _t('_emmetbytes_club_cover_business_fax_caption', _t('_emmetbytes_club_cover_not_available_caption')),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_business_fax_container', $aVars);
    }

    // container for the club capacity container
    private function getClubCapacityContainer(){
        $aVars = array(
                'club_capacity' => $this->hasClubCapacity() ? 
                    _t('_emmetbytes_club_cover_club_capacity_caption', $this->clubDatas['capacity'])
                    : _t('_emmetbytes_club_cover_club_capacity_caption', _t('_emmetbytes_club_cover_not_available_caption')),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_club_capacity_container', $aVars);
    }

    // container for the club charge
    private function getClubCoverChargeContainer(){
        $aVars = array(
                'club_cover_charge' => $this->hasClubCoverCharge() ? 
                   _t('_emmetbytes_club_cover_club_charge_caption', $this->clubDatas['cover_charge'])
                   : _t('_emmetbytes_club_cover_club_charge_caption', _t('_emmetbytes_club_cover_not_available_caption')),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_club_charge_container', $aVars);
    }

    // container for the entry age
    private function getClubEntryAgeContainer(){
        $aVars = array(
                'club_entry_age' => $this->hasClubEntryAge() ? 
                    _t('_emmetbytes_club_cover_club_entry_age_caption', $this->clubDatas['entry_age'])
                    : _t('_emmetbytes_club_cover_club_entry_age_caption', _t('_emmetbytes_club_cover_not_available_caption')),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_club_entry_age_container', $aVars);
    }

    // container for the hours
    private function getClubHoursContainer(){
        $aVars = array(
                'club_hours' => $this->hasClubHours() ? 
                    _t('_emmetbytes_club_cover_club_hours_caption', $this->clubDatas['hours'])
                    : _t('_emmetbytes_club_cover_club_hours_caption', _t('_emmetbytes_club_cover_not_available_caption')),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_club_hours_container', $aVars);
    }

    // container for the vip area
    private function getClubVIPAreaContainer(){
        $aVars = array(
                'club_vip_area' => $this->hasClubVIPArea() ? 
                    _t('_emmetbytes_club_cover_club_vip_area_caption', $this->clubDatas['vip_area'])
                    : _t('_emmetbytes_club_cover_club_vip_area_caption', _t('_emmetbytes_club_cover_not_available_caption')),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_club_vip_area_container', $aVars);
    }

    // container for the vip area
    private function getClubBarTypeContainer(){
        $aVars = array(
                'club_bar_type' => $this->hasClubBarType() ? 
                    _t('_emmetbytes_club_cover_club_bar_type_caption', $this->clubDatas['bar_type'])
                    : _t('_emmetbytes_club_cover_club_bar_type_caption', _t('_emmetbytes_club_cover_not_available_caption')),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_club_bar_type_container', $aVars);
    }

    // container for the vip area
    private function getClubFoodServiceContainer(){
        $aVars = array(
                'club_food_service' => $this->hasClubFoodService() ? 
                    _t('_emmetbytes_club_cover_club_food_service_caption', $this->clubDatas['food_service'])
                    : _t('_emmetbytes_club_cover_club_food_service_caption', _t('_emmetbytes_club_cover_not_available_caption')),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_club_food_service_container', $aVars);
    }

    private function createAnchorTag($url, $value = ''){
        if($value == '')
            $value = $url;
        return "<a href='$url'>$value</a>";
    }

    private function displayBusinessWebsite(){
        if($this->hasBusinessWebsite() || !$this->isOwner())
            return true;
    }
    
    private function hasBusinessWebsite(){
        if(!empty($this->clubDatas['businesswebsite']))
            return true;
    }

    private function displayBusinessEmail(){
        if($this->hasBusinessEmail() || !$this->isOwner())
            return true;
    }

    private function hasBusinessEmail(){
        if(!empty($this->clubDatas['businessemail']))
            return true;
    }

    private function displayBusinessPhoneNumber(){
        if($this->hasBusinessPhoneNumber() || !$this->isOwner())
            return true;
    }

    private function hasBusinessPhoneNumber(){
        if(!empty($this->clubDatas['businesstelephone']))
            return true;
    }

    private function displayBusinessFaxNumber(){
        if($this->hasBusinessFaxNumber() || !$this->isOwner())
            return true;
    }

    private function hasBusinessFaxNumber(){
        if(!empty($this->clubDatas['businessfax']))
            return true;
    }

    // club capacity
    private function displayClubCapacity(){
        if($this->hasClubCapacity() || !$this->isOwner())
            return true;
    }

    private function hasClubCapacity(){
        if(!empty($this->clubDatas['capacity']))
            return true;
    }

    // club capacity
    private function displayClubCoverCharge(){
        if($this->hasClubCoverCharge() || !$this->isOwner())
            return true;
    }

    private function hasClubCoverCharge(){
        if(!empty($this->clubDatas['cover_charge']))
            return true;
    }

    // club capacity
    private function displayClubEntryAge(){
        if($this->hasClubEntryAge() || !$this->isOwner())
            return true;
    }

    private function hasClubEntryAge(){
        if(!empty($this->clubDatas['entry_age']))
            return true;
    }

    // club capacity
    private function displayClubHours(){
        if($this->hasClubHours() || !$this->isOwner())
            return true;
    }

    private function hasClubHours(){
        if(!empty($this->clubDatas['hours']))
            return true;
    }

    // club capacity
    private function displayClubVIPArea(){
        if($this->hasClubVIPArea() || !$this->isOwner())
            return true;
    }

    private function hasClubVIPArea(){
        if(!empty($this->clubDatas['vip_area']))
            return true;
    }

    // club capacity
    private function displayClubBarType(){
        if($this->hasClubBarType() || !$this->isOwner())
            return true;
    }

    private function hasClubBarType(){
        if(!empty($this->clubDatas['bar_type']))
            return true;
    }

    // club capacity
    private function displayClubFoodService(){
        if($this->hasClubFoodService() || !$this->isOwner())
            return true;
    }

    private function hasClubFoodService(){
        if(!empty($this->clubDatas['food_service']))
            return true;
    }

    // getting the club additional datas
    protected function getClubAddonDatas(){

        $aVars = array();
        $displayAddonDatasContainerCount = 0;
        $hideEmptyContainers = getParam('emmet_bytes_club_cover_hide_empty_containers');
        // getting the club fans
        $displayFansContainer = getParam('emmet_bytes_club_cover_display_fans');
        $clubFansDatas = $this->getClubFans();
        $hasFansContainer = true;
        if(($hideEmptyContainers && $clubFansDatas['count'] <= 0) || !$displayFansContainer){ 
            $hasFansContainer = false;
        }else{
            $displayAddonDatasContainerCount++; 
        }
        $aVars['bx_if:display_club_fans'] = array(
            'condition' => $hasFansContainer,
            'content' => array(
                'club_fans' => $clubFansDatas['contents'],
            ),
        );

        // getting the member photo albums
        $clubPhotosDatas = $this->getClubPhotoAlbumsDatas();
        $displayPhotoAlbumsConainer = getParam('emmet_bytes_club_cover_display_photo_albums');
        $hasPhotoAlbumsContainer = true;
        if(($hideEmptyContainers && $clubPhotosDatas['count'] <= 0) || !$displayPhotoAlbumsConainer){ 
            $hasPhotoAlbumsContainer = false;
        }else{
            $displayAddonDatasContainerCount++; 
        }
        $aVars['bx_if:display_club_photo_albums'] = array(
            'condition' => $hasPhotoAlbumsContainer,
            'content' => array(
                'club_photo_albums' => $clubPhotosDatas['content']['contents'],
            ),
        );

        // getting the members video datas
        $clubVideoDatas = $this->getClubVideoAlbumsDatas();
        $displayVideoAlbumsContainer = getParam('emmet_bytes_club_cover_display_video_albums');
        $hasVideAlbumsContainer = true;
        if(($hideEmptyContainers && $clubVideoDatas['count'] <= 0) || !$displayVideoAlbumsContainer){ 
            $hasVideAlbumsContainer = false;
        }else{
            $displayAddonDatasContainerCount++; 
        }
        $aVars['bx_if:display_club_video_albums'] = array(
            'condition' => $hasVideAlbumsContainer,
            'content' => array(
                'club_video_albums' => $clubVideoDatas['content']['contents'],
            ),
        );

        // getting the members sound datas
        $clubSoundDatas = $this->getClubSoundAlbumsDatas();
        $displaySoundAlbumsContainer = getParam('emmet_bytes_club_cover_display_sounds_albums');
        $hasSoundsAlbumsContainer = true;
        if(($hideEmptyContainers && $clubSoundDatas['count'] <= 0) || !$displaySoundAlbumsContainer){ 
            $hasSoundsAlbumsContainer = false;
        }else{
            $displayAddonDatasContainerCount++; 
        }
        $aVars['bx_if:display_club_sound_albums'] = array(
            'condition' => $hasSoundsAlbumsContainer,
            'content' => array(
                'club_sound_albums' => $clubSoundDatas['content']['contents'],
            ),
        );

        // getting the members files datas
        $clubFileDatas = $this->getClubFileFoldersDatas($profileId, $filesModuleExist);
        $displayFilesFolderContainer = getParam('emmet_bytes_club_cover_display_file_folders');
        $hasFilesContainer = true;
        if(($hideEmptyContainers && $clubFileDatas['count'] <= 0) || !$displayFilesFolderContainer){ 
            $hasFilesContainer = false;
        }else{
            $displayAddonDatasContainerCount++; 
        }
        $aVars['bx_if:display_club_file_folders'] = array(
            'condition' => $hasFilesContainer,
            'content' => array(
                'club_file_folders' => $clubFileDatas['content']['contents'],
            ),
        );

        // getting the members news datas
        $clubNewsDatas = $this->getClubNewsDatas($profileId, $newsModuleExist);
        $displayNewsFolderContainer = getParam('emmet_bytes_club_cover_display_news');
        $hasNewsContainer = true;
        if(($hideEmptyContainers && $clubNewsDatas['count'] <= 0) || !$displayNewsFolderContainer){ 
            $hasNewsContainer = false;
        }else{
            $displayAddonDatasContainerCount++; 
        }
        $aVars['bx_if:display_club_news'] = array(
            'condition' => $hasNewsContainer,
            'content' => array(
                'club_news' => $clubNewsDatas['content']['contents'],
            ),
        );

        // getting the members website datas
        $clubWebsiteDatas = $this->getClubWebsiteDatas($profileId, $websiteModuleExist);
        $displayWebsiteFolderContainer = getParam('emmet_bytes_club_cover_display_websites');
        $hasWebsiteContainer = true;
        if(($hideEmptyContainers && $clubWebsiteDatas['count'] <= 0) || !$displayWebsiteFolderContainer){ 
            $hasWebsiteContainer = false;
        }else{
            $displayAddonDatasContainerCount++; 
        }
        $aVars['bx_if:display_club_website'] = array(
            'condition' => $hasWebsiteContainer,
            'content' => array(
                'club_website' => $clubWebsiteDatas['content']['contents'],
            ),
        );

        // getting the members events datas
        $clubEventsDatas = $this->getClubEventsDatas($profileId, $eventsModuleExist);
        $displayEventsFolderContainer = getParam('emmet_bytes_club_cover_display_events');
        $hasEventsContainer = true;
        if(($hideEmptyContainers && $clubEventsDatas['count'] <= 0) || !$displayEventsFolderContainer){ 
            $hasEventsContainer = false;
        }else{
            $displayAddonDatasContainerCount++; 
        }
        $aVars['bx_if:display_club_events'] = array(
            'condition' => $hasEventsContainer,
            'content' => array(
                'club_events' => $clubEventsDatas['content']['contents'],
            ),
        );
        return array(
            'displayed_addon_datas_container_count' => $displayAddonDatasContainerCount,
            'container' => $this->_oTemplate->parseHTMLByName('ebytes_club_cover_addon_datas_container', $aVars),
        );
    }

    // getting the club fans
    protected function getClubFans(){
        $aProfiles = array();
        $fansCount = (int)$this->_oDb->getFans($aProfiles, $this->clubDatas['id'], true, 0, 6);
        if($fansCount > 0){
            $fansContainer = $this->getFansAvatarContainer($aProfiles);
        }
        $aVars = array(
            'contents' => $fansContainer,
            'empty_class' => ($fansCount > 0) ? '' : 'ebytes_club_cover_addon_data_empty_contents',
            'class' => 'ebytes_club_cover_club_fans_container',
            'addon_class' => 'ebytes_club_cover_addon_data_container_link',
            'caption' => ((int)$fansCount) ? _t('_emmetbytes_club_cover_fans_count_caption', $fansCount) : _t('_emmetbytes_club_cover_fans_count_caption', $fansCount), 
            'link' => $this->getClubBaseUrl() . 'browse_fans/' . $this->clubDatas['uri'],
            'link_action' => '',
        );
        return array(
            'count' => $fansCount,
            'contents' => $this->_oTemplate->parseHTMLByName(    
                'ebytes_club_cover_addon_data_container', 
                $aVars
            ), 
        );
    }

    // getting the fans container
    protected function getFansAvatarContainer($clubFans){
        $fansContainer = '';
        foreach($clubFans as $key=>$fanInfo){
            if($fanInfo['Couple']){
                $fanInfo = getProfileInfo($fanInfo['Couple']);
            }
            $fansContainer .= $this->getFanContainer($fanInfo);
        }
        return $fansContainer;
    }

    // getting the fan avatar container
    protected function getFanContainer($fanInfo){
        $profileId = (!$fanInfo['Couple']) ? $fanInfo['ID'] : $fanInfo['Couple'];
        $fanAvatar = $GLOBALS['oFunctions']->getMemberAvatar($profileId, 'medium');
        $aVars = array(
            'avatar' => $fanAvatar,
            'profile_id' => $profileId,
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_fan_container', $aVars);
    }

    // getting the fan popup datas
    function getFanPopupDatas($profileId){
        $nickName = getNickName($profileId);
        $profileCoverDatas = $this->_oDb->getProfileCoverDataByProfileId($profileId);
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

    // getting the profile cover own images
    protected function getProfileCoverOwnImages($imagePath){
        $profileCoverModule = BxDolModule::getInstance('EmmetBytesProfileCoverModule');
        $imageUrlPath = base64_encode($imagePath);
        return array(BX_DOL_URL_ROOT . $profileCoverModule->_oConfig->getBaseUri() . 'get_own_image/' . $imageUrlPath);
    }

    // getting the member photo albums
    protected function getClubPhotoAlbumsDatas(){
        // getting the member photos
        $memberPhotos = array();
        $photoAlbumsCount = 0;
        $clubPhotos = $this->_oDb->getMediaIds($this->clubDatas['id'], 'images');
        $images = array();
        if(sizeof($clubPhotos) > 0){
            foreach($clubPhotos as $clubPhoto){
                $a = array(
                    'ID' => $this->clubDatas['author_id'],
                    'Avatar' => $clubPhoto
                );
                $photo = BxDolService::call('photos', 'get_image', array($a, 'browse'), 'Search');
                if(!empty($photo['file'])){
                    $images[] = $photo['file'];
                }
                $photoAlbumsCount++;
            }
        }

        if(sizeof($images) <= 0){
            $images = $this->getClubCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_club_cover/images/default_photo_icon.jpg');
        }
        $imageData = array(
            'images' => $images,
            'is_empty' => !$photoAlbumsCount,
            'caption' => _t('_emmetbytes_club_cover_photo_albums_count_caption', $photoAlbumsCount),
            'link' => '#',
            'link_action' => 'onclick="return false;"',
            'addon_class' => '',
        );
        return array(
            'content' => $this->getAddonDatasCommonContainer($imageData),
            'count' => $photoAlbumsCount,
        );
    }

    // getting the member video albums
    protected function getClubVideoAlbumsDatas(){
        // getting the member photos
        $memberPhotos = array();
        $videoAlbumsCount = 0;
        $clubVideos = $this->_oDb->getMediaIds($this->clubDatas['id'], 'videos');
        $images = array();
        if(sizeof($clubVideos) > 0){
            foreach($clubVideos as $clubVideo){
                $a = BxDolService::call('videos', 'get_video_array', array($clubVideo), 'Search');
                if(!empty($a['file']) && $a['status'] == 'approved'){
                    $images[] = $a['file'];
                    $videoAlbumsCount++;
                }
            }
        }

        if(sizeof($images) <= 0){
            $images = $this->getClubCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_club_cover/images/default_video_icon.jpg');
        }

        $imageData = array(
            'images' => $images,
            'is_empty' => !$videoAlbumsCount,
            'caption' => _t('_emmetbytes_club_cover_video_albums_count_caption', $videoAlbumsCount),
            'link' => '#',
            'link_action' => 'onclick="return false;"',
            'addon_class' => '',
        );
        return array(
            'content' => $this->getAddonDatasCommonContainer($imageData),
            'count' => $photoAlbumsCount,
        );
    }

    // getting the member sounds albums
    protected function getClubSoundAlbumsDatas(){
        $imagePath = $this->getClubCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_club_cover/images/default_sound_icon.jpg');
        $soundAlbumsCount = 0;
        $soundAlbums = $this->_oDb->getMediaIds($this->clubDatas['id'], 'sounds');
        if(sizeof($soundAlbums) > 0){
            foreach($soundAlbums as $soundAlbum){
                $a = BxDolService::call('sounds', 'get_music_array', array($soundAlbum), 'Search');
                if(!empty($a['file']) && $a['status'] == 'approved'){
                    $images[] = $a['file'];
                    $soundAlbumsCount++;
                }
            }
        }
        $imageData = array(
            'images' => $imagePath,
            'is_empty' => !$soundAlbumsCount,
            'caption' => _t('_emmetbytes_club_cover_sound_albums_count_caption', $soundAlbumsCount),
            'link' => '#',
            'link_action' => 'onclick="return false;"',
            'addon_class' => '',
        );
        return array(
            'count' => $soundAlbumsCount,
            'content' => $this->getAddonDatasCommonContainer($imageData),
        );
    }

    // getting the member files albums folders
    protected function getClubFileFoldersDatas($profileId, $filesModuleExist){
        $imagePath = $this->getClubCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_club_cover/images/default_file_icon.jpg');
        $files = $this->_oDb->getMediaIds($this->clubDatas['id'], 'files');
        $fileAlbumsCount = sizeof($files);
        $imageData = array(
            'images' => $imagePath,
            'is_empty' => !$fileAlbumsCount,
            'caption' => _t('_emmetbytes_club_cover_file_folders_count_caption', $fileAlbumsCount),
            'link' => '#',
            'link_action' => 'onclick="return false;"',
            'addon_class' => '',

        );
        return array(
            'count' => $fileAlbumsCount,
            'content' => $this->getAddonDatasCommonContainer($imageData),
        );
    }

    // getting the club news folders
    protected function getClubNewsDatas($profileId, $filesModuleExist){
        $imagePaths = array();
        $clubSearchResultObj = $this->getClubSearchResultObj('news', $this->clubDatas['uri']);
        $clubSearchResultObj->aCurrent['paginate']['perPage'] = 1;
        $newsDatas = $clubSearchResultObj->getSearchData();
        if(sizeof($newsDatas) > 0){
            foreach($newsDatas as $newsData){
                if($newsData['thumb'] != ''){
                    $a = array(
                        'ID' => $newsData['author_id'],
                        'Avatar' => $newsData['thumb'],
                    );
                    $photo = BxDolService::call('photos', 'get_image', array($a, 'browse'), 'Search');
                    if($photo['file'] != '')
                        $imagePaths[] = $photo['file'];
                }
            }
        }
        if(sizeof($imagePaths) <= 0)
            $imagePaths = $this->getClubCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_club_cover/images/default_ads_icon.jpg');

        $newsCount = $clubSearchResultObj->aCurrent['paginate']['totalNum'];
        $imageData = array(
            'images' => $imagePaths,
            'is_empty' => !$newsCount,
            'caption' => _t('_emmetbytes_club_cover_news_count_caption', $newsCount),
            'link' => $this->getClubBaseUrl() . 'news/browse/' . $this->clubDatas['uri'],
            'link_action' => '',
            'addon_class' => 'ebytes_club_cover_addon_data_container_link',
        );
        return array(
            'count' => $newsCount,
            'content' => $this->getAddonDatasCommonContainer($imageData),
        );
    }

    // getting the club website
    protected function getClubWebsiteDatas($profileId, $filesModuleExist){
        $imagePath = $this->getClubCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_club_cover/images/default_site_icon.jpg');
        $websiteCount = 0;
        if($this->clubDatas['businesswebsite'] != '')
            $websiteCount = 1;
        $imageData = array(
            'images' => $imagePath,
            'is_empty' => !$websiteCount,
            'caption' => _t('_emmetbytes_club_cover_website_count_caption', $websiteCount),
            'link' => '#',
            'link_action' => 'onclick="return false;"',
            'addon_class' => '',
        );
        return array(
            'count' => $fileAlbumsCount,
            'content' => $this->getAddonDatasCommonContainer($imageData),
        );
    }

    // getting the club events
    protected function getClubEventsDatas($profileId, $filesModuleExist){
        $imagePaths = array();
        $clubSearchResultObj = $this->getClubSearchResultObj('events', $this->clubDatas['uri']);
        $clubSearchResultObj->aCurrent['paginate']['perPage'] = 1;
        $eventDatas = $clubSearchResultObj->getSearchData();
        if(sizeof($eventDatas) > 0){
            foreach($eventDatas as $eventData){
                if($eventData['thumb'] != ''){
                    $a = array(
                        'ID' => $eventData['author_id'],
                        'Avatar' => $eventData['thumb'],
                    );
                    $photo = BxDolService::call('photos', 'get_image', array($a, 'browse'), 'Search');
                    if($photo['file'] != '')
                        $imagePaths[] = $photo['file'];
                }
            }
        }
        if(sizeof($imagePaths) <= 0)
            $imagePaths = $this->getClubCoverOwnImages(BX_DIRECTORY_PATH_ROOT . 'modules/EmmetBytes/emmetbytes_club_cover/images/default_ads_icon.jpg');

        $eventsCount = $clubSearchResultObj->aCurrent['paginate']['totalNum'];
        $imageData = array(
            'images' => $imagePaths,
            'is_empty' => !$eventsCount,
            'caption' => _t('_emmetbytes_club_cover_events_count_caption', $eventsCount),
            'link' => $this->getClubBaseUrl() . 'event/browse/' . $this->clubDatas['uri'],
            'link_action' => '',
            'addon_class' => 'ebytes_club_cover_addon_data_container_link',
        );
        return array(
            'count' => $eventsCount,
            'content' => $this->getAddonDatasCommonContainer($imageData),
        );
    }

    // getting the addon_datas common container
    protected function getAddonDatasCommonContainer($datas){
        $imageContents = '';
        $class = 'ebytes_club_cover_common_addon_datas_single_image';
        $imageVars = array( 
            'image_bg' => $datas['images'][0],
            'image' => $GLOBALS['oSysTemplate']->getIconUrl('spacer.gif')
        );
        $imageContents = $this->_oTemplate->parseHTMLByName('ebytes_club_cover_common_addon_data_icon_container', $imageVars);
        $class = (isset($datas['class']) && !empty($datas['class'])) ? $datas['class'] : $class;
        $aVars = array(
            'class' => $class,
            'addon_class' => $datas['addon_class'],
            'is_empty' => $datas['is_empty'],
            'contents' => ($datas['is_empty']) ? '' : $imageContents,
            'caption' => $datas['caption'],
            'link' => $datas['link'],
            'link_action' => $datas['link_action'],
        );
        return $this->getCommonAddonDataContainer($aVars);
    }

    protected function getCommonAddonDataContainer($aVars){
        $aVars = array(
            'class' => (isset($aVars['class'])) ? $aVars['class'] : '',
            'addon_class' => $aVars['addon_class'],
            'empty_class' => (isset($aVars['is_empty']) && $aVars['is_empty']) ? 'ebytes_club_cover_addon_data_empty_contents' : '',
            'contents' => (isset($aVars['contents'])) ? $aVars['contents'] : '',
            'caption' => (isset($aVars['caption'])) ? $aVars['caption'] : '',
            'link' => (isset($aVars['link'])) ? $aVars['link'] : '',
            'link_action' => $aVars['link_action'],
        );
        return array(
            'contents' => $this->_oTemplate->parseHTMLByName('ebytes_club_cover_addon_data_container', $aVars), 
        );
    }

    private function isOwner(){
        if(getLoggedId() == $this->clubDatas['author_id'])
            return true;
    }

    // method that gets the club module search result
    private function getClubSearchResultObj($sMode = '', $sValue = '', $sValue2 = '', $sValue3 = '', $sValue4 = '', $sValue5 = '', $sValue6 = ''){
        bx_import('BxClubSearchResult');
        return new BxClubSearchResult($sMode, $sValue, $sValue2, $sValue3, $sValue4, $sValue5, $sValue6);
    }

    // getting the business website popup
    private function getBusinessWebsitePopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
            $authorId = $this->clubDatas['author_id'];
            $loggedId = getLoggedId();
            if($authorId == $loggedId){
                if(isset($_POST['business_website']) && !empty($_POST['business_website'])){
                    $urlPattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
                    $businessWebsite = $_POST['business_website'];
                    if(!preg_match($urlPattern, $businessWebsite)){
                        $errors = array(_t('_emmetbytes_club_cover_business_website_invalid'));
                    }else{
                        $clubId = $this->clubDatas['id'];
                        $formData = array(
                            'club_id' => $clubId,
                            'column_name' => 'businesswebsite',
                            'value' => $businessWebsite,
                        );
                        // update the headline
                        $success = $this->_oDb->updateClubData($formData);
                        if($success > 0){
                            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
                            $content = $this->getBusinessWebsiteContainer();
                        }
                    }
                }else{
                    $errors = array(_t('_emmetbytes_club_cover_no_business_website_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_club_cover_not_allowed_error'));
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
                'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'get_business_website_popup',
                'inputs_container' => $this->getBusinessWebsiteForm(),
                'container_id' => 'ebytes_club_cover_insert_business_website_container',
                'form_id' => 'ebytes_club_cover_business_website_form_id',
                'club_id' => $_GET['club_id'],
                'close_caption' => _t('_emmetbytes_club_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_club_cover_popup_submit_business_website_caption'),
            );
            return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_popup', $aVars);
        }
    }

    // getting the business website form
    protected function getBusinessWebsiteForm(){
        $aVars = array(
            'help_caption' => _t('_emmetbytes_club_cover_help_business_website_caption'),
            'input_name' => 'business_website',
            'business_website_caption' => _t('_emmetbytes_club_cover_business_website_input_caption'),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_business_website_form', $aVars);
    }

    // getting the business email popup
    private function getBusinessEmailPopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
            $authorId = $this->clubDatas['author_id'];
            $loggedId = getLoggedId();
            if($authorId == $loggedId){
                if(isset($_POST['business_email']) && !empty($_POST['business_email'])){
                    $businessEmail = $_POST['business_email'];
                    if(!filter_var($businessEmail, FILTER_VALIDATE_EMAIL)){
                        $errors = array(_t('_emmetbytes_club_cover_business_email_invalid'));
                    }else{
                        $clubId = $this->clubDatas['id'];
                        $formData = array(
                            'club_id' => $clubId,
                            'column_name' => 'businessemail',
                            'value' => $businessEmail,
                        );
                        // update the headline
                        $success = $this->_oDb->updateClubData($formData);
                        if($success > 0){
                            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
                            $content = $this->getBusinessEmailContainer();
                        }
                    }
                }else{
                    $errors = array(_t('_emmetbytes_club_cover_no_business_email_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_club_cover_not_allowed_error'));
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
                'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'get_business_email_popup',
                'inputs_container' => $this->getBusinessEmailForm(),
                'container_id' => 'ebytes_club_cover_insert_business_email_container',
                'form_id' => 'ebytes_club_cover_business_email_form_id',
                'club_id' => $_GET['club_id'],
                'close_caption' => _t('_emmetbytes_club_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_club_cover_popup_submit_business_email_caption'),
            );
            return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_popup', $aVars);
        }
    }

    // getting the business email form
    protected function getBusinessEmailForm(){
        $aVars = array(
            'help_caption' => _t('_emmetbytes_club_cover_help_business_email_caption'),
            'input_name' => 'business_email',
            'business_email_caption' => _t('_emmetbytes_club_cover_business_email_input_caption'),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_business_email_form', $aVars);
    }

    // getting the business telephone popup
    private function getBusinessTelephonePopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
            $authorId = $this->clubDatas['author_id'];
            $loggedId = getLoggedId();
            if($authorId == $loggedId){
                if(isset($_POST['business_telephone']) && !empty($_POST['business_telephone'])){
                    $businessTelephone = $_POST['business_telephone'];
                    if(trim($businessTelephone) == _t('_emmetbytes_club_cover_business_telephone_input_caption')){
                        $errors = array(_t('_emmetbytes_club_cover_no_business_telephone_problem'));
                    }else{
                        $clubId = $this->clubDatas['id'];
                        $formData = array(
                            'club_id' => $clubId,
                            'column_name' => 'businesstelephone',
                            'value' => $businessTelephone,
                        );
                        $success = $this->_oDb->updateClubData($formData);
                        if($success > 0){
                            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
                            $content = $this->getBusinessTelephoneContainer();
                        }
                    }
                }else{
                    $errors = array(_t('_emmetbytes_club_cover_no_business_telephone_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_club_cover_not_allowed_error'));
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
                'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'get_business_telephone_popup',
                'inputs_container' => $this->getBusinessTelephoneForm(),
                'container_id' => 'ebytes_club_cover_insert_business_telephone_container',
                'club_id' => $_GET['club_id'],
                'form_id' => 'ebytes_club_cover_business_telephone_form_id',
                'close_caption' => _t('_emmetbytes_club_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_club_cover_popup_submit_business_telephone_caption'),
            );
            return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_popup', $aVars);
        }
    }

    // getting the business telephone form
    protected function getBusinessTelephoneForm(){
        $aVars = array(
            'help_caption' => _t('_emmetbytes_club_cover_help_business_telephone_caption'),
            'input_name' => 'business_telephone',
            'business_telephone_caption' => _t('_emmetbytes_club_cover_business_telephone_input_caption'),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_business_telephone_form', $aVars);
    }

    // getting the business fax popup
    private function getBusinessFaxPopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
            $authorId = $this->clubDatas['author_id'];
            $loggedId = getLoggedId();
            if($authorId == $loggedId){
                if(isset($_POST['business_fax']) && !empty($_POST['business_fax'])){
                    $businessFax = $_POST['business_fax'];
                    if($businessFax == _t('_emmetbytes_club_cover_business_fax_input_caption')){
                        $errors = array(_t('_emmetbytes_club_cover_no_business_fax_problem'));
                    }else{
                        $clubId = $this->clubDatas['id'];
                        $formData = array(
                            'club_id' => $clubId,
                            'column_name' => 'businessfax',
                            'value' => $businessFax,
                        );
                        // update the headline
                        $success = $this->_oDb->updateClubData($formData);
                        if($success > 0){
                            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
                            $content = $this->getBusinessFaxContainer();
                        }
                    }
                }else{
                    $errors = array(_t('_emmetbytes_club_cover_no_business_fax_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_club_cover_not_allowed_error'));
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
                'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'get_business_fax_popup',
                'inputs_container' => $this->getBusinessFaxForm(),
                'container_id' => 'ebytes_club_cover_insert_business_fax_container',
                'form_id' => 'ebytes_club_cover_business_fax_form_id',
                'club_id' => $_GET['club_id'],
                'close_caption' => _t('_emmetbytes_club_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_club_cover_popup_submit_business_fax_caption'),
            );
            return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_popup', $aVars);
        }
    }

    // getting the business fax form
    protected function getBusinessFaxForm(){
        $aVars = array(
            'help_caption' => _t('_emmetbytes_club_cover_help_business_fax_caption'),
            'input_name' => 'business_fax',
            'business_fax_caption' => _t('_emmetbytes_club_cover_business_fax_input_caption'),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_business_fax_form', $aVars);
    }

    // getting the club capacity popup
    private function getClubCapacityPopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
            $authorId = $this->clubDatas['author_id'];
            $loggedId = getLoggedId();
            if($authorId == $loggedId){
                if(isset($_POST['club_capacity']) && !empty($_POST['club_capacity'])){
                    $clubCapacity = $_POST['club_capacity'];
                    if($clubCapacity == _t('_emmetbytes_club_cover_club_capacity_input_caption')){
                        $errors = array(_t('_emmetbytes_club_cover_no_club_capacity_problem'));
                    }else{
                        $clubId = $this->clubDatas['id'];
                        $formData = array(
                            'club_id' => $clubId,
                            'column_name' => 'capacity',
                            'value' => $clubCapacity,
                        );
                        // update the headline
                        $success = $this->_oDb->updateClubData($formData);
                        if($success > 0){
                            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
                            $content = $this->getClubCapacityContainer();
                        }
                    }
                }else{
                    $errors = array(_t('_emmetbytes_club_cover_no_club_capacity_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_club_cover_not_allowed_error'));
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
                'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'get_club_capacity_popup',
                'inputs_container' => $this->getClubCapacityForm(),
                'container_id' => 'ebytes_club_cover_insert_club_capacity_container',
                'form_id' => 'ebytes_club_cover_club_capacity_form_id',
                'club_id' => $_GET['club_id'],
                'close_caption' => _t('_emmetbytes_club_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_club_cover_popup_submit_club_capacity_caption'),
            );
            return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_popup', $aVars);
        }
    }

    // getting the club capacity form
    protected function getClubCapacityForm(){
        $aVars = array(
            'help_caption' => _t('_emmetbytes_club_cover_help_club_capacity_caption'),
            'input_name' => 'club_capacity',
            'club_capacity_caption' => _t('_emmetbytes_club_cover_club_capacity_input_caption'),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_club_capacity_form', $aVars);
    }

    // getting the club charge popup
    private function getClubChargePopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
            $authorId = $this->clubDatas['author_id'];
            $loggedId = getLoggedId();
            if($authorId == $loggedId){
                if(isset($_POST['club_charge']) && !empty($_POST['club_charge'])){
                    $clubCharge = $_POST['club_charge'];
                    if($clubCharge == _t('_emmetbytes_club_cover_club_charge_input_caption')){
                        $errors = array(_t('_emmetbytes_club_cover_no_club_charge_problem'));
                    }else{
                        $clubId = $this->clubDatas['id'];
                        $formData = array(
                            'club_id' => $clubId,
                            'column_name' => 'cover_charge',
                            'value' => $clubCharge,
                        );
                        // update the headline
                        $success = $this->_oDb->updateClubData($formData);
                        if($success > 0){
                            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
                            $content = $this->getClubCoverChargeContainer();
                        }
                    }
                }else{
                    $errors = array(_t('_emmetbytes_club_cover_no_club_charge_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_club_cover_not_allowed_error'));
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
                'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'get_club_charge_popup',
                'inputs_container' => $this->getClubChargeForm(),
                'container_id' => 'ebytes_club_cover_insert_charge_container',
                'form_id' => 'ebytes_club_cover_club_charge_form_id',
                'club_id' => $_GET['club_id'],
                'close_caption' => _t('_emmetbytes_club_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_club_cover_popup_submit_club_charge_caption'),
            );
            return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_popup', $aVars);
        }
    }

    // getting the club charge form
    protected function getClubChargeForm(){
        $aVars = array(
            'help_caption' => _t('_emmetbytes_club_cover_help_club_charge_caption'),
            'input_name' => 'club_charge',
            'club_charge_caption' => _t('_emmetbytes_club_cover_club_charge_input_caption'),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_club_charge_form', $aVars);
    }

    // getting the club entry_age popup
    private function getClubEntryAgePopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
            $authorId = $this->clubDatas['author_id'];
            $loggedId = getLoggedId();
            if($authorId == $loggedId){
                if(isset($_POST['club_entry_age']) && !empty($_POST['club_entry_age'])){
                    $clubEntryAge = $_POST['club_entry_age'];
                    if($clubEntryAge == _t('_emmetbytes_club_cover_club_entry_age_input_caption')){
                        $errors = array(_t('_emmetbytes_club_cover_no_club_entry_age_problem'));
                    }else{
                        $clubId = $this->clubDatas['id'];
                        $formData = array(
                            'club_id' => $clubId,
                            'column_name' => 'entry_age',
                            'value' => $clubEntryAge,
                        );
                        // update the headline
                        $success = $this->_oDb->updateClubData($formData);
                        if($success > 0){
                            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
                            $content = $this->getClubEntryAgeContainer();
                        }
                    }
                }else{
                    $errors = array(_t('_emmetbytes_club_cover_no_club_entry_age_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_club_cover_not_allowed_error'));
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
                'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'get_club_entry_age_popup',
                'inputs_container' => $this->getClubEntryAgeForm(),
                'container_id' => 'ebytes_club_cover_insert_entry_age_container',
                'form_id' => 'ebytes_club_cover_club_entry_age_form_id',
                'club_id' => $_GET['club_id'],
                'close_caption' => _t('_emmetbytes_club_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_club_cover_popup_submit_club_entry_age_caption'),
            );
            return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_popup', $aVars);
        }
    }

    // getting the club entry_age form
    protected function getClubEntryAgeForm(){
        $aVars = array(
            'help_caption' => _t('_emmetbytes_club_cover_help_club_entry_age_caption'),
            'input_name' => 'club_entry_age',
            'club_entry_age_caption' => _t('_emmetbytes_club_cover_club_entry_age_input_caption'),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_club_entry_age_form', $aVars);
    }

    // getting the club hours popup
    private function getClubHoursPopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
            $authorId = $this->clubDatas['author_id'];
            $loggedId = getLoggedId();
            if($authorId == $loggedId){
                if(isset($_POST['club_hours']) && !empty($_POST['club_hours'])){
                    $clubHours = $_POST['club_hours'];
                    if($clubHours == _t('_emmetbytes_club_cover_club_hours_input_caption')){
                        $errors = array(_t('_emmetbytes_club_cover_no_club_hours_problem'));
                    }else{
                        $clubId = $this->clubDatas['id'];
                        $formData = array(
                            'club_id' => $clubId,
                            'column_name' => 'hours',
                            'value' => $clubHours,
                        );
                        // update the headline
                        $success = $this->_oDb->updateClubData($formData);
                        if($success > 0){
                            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
                            $content = $this->getClubHoursContainer();
                        }
                    }
                }else{
                    $errors = array(_t('_emmetbytes_club_cover_no_club_hours_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_club_cover_not_allowed_error'));
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
                'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'get_club_hours_popup',
                'inputs_container' => $this->getClubHoursForm(),
                'container_id' => 'ebytes_club_cover_insert_club_hours_container',
                'form_id' => 'ebytes_club_cover_club_hours_form_id',
                'club_id' => $_GET['club_id'],
                'close_caption' => _t('_emmetbytes_club_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_club_cover_popup_submit_club_hours_caption'),
            );
            return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_popup', $aVars);
        }
    }

    // getting the club hours form
    protected function getClubHoursForm(){
        $aVars = array(
            'help_caption' => _t('_emmetbytes_club_cover_help_club_hours_caption'),
            'input_name' => 'club_hours',
            'club_hours_caption' => _t('_emmetbytes_club_cover_club_hours_input_caption'),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_club_hours_form', $aVars);
    }

    // getting the club vip_area popup
    private function getClubVIPAreaPopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
            $authorId = $this->clubDatas['author_id'];
            $loggedId = getLoggedId();
            if($authorId == $loggedId){
                if(isset($_POST['club_vip_area']) && !empty($_POST['club_vip_area'])){
                    $vipArea = $_POST['club_vip_area'];
                    if($vipArea == _t('_emmetbytes_club_cover_club_vip_area_input_caption')){
                        $errors = array(_t('_emmetbytes_club_cover_no_club_vip_area_problem'));
                    }else{
                        $clubId = $this->clubDatas['id'];
                        $formData = array(
                            'club_id' => $clubId,
                            'column_name' => 'vip_area',
                            'value' => $vipArea,
                        );
                        // update the headline
                        $success = $this->_oDb->updateClubData($formData);
                        if($success > 0){
                            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
                            $content = $this->getClubVIPAreaContainer();
                        }
                    }
                }else{
                    $errors = array(_t('_emmetbytes_club_cover_no_club_vip_area_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_club_cover_not_allowed_error'));
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
                'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'get_club_vip_area_popup',
                'inputs_container' => $this->getClubVIPAreaForm(),
                'container_id' => 'ebytes_club_cover_insert_club_vip_area_container',
                'form_id' => 'ebytes_club_cover_club_vip_area_form_id',
                'club_id' => $_GET['club_id'],
                'close_caption' => _t('_emmetbytes_club_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_club_cover_popup_submit_club_vip_area_caption'),
            );
            return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_popup', $aVars);
        }
    }

    // getting the club vip_area form
    protected function getClubVIPAreaForm(){
        $aVars = array(
            'help_caption' => _t('_emmetbytes_club_cover_help_club_vip_area_caption'),
            'input_name' => 'club_vip_area',
            'club_vip_area_caption' => _t('_emmetbytes_club_cover_club_vip_area_input_caption'),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_club_vip_area_form', $aVars);
    }

    // getting the club bar_type popup
    private function getClubBarTypePopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
            $authorId = $this->clubDatas['author_id'];
            $loggedId = getLoggedId();
            if($authorId == $loggedId){
                if(isset($_POST['club_bar_type']) && !empty($_POST['club_bar_type'])){
                    $clubBarType = $_POST['club_bar_type'];
                    if($clubBarType == _t('_emmetbytes_club_cover_club_bar_type_input_caption')){
                        $errors = array(_t('_emmetbytes_club_cover_no_club_bar_type_problem'));
                    }else{
                        $clubId = $this->clubDatas['id'];
                        $formData = array(
                            'club_id' => $clubId,
                            'column_name' => 'bar_type',
                            'value' => $clubBarType,
                        );
                        // update the headline
                        $success = $this->_oDb->updateClubData($formData);
                        if($success > 0){
                            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
                            $content = $this->getClubBarTypeContainer();
                        }
                    }
                }else{
                    $errors = array(_t('_emmetbytes_club_cover_no_club_bar_type_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_club_cover_not_allowed_error'));
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
                'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'get_club_bar_type_popup',
                'inputs_container' => $this->getClubBarTypeForm(),
                'container_id' => 'ebytes_club_cover_insert_bar_type_container',
                'form_id' => 'ebytes_club_cover_club_bar_type_form_id',
                'club_id' => $_GET['club_id'],
                'close_caption' => _t('_emmetbytes_club_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_club_cover_popup_submit_club_bar_type_caption'),
            );
            return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_popup', $aVars);
        }
    }

    // getting the club bar_type form
    protected function getClubBarTypeForm(){
        $aVars = array(
            'help_caption' => _t('_emmetbytes_club_cover_help_club_bar_type_caption'),
            'input_name' => 'club_bar_type',
            'club_bar_type_caption' => _t('_emmetbytes_club_cover_club_bar_type_input_caption'),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_club_bar_type_form', $aVars);
    }

    // getting the club food_service popup
    private function getClubFoodServicePopup(){
        if(isset($_POST) && sizeof($_POST) > 0){
            $errors = false;
            $content = '';
            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
            $authorId = $this->clubDatas['author_id'];
            $loggedId = getLoggedId();
            if($authorId == $loggedId){
                if(isset($_POST['club_food_service']) && !empty($_POST['club_food_service'])){
                    $foodService = $_POST['club_food_service'];
                    if($foodService == _t('_emmetbytes_club_cover_club_food_service_input_caption')){
                        $errors = array(_t('_emmetbytes_club_cover_no_club_food_service_problem'));
                    }else{
                        $clubId = $this->clubDatas['id'];
                        $formData = array(
                            'club_id' => $clubId,
                            'column_name' => 'food_service',
                            'value' => $foodService,
                        );
                        // update the headline
                        $success = $this->_oDb->updateClubData($formData);
                        if($success > 0){
                            $this->clubDatas = $this->_oDb->getEntryById($_POST['club_id']);
                            $content = $this->getClubFoodServiceContainer();
                        }
                    }
                }else{
                    $errors = array(_t('_emmetbytes_club_cover_no_club_food_service_problem'));
                }
            }else{
                $errors = array(_t('_emmetbytes_club_cover_not_allowed_error'));
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
                'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'get_club_food_service_popup',
                'inputs_container' => $this->getClubFoodServiceForm(),
                'container_id' => 'ebytes_club_cover_insert_club_food_service_container',
                'form_id' => 'ebytes_club_cover_club_food_service_form_id',
                'club_id' => $_GET['club_id'],
                'close_caption' => _t('_emmetbytes_club_cover_popup_close_caption'),
                'submit_caption' => _t('_emmetbytes_club_cover_popup_submit_club_food_service_caption'),
            );
            return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_popup', $aVars);
        }
    }

    // getting the club food_service form
    protected function getClubFoodServiceForm(){
        $aVars = array(
            'help_caption' => _t('_emmetbytes_club_cover_help_club_food_service_caption'),
            'input_name' => 'club_food_service',
            'club_food_service_caption' => _t('_emmetbytes_club_cover_club_food_service_input_caption'),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_club_food_service_form', $aVars);
    }

    // generate the form error contents
    protected function generateFormErrorContents($errors = array()){
        $contents = '';
        foreach($errors as $error){
            $aVars = array( 'error' => $error,); 
            $contents .= $this->_oTemplate->parseHTMLByName('ebytes_club_cover_form_error_container', $aVars);
        }
        return $contents;
    }

    private function getClubStreamPlayer(){
        $content = '';
        if($this->streamPlayerAvailable()){
            $content = $this->getClubStreamPlayerContents();
        }else{
            if($this->isOwner()){
                $content = $this->getClubStreamPlayerFormContainer();
            }else{
                $content = '';
            }
        }
        return $content;
    }

    private function getClubStreamPlayerContents(){
        $aVars = array(
            'stream_url' => $this->clubDatas['stream_url']
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_stream_player_container', $aVars);
    }

    private function getClubStreamPlayerNotAvailableContainer(){
        $aVars = array(
            'stream_url_not_avialable_caption' => _t('_emmetbytes_club_cover_stream_player_not_available'),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_stream_player_not_available_container', $aVars);
    }

    private function getClubStreamPlayerFormContainer(){
        $aVars = array(
            'form_action' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'update_stream_url',
            'club_id' => $this->clubDatas['id'],
            'url_input_value' => _t('_emmetbytes_club_cover_stream_player_url_input_caption'),
            'stream_label' => _t('_emmetbytes_club_cover_stream_player_form_label'),
        );
        return $this->_oTemplate->parseHTMLByName('ebytes_club_cover_stream_player_form_container', $aVars);
    }

    private function streamPlayerAvailable(){
        if($this->clubDatas['stream_url'] != '')
            return true;
    }

    // method that gets the club module base url
    private function getClubBaseUrl(){
        return BX_DOL_URL_ROOT . $GLOBALS['oBxClubModule']->_oConfig->getBaseUri();
    }

}

?>
