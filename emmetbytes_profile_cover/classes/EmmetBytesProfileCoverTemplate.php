<?
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

bx_import ('BxDolTwigTemplate');

/*
 * ProfileCover module View
 */
class EmmetBytesProfileCoverTemplate extends BxDolTwigTemplate {
    
	/**
	 * Constructor
	 */
	function EmmetBytesProfileCoverTemplate(&$oConfig, &$oDb) {
        parent::BxDolTwigTemplate($oConfig, $oDb);
        $this->_iPageIndex = 300;
    }

    function unit ($aData, $sTemplateName, &$oVotingView) {

        if (null == $this->_oMain)
            $this->_oMain = BxDolModule::getInstance('EmmetBytesProfileCoverModule');

        if (!$this->_oMain->isAllowedView ($aData)) {            
            $aVars = array ('extra_css_class' => 'emmet_bytes_profile_cover_unit');
            return $this->parseHtmlByName('browse_unit_private', $aVars);
        }

        $sImage = '';
        if ($aData['PrimPhoto']) {
            $a = array ('ID' => $aData['ResponsibleID'], 'Avatar' => $aData['PrimPhoto']);
            $aImage = BxDolService::call('photos', 'get_image', array($a, 'browse'), 'Search');
            $sImage = $aImage['no_image'] ? '' : $aImage['file'];
        } 

        $aVars = array (            
            'id' => $aData['ID'],
            'thumb_url' => $sImage ? $sImage : $this->getIconUrl('no-photo.png'),
            'profileCover_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aData['EntryUri'],
            'profileCover_title' => $aData['Title'],
            'profileCover_start' => defineTimeInterval($aData['profileCoverStart']),
            'author' => $aData['ResponsibleID'] ? $aData['NickName'] : _t('_emmet_bytes_profile_cover_admin'),
            'author_url' => $aData['ResponsibleID'] ? getProfileLink($aData['ResponsibleID']) : 'javascript:void(0);',
            'author_title' => _t('_From'),
            'spacer' => getTemplateIcon('spacer.gif'),
            'participants' => $aData['FansCount'],
            'country_city' => _t($GLOBALS['aPreValues']['Country'][$aData['Country']]['LKey']) . (trim($aData['City']) ? ', '.$aData['City'] : ''),
        );        

        $aVars['rate'] = $oVotingView ? $oVotingView->getJustVotingElement(0, $aData['ID'], $aData['Rate']) : '&#160;';

        $aVars = array_merge ($aVars, $aData);
        return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    // ======================= ppage compose block functions 
    
    function blockInfo (&$aprofileCover) {

        $aAuthor = getProfileInfo($aprofileCover['ResponsibleID']);

        $aVars = array (
            'author_thumb' => get_member_thumbnail($aAuthor['ID'], 'none'),
            'date' => getLocaleDate($aprofileCover['Date'], BX_DOL_LOCALE_DATE_SHORT),
            'date_ago' => defineTimeInterval($aprofileCover['Date']),
            'cats' => $this->parseCategories($aprofileCover['Categories']),
            'tags' => $this->parseTags($aprofileCover['Tags']),
            'country_city' => '<a href="' . BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'browse/country/' . strtolower($aprofileCover['Country']) . '">' . _t($GLOBALS['aPreValues']['Country'][$aprofileCover['Country']]['LKey']) . '</a>' . (trim($aprofileCover['City']) ? ', '.$aprofileCover['City'] : ''),
            'flag_image' => genFlag($aprofileCover['Country']),
            'fields' => $this->blockFields($aprofileCover),
            'author_username' => $aAuthor ? $aAuthor['NickName'] : _t('_emmet_bytes_profile_cover_admin'),
            'author_url' => $aAuthor ? getProfileLink($aAuthor['ID']) : 'javascript:void(0)',
        );
        return $this->parseHtmlByName('block_info', $aVars);
    }

    function blockDesc (&$aprofileCover) {
        $aVars = array (
            'description' => $aprofileCover['Description'],
        );
        return $this->parseHtmlByName('block_description', $aVars);
    }

    function blockFields (&$aprofileCover) {
        $sRet = '<table class="emmet_bytes_profile_cover_fields">';
        emmet_bytes_profile_cover_import ('FormAdd');
        $oForm = new EmmetBytesProfileCoverFormAdd ($GLOBALS['oEmmetBytesProfileCoverModule'], $this->_iProfileId);
        foreach ($oForm->aInputs as $k => $a) {
            if (!isset($a['display'])) continue;
            $sRet .= '<tr><td class="emmet_bytes_profile_cover_field_name" valign="top">' . $a['caption'] . '<td><td class="emmet_bytes_profile_cover_field_value">';
            if (is_string($a['display']) && is_callable(array($this, $a['display'])))
                $sRet .= call_user_func_array(array($this, $a['display']), array($aprofileCover[$k]));
            else
                $sRet .= $aprofileCover[$k];
            $sRet .= '<td></tr>';
        }
        $sRet .= '</table>';
        return $sRet;
    }

    // ======================= output display filters functions

    function filterDate ($i) {
        return getLocaleDate($i, BX_DOL_LOCALE_DATE) . ' ('.defineTimeInterval($i) . ')';
    }
}

?>
