<?
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
bx_import('BxDolTwigModuleDb');
class EmmetBytesBonConSpecialInfoDb extends BxDolTwigModuleDb {	

	function EmmetBytesBonConSpecialInfoDb(&$oConfig) {
		parent::BxDolTwigModuleDb($oConfig);
        $this->_sTableSettings = 'emmet_bytes_bon_con_special_info_settings';
        $this->_sFieldId = 'id';
	}

    //insert the bon con special info
    function insertBonConSpecialInfoSettings($values = array()){
        if(sizeof($values) <= 0){ return false; }
        if($this->getBonConSpecialInfoSettings($values['name'])){
            $this->updateBonConSpecialInfoSettings($values);
        } else {
            $this->query('
                INSERT INTO '. $this->_sTableSettings .'(
                    `name`,
                    `default_tab`,
                    `info_image_height`,
                    `info_image_width`,
                    `display_sites_url`,
                    `display_author`,
                    `display_album`,
                    `display_rating`,
                    `display_location`,
                    `display_tags`,
                    `display_categories`,
                    `display_date_start`,
                    `display_date_end`,
                    `display_description`,
                    `max_description_chars`
                ) VALUES (
                    "'. $values['name'] .'",
                    "'. $values['default_tab'] .'",
                    "'. $values['info_image_height'] .'",
                    "'. $values['info_image_width'] .'",
                    "'. $values['display_sites_url'] .'",
                    "'. $values['display_author'] .'",
                    "'. $values['display_album'] .'",
                    "'. $values['display_rating'] .'",
                    "'. $values['display_location'] .'",
                    "'. $values['display_tags'] .'",
                    "'. $values['display_categories'] .'",
                    "'. $values['display_date_start'] .'",
                    "'. $values['display_date_end'] .'",
                    "'. $values['display_description'] .'",
                    "'. $values['max_description_chars'] .'"
                )
            ');
        }
    }

    // update the bon con special info settings values
    function updateBonConSpecialInfoSettings($inputVals){
        return $this->query('
            UPDATE
                '. $this->_sTableSettings .'
            SET
                `default_tab` = "'.$inputVals['default_tab'].'",
                `info_image_height` = "'.$inputVals['info_image_height'].'",
                `info_image_width` = "'.$inputVals['info_image_width'].'",
                `display_sites_url` = "'.$inputVals['display_sites_url'].'",
                `display_author` = "'.$inputVals['display_author'].'",
                `display_album` = "'.$inputVals['display_album'].'",
                `display_rating` = "'.$inputVals['display_rating'].'",
                `display_location` = "'.$inputVals['display_location'].'",
                `display_tags` = "'.$inputVals['display_tags'].'",
                `display_categories` = "'.$inputVals['display_categories'].'",
                `display_date_start` = "'.$inputVals['display_date_start'].'",
                `display_date_end` = "'.$inputVals['display_date_end'].'",
                `display_description` = "'.$inputVals['display_description'].'",
                `max_description_chars` = "'.$inputVals['max_description_chars'].'"
            WHERE
                `name` = "'.$inputVals['name'].'"
        ');
    }

    // gets the module info block settings
    function getBonConSpecialInfoSettings($name){
        return $this->getRow('
            SELECT
                `ID`,
                `name`,
                `default_tab`,
                `info_image_height`,
                `info_image_width`,
                `display_sites_url`,
                `display_author`,
                `display_album`,
                `display_rating`,
                `display_location`,
                `display_tags`,
                `display_categories`,
                `display_date_start`,
                `display_date_end`,
                `display_description`,
                `max_description_chars`
            FROM 
                ' . $this->_sTableSettings . '
            WHERE
               `name` = "'. $name .'" 
        ');

    }

}

?>
