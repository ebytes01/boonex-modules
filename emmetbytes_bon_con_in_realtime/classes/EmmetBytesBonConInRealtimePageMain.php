<?php
/**********************************************************************************************
 * Created By : EmmetBytes Software Solutions
 * Created Date : June 10, 2012
 * Email : emmetbytes@gmail.com
 *
 * Copyright : (c) EmmetBytes Software Solutions 2012
 * Product Name : Boonex Contents in Realtime
 * Product Version : 1.0
 * 
 * Important : This is a commercial product by EmmetBytes Software Solutions and 
 *   cannot be modified, redistributed or resold without any written permission 
 *   from EmmetBytes Software Solutions
 **********************************************************************************************/

bx_import ('BxDolTwigPageMain');

class EmmetBytesBonConInRealtimePageMain extends BxDolTwigPageMain {	

    function EmmetBytesBonConInRealtimePageMain(&$oBonConInRealtimeMain) {        
        parent::BxDolTwigPageMain('emmet_bytes_bon_con_in_realtime_main', $oBonConInRealtimeMain);
        $this->sFilterName = 'emmet_bytes_bon_con_in_realtime_filter';
	}

}

?>
