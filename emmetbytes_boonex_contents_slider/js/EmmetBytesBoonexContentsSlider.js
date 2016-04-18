/**********************************************************************************************
 * Created By : EmmetBytes
 * Created Date : January 10, 2013
 * Email : emmetbytes@gmail.com
 *
 * Copyright : (c) EmmetBytes 2012
 * Product Name : Boonex Contents Slider
 * Product Version : 1.0
 * 
 * Important : This is a commercial product by EmmetBytes 
 * and cannot be modified 
 *
 * This product cannot be redistributed for free or a fee without written 
 * permission from EmmetBytes
 **********************************************************************************************/

//create the class for the module info block
function EmmetBytesBoonexContentsSlider(params){
    var $this = this;
    // configurations
    // main container settings
    this.mainContainerClass = params.mainContainerClass;
    this.mainContainerId = params.mainContainerId;
    this.mainContainerObj = $('#' + this.mainContainerId);
    this.mainContainerObjWidth = $this.mainContainerObj.width();
    // this.mainContainerObj.css({'border' : '1px solid green'});

    // slider container inner obj
    this.sliderContainerInnerClass = params.sliderContainerInnerClass;
    this.mainContainerObj.find('.' + this.sliderContainerInnerClass);
    this.mainContainerObjOffset = this.mainContainerObj.offset();
    this.mainContainerObjOffsetLeft = this.mainContainerObjOffset.left;
    this.mainContainerObjOffsetRight = this.mainContainerObjOffsetLeft + this.mainContainerObj.width();
    // this.mainContainerObj.css({'border' : '1px solid red'});

    // sliders container inner objects
    this.slidersContainerInnerClass = params.slidersContainerInnerClass;
    this.slidersContainerInnerObj = this.mainContainerObj.find('.' + this.slidersContainerInnerClass);
    // this.slidersContainerInnerObj.css({'border' : '1px solid blue'});

    // slider container main object
    this.sliderContainerMainClass = params.sliderContainerMainClass;
    this.sliderContainerMainObj = this.mainContainerObj.find('.' + this.sliderContainerMainClass);
    this.sliderContainerMainCount = this.sliderContainerMainObj.length;
    this.sliderContainerHeight = 207;
    // this.sliderContainerMainObj.css({'border' : '1px solid red'});

    // slider container objects
    this.sliderContainerClass = params.sliderContainerClass;
    this.sliderContainerObj = this.mainContainerObj.find('.' + this.sliderContainerClass);
    this.sliderContainerObjWidth = this.sliderContainerObj.width();
    this.slidersContainerWidth = ($this.mainContainerObjWidth + 2) * this.sliderContainerMainCount;
    // this.sliderContainerObj.css({'border' : '1px solid blue'});

    // slider inner container class - image container
    this.sliderInnerContainerClass = params.sliderInnerContainerClass;
    this.sliderInnerContainerObj = this.mainContainerObj.find('.' + this.sliderInnerContainerClass);

    // slider informations container
    this.sliderInformationsContainer = params.sliderInformationsContainer;
    this.sliderInformationsContainerObj = this.mainContainerObj.find('.' + this.sliderInformationsContainer);

    // slider informations
    this.sliderTitleContainer = params.sliderTitleContainer;
    this.sliderAdditionalDatasContainer = params.sliderAdditionalDatasContainer;
    this.sliderAdditionalDataContainer = params.sliderAdditionalDataContainer;
    this.sliderAdditionalDataSeparator = params.sliderAdditionalDataSeparator;
    this.sliderLocationContainer = params.sliderLocationContainer;
    this.sliderFansCountContainer = params.sliderFansCountContainer;
    this.sliderRatingContainer = params.sliderRatingContainer;
    this.sliderAuthorContainer = params.sliderAuthorContainer;

    // settings for the navigation
    this.mainContainerNavigationClass = params.mainContainerNavigationClass;
    this.mainContainerNavigationObj = this.mainContainerObj.find('.' + this.mainContainerNavigationClass);

    this.slidersNavInnerContainers = params.slidersNavInnerContainers;
    this.slidersNavInnerContainersObj = this.mainContainerObj.find('.' + this.slidersNavInnerContainers);

    this.slidersNavCountersContainer = params.slidersNavCountersContainer;
    this.slidersNavCountersContainerObj = this.mainContainerObj.find('.' + this.slidersNavCountersContainer);
    // this.slidersNavCountersContainerObj.css({'border' : '1px solid blue'});

    this.slidersNavCounterContainer = params.slidersNavCounterContainer;
    // this.slidersNavCounterContainerObj.css({'border' : '1px solid red'});

    this.slidersNavCounterContainerActive  = params.slidersNavCounterContainerActive;
    // this.slidersNavCounterContainerActiveObj.css({'border' : '1px solid blue'});

    this.slidersNavContainerPrev = params.slidersNavContainerPrev;
    this.slidersNavContainerNext = params.slidersNavContainerNext;

    // set the image current index
    this.currentIndex = 1;
    this.slideTime = this.slideTimeDefault = 4000;
    // set the container height
    this.getContainersHeights();
}

// sets the container height
EmmetBytesBoonexContentsSlider.prototype.getContainersHeights = function(){
    var $this = this;
    // gets the containers common height
    var _counter = 1;
    this.sliderContainerMainObj.each(function(index, el){
        var _height = $(el).height();
        $(el).width($this.mainContainerObjWidth);
        if($this.sliderContainerHeight < _height){ $this.sliderContainerHeight = _height; }
        if(_counter >= $this.sliderContainerMainCount){
            $this.setContainersHeights();
        }
        _counter++;
    })
    
}

EmmetBytesBoonexContentsSlider.prototype.setContainersHeights = function(){
    var $this = this;
    var _counter = 1;
    this.slidersContainerInnerObj.width(this.slidersContainerWidth);
    this.sliderContainerMainObj.each(function(index, el){
        var _height = $(el).height();
        var _innerEl = $(el).find('.' + $this.sliderContainerClass);
        if($this.sliderContainerHeight > _height){
            var _paddingTop = _innerEl.css('paddingTop');
            var _paddingTopReplacement = parseInt(_paddingTop) + $this.sliderContainerHeight - _height;
            _innerEl.css({'paddingTop' : _paddingTopReplacement + 'px'});
        }
        $(el).css({'height' : $this.sliderContainerHeight +'px'});
        if(_counter >= $this.sliderContainerMainCount){
            $this.initSlide();
            $this.initNavigationContainer();
        }
        _counter++;
    })
}

EmmetBytesBoonexContentsSlider.prototype.initSlide = function(){
    var $this = this;
    this.startSlideInterval = setInterval(function(){
        $this.slideTheContainer();
    }, this.slideTime);
}

EmmetBytesBoonexContentsSlider.prototype.slideTheContainer = function(){
    var $this = this;
    if((parseInt(this.currentIndex)) > this.sliderContainerMainCount){
        this.currentIndex = 1;
    }else{
        var _leftPos = - (($this.mainContainerObjWidth) * (this.currentIndex - 1));
        this.slidersContainerInnerObj.animate({'left' : _leftPos + 'px'});
        $this.setActiveNavigation();
        $this.currentIndex++;
    }
}

// initialize the navigation container
EmmetBytesBoonexContentsSlider.prototype.initNavigationContainer = function(){
    if(this.sliderContainerMainCount > 1){
        var $this = this;
        var _navCount = 1; 
        for(_navCount; _navCount <= this.sliderContainerMainCount; _navCount++){
            this.createNavigationContainer(_navCount);
        }
        this.initNavContainerActions();
    }
}

EmmetBytesBoonexContentsSlider.prototype.createNavigationContainer = function(_navCount){
    var _navContainer = $('<div>')
    .addClass(this.slidersNavCounterContainer)
    .attr('counter', _navCount);
    if(_navCount == 1){
        _navContainer.addClass(this.slidersNavCounterContainerActive);
    }
    this.slidersNavCountersContainerObj.append(_navContainer);
    this.mainContainerNavigationObj.show();
}

EmmetBytesBoonexContentsSlider.prototype.initNavContainerActions = function(){
    var $this = this;
    this.slidersNavCountersContainerObj
    .find('.' + this.slidersNavCounterContainer)
    .click(function(evData){
        $this.currentIndex = $(this).attr('counter');
        $this.slideTheContainer();
        clearInterval($this.startSlideInterval);
        $this.initSlide();
    })
}

EmmetBytesBoonexContentsSlider.prototype.setActiveNavigation = function(){
    var $this = this;
    this.slidersNavCountersContainerObj
    .find('.' + this.slidersNavCounterContainer)
    .each(function(){
        $(this).removeClass($this.slidersNavCounterContainerActive);
        var _counter = $(this).attr('counter');
        if(_counter == $this.currentIndex){
            $(this).addClass($this.slidersNavCounterContainerActive);
        }
    });
}
