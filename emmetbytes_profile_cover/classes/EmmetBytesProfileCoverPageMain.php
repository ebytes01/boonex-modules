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

bx_import ('BxDolTwigPageMain');

class EmmetBytesProfileCoverPageMain extends BxDolTwigPageMain {	

    function EmmetBytesProfileCoverPageMain(&$oProfileCoverMain) {        
        parent::BxDolTwigPageMain('emmet_bytes_profile_cover_main', $oProfileCoverMain);
        $this->sFilterName = 'emmet_bytes_profile_cover_filter';
	}

}

?>
