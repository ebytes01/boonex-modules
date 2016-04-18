<?
/************************************************************************************************************
 * EmmetBytes Default Profile Cvr
 * Default Profile Cvr For Creating New Boonex Modules
 ************************************************************************************************************/

require_once(BX_DIRECTORY_PATH_INC . 'profiles.inc.php');

check_logged();

bx_import('BxDolRequest');

class EmmetBytesProfileCvrRequest extends BxDolRequest {

    function EmmetBytesProfileCvrRequest() {
        parent::BxDolRequest();
    }

    function processAsAction($aModule, &$aRequest, $sClass = "Module") {

        $sClassRequire = $aModule['class_prefix'] . $sClass;
        $oModule = BxDolRequest::_require($aModule, $sClassRequire);
        $aVars = array ('BaseUri' => $oModule->_oConfig->getBaseUri());
        $GLOBALS['oTopMenu']->setCustomSubActions($aVars, 'emmet_bytes_profile_cvr_title', false);

        return BxDolRequest::processAsAction($aModule, $aRequest, $sClass);
    }
}

EmmetBytesProfileCvrRequest::processAsAction($GLOBALS['aModule'], $GLOBALS['aRequest']);

?>
