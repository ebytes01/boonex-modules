/**
 * EmmetBytes Realtime InfoBlock Main Javascript
 */

function EmmetBytesBonConInRealtime(params){
    var $this = this;
    // block id
    this.blockId = params.blockId;
    // entry id prefix
    this.entryIdPrefix = '#entry_' + this.blockId;
    // new entries caption
    this.newEntriesCaption = params.newEntriesCaption;
    // removed entries caption
    this.removedEntriesCaption = params.removedEntriesCaption;
    // display the show new button
    this.displayShowNewButton = params.displayShowNewButton;
    // for the main container
    this.mainContainerId = params.mainContainerId;
    this.mainContainerObj = $('#' + this.mainContainerId);
    // new entries container 
    this.newEntriesContainerClass = params.newEntriesContainerClass;
    this.newEntriesContainerObj = this.mainContainerObj.find('.' + this.newEntriesContainerClass); 
    // for the thumbnails container
    this.thumbnailsContainerClass = params.thumbnailsContainerClass;
    this.thumbnailsContainerObj = this.mainContainerObj.find('.' + this.thumbnailsContainerClass); 
    // for the thumbnails inner class
    this.thumbnailsContainerInnerClass = params.thumbnailsContainerInnerClass;
    this.thumbnailsContainerInnerObj = this.thumbnailsContainerObj.find('.' + this.thumbnailsContainerInnerClass);
    // for the individual thumbnails container
    this.thumbnailContainerClass = params.thumbnailContainerClass;
    this.thumbnailContainerObj = this.thumbnailsContainerInnerObj.find('.' + this.thumbnailContainerClass);
    // for the empty container and private container
    this.privateContainerContent = $(decodeURIComponent(params.privateContainerContent));
    this.emptyContainerClass = params.emptyContainerClass;
    this.emptyContainerContent = $(decodeURIComponent(params.emptyContainerContent));
    // go to page 
    this.thumbnailGoToPageClass = params.thumbnailGoToPageClass;     
    this.thumbnailGoToPageObj = this.mainContainerObj.find('.' + this.thumbnailGoToPageClass);
    // sets up the new entries url
    this.getNewEntriesUrl = params.getNewEntriesUrl;
    this.checkingEntries = false;
    // module variables
    this.moduleVars = params.moduleVars;
    // search results
    this.searchResults =  params.searchResults;
    this.executingEntriesActions = false;
    // additional entries
    this.additionalEntries = new Array();
    // removable entries
    this.removableEntries = new Array();
    // execute the entries
    this.newEntriesChecker = setInterval(function(){ $this.checkNewEntries(); }, 6000);
    this.executeEntries = false;
}

// gets and checks for new entries
EmmetBytesBonConInRealtime.prototype.checkNewEntries = function(){
    var $this = this;
    if(!this.checkingEntries && !this.executingEntriesActions){
        this.checkingEntries = true;
        try{
            var ebReq = $.ajax({
                type : 'POST',
                url : $this.getNewEntriesUrl,
                data : {searchResults : JSON.stringify($this.searchResults), moduleVars : JSON.stringify($this.moduleVars)},
                timeout : 10000,
                success : function(resp){
                    try{
                        var _resp = eval('(' + resp + ')');
                        $this.resp = _resp;
                        $this.additional_entries = _resp.additional_entries;
                        $this.removable_entries = _resp.removable_entries;
                        $this.sortable_entries = _resp.sortable_entries;
                        $this.refreshed_entries = _resp.refreshed_entries;
                        $this.isPrivate = _resp.is_private;
                        if(!$this.executingEntriesActions){
                            if($this.displayShowNewButton == 'true'){
                                $this.initNewEntriesButton(_resp);
                            }else{
                                $this.moduleVars.total_entries = _resp.total_entries;
                                $this.moduleVars.data_ids = _resp.new_data_ids;
                                $this.searchResults = _resp.search_result_datas;
                                $this.executeEntriesActions(0);
                            }
                            $this.checkingEntries = false;
                        }
                    }catch(err){
                        $this.checkingEntries = false;
                    }
                },
                error : function(request, status, err){
                    $this.checkingEntries = false; 
                }
            });
        }catch(err){
            $this.checkingEntries = false;
        }
    }
}

// execute the entries actions
EmmetBytesBonConInRealtime.prototype.executeEntriesActions = function(arrayIndex){
    if(arrayIndex >= this.additional_entries.length && arrayIndex >= this.removable_entries.length){
        // adds the go to page if there arent any
        if(this.moduleVars.total_entries > this.moduleVars.mod_vars.settings.maximum_numbers_of_datas && !this.thumbnailGoToPageObj.is(':visible')){
            this.thumbnailGoToPageObj.fadeIn();
        }else if(this.moduleVars.total_entries <= this.moduleVars.mod_vars.settings.maximum_numbers_of_datas && this.thumbnailGoToPageObj.is(':visible')){
            this.thumbnailGoToPageObj.fadeOut();
        }
        if(this.sortable_entries.length > 0){
            this.sortableEntries = this.sortable_entries;
            this.srtLength = this.sortableEntries.length;
            this.sortEntries(0);
        }else{
            this.executingEntriesActions = false; 
        }
        this.additional_entries = new Array();
        this.removable_entries = new Array();
    }
    if(!this.executeEntries && (this.additional_entries.length > 0 || this.removable_entries.length > 0)){
        var $this = this; 
        this.executingEntriesActions = true;
        this.executeEntries = true;
        var _additional_entry = (this.additional_entries.length > 0) ? this.additional_entries[arrayIndex] : null;
        var _removable_entry = (this.removable_entries.length > 0) ? this.removable_entries[arrayIndex] : null;
        arrayIndex += 1;
        if(null != _removable_entry){
            this.thumbnailsContainerInnerObj
                .find(this.entryIdPrefix + '_' + _removable_entry)
                .slideUp('1200', function(){
                    $(this).remove();
                    if(_additional_entry == null){
                        $this.executeEntries = false;
                        $this.executeEntriesActions(arrayIndex);
                    }
                    var thumbnailContainers = $this.thumbnailsContainerInnerObj.find('.' + $this.thumbnailContainerClass);
                    if(null == thumbnailContainers || thumbnailContainers.length <= 0){
                        if($this.isPrivate == 'true'){
                            $this.privateContainerContent
                            .appendTo($this.thumbnailsContainerObj);
                            $this.thumbnailGoToPageObj.hide();
                        }else{
                            $this.emptyContainerContent
                            .appendTo($this.thumbnailsContainerObj);
                            $this.thumbnailGoToPageObj.hide();
                        }
                    }
                });
        }
        if(null != _additional_entry){
            // removes the empty container if theres any
            var _emptyContainer = this.thumbnailsContainerObj.find('.' + this.emptyContainerClass);
            if(_emptyContainer.length > 0){
                _emptyContainer.remove();
            }
            if(_additional_entry.before == 'last'){
                $(_additional_entry.thumbnailContainer)
                    .appendTo(this.thumbnailsContainerInnerObj)
                    .hide()
                    .slideDown(1200, function(){
                        $this.executeEntries = false;
                        $this.executeEntriesActions(arrayIndex);
                    });
            }else{
                $(_additional_entry.thumbnailContainer)
                    .insertBefore(this.entryIdPrefix + '_' + _additional_entry.before)
                    .hide()
                    .slideDown(1200, function(){
                        $this.executeEntries = false;
                        $this.executeEntriesActions(arrayIndex);
                    });
            }
        }
    }
}

// reorder the entries 
EmmetBytesBonConInRealtime.prototype.sortEntries = function(sortIndex){
    if(sortIndex >= this.srtLength){
        this.executingEntriesActions = false; 
    }else{
        var $this = this;
        var _sortableEntry = this.sortableEntries[sortIndex];
        sortIndex++;
        var _sortableEntryId = _sortableEntry.id;
        var _sortableEntryTopId = _sortableEntry.after;
        var _sortableEntryCurrentTopId = this.thumbnailsContainerObj
            .find(this.entryIdPrefix + '_' + _sortableEntryId)
            .prev().attr('id');
        var _alreadyDisplayed = this.thumbnailsContainerObj
            .find(this.entryIdPrefix + '_' + _sortableEntryId);

        if(undefined==_sortableEntryCurrentTopId && _sortableEntryTopId=='first' && _sortableEntry.changed!='true'){
            this.sortEntries(sortIndex);
        }else if(('#'+_sortableEntryCurrentTopId)!=(this.entryIdPrefix + '_' + _sortableEntryTopId) || _sortableEntry.changed=='true'){
            this.thumbnailsContainerObj
            .find(this.entryIdPrefix + '_' + _sortableEntryId)
            .fadeOut('fast', function(){
                $(this).remove();
                if(_sortableEntry.after == 'first'){
                    $(_sortableEntry.thumbnailContainer)
                    .prependTo($this.thumbnailsContainerInnerObj)
                    .hide()
                    .fadeIn('slow', function(){
                        $this.sortEntries(sortIndex);
                        });
                }else{
                    $(_sortableEntry.thumbnailContainer)
                    .insertAfter($this.entryIdPrefix + '_' + _sortableEntryTopId)
                    .hide()
                    .fadeIn('slow', function(){
                        $this.sortEntries(sortIndex);
                    });
                }
            }); 
        }else{
            this.sortEntries(sortIndex);
        }
    }
}

// initialize the new entries button 
EmmetBytesBonConInRealtime.prototype.initNewEntriesButton = function(_resp){
    var $this = this;
    if(!this.executingEntriesActions && (_resp.additional_entries_count > 0 || _resp.removable_entries_count > 0 || _resp.sortable_entries.length > 0)){
        var _LoadText;
        if(!this.executingEntriesActions && _resp.additional_entries_count > 0){
            _LoadText = this.newEntriesCaption;
        }else{
            _LoadText = this.removedEntriesCaption;
        }
        this.newEntriesContainerObj
            .html(_LoadText)
            .fadeIn('slow')
            .unbind('click')
            .click(function(){
                $this.executingEntriesActions = true;
                $this.moduleVars.total_entries = _resp.total_entries;
                $this.moduleVars.data_ids = _resp.new_data_ids;
                $this.searchResults = _resp.search_result_datas;
                $(this).html('').fadeOut('fast');
                $this.executeEntriesActions(0);
            });
    }
}

