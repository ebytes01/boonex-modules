/**********************************************************************************************
 * Created By : EmmetBytes
 * Created Date : June 10, 2012
 * Email : emmetbytes@gmail.com
 *
 * Copyright : (c) EmmetBytes 2012
 * Product Name : Bon Con Special Info
 * Product Version : 1.0
 * 
 * Important : This is a commercial product by EmmetBytes 
 * and cannot be modified 
 *
 * This product cannot be redistributed for free or a fee without written 
 * permission from EmmetBytes
 **********************************************************************************************/

//create the class for the module info block
function EBBonConSpecialInfo(params){

    var $this = this;
    // initialize the values
    // set up for the entries url
    this.getEntriesUrl = params.getEntriesUrl;
    
    // set up for the get information container url
    this.getInfoContainerUrl = params.getInfoContainerUrl; 

    // set up for the main container
    this.mainContainerId = params.mainContainerId;
    this.mainContainerObj = $('#' + this.mainContainerId);

    // set up for the loader container
    this.loaderContainerClass = params.loaderContainerClass;
    this.loaderContainerObj = this.mainContainerObj.find('.' + this.loaderContainerClass);

    // set up for the info container
    this.infoContainerClass = params.infoContainerClass;
    this.infoContainerObj = this.mainContainerObj.find('.' + this.infoContainerClass);

    // set up for the navigation container
    this.thumbnailsContainerNavPrevClass = params.thumbnailsContainerNavPrevClass;
    this.thumbnailsContainerNavPrevObj = this.mainContainerObj.find('.' + this.thumbnailsContainerNavPrevClass);
    this.thumbnailsContainerNavPrevObjWidth = this.thumbnailsContainerNavPrevObj.outerWidth();

    this.thumbnailsContainerNavNextClass = params.thumbnailsContainerNavNextClass;
    this.thumbnailsContainerNavNextObj = this.mainContainerObj.find('.' + this.thumbnailsContainerNavNextClass);
    this.thumbnailsContainerNavNextObjWidth = this.thumbnailsContainerNavNextObj.outerWidth();

    // set up for the thumbnails container
    this.thumbnailsContainerClass = params.thumbnailsContainerClass;
    this.thumbnailsContainerObj = this.mainContainerObj.find('.' + this.thumbnailsContainerClass);
    this.thumbnailsContainerObjWidth = this.thumbnailsContainerObj.outerWidth();
    this.thumbnailsContainerObjOffset = this.thumbnailsContainerObj.offset();
    this.thumbnailsContainerObjOffsetLeft = this.thumbnailsContainerObjOffset.left;
    this.thumbnailsContainerObjOffsetRight = this.thumbnailsContainerObjOffset.left + this.thumbnailsContainerObjWidth - this.thumbnailsContainerNavNextObjWidth;
    this.thumbnailsContainerInnerClass = params.thumbnailsContainerInnerClass;
    this.thumbnailsContainerInnerObj = this.mainContainerObj.find('.' + this.thumbnailsContainerInnerClass);  

     //set up for the thumbnail container
    this.thumbnailContainerClass = params.thumbnailContainerClass;

    //sets up the default movement gap to zero
    this.slideGap = this.slideGapWidth = 0;
    this.thumbnailsContainerReferenceOffsetLeft = this.thumbnailsContainerObjOffsetLeft + this.thumbnailsContainerNavPrevObjWidth;
    this.thumbnailReferenceWidth = this.thumbnailsContainerObjWidth - this.thumbnailsContainerNavPrevObjWidth - this.thumbnailsContainerNavNextObjWidth;

    // set up for the module datas
    this.moduleVars = params.moduleVars;
    this.moduleVars.page = 1;
    this.currentEntry = params.moduleVars.current_entry;
    
    // flag to show the next entries
    this.processNextEntries = false;

    this.initInfoSwitcher(); //initialize the switcher
    this.initThumbnailsContainerInnerWidth();
    this.autoGetNextEntries = setInterval(function(){ $this.getNextEntries() }, 4000);
}

//initialize the information switcher
EBBonConSpecialInfo.prototype.initInfoSwitcher = function(){
    var $this = this;            
    this.thumbnailsContainerObj
    .find('.' + this.thumbnailContainerClass)
    .unbind('click').click(function(){
        var _this = this;
        var _entryId = $(this).attr('entry_id');
        if(_entryId != $this.currentEntry){
            $this.showLoader();
            $this.currentEntry = _entryId;
            $this.getInfoContainer($(_this), _entryId);
        }
    });    
}

// getting the information container
EBBonConSpecialInfo.prototype.getInfoContainer = function(_selObj, _entryId){
    var $this = this;
    _selObj.parent().find('.active').removeClass('active');
    var _formParams = JSON.stringify(this.moduleVars);
    $.post(this.getInfoContainerUrl, { entryID : _entryId, moduleVars : _formParams }, function(resp){
        _selObj.addClass('active');
        $this.displayInfoContainer(resp);
        $this.hideLoader();
    });
}

// show the loader
EBBonConSpecialInfo.prototype.showLoader = function(){
    this.loaderContainerObj.show();
}

// hide the loader
EBBonConSpecialInfo.prototype.hideLoader = function(){
    this.loaderContainerObj.hide();
}

// display the information container
EBBonConSpecialInfo.prototype.displayInfoContainer = function(contents){
    this.infoContainerObj
    .html($(contents));
}

//sets up the thumbnails container width
EBBonConSpecialInfo.prototype.initThumbnailsContainerInnerWidth = function(){
    var $this = this;
    this.thumbnailContainerObjs = $(this.thumbnailsContainerInnerObj.find('.' + this.thumbnailContainerClass));
    this.thumbnailContainerObjsLength = this.thumbnailContainerObjs.length;
    this.thumbContainerWidth = $(this.thumbnailContainerObjs.get(0)).outerWidth(true) + 4;
    this.slideGap = Math.floor(this.thumbnailReferenceWidth/this.thumbContainerWidth);
    this.slideGapWidth = this.slideGap * this.thumbContainerWidth;
    this.thumbnailsContainerInnerObjWidth = this.thumbContainerWidth * this.thumbnailContainerObjsLength;
    this.thumbnailsContainerInnerObj.width(this.thumbnailsContainerInnerObjWidth);
    this.initThumbnailsSlider();
}

//initialize the thumbnails slider
EBBonConSpecialInfo.prototype.initThumbnailsSlider = function(){
    var $this = this;
    if(this.thumbnailsContainerObjWidth < this.thumbnailsContainerInnerObjWidth){
        this.resetInnerMargin();
    }else{
        this.removeNavigations();
    }

    this.thumbnailsContainerNavPrevObj.click(function(){
        if(!$this.slide){
            var _offset = $this.thumbnailsContainerInnerObj.offset();
            var _offsetLeft = _offset.left; 
            if(_offsetLeft < $this.thumbnailsContainerReferenceOffsetLeft){
                $this.slide = true;
                $this.showBatch($this.slideGapWidth);
            }
        }
    });

    this.thumbnailsContainerNavNextObj.click(function(){
        if(!$this.slide){
            var _offset = $this.thumbnailsContainerInnerObj.offset();
            var _offsetRight = _offset.left + $this.thumbnailsContainerInnerObjWidth; 
            if(_offsetRight > $this.thumbnailsContainerObjOffsetRight){
                $this.slide = true;
                $this.showBatch(-$this.slideGapWidth);
            }
        }
    });
}

// reset the inner margin
EBBonConSpecialInfo.prototype.resetInnerMargin = function(){
    this.thumbnailsContainerInnerObj.css({'marginLeft' : this.thumbnailsContainerNavPrevObjWidth + 'px'});
}

// remove the navigations
EBBonConSpecialInfo.prototype.removeNavigations = function(){
    this.thumbnailsContainerNavPrevObj.hide();
    this.thumbnailsContainerNavNextObj.hide();
}

//show the next or previous batch of datas
EBBonConSpecialInfo.prototype.showBatch = function(_gapWidth){
    var $this = this;
    var left = parseInt(this.thumbnailsContainerInnerObj.css('left')) + _gapWidth;
    this.thumbnailsContainerInnerObj.animate({'left' : left + 'px'}, function(){
        $this.slide = false;
        $this.getNextEntries();
    });
}

// gets the next batch of entries
EBBonConSpecialInfo.prototype.getNextEntries = function(){
    if(this.moduleVars.num_of_displayed_entries < this.moduleVars.total_entries){
        if(!this.processNextEntries){
            var $this = this;
            this.processNextEntries = true;
            this.moduleVars.page = this.moduleVars.page + 1;
            var _formParams = JSON.stringify(this.moduleVars);
            this.appendEntryLoader();
            $.post(this.getEntriesUrl, { moduleVars : _formParams }, function(resp){
                var _resp = eval('(' + resp + ')');
                $this.moduleVars.num_of_displayed_entries = _resp.num_of_displayed_entries;
                $this.thumbnailsContainerInnerObjWidth = $this.thumbContainerWidth * _resp.num_of_displayed_entries;
                var _content = $(_resp.content);
                $this.thumbnailsContainerInnerObj.animate({'width' : $this.thumbnailsContainerInnerObjWidth + 'px'}, 0, function(){
                    $this.thumbnailsContainerInnerObj.append(_content);
                    $this.processNextEntries = false;
                    $this.initInfoSwitcher();
                    $this.removeEntryLoader();
                });
            });
        }
    }else{
        if(undefined != this.autoGetNextEntries){
            clearInterval(this.autoGetNextEntries);
            delete this.autoGetNextEntries;
        }
    }
}

EBBonConSpecialInfo.prototype.appendEntryLoader = function(){
   var $this = this; 
   var _width = ($this.thumbContainerWidth * $this.moduleVars.num_of_displayed_entries) + 170;
   $this.thumbnailsContainerInnerObj.css({'width' : _width + 'px'}).append('<div class="emmetbytes_bon_con_special_info_common_thumbnail_container entries_loader"></div>');
}

EBBonConSpecialInfo.prototype.removeEntryLoader = function(){
   var $this = this; 
   $this.thumbnailsContainerInnerObj.find('.entries_loader').remove();
}
