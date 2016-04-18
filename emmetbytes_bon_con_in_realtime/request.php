<?
/**********************************************************************************************
 * Created By : EmmetBytes Software Solutions
 * Created Date : June 10, 2012
 * Email : emmetbytes@gmail.com
 *
 * Copyright : (c) EmmetBytes Software Solutions 
 * Product Name : Boonex Contents in Realtime
 * Product Version : 1.0
 * 
 * Important : This is a commercial product by EmmetBytes Software Solutions and 
 *   cannot be modified, redistributed or resold without any written permission 
 *   from EmmetBytes Software Solutions
 **********************************************************************************************/
require_once(BX_DIRECTORY_PATH_INC . 'profiles.inc.php');

check_logged();

bx_import('BxDolRequest');

class EmmetBytesBonConInRealtimeRequest extends BxDolRequest {

    function EmmetBytesBonConInRealtimeRequest() {
        parent::BxDolRequest();
    }

    function processAsAction($aModule, &$aRequest, $sClass = "Module") {

        $sClassRequire = $aModule['class_prefix'] . $sClass;
        $oModule = BxDolRequest::_require($aModule, $sClassRequire);
        $aVars = array ('BaseUri' => $oModule->_oConfig->getBaseUri());
        return BxDolRequest::processAsAction($aModule, $aRequest, $sClass);
    }
}

EmmetBytesBonConInRealtimeRequest::processAsAction($GLOBALS['aModule'], $GLOBALS['aRequest']);

?>
