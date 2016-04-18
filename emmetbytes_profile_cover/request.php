<?
/************************************************************************************************************
 * EmmetBytes Default Profile Cover
 * Default Profile Cover For Creating New Boonex Modules
 ************************************************************************************************************/

require_once(BX_DIRECTORY_PATH_INC . 'profiles.inc.php');

check_logged();

bx_import('BxDolRequest');

class EmmetBytesProfileCoverRequest extends BxDolRequest {

    function EmmetBytesProfileCoverRequest() {
        parent::BxDolRequest();
    }

    function processAsAction($aModule, &$aRequest, $sClass = "Module") {

        $sClassRequire = $aModule['class_prefix'] . $sClass;
        $oModule = BxDolRequest::_require($aModule, $sClassRequire);
        $aVars = array ('BaseUri' => $oModule->_oConfig->getBaseUri());
        $GLOBALS['oTopMenu']->setCustomSubActions($aVars, 'emmet_bytes_profile_cover_title', false);

        return BxDolRequest::processAsAction($aModule, $aRequest, $sClass);
    }
}

EmmetBytesProfileCoverRequest::processAsAction($GLOBALS['aModule'], $GLOBALS['aRequest']);

?>
