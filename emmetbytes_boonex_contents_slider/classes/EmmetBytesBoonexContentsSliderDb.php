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
bx_import('BxDolTwigModuleDb');
class EmmetBytesBoonexContentsSliderDb extends BxDolTwigModuleDb {	

	function EmmetBytesBoonexContentsSliderDb(&$oConfig) {
		parent::BxDolTwigModuleDb($oConfig);
        $this->_sTableSettings = 'emmet_bytes_boonex_contents_slider_settings';
        $this->_sFieldId = 'id';
	}

    //insert the boonex contents slider
    function insertBoonexContentsSliderSettings($values = array()){
        if(sizeof($values) <= 0){ return false; }
        if($this->getBoonexContentsSliderSettings($values['name'])){
            $this->updateBoonexContentsSliderSettings($values);
        } else {
            $this->query('
                INSERT INTO '. $this->_sTableSettings .'(
                    `name`,
                    `default_tab`,
                    `maximum_datas`,
                    `maximum_title_characters`,
                    `display_date_start`,
                    `display_created_date`,
                    `display_views`,
                    `display_size`,
                    `display_categories`,
                    `display_tags`,
                    `display_comments_count`,
                    `display_author`,
                    `display_rate`,
                    `display_fans_count`,
                    `display_location`,
                    `display_site_url`,
                    `display_ads_price`,
                    `display_description`,
                    `maximum_description_characters`
                ) VALUES (
                    "'. $values['name'] .'",
                    "'. $values['default_tab'] .'",
                    "'. $values['maximum_datas'].'",
                    "'. $values['maximum_title_characters'] .'",
                    "'. $values['display_date_start'] .'",
                    "'. $values['display_created_date'] .'",
                    "'. $values['display_views'] .'",
                    "'. $values['display_size'] .'",
                    "'. $values['display_categories'] .'",
                    "'. $values['display_tags'] .'",
                    "'. $values['display_comments_count'] .'",
                    "'. $values['display_author'] .'",
                    "'. $values['display_rate'] .'",
                    "'. $values['display_fans_count'] .'",
                    "'. $values['display_location'] .'",
                    "'. $values['display_site_url'] .'",
                    "'. $values['display_ads_price'] .'",
                    "'. $values['display_description'] .'",
                    "'. $values['maximum_description_characters'] .'"
                )
            ');
        }
    }

    // update the boonex contents slider settings values
    function updateBoonexContentsSliderSettings($inputVals){
        return $this->query('
            UPDATE
                '. $this->_sTableSettings .'
            SET
                `default_tab` = "'.$inputVals['default_tab'].'",
                `maximum_datas` = "'.$inputVals['maximum_datas'].'",
                `maximum_title_characters` = "'.$inputVals['maximum_title_characters'].'",
                `display_date_start` = "'.$inputVals['display_date_start'].'",
                `display_created_date` = "'.$inputVals['display_created_date'].'",
                `display_views` = "'.$inputVals['display_views'].'",
                `display_size` = "'.$inputVals['display_size'].'",
                `display_categories` = "'.$inputVals['display_categories'].'",
                `display_tags` = "'.$inputVals['display_tags'].'",
                `display_comments_count` = "'.$inputVals['display_comments_count'].'",
                `display_author` = "'.$inputVals['display_author'].'",
                `display_rate` = "'.$inputVals['display_rate'].'",
                `display_fans_count` = "'.$inputVals['display_fans_count'].'",
                `display_location` = "'.$inputVals['display_location'].'",
                `display_site_url` = "'.$inputVals['display_site_url'].'",
                `display_ads_price` = "'.$inputVals['display_ads_price'].'",
                `display_description` = "'.$inputVals['display_description'].'",
                `maximum_description_characters` = "'.$inputVals['maximum_description_characters'].'"
            WHERE
                `name` = "'.$inputVals['name'].'"
        ');
    }

    // gets the module info block settings
    function getBoonexContentsSliderSettings($name){
        return $this->getRow('
            SELECT
                `ID`,
                `name`,
                `default_tab`,
                `maximum_datas`,
                `maximum_title_characters`,
                `display_date_start`,
                `display_created_date`,
                `display_views`,
                `display_size`,
                `display_categories`,
                `display_tags`,
                `display_comments_count`,
                `display_author`,
                `display_rate`,
                `display_fans_count`,
                `display_location`,
                `display_site_url`,
                `display_ads_price`,
                `display_description`,
                `maximum_description_characters`
            FROM 
                ' . $this->_sTableSettings . '
            WHERE
               `name` = "'. $name .'" 
        ');

    }

}

?>
