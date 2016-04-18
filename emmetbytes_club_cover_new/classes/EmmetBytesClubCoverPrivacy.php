<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

bx_import('BxDolPrivacy');

class EmmetBytesClubCoverPrivacy extends BxDolPrivacy
{
    var $oModule;

    /**
     * Constructor
     */
    function EmmetBytesClubCoverPrivacy(&$oModule)
    {
        $this->oModule = $oModule;
        parent::BxDolPrivacy($oModule->_oDb->_sPrefix . 'main', 'id', 'author_id');
    }

    /**
     * Check whethere viewer is a member of dynamic group.
     *
     * @param  mixed   $mixedGroupId   dynamic group ID.
     * @param  integer $iObjectOwnerId object owner ID.
     * @param  integer $iViewerId      viewer ID.
     * @return boolean result of operation.
     */
    function isDynamicGroupMember($mixedClubId, $iObjectOwnerId, $iViewerId, $iObjectId) {
 
        $aDataEntry = array ('id' => $iObjectId, 'author_id' => $iObjectOwnerId);
        if ('f' == $mixedClubId)  // fans + admins                       
            return ($this->oModule->isEntryAdmin ($aDataEntry, $iViewerId) || $this->oModule->isFan ($aDataEntry, $iViewerId, true)); 
        elseif ('a' == $mixedClubId) // admins only
            return $this->oModule->isEntryAdmin ($aDataEntry, $iViewerId); 
	    return false; 
 
	}    
}
