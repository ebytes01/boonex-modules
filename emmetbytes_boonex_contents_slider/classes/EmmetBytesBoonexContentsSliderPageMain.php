<?php
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

bx_import ('BxDolTwigPageMain');

class EmmetBytesBoonexContentsSliderPageMain extends BxDolTwigPageMain {	

    function EmmetBytesBoonexContentsSliderPageMain(&$oBoonexContentsSliderMain) {        
        parent::BxDolTwigPageMain('emmet_bytes_boonex_contents_slider_main', $oBoonexContentsSliderMain);
        $this->sFilterName = 'emmet_bytes_boonex_contents_slider_filter';
	}

}

?>
