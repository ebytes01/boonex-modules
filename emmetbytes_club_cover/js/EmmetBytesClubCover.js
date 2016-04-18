function EmmetBytesClubCover(clubCoverParams){

    // getting the club cover parameters
    this.clubCoverParams = clubCoverParams;

    this.clubId = this.clubCoverParams.clubId;
    // getting the top parameters
    this.clubCoverTopParams = this.clubCoverParams.topParams;

    // getting the club cover bottom parameters
    this.popupClicked = false;
    this.clubCoverBottomParams = this.clubCoverParams.bottomParams;
    this.bottomContainerClass = this.clubCoverBottomParams.bottomContainerClass;
    this.bottomContainerObj = $('.' + this.bottomContainerClass);
    this.bottomDataContainerClass = this.clubCoverBottomParams.bottomDataContainerClass;
    this.bottomDataLinkClass = this.clubCoverBottomParams.bottomDataLinkClass;
    this.bottomDataContainerObj = $('.' + this.bottomDataContainerClass);
    this.mainInfoContainerObj = $('#' + this.mainInfoContainerId);
    this.clubAddonDatasContainerClass = this.clubCoverBottomParams.clubAddonDatasContainerClass;
    this.clubAddonDatasContainerObj = $('.' + this.clubAddonDatasContainerClass);
    this.informationsContainerClass = this.clubCoverBottomParams.informationsContainer;
    this.informationsContainerObj = $('.' + this.informationsContainerClass);
    this.infosParams = this.clubCoverBottomParams.infosParams;
    this.popupContainerClass = this.clubCoverBottomParams.mainPopupContainer;
    this.popupContainerObj = $('.' + this.popupContainerClass);
    this.innerPopupContainerClass = this.clubCoverBottomParams.innerPopupContainer;
    this.innerPopupContainerObj = $('.' + this.innerPopupContainerClass);
    this.fansContainer = this.clubCoverBottomParams.fansContainer;
    this.toggleActvitiesContainer = this.clubCoverBottomParams.toggleActvitiesContainer;
    this.toggleActvitiesObj = $('.' + this.toggleActvitiesContainer);

    // getting the club cover loader parameters
    this.clubCoverLoaderParams = this.clubCoverParams.loaderParams;

    // getting the club cover notifier parameters
    this.clubCoverNotifierParams = this.clubCoverParams.notifierParams;
}

// INITIALIZE THE CLUB COVER SCRIPT
EmmetBytesClubCover.prototype.init = function(){
    this.initTopContainerActions();
    this.initShowMoreInforations();
    this.initAddInfos();
    this.initBottomContainerLinks();
    this.initFansContainerPopup();
    this.initToggleAddonDatasContainer();
}

// BOF THE TOP CONTAINER ACTIONS
// initialize the top container actions
EmmetBytesClubCover.prototype.initTopContainerActions = function(){
    this.topContainerObj = $('.' + this.clubCoverTopParams.topContainerClass)
    this.initBackgroundContainer();
    this.initLogoContainer();
}

// the method that initialize the background container
EmmetBytesClubCover.prototype.initBackgroundContainer = function(){
    var $this = this;
    this.bgCntrObj = this.topContainerObj.find('.' + this.clubCoverTopParams.backgroundContainerClass);
    this.bgMenuCntrObj = $('.' + this.clubCoverTopParams.backgroundMainMenuContainerClass);
    this.bgMenuOptionsLoaderObj = this.bgMenuCntrObj.find('.' + this.clubCoverTopParams.backgroundMenuOptionsLoaderContainerClass);
    this.initMenuContainer();
}

// the method that initialize the background image uploader
EmmetBytesClubCover.prototype.initUploadBackgroundSubmit = function(){
    var $this = this;
    $('#' + this.clubCoverTopParams.uploadBackgroundFormId).find('input')
    .change(function(){
        $(this).blur();
        $(this).focus();
        var _val = $(this).val();
        if(_val != ''){
            $this.displayLoader();
            $('#' + $this.clubCoverTopParams.uploadBackgroundFormId).submit();
            $('#upload_background').unbind('load').load(function(){
                 iframeId = document.getElementById("upload_background");
                 // getting the response
                 if (iframeId.contentDocument) {
                     content = iframeId.contentDocument.body.innerHTML;
                 } else if (iframeId.contentWindow) {
                     content = iframeId.contentWindow.document.body.innerHTML;
                 } else if (iframeId.document) {
                     content = iframeId.document.body.innerHTML;
                 }
                 content = eval('(' + content + ')');
                 content.top = 0;
                 content.left = 0;
                 content.fresh = true;
                 content.type = 'upload';
                 if(error = content.error){
                    $this.displayNotifier(error);
                 }else{
                     $this.setupBackgroundContainer(content);
                     $this.removeBackgroundMenuOptions();
                 }
            });
        }
    });
}

// the method that reposition the club cover background
EmmetBytesClubCover.prototype.initRepositionBackgroundSubmit = function(){
    var $this = this;
    $('#' + this.clubCoverTopParams.repositionBackgroundFormId)
    .unbind('click')
    .click(function(){
        var _url = $(this).attr('eb_action');
        $this.displayLoader();
        $.post(_url, {'club_id' : $this.clubId}, function(resp){
            var _resp = eval('(' + resp + ')');
            _resp.image_path = _resp.bg_image;
            _resp.image_name = _resp.bg_image_name;
            _resp.top = _resp.bg_pos_y;
            _resp.left = _resp.bg_pos_x;
            _resp.fresh = false;
            _resp.type = 'reposition';
            $this.setupBackgroundContainer(_resp);
            $this.removeBackgroundMenuOptions();
        })
    });
}

// the method that removes the club cover background
EmmetBytesClubCover.prototype.initRemoveBackgroundSubmit = function(){
    var $this = this;
    $('#' + this.clubCoverTopParams.removeBackgroundFormId)
    .unbind('click')
    .click(function(){
        var _url = $(this).attr('eb_action');
        $this.displayLoader();
        $.post(_url, { 'club_id' : $this.clubId }, function(resp){
            $this.removeBackgroundMenuOptions();
            $this.setEmptyBackground();
            $this.hideLoader();
            $this.changeBackgroundMenuCaption($this.clubCoverTopParams.backgroundMenuInsertCaption);
        })
    });
}

// change the caption of the background menu
EmmetBytesClubCover.prototype.changeBackgroundMenuCaption = function(caption){
    $('.' + this.clubCoverTopParams.backgroundMenuCaptionContainer)
    .text(caption);
}

// initialize the club cover background menu options
EmmetBytesClubCover.prototype.initMenuContainer = function(){
    var $this = this;
    this.clubCoverTopParams.menuIsDisplayed = false;
    this.clubCoverTopParams.menuOptionsRemoved = true;
    var _menuIsDisplayed = false;
    $this.bgMenuCntrObj.click(function(){
        if($this.clubCoverTopParams.menuIsDisplayed){
            $this.hideBackgroundMenuOptions();
        }else{
            $this.clubCoverTopParams.backgroundOptionsClicked = false;
            $this.fetchBackgroundMenuOptions();
        }
    });
}

// setting up the background container
EmmetBytesClubCover.prototype.setupBackgroundContainer = function(content){
    var image_src = content.image_path;
    var image_name = content.image_name;
    var $this = this;
    // insert the uploaded image 
    var _imageContainer = $('<img />').attr('src', image_src);
    // if it doesn't have any background yet
    if(this.topContainerObj.hasClass(this.clubCoverTopParams.topContainerNoBackgroundClass)){
        this.topContainerObj.removeClass(this.clubCoverTopParams.topContainerNoBackgroundClass)
    }
    // set up the css designs
    if(!content.fresh){
        _imageContainer.css({'left' : - content.left + 'px', 'top' : - content.top + 'px'});
    }
    this.topContainerObj
    .find('.' + this.clubCoverTopParams.backgroundImageContainerClass)
    .append(_imageContainer);
    this.setupBackgroundContainerAfterImageLoad(_imageContainer, image_name, content);
}

// call after background container image load
EmmetBytesClubCover.prototype.setupBackgroundContainerAfterImageLoad = function(_imageContainer, image_name, content){
    var $this = this;
    var _formData = {
        'form_url' : this.clubCoverParams.backgroundFormUrl,
        'image_name' : image_name,
        'pos_x' : content.left,
        'pos_y' : content.top,
        'fresh' : content.fresh,
        'type' : content.type,
        'callback' : this.submitBackgroundCallback
    }
    _imageContainer.bind('load', function(){
        var _images = $(this).parent().find('img');
        if(_images.length > 1){
            $(_images.get(0)).remove();
        }
        $(this).draggable({
            start : function(){
                $this.bgCntrObjOffset = $this.bgCntrObj.offset();
                $this.bgCntrObjHeight = $this.bgCntrObj.height();
                $this.bgCntrObjWidth = $this.bgCntrObj.width();
                $this.bgCntrOffLeft = $this.bgCntrObjOffset.left;
                $this.bgCntrOffTop = $this.bgCntrObjOffset.top;
                $this.bgCntrOffRight = $this.bgCntrOffLeft + $this.bgCntrObjWidth;
                $this.bgCntrOffBottom = $this.bgCntrOffTop + $this.bgCntrObjHeight;
            },
            drag : function(event, ui){
                // setting up the image datas
                var _imgHeight = $(this).height();
                var _imgWidth = $(this).width();
                var _imgOffLeft = ui.offset.left;
                var _imgOffTop = ui.offset.top;
                var _imgOffBottom = _imgOffTop + _imgHeight;
                var _imgOffRight = _imgOffLeft + _imgWidth;
                var cPosition = ui.position;
                // check for the top position
                if($this.bgCntrOffTop < _imgOffTop){
                    cPosition.top = 0;
                }
                // check for the bottom position
                if($this.bgCntrOffBottom > _imgOffBottom){
                    cPosition.top = cPosition.top + $this.bgCntrOffBottom - _imgOffBottom;
                }
                // check for the left position
                if($this.bgCntrOffLeft < _imgOffLeft){
                    cPosition.left = 0;
                }
                // check for the right position
                if($this.bgCntrOffRight > _imgOffRight){
                    cPosition.left = cPosition.left + $this.bgCntrOffRight - _imgOffRight;
                }
                // insert the x position
                $('input#ebytes_club_cover_x_position_input').val(cPosition.left);
                $('input#ebytes_club_cover_y_position_input').val(cPosition.top);
                return cPosition;
            }
        })
        // hide the containers
        $this.hideCommonContainers('background');
        $this.createClubCoverForm(_formData);
        $this.hideLoader();
    });
}

// after submitting the club cover background callback function
EmmetBytesClubCover.prototype.submitBackgroundCallback = function(resp, obj){
    if(resp.action == 'cancel'){ // cancel the actions
        if(resp.hasData){ // contains a background image
            obj.displayBackground(resp);
        }else{ // do not have any background image
            obj.setEmptyBackground();
        }
    }else{ // insert the background
        obj.displayBackground(resp);
    }
}

// the method that hides the containers
EmmetBytesClubCover.prototype.hideCommonContainers = function(type){
    if(type == 'background'){
        // hide the logo container
        $('.' + this.clubCoverTopParams.logoContainerClass)
        .css({'visibility' : 'hidden'});
    }else{
        // hide the background container
        $('.' + this.clubCoverTopParams.backgroundContainerClass)
        .css({'visibility' : 'hidden'});
    }
    // hide the logo button container
    $('.' + this.clubCoverTopParams.logoButtonContainerClass)
    .css({'visibility' : 'hidden'});
    // hide the background main menu container
    $('.' + this.clubCoverTopParams.backgroundMainMenuContainerClass)
    .css({'visibility' : 'hidden'}); 
    // hide the bottom containers
    $('.' + this.clubCoverBottomParams.bottomDataContainerClass).hide();
    $('.' + this.clubCoverBottomParams.clubAddonDatasContainerClass).hide();
    // hide the stream player
    $('.ebytes_club_cover_stream_player_container').hide();
    // hide the actions container
    $('.' + this.clubCoverTopParams.clubCoverCommonActionsContainerClass).hide();
    // hide the toggle addon_datas container
    this.toggleActvitiesObj.hide();
}

// the method that displays the container
EmmetBytesClubCover.prototype.showCommonContainers = function(){
    // hide the background container
    $('.' + this.clubCoverTopParams.backgroundContainerClass)
    .css({'visibility' : 'visible'});
    // background menu container
    $('.' + this.clubCoverTopParams.backgroundMainMenuContainerClass)
    .css({'visibility' : 'visible'}); 
    // hide the logo button container
    $('.' + this.clubCoverTopParams.logoButtonContainerClass)
    .css({'visibility' : 'visible'});
    // show the logo container
    $('.' + this.clubCoverTopParams.logoContainerClass).css({'visibility' : 'visible'});
    // show the bottom containers
    $('.' + this.clubCoverBottomParams.bottomDataContainerClass).show();
    $('.ebytes_club_cover_stream_player_container').show();
    // show the actions container
    $('.' + this.clubCoverTopParams.clubCoverCommonActionsContainerClass).show();
    $('.' + this.clubCoverBottomParams.clubAddonDatasContainerClass).show();
    // removes the appended form
    $('.' + this.clubCoverTopParams.clubCoverActionsContainerClass)
    .find('form').remove();
    this.toggleActvitiesObj.show();
}

// the method that creates the form, for the profile background and the profile logo
EmmetBytesClubCover.prototype.createClubCoverForm = function(_formData){
    var $this = this;
    var _action = _formData.form_url;
    var image_name = _formData.image_name;
    // create the main form
    var _form = $('<form>').submit(function(eventObj){
        var _formParams = $(this).serialize() + '&submit=' + $this.clickedSubmitButton;
        var callback = _formData.callback;
        _formParams += '&fresh=' + _formData.fresh + '&type=' + _formData.type;
        $this.submitClubCoverFormImageData(_action, _formParams, callback);
        return false;
    });

    // create the input for the club id
    var _clubId = $('<input/>').attr({'type' : 'hidden', 'name' : 'club_id'})
        .val(this.clubId);
    // create the input for the image name
    var _name = $('<input/>').attr({'type' : 'hidden', 'name' : 'image_name'})
        .val(image_name)
    // create the input for the x position
    var _xPosition = $('<input/>').attr({'type' : 'hidden', 'name' : 'x_pos', 'id' : 'ebytes_club_cover_x_position_input'})
        .val(_formData.pos_x);
    // create the input for the y position
    var _yPosition = $('<input/>').attr({'type' : 'hidden', 'name' : 'y_pos', 'id' : 'ebytes_club_cover_y_position_input'})
        .val(_formData.pos_y);
    // create the input for the save button
    var _save = $('<div>').addClass('ebytes_club_cover_actions_button_container')
        .append($('<input/>').attr({'type' : 'submit'}).val('Save'))
        .click(function(){
            $this.clickedSubmitButton = 'Save';
        });
    // create the input for the cancel button
    var _cancel = $('<div>').addClass('ebytes_club_cover_actions_button_container')
        .append($('<input/>').attr({'type' : 'submit'}).val('Cancel'))
        .click(function(){
            $this.clickedSubmitButton = 'Cancel';
        });
    // append the form to the button container
    _form.append(_clubId)
    .append(_name)
    .append(_xPosition)
    .append(_yPosition)
    .append(_save)
    .append(_cancel)
    .appendTo($('.' + this.clubCoverTopParams.clubCoverActionsContainerClass));
}

// submit the club cover image datas
EmmetBytesClubCover.prototype.submitClubCoverFormImageData = function(action, _formParams, callback){
    var $this = this;
    this.displayLoader();
    $.post(action, _formParams, function(resp){
        _resp = eval('(' + resp + ')'); 
        callback(_resp, $this);
    });
}

// method that sets an empty background again
EmmetBytesClubCover.prototype.setEmptyBackground = function(){
   this.topContainerObj
   .addClass(this.clubCoverTopParams.topContainerNoBackgroundClass) // insert the no background style
   .find('.' + this.clubCoverTopParams.backgroundContainerClass)
   .find('img').remove(); // remove the image
   this.hideLoader();
   this.showCommonContainers();
}

// method that displays the background
EmmetBytesClubCover.prototype.displayBackground = function(resp){
    var $this = this;
    this.topContainerObj
    .find('.' + this.clubCoverTopParams.backgroundImageContainerClass)
    .find('img').attr({'src' : resp.bg_image_cropped}).bind('load', function(){
        $(this).css({'top' : '0', 'left' : '0', 'height' : '100%'});
        $(this).draggable('destroy');
        $this.showCommonContainers();
        $this.hideLoader();
    });
    this.changeBackgroundMenuCaption(this.clubCoverTopParams.backgroundMenuChangeCaption);
}

// method that fetch the menu container
EmmetBytesClubCover.prototype.fetchBackgroundMenuOptions = function(){
    var $this = this;
    if(this.clubCoverTopParams.menuOptionsRemoved){
        this.displayBackgroundMenuOptionsLoader();
        $.post(this.clubCoverTopParams.backgroundMenuOptionsUrl, 
            {'club_id' : this.clubId}, 
            function(resp){
                $this.hideBackgroundMenuOptionsLoader();
                $this.bgMenuCntrObj.append(resp);
                $this.initializeBackgroundSubmitButtons();
                $this.initializeBackgroundMenuOptionsActions();
        });
    }else{
        $('.' + $this.clubCoverTopParams.backgroundMainMenuContainerClass)
        .css({'background' : '#FFF', 'color' : '#000'})
        .find('.' + $this.clubCoverTopParams.backgroundMenuOptionsContainerClass)
        .css({'height' : 'auto', 'border' : '1px solid #717171' });
        $this.initializeBackgroundMenuOptionsActions();
    }
    $this.clubCoverTopParams.menuIsDisplayed = true;
}

// initialize the background menu submit buttons
EmmetBytesClubCover.prototype.initializeBackgroundSubmitButtons = function(){
    this.initUploadBackgroundSubmit();
    this.initRepositionBackgroundSubmit();
    this.initRemoveBackgroundSubmit();
}

// display the background menu options
EmmetBytesClubCover.prototype.initializeBackgroundMenuOptionsActions = function(){
    var $this = this;
    this.bgMenuCntrObj.css({'background' : '#3b5998', 'color' : '#FFF'})
    .find('.' + this.clubCoverTopParams.backgroundMenuOptionsContainerClass)
    .find('li').hover(function(){
        $(this).css({'background' : '#3b5998', 'color' : '#FFF'});
    }, function(){
        $(this).css({'background' : '#FFF', 'color' : '#000'});
    }).click(function(){
        $this.clubCoverTopParams.backgroundOptionsClicked = true;
    });
}

// display the menu options loader
EmmetBytesClubCover.prototype.displayBackgroundMenuOptionsLoader = function(){
    this.bgMenuOptionsLoaderObj.show();
}

// hide the background menu options loader
EmmetBytesClubCover.prototype.hideBackgroundMenuOptionsLoader = function(){
    this.bgMenuOptionsLoaderObj.hide();
}

// method that removes the menu container
EmmetBytesClubCover.prototype.removeBackgroundMenuOptions = function(){
    this.clubCoverTopParams.menuIsDisplayed = false;
    this.clubCoverTopParams.menuOptionsHidden = false;
    this.clubCoverTopParams.menuOptionsRemoved = true;
    var $this = this;
    $('.' + $this.clubCoverTopParams.backgroundMainMenuContainerClass)
    .css({'background' : '#FFF', 'color' : '#000'})
    .find('.' + $this.clubCoverTopParams.backgroundMenuOptionsContainerClass)
    .remove();
}

// method that hide the menu container
EmmetBytesClubCover.prototype.hideBackgroundMenuOptions = function(){
    this.clubCoverTopParams.menuIsDisplayed = false;
    this.clubCoverTopParams.menuOptionsHidden = true;
    this.clubCoverTopParams.menuOptionsRemoved = false;
    var $this = this;
    $('.' + $this.clubCoverTopParams.backgroundMainMenuContainerClass)
    .css({'background' : '#FFF', 'color' : '#000'})
    .find('.' + $this.clubCoverTopParams.backgroundMenuOptionsContainerClass)
    .css({'height' : '0px', 'border' : 'none'})
}

// the method that displays the error notification
EmmetBytesClubCover.prototype.displayNotifier = function(message){
    $('.' + this.clubCoverNotifierParams.notifierContainerClass)
    .click(function(){
        $(this).hide();
    }).show()
    .find('.' + this.clubCoverNotifierParams.notifierTextContainerClass)
    .text(message);
    this.hideLoader();
}

// the method that initialize the logo container
EmmetBytesClubCover.prototype.initLogoContainer = function(){
    this.logoCntrObj = this.topContainerObj.find('.' + this.clubCoverTopParams.logoContainerClass);
    this.initLogoButtonContainer();
}

// initialize the logo button
EmmetBytesClubCover.prototype.initLogoButtonContainer = function(){
    var $this = this;
    $('#' + this.clubCoverTopParams.logoUploaderFormId).find('input')
    .change(function(){
        $(this).blur();
        $(this).focus();
        var _val = $(this).val();
        if(_val != ''){
            $this.displayLoader();
            $('#' + $this.clubCoverTopParams.logoUploaderFormId).submit();
            $('#' + $this.clubCoverTopParams.logoIframeId).unbind('load').load(function(){
                 iframeId = document.getElementById($this.clubCoverTopParams.logoIframeId);
                 // getting the response
                 if (iframeId.contentDocument) {
                     content = iframeId.contentDocument.body.innerHTML;
                 } else if (iframeId.contentWindow) {
                     content = iframeId.contentWindow.document.body.innerHTML;
                 } else if (iframeId.document) {
                     content = iframeId.document.body.innerHTML;
                 }
                 content = eval('(' + content + ')');
                 content.top = 0;
                 content.left = 0;
                 content.fresh = true;
                 content.type = 'upload';
                 if(error = content.error){
                    $this.displayNotifier(error);
                 }else{
                     $this.setupLogoContainer(content);
                     $this.removeBackgroundMenuOptions();
                 }
            });
        }
    });

}

// setup the logo container
EmmetBytesClubCover.prototype.setupLogoContainer = function(content){
    var $this = this;
    var image_src = content.image_path;
    var image_name = content.image_name;
    var _imageContainer = $('<img/>').attr('src', image_src);
    _imageContainer.css({'left' : '0px', 'top' : '0px'}); 
    this.topContainerObj
    .find('.' + this.clubCoverTopParams.logoContainerClass)
    .append(_imageContainer);
    this.setupLogoImageAfterLoad(_imageContainer, image_name, content);
}

// logo after image load setup
EmmetBytesClubCover.prototype.setupLogoImageAfterLoad = function(_imageContainer, image_name, content){
    var $this = this;
    var _formData = {
        'form_url' : this.clubCoverTopParams.logoFormUrl,
        'image_name' : image_name,
        'pos_x' : content.left,
        'pos_y' : content.top,
        'fresh' : content.fresh,
        'type' : content.type,
        'callback' : this.clubCoverInsertLogoCallback
    }
    _imageContainer.bind('load', function(){
        var _images = _imageContainer.parent().find('img');
        if(_images.length > 1){
            $(_images.get(0)).remove();
        }
        _imageContainer.draggable({
            start : function(){
                // getting the logo container values
                $this.logoCntrObjOffset = $this.logoCntrObj.offset();
                $this.logoCntrObjHeight = $this.logoCntrObj.height();
                $this.logoCntrObjWidth = $this.logoCntrObj.width();
                $this.logoCntrOffLeft = $this.logoCntrObjOffset.left;
                $this.logoCntrOffTop = $this.logoCntrObjOffset.top;
                $this.logoCntrOffRight = $this.logoCntrOffLeft + $this.logoCntrObjWidth;
                $this.logoCntrOffBottom = $this.logoCntrOffTop + $this.logoCntrObjHeight;
            },
            drag : function(event, ui){
                // setting up the image datas
                var _imgHeight = $(this).height();
                var _imgWidth = $(this).width();
                var _imgOffLeft = ui.offset.left;
                var _imgOffTop = ui.offset.top;
                var _imgOffBottom = _imgOffTop + _imgHeight;
                var _imgOffRight = _imgOffLeft + _imgWidth;
                var cPosition = ui.position;
                // check for the top position
                if($this.logoCntrOffTop < _imgOffTop){
                    cPosition.top = 0;
                }
                // check for the bottom position
                if($this.logoCntrOffBottom > _imgOffBottom){
                    cPosition.top = cPosition.top + $this.logoCntrOffBottom - _imgOffBottom;
                }
                // check for the left position
                if($this.logoCntrOffLeft < _imgOffLeft){
                    cPosition.left = 0;
                }
                // check for the right position
                if($this.logoCntrOffRight > _imgOffRight){
                    cPosition.left = cPosition.left + $this.logoCntrOffRight - _imgOffRight;
                }
                // insert the x position
                $('input#ebytes_club_cover_x_position_input').val(cPosition.left);
                $('input#ebytes_club_cover_y_position_input').val(cPosition.top);
                return cPosition;
            }
        })
        // hide the containers
        $this.hideCommonContainers('logo');
        $this.createClubCoverForm(_formData);
        $this.hideLoader();
    });
}

// insert logo callback
EmmetBytesClubCover.prototype.clubCoverInsertLogoCallback = function(resp, obj){
    obj.displayLogo(resp);
}

// display the logo
EmmetBytesClubCover.prototype.displayLogo = function(resp){
    var $this = this;
    var _imagePath = resp.image_path;
    var imgTag = $('<img/>').attr({'src' : _imagePath});
    this.topContainerObj.find('.' + this.clubCoverTopParams.logoContainerClass)
    .append(imgTag);
    imgTag.bind('load', function(){
        var _images = $(this).parent().find('img');
        if(_images.length > 1){
            $(_images.get(0)).remove();
        }
        if(undefined == resp.margin_left){
            $(this).css({'top' : '0', 'left' : '0', 'height' : '100%'});
        }else{
            $(this).css({
                'margin-top' : resp.margin_top + 'px'
            });
        }
        $this.showCommonContainers();
        $this.hideLoader();
    });
}
// EOF THE TOP CONTAINER ACTIONS

// BOF THE BOTTOM CONTAINER ACTIONS
// initialize the bottom container links
EmmetBytesClubCover.prototype.initBottomContainerLinks = function(){
    var $this = this;
    this.bottomDataContainerObj.click(function(){
        if(!$this.popupClicked){
            var _link = $(this).find('.' + $this.bottomDataLinkClass).find('a').attr('href');
            if(undefined != _link && _link != '' && _link != '#'){ 
                window.location = _link; 
            }
        }
    });

}

// initialize the showing of more informations for the club
EmmetBytesClubCover.prototype.initShowMoreInforations = function(){
    var $this = this;
    var _showMoreInfo = false;
    var _infoInnerContainer = $('#' + this.infosParams.informationsContainerId);
    var _defHeight = _infoInnerContainer.height();
    $('#' + this.infosParams.showMoreInformationId).click(function(){
        if(_showMoreInfo == false){
            var _curObj = this;

            var _height = _infoInnerContainer
            .find('.ebytes_club_cover_informations_container')
            .height();

            _height += 10;
            $('#' + $this.infosParams.informationsContainerId)
            .animate({'height' : _height + 'px'}, function(){
                $(_curObj).html($this.infosParams.showLessInformationCaption);
            });
            _showMoreInfo = true;
        }else{
            var _curObj = this;
            $('#' + $this.infosParams.informationsContainerId)
            .animate({'height' : _defHeight + 'px'}, function(){
                $(_curObj).html($this.infosParams.showMoreInformationCaption);
            });
            _showMoreInfo = false;
        }
    });
}

// initialize the insertion of the informations
EmmetBytesClubCover.prototype.initAddInfos = function(){
    var $this = this;

    // for the busines website
    this.informationsContainerObj
    .find('#' + this.infosParams.businessWebsiteContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this,$this.infosParams.businessWebsiteContainerId, 'get_business_website_popup');
    });

    // for the busines email
    this.informationsContainerObj
    .find('#' + this.infosParams.businessEmailContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.businessEmailContainerId, 'get_business_email_popup');
    });

    // for the busines telephone
    this.informationsContainerObj
    .find('#' + this.infosParams.businessTelephoneContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.businessTelephoneContainerId, 'get_business_telephone_popup');
    });

    // for the busines fax
    this.informationsContainerObj
    .find('#' + this.infosParams.businessFaxContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.businessFaxContainerId, 'get_business_fax_popup');
    });

    // for the club capacity
    this.informationsContainerObj
    .find('#' + this.infosParams.clubCapacityContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.clubCapacityContainerId, 'get_club_capacity_popup');
    });

    // for the club charge
    this.informationsContainerObj
    .find('#' + this.infosParams.clubChargeContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.clubChargeContainerId, 'get_club_charge_popup');
    });

    // for the club entry age
    this.informationsContainerObj
    .find('#' + this.infosParams.clubEntryAgeContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.clubEntryAgeContainerId, 'get_club_entry_age_popup');
    });

    // for the club hours
    this.informationsContainerObj
    .find('#' + this.infosParams.clubHoursContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.clubHoursContainerId, 'get_club_hours_popup');
    });

    // for the club vip area
    this.informationsContainerObj
    .find('#' + this.infosParams.clubVIPAreaContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.clubVIPAreaContainerId, 'get_club_vip_area_popup');
    });

    // for the club bar type
    this.informationsContainerObj
    .find('#' + this.infosParams.clubBarTypeContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.clubBarTypeContainerId, 'get_club_bar_type_popup');
    });

    // for the club food service
    this.informationsContainerObj
    .find('#' + this.infosParams.clubFoodServiceContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.clubFoodServiceContainerId, 'get_club_food_service_popup');
    });
}

// display the insert popup
EmmetBytesClubCover.prototype.displayInsertPopup = function(obj, _class, urlsuffix){
    var _containerObj = $('#' + _class);
    _class = _class + '_insert_popup';
    var $this = this;
    var _containerOffset = $(obj).offset();
    var _popupLeftPosition = _containerOffset.left + $(obj).width() + 2;
    var _popupTopPosition = (_containerOffset.top - $(obj).height()) + 4;
    showInformationPopupLoader(_containerObj);
    if($('.' + _class).length > 0){
        $('.' + _class).show();
        hideInformationPopupLoader(_containerObj);
        $this.popupClicked = false;
    }else{
        var _popupClone = $this.popupContainerObj
        .clone().addClass(_class).appendTo('body');
        $.post($this.clubCoverParams.baseUrl + urlsuffix + '?club_id=' + $this.clubId, function(resp){
            _popupClone
            .css({
                'left' : _popupLeftPosition + 'px', 
                'top' : _popupTopPosition + 'px',
                'display' : 'block'
            })
            .find('.' + $this.innerPopupContainerClass)
            .html(resp);
            hideInformationPopupLoader(_containerObj);
            $this.popupClicked = false;
        })
    }
}

// initialize the fans container popup informations
EmmetBytesClubCover.prototype.initFansContainerPopup = function(){
    this.fanPopupIsHovered = this.fanIsHovered = false;
    this.fansMainContainer = 'fan_popup_main_container';
    var $this = this;
    $('.' + this.fansContainer.mainContainer)
    .find('.' + this.fansContainer.perFan)
    .hover(
        function(){
            $this.fanIsHovered = true;
            $('.' + $this.fansMainContainer).hide();
            var _profileId = $(this).attr('eb_profile_id');
            $this.callFanPopup(this, _profileId);
        }, function(){
            $this.fanIsHovered = false;
            var _profileId = $(this).attr('eb_profile_id');
            setTimeout(function(){
                $this.hideFanPopupContainer(_profileId);
            }, 500);
        }
    );
}

// call the fan popup
EmmetBytesClubCover.prototype.callFanPopup = function(obj, _profileId){
    var _mainContainer = $('#' + this.fansMainContainer + '_' + _profileId);
    if(_mainContainer.length > 0){
        this.displayFanPopupContainer(_profileId);
    }else{
        this.createFanPopupContainer(obj, _profileId);
    }
}

// create the fan popup container
EmmetBytesClubCover.prototype.createFanPopupContainer = function(obj, _profileId){
    var _offset = $(obj).offset();
    var $this = this;

    var _mainContainer = $('<div>')
        .addClass(this.fansMainContainer)
        .attr('id', this.fansMainContainer + '_' + _profileId);
    var _backgroundContainer = $('<div>')
        .addClass('fan_popup_background_container');
    var _thumbnailContainer = $('<div>')
        .addClass('fan_popup_thumbnail_container');
    var _inputContainer = $('<div>')
        .addClass('fan_popup_info_container');
    var _ebLoaderContainer = $('<div>')
        .addClass('eb_fan_popup_loader_container');

    var _bodyWidth = $('body').width();
    var _offsetLeft = _offset.left;
    var _offsetRight = _offsetLeft + 333;
    var _popupOffsetLeft = _offsetLeft;
    if(_offsetRight >= _bodyWidth){
        _popupOffsetLeft = _offsetLeft - 40 - (_offsetRight - _bodyWidth);
    }
    $('body').append(
        _mainContainer
        .hover(function(){
            $this.fanPopupIsHovered = true;
        }, function(){
            $this.fanPopupIsHovered = false;
            $this.hideFanPopupContainer(_profileId);
        })
        .append(_ebLoaderContainer)
        .append(_backgroundContainer)
        .append(_thumbnailContainer)
        .append(_inputContainer)
        .animate(
            {'left' : _popupOffsetLeft, 'top' : _offset.top - 140}, 
            0,
            function(){
                $this.getFansPopupDatas(_profileId);
            }
        )
    );
}

// display the fan popup container
EmmetBytesClubCover.prototype.displayFanPopupContainer = function(_profileId){
    $('#' + this.fansMainContainer + '_' + _profileId).show();
}

// hide the fan popup container
EmmetBytesClubCover.prototype.hideFanPopupContainer = function(_profileId){
    if(!this.fanIsHovered && !this.fanPopupIsHovered){
        $('#' + this.fansMainContainer + '_' + _profileId).hide();
    }
}

EmmetBytesClubCover.prototype.getFansPopupDatas = function(_profileId){
    var $this = this;
    $.post(this.clubCoverParams.baseUrl + 'get_fan_popup_datas', {'profile_id' : _profileId}, function(resp){
        var _resp = eval('(' + resp + ')');
        _resp.profileId = _profileId;
        $this.appendFansPopupDatas(_resp);
    });
}

EmmetBytesClubCover.prototype.appendFansPopupDatas = function(_fansPopupDatas){
    var _mainContainer = $('#' + this.fansMainContainer + '_' + _fansPopupDatas.profileId);
    _mainContainer.find('.eb_fan_popup_loader_container').remove();

    _mainContainer.find('.fan_popup_thumbnail_container')
    .css({
        'backgroundImage' : "url('" + _fansPopupDatas.avatar + "')"
    }).parent().find('.fan_popup_info_container').html('<a href="' + _fansPopupDatas.link + '">' + _fansPopupDatas.nickname + '</a>')

    if(_fansPopupDatas.backgroundImage){
        _mainContainer.find('.fan_popup_background_container')
        .css({'backgroundImage' : "url('" + _fansPopupDatas.backgroundImage + "')"})
    }else{
        _mainContainer.find('.fan_popup_background_container').addClass('fan_popup_background_container_empty');
    }
}

EmmetBytesClubCover.prototype.initToggleAddonDatasContainer = function(){
    var show  = false;
    var $this = this;
    this.toggleActvitiesObj.click(function(){
        if(!show){
            show = true;
            $this.displayAddonDatasContainer(this);
        }else{
            show = false;
            $this.hideAddonDatasContainer(this);
        }
    });
}

EmmetBytesClubCover.prototype.displayAddonDatasContainer = function(_obj){
    var _height = this.clubAddonDatasContainerObj
        .find('.' + this.clubCoverBottomParams.clubAddonDatasMainContainerClass)
        .height();
    this.clubAddonDatasContainerObj.animate({'height' : _height + 'px'});
    $(_obj).addClass('ebytes_hide_addon_datas_container');
}

EmmetBytesClubCover.prototype.hideAddonDatasContainer = function(_obj){
    this.clubAddonDatasContainerObj.animate({'height' : '103px'});
    $(_obj).removeClass('ebytes_hide_addon_datas_container');
}
// EOF THE BOTTOM CONTAINER ACTIONS

// BOF THE LOADER CONTAINER METHODS
// display the loader
EmmetBytesClubCover.prototype.displayLoader = function() {
    $('.' + this.clubCoverLoaderParams.loaderContainerClass).show();
}

// hide the loader
EmmetBytesClubCover.prototype.hideLoader = function(){
    $('.' + this.clubCoverLoaderParams.loaderContainerClass).hide();
}
// EOF THE LOADER CONTAINER METHODS

// BOF THE CLUB COVER FUNCTIONS
// submit the popup datas
function submitInformationPopupDatas(obj, container_id){
    var _containerClass = container_id.replace('insert_', '');
    var _errorContainer = $(obj).find('.ebytes_club_cover_popup_form_errors_container');
    var _containerObj = $('#' + container_id);
    showInformationPopupLoader(_containerObj); // display the loader
    var _action = $(obj).attr('action');
    var _vals = $(obj).serialize();
    $.post(_action, _vals, function(resp){
        // console.log('resp : ', resp); // TODO, remove me later
        // return false;
        var _resp = eval('(' + resp + ')');
        if(_resp.error == 'true'){
            _errorContainer.show();
            _errorContainer.html(_resp.error_message);
        }else{
            var _closeObj = $(obj).find('.ebytes_club_cover_popup_close_button_container').find('input');
            closeInformationPopup(_closeObj);
            _containerObj.replaceWith(_resp.content);
        }
        hideInformationPopupLoader(_containerObj); // display the loader
    });
    return false;
}

// show the loader
function showInformationPopupLoader(containerObj){
    containerObj.find('.ebytes_club_cover_informations_loader').css({'display' : 'inline-block'});
}

// hide the loader
function hideInformationPopupLoader(containerObj){
    containerObj.find('.ebytes_club_cover_informations_loader').hide();
}

// close the popup
function closeInformationPopup(obj){
    $(obj)
    .parent()
    .parent()
    .parent()
    .parent()
    .parent()
    .parent()
    .parent()
    .remove();
}

function ebClubCoverBecomeAFanButton(obj, _leaveText, _joinText){
    var _link = $(obj).attr('eb_link');
    var _innerDiv = $(obj).find('.ebytes_club_cover_add_friend');
    _innerDiv.css({'cursor' : 'wait'});
    $('body').css({'cursor' : 'wait'});
    $.post(_link, function(resp){
        if(_innerDiv.html() == _leaveText){
            _innerDiv.html(_joinText);
             $('body').css({'cursor' : 'default'});
             _innerDiv.css({'cursor' : 'pointer'});
        }else{
            _innerDiv.html(_leaveText);
             $('body').css({'cursor' : 'default'});
             _innerDiv.css({'cursor' : 'pointer'});
        }
    });
}

function ebClubCoverSubscriptionButton(obj, subText, unSubText, profileId, clubId){
    var _innerDiv = $(obj).find('.club_cover_subscription_button');
    var _subsType = _innerDiv.html();
    _innerDiv.css({'cursor' : 'wait'});
    $('body').css({'cursor' : 'wait'});
    if(_subsType == subText){
         oBxDolSubscription.subscribe(profileId, 'modzzz_club', '', clubId, function(resp){
             alert(resp.message);
             _innerDiv.html(unSubText);
             $('body').css({'cursor' : 'default'});
             _innerDiv.css({'cursor' : 'pointer'});
         });
    }else{
         oBxDolSubscription.unsubscribe(profileId, 'modzzz_club', '', clubId, function(resp){
             alert(resp.message);
             _innerDiv.html(subText);
             $('body').css({'cursor' : 'default'});
             _innerDiv.css({'cursor' : 'pointer'});
         });
    }
}

function ebClubCoverPopupInputFocus(obj){
    $(obj).val('');
}

function ebClubCoverPopupInputBlur(obj, dLang){
    // var _val = $(obj).val();
    // if(_val == '')
    //     $(obj).val(dLang);
}

function submitClubCoverStreamUrl(_obj){
    var _action = _obj.action;
    var _streamUrl = $("input:text[name=stream_url]").val();
    var _clubId = $("input:hidden[name=club_id]").val();
    $.post(_action, {'club_id' : _clubId,  'stream_url' : _streamUrl}, function(resp){
        var _resp = eval('(' + resp +  ')');
        if(_resp.has_errors == 'true'){
            $('.ebytes_club_cover_stream_form_error_container').show().html('*' + _resp.error);
        }else{
            $('.ebytes_club_cover_stream_player_container').html(_resp.content);
            setTimeout(function(){
                $('#ebytes_club_cover_audio_tag').load();
            }, 800);
        }
    });
    return false;
}

function ebClubCoverSubmitStreamUrlFocus(_obj, dLang){
    $(_obj).val('');
}

function ebClubCoverSubmitStreamUrlBlur(_obj, dLang){
    var _val = $(_obj).val();
    if(_val == '')
        $(_obj).val(dLang);
}
// EOF THE CLUB COVER FUNCTIONS

