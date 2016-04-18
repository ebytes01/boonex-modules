<?
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
require_once(BX_DIRECTORY_PATH_INC . 'profiles.inc.php');

check_logged();

bx_import('BxDolRequest');

class EmmetBytesBoonexContentsSliderRequest extends BxDolRequest {

    function EmmetBytesBoonexContentsSliderRequest() {
        parent::BxDolRequest();
    }

    function processAsAction($aModule, &$aRequest, $sClass = "Module") {

        $sClassRequire = $aModule['class_prefix'] . $sClass;
        $oModule = BxDolRequest::_require($aModule, $sClassRequire);
        $aVars = array ('BaseUri' => $oModule->_oConfig->getBaseUri());
        return BxDolRequest::processAsAction($aModule, $aRequest, $sClass);
    }
}

EmmetBytesBoonexContentsSliderRequest::processAsAction($GLOBALS['aModule'], $GLOBALS['aRequest']);

?>
