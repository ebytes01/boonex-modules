<?php

/************************************************************************************************************
 * EmmetBytes Default Profile Cvr
 * Default Profile Cvr For Creating New Boonex Modules
 ************************************************************************************************************/

require_once(BX_DIRECTORY_PATH_CLASSES . "BxDolInstaller.php");

class EmmetBytesProfileCvrInstaller extends BxDolInstaller {

    function EmmetBytesProfileCvrInstaller($aConfig) {
        parent::BxDolInstaller($aConfig);
    }

    function install($aParams) {
        $aResult = parent::install($aParams);

        if($aResult['result'] && BxDolRequest::serviceExists('wall', 'update_handlers'))
            BxDolService::call('wall', 'update_handlers', array($this->_aConfig['home_uri'], true));

        if($aResult['result'] && BxDolRequest::serviceExists('spy', 'update_handlers'))
            BxDolService::call('spy', 'update_handlers', array($this->_aConfig['home_uri'], true));

        $this->addHtmlFields (array ('POST.Description', 'REQUEST.Description'));
		$this->updateEmailTemplatesExceptions ();

        return $aResult;
    }

    function uninstall($aParams) {

        if(BxDolRequest::serviceExists('wall', 'update_handlers'))
            BxDolService::call('wall', 'update_handlers', array($this->_aConfig['home_uri'], false));

        if(BxDolRequest::serviceExists('spy', 'update_handlers'))
            BxDolService::call('spy', 'update_handlers', array($this->_aConfig['home_uri'], false));

        $this->removeHtmlFields ();
		$this->updateEmailTemplatesExceptions ();

        return parent::uninstall($aParams);
    }    
}
?>
