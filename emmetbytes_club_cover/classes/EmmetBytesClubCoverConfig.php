<?
/**********************************************************************************************
 * Created By : EmmetBytes Software Solutions
 * Created Date : February 20, 2013
 * Email : emmetbytes@gmail.com
 *
 * Copyright : (c) EmmetBytes Software Solutions 2012
 * Product Name : Club Cover
 * Product Version : 1.1
 * 
 * Important : This is a commercial product by EmmetBytes Software Solutions and 
 *   cannot be modified, redistributed or resold without any written permission 
 *   from EmmetBytes Software Solutions
 **********************************************************************************************/

bx_import('BxDolConfig');

class EmmetBytesClubCoverConfig extends BxDolConfig {
	var $sMediaPath;
	var $sMediaUrl;
    /**
     * Constructor
     */
    function EmmetBytesClubCoverConfig($aModule) {
        parent::BxDolConfig($aModule);

        $this->sClubModuleMediaPath = BX_DIRECTORY_PATH_MODULES . "modzzz/club/media/"; 
		$this->sClubModuleMediaUrl =  BX_DOL_URL_MODULES . "modzzz/club/media/";  
    }

    function getClubModuleMediaDir(){
        return $this->sClubModuleMediaPath;
    }

    function getClubModuleMediaUrl(){
        return $this->sClubModuleMediaUrl;
    }
}

?>
