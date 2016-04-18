<?
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
bx_import('BxDolTwigModuleDb');
class EmmetBytesBonConInRealtimeDb extends BxDolTwigModuleDb {	

	function EmmetBytesBonConInRealtimeDb(&$oConfig) {
		parent::BxDolTwigModuleDb($oConfig);
        $this->_sTableSettings = 'emmet_bytes_bon_con_in_realtime_settings';
        $this->_sFieldId = 'id';
	}

    //insert the module info blocks
    function insertBonConInRealtimeSettings($values = array()){
        if(sizeof($values) <= 0){ return false; }
        if($this->getBonConInRealtimeSettings($values['name'])){
            $this->updateBonConInRealtimeSettings($values);
        } else {
            $this->query('
                INSERT INTO '. $this->_sTableSettings .'(
                    `name`,
                    `default_tab`,
                    `maximum_numbers_of_datas`,
                    `fetch_type`,
                    `display_sites_url`,
                    `display_author`,
                    `display_date`,
                    `display_location`,
                    `display_fans_count`,
                    `display_rating`,
                    `display_size`,
                    `display_length`,
                    `display_view`,
                    `display_categories`,
                    `display_tags`,
                    `display_comments_count`,
                    `display_description`,
                    `max_description_chars`,
                    `display_contents`,
                    `max_contents_chars`
                ) VALUES (
                    "'. $values['name'] .'",
                    "'. $values['default_tab'] .'",
                    "'. $values['maximum_numbers_of_datas'] .'",
                    "'. $values['fetch_type'] .'",
                    "'. $values['display_sites_url'] .'",
                    "'. $values['display_author'] .'",
                    "'. $values['display_date'] .'",
                    "'. $values['display_location'] .'",
                    "'. $values['display_fans_count'] .'",
                    "'. $values['display_rating'] .'",
                    "'. $values['display_size'] .'",
                    "'. $values['display_length'] .'",
                    "'. $values['display_view'] .'",
                    "'. $values['display_categories'] .'",
                    "'. $values['display_tags'] .'",
                    "'. $values['display_comments_count'] .'",
                    "'. $values['display_description'] .'",
                    "'. $values['max_description_chars'] .'",
                    "'. $values['display_contents'] .'",
                    "'. $values['max_contents_chars'] .'"
                )
            ');
        }
    }

    // update the module info blocks settings values
    function updateBonConInRealtimeSettings($inputVals){
        return $this->query('
            UPDATE
                '. $this->_sTableSettings .'
            SET
                `default_tab` = "'. $inputVals['default_tab'] .'",
                `maximum_numbers_of_datas` = "'. $inputVals['maximum_numbers_of_datas'] .'",
                `fetch_type` = "'. $inputVals['fetch_type'] .'",
                `display_sites_url` = "'. $inputVals['display_sites_url'] .'",
                `display_author` = "'. $inputVals['display_author'] .'",
                `display_date` = "'. $inputVals['display_date'] .'",
                `display_location` = "'. $inputVals['display_location'] .'",
                `display_fans_count` = "'. $inputVals['display_fans_count'] .'",
                `display_rating` = "'. $inputVals['display_rating'] .'",
                `display_size` = "'. $inputVals['display_size'] .'",
                `display_length` = "'. $inputVals['display_length'] .'",
                `display_view` = "'. $inputVals['display_view'] .'",
                `display_categories` = "'. $inputVals['display_categories'] .'",
                `display_tags` = "'. $inputVals['display_tags'] .'",
                `display_comments_count` = "'. $inputVals['display_comments_count'] .'",
                `display_description` = "'. $inputVals['display_description'] .'",
                `max_description_chars` = "'. $inputVals['max_description_chars'] .'",
                `display_contents` = "'. $inputVals['display_contents'] .'",
                `max_contents_chars` = "'. $inputVals['max_contents_chars'] .'"
            WHERE
                `name` = "'.$inputVals['name'].'"
        ');
    }

    // gets the module info block settings
    function getBonConInRealtimeSettings($name){
        return $this->getRow('
            SELECT
                `ID`,
                `name`,
                `default_tab`,
                `maximum_numbers_of_datas`,
                `fetch_type`,
                `display_sites_url`,
                `display_author`,
                `display_date`,
                `display_location`,
                `display_fans_count`,
                `display_rating`,
                `display_size`,
                `display_length`,
                `display_view`,
                `display_categories`,
                `display_tags`,
                `display_comments_count`,
                `display_description`,
                `max_description_chars`,
                `display_contents`,
                `max_contents_chars`
            FROM 
                ' . $this->_sTableSettings . '
            WHERE
               `name` = "'. $name .'" 
        ');

    }

}

?>
