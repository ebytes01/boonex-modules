<?php
/**********************************************************************************************
 * Created By : EmmetBytes Software Solutions
 * Created Date : June 10, 2012
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
require_once(BX_DIRECTORY_PATH_CLASSES . "BxDolInstaller.php"); class EmmetBytesBoonexContentsSliderInstaller extends BxDolInstaller { function EmmetBytesBoonexContentsSliderInstaller($aConfig) { parent::BxDolInstaller($aConfig); } function install($aParams) { $aResult = parent::install($aParams); if($aResult['result'] && BxDolRequest::serviceExists('wall', 'update_handlers')) BxDolService::call('wall', 'update_handlers', array($this->_aConfig['home_uri'], true)); if($aResult['result'] && BxDolRequest::serviceExists('spy', 'update_handlers')) BxDolService::call('spy', 'update_handlers', array($this->_aConfig['home_uri'], true)); $this->addHtmlFields (array ('POST.Description', 'REQUEST.Description')); $this->updateEmailTemplatesExceptions (); return $aResult; } function uninstall($aParams) { if(BxDolRequest::serviceExists('wall', 'update_handlers')) BxDolService::call('wall', 'update_handlers', array($this->_aConfig['home_uri'], false)); if(BxDolRequest::serviceExists('spy', 'update_handlers')) BxDolService::call('spy', 'update_handlers', array($this->_aConfig['home_uri'], false)); $this->removeHtmlFields (); $this->updateEmailTemplatesExceptions (); return parent::uninstall($aParams); }    }
?>
