<?php
/**********************************************************************************************
 * Created By : EmmetBytes Software Solutions
 * Created Date : June 10, 2012
 * Email : emmetbytes@gmail.com
 *
 * Copyright : (c) EmmetBytes Software Solutions 2012
 * Product Name : Bon Con Special Info
 * Product Version : 1.0
 * 
 * Important : This is a commercial product by EmmetBytes Software Solutions and 
 *   cannot be modified, redistributed or resold without any written permission 
 *   from EmmetBytes Software Solutions
 **********************************************************************************************/

bx_import ('BxDolTwigPageMain');

class EmmetBytesBonConSpecialInfoPageMain extends BxDolTwigPageMain {	

    function EmmetBytesBonConSpecialInfoPageMain(&$oBonConSpecialInfoMain) {        
        parent::BxDolTwigPageMain('emmet_bytes_bon_con_special_info_main', $oBonConSpecialInfoMain);
        $this->sFilterName = 'emmet_bytes_bon_con_special_info_filter';
	}

}

?>
