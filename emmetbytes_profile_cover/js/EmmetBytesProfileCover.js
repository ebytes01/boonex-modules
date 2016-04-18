function EmmetBytesProfileCover(profileCoverParams){
    // getting the profile cover parameters
    this.profileCoverParams = profileCoverParams;

    // getting the top parameters
    this.profileCoverTopParams = this.profileCoverParams.topParams;

    // getting the profile cover bottom parameters
    this.popupClicked = false;
    this.profileCoverBottomParams = this.profileCoverParams.bottomParams;
    this.bottomContainerClass = this.profileCoverBottomParams.bottomContainerClass;
    this.bottomContainerObj = $('.' + this.bottomContainerClass);
    this.bottomDataContainerClass = this.profileCoverBottomParams.bottomDataContainerClass;
    this.bottomDataLinkClass = this.profileCoverBottomParams.bottomDataLinkClass;
    this.bottomDataContainerObj = $('.' + this.bottomDataContainerClass);
    this.mainInfoContainerObj = $('#' + this.mainInfoContainerId);
    this.memberActivitesContainerClass = this.profileCoverBottomParams.memberActivitiesContainerClass;
    this.memberActivitiesContainerObj = $('.' + this.memberActivitesContainerClass);
    this.informationsContainerClass = this.profileCoverBottomParams.informationsContainer;
    this.informationsContainerObj = $('.' + this.informationsContainerClass);
    this.infosParams = this.profileCoverBottomParams.infosParams;
    this.popupContainerClass = this.profileCoverBottomParams.mainPopupContainer;
    this.popupContainerObj = $('.' + this.popupContainerClass);
    this.innerPopupContainerClass = this.profileCoverBottomParams.innerPopupContainer;
    this.innerPopupContainerObj = $('.' + this.innerPopupContainerClass);
    this.friendsContainer = this.profileCoverBottomParams.friendsContainer;
    this.toggleActvitiesContainer = this.profileCoverBottomParams.toggleActvitiesContainer;
    this.toggleActvitiesObj = $('.' + this.toggleActvitiesContainer);

    // getting the profile cover loader parameters
    this.profileCoverLoaderParams = this.profileCoverParams.loaderParams;

    // getting the profile cover notifier parameters
    this.profileCoverNotifierParams = this.profileCoverParams.notifierParams;
}

// INITIALIZE THE PROFILE COVER SCRIPT
EmmetBytesProfileCover.prototype.init = function(){
    this.initTopContainerActions();
    this.initAddInfos();
    this.initBottomContainerLinks();
    this.initFriendsContainerPopup();
    this.initToggleActivitiesContainer();
}

// BOF THE TOP CONTAINER ACTIONS
// initialize the top container actions
EmmetBytesProfileCover.prototype.initTopContainerActions = function(){
    this.topContainerObj = $('.' + this.profileCoverTopParams.topContainerClass)
    this.initBackgroundContainer();
    this.initAvatarContainer();
}

// the method that initialize the background container
EmmetBytesProfileCover.prototype.initBackgroundContainer = function(){
    var $this = this;
    this.bgCntrObj = this.topContainerObj.find('.' + this.profileCoverTopParams.backgroundContainerClass);
    this.bgMenuCntrObj = $('.' + this.profileCoverTopParams.backgroundMainMenuContainerClass);
    this.bgMenuOptionsLoaderObj = this.bgMenuCntrObj.find('.' + this.profileCoverTopParams.backgroundMenuOptionsLoaderContainerClass);
    this.initMenuContainer();
}

// the method that initialize the background image uploader
EmmetBytesProfileCover.prototype.initUploadBackgroundSubmit = function(){
    var $this = this;
    $('#' + this.profileCoverTopParams.uploadBackgroundFormId).find('input')
    .change(function(){
        $(this).blur();
        $(this).focus();
        var _val = $(this).val();
        if(_val != ''){
            $this.displayLoader();
            $('#' + $this.profileCoverTopParams.uploadBackgroundFormId).submit();
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

// the method that reposition the profile cover background
EmmetBytesProfileCover.prototype.initRepositionBackgroundSubmit = function(){
    var $this = this;
    $('#' + this.profileCoverTopParams.repositionBackgroundFormId)
    .unbind('click')
    .click(function(){
        var _url = $(this).attr('eb_action');
        $this.displayLoader();
        $.post(_url, function(resp){
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

// the method that removes the profile cover background
EmmetBytesProfileCover.prototype.initRemoveBackgroundSubmit = function(){
    var $this = this;
    $('#' + this.profileCoverTopParams.removeBackgroundFormId)
    .unbind('click')
    .click(function(){
        var _url = $(this).attr('eb_action');
        $this.displayLoader();
        $.post(_url, function(resp){
            $this.removeBackgroundMenuOptions();
            $this.setEmptyBackground();
            $this.hideLoader();
            $this.changeBackgroundMenuCaption($this.profileCoverTopParams.backgroundMenuInsertCaption);
        })
    });
}

// change the caption of the background menu
EmmetBytesProfileCover.prototype.changeBackgroundMenuCaption = function(caption){
    $('.' + this.profileCoverTopParams.backgroundMenuCaptionContainer)
    .text(caption);
}

// initialize the profile cover background menu options
EmmetBytesProfileCover.prototype.initMenuContainer = function(){
    var $this = this;
    this.profileCoverTopParams.menuIsDisplayed = false;
    this.profileCoverTopParams.menuOptionsRemoved = true;
    var _menuIsDisplayed = false;
    $this.bgMenuCntrObj.click(function(){
        if($this.profileCoverTopParams.menuIsDisplayed){
            $this.hideBackgroundMenuOptions();
        }else{
            $this.profileCoverTopParams.backgroundOptionsClicked = false;
            $this.fetchBackgroundMenuOptions();
        }
    });
}

// setting up the background container
EmmetBytesProfileCover.prototype.setupBackgroundContainer = function(content){
    var image_src = content.image_path;
    var image_name = content.image_name;
    var $this = this;
    // insert the uploaded image 
    var _imageContainer = $('<img />').attr('src', image_src);
    // if it doesn't have any background yet
    if(this.topContainerObj.hasClass(this.profileCoverTopParams.topContainerNoBackgroundClass)){
        this.topContainerObj.removeClass(this.profileCoverTopParams.topContainerNoBackgroundClass)
    }
    // set up the css designs
    if(!content.fresh){
        _imageContainer.css({'left' : - content.left + 'px', 'top' : - content.top + 'px'});
    }
    this.topContainerObj
    .find('.' + this.profileCoverTopParams.backgroundImageContainerClass)
    .append(_imageContainer);
    this.setupBackgroundContainerAfterImageLoad(_imageContainer, image_name, content);
}

// call after background container image load
EmmetBytesProfileCover.prototype.setupBackgroundContainerAfterImageLoad = function(_imageContainer, image_name, content){
    var $this = this;
    var _formData = {
        'form_url' : this.profileCoverParams.backgroundFormUrl,
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
                $('input#ebytes_profile_cover_x_position_input').val(cPosition.left);
                $('input#ebytes_profile_cover_y_position_input').val(cPosition.top);
                return cPosition;
            }
        })
        // hide the containers
        $this.hideCommonContainers('background');
        $this.createProfileCoverForm(_formData);
        $this.hideLoader();
    });
}

// after submitting the profile cover background callback function
EmmetBytesProfileCover.prototype.submitBackgroundCallback = function(resp, obj){
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
EmmetBytesProfileCover.prototype.hideCommonContainers = function(type){
    if(type == 'background'){
        // hide the avatar container
        $('.' + this.profileCoverTopParams.avatarContainerClass)
        .css({'visibility' : 'hidden'});
    }else{
        // hide the background container
        $('.' + this.profileCoverTopParams.backgroundContainerClass)
        .css({'visibility' : 'hidden'});
    }
    // hide the avatar button container
    $('.' + this.profileCoverTopParams.avatarButtonContainerClass)
    .css({'visibility' : 'hidden'});
    // hide the background main menu container
    $('.' + this.profileCoverTopParams.backgroundMainMenuContainerClass)
    .css({'visibility' : 'hidden'}); 
    // hide the bottom containers
    $('.' + this.profileCoverBottomParams.bottomDataContainerClass).hide();
    $('.' + this.profileCoverBottomParams.memberActivitiesContainerClass).hide();
    // hide the actions container
    $('.' + this.profileCoverTopParams.profileCoverCommonActionsContainerClass).hide();
    // hide the toggle activities container
    this.toggleActvitiesObj.hide();
}

// the method that displays the container
EmmetBytesProfileCover.prototype.showCommonContainers = function(){
    // hide the background container
    $('.' + this.profileCoverTopParams.backgroundContainerClass)
    .css({'visibility' : 'visible'});
    // background menu container
    $('.' + this.profileCoverTopParams.backgroundMainMenuContainerClass)
    .css({'visibility' : 'visible'}); 
    // hide the avatar button container
    $('.' + this.profileCoverTopParams.avatarButtonContainerClass)
    .css({'visibility' : 'visible'});
    // show the avatar container
    $('.' + this.profileCoverTopParams.avatarContainerClass).css({'visibility' : 'visible'});
    // show the bottom containers
    $('.' + this.profileCoverBottomParams.bottomDataContainerClass).show();
    // show the actions container
    $('.' + this.profileCoverTopParams.profileCoverCommonActionsContainerClass).show();
    $('.' + this.profileCoverBottomParams.memberActivitiesContainerClass).show();
    // removes the appended form
    $('.' + this.profileCoverTopParams.profileCoverActionsContainerClass)
    .find('form').remove();
    this.toggleActvitiesObj.show();
}

// the method that creates the form, for the profile background and the profile avatar
EmmetBytesProfileCover.prototype.createProfileCoverForm = function(_formData){
    var $this = this;
    var _action = _formData.form_url;
    var image_name = _formData.image_name;
    // create the main form
    var _form = $('<form>').submit(function(eventObj){
        var _formParams = $(this).serialize() + '&submit=' + $this.clickedSubmitButton;
        var callback = _formData.callback;
        _formParams += '&fresh=' + _formData.fresh + '&type=' + _formData.type;
        $this.submitProfileCoverFormImageData(_action, _formParams, callback);
        return false;
    });
    // create the input for the image name
    var _name = $('<input/>').attr({'type' : 'hidden', 'name' : 'image_name'})
        .val(image_name)
    // create the input for the x position
    var _xPosition = $('<input/>').attr({'type' : 'hidden', 'name' : 'x_pos', 'id' : 'ebytes_profile_cover_x_position_input'})
        .val(_formData.pos_x);
    // create the input for the y position
    var _yPosition = $('<input/>').attr({'type' : 'hidden', 'name' : 'y_pos', 'id' : 'ebytes_profile_cover_y_position_input'})
        .val(_formData.pos_y);
    // create the input for the save button
    var _save = $('<div>').addClass('ebytes_profile_cover_actions_button_container')
        .append($('<input/>').attr({'type' : 'submit'}).val('Save'))
        .click(function(){
            $this.clickedSubmitButton = 'Save';
        });
    // create the input for the cancel button
    var _cancel = $('<div>').addClass('ebytes_profile_cover_actions_button_container')
        .append($('<input/>').attr({'type' : 'submit'}).val('Cancel'))
        .click(function(){
            $this.clickedSubmitButton = 'Cancel';
        });
    // append the form to the button container
    _form.append(_name)
    .append(_xPosition)
    .append(_yPosition)
    .append(_save)
    .append(_cancel)
    .appendTo($('.' + this.profileCoverTopParams.profileCoverActionsContainerClass));
}

// submit the profile cover image datas
EmmetBytesProfileCover.prototype.submitProfileCoverFormImageData = function(action, _formParams, callback){
    var $this = this;
    this.displayLoader();
    $.post(action, _formParams, function(resp){
        resp = eval('(' + resp + ')'); 
        callback(resp, $this);
    });
}

// method that sets an empty background again
EmmetBytesProfileCover.prototype.setEmptyBackground = function(){
   this.topContainerObj
   .addClass(this.profileCoverTopParams.topContainerNoBackgroundClass) // insert the no background style
   .find('.' + this.profileCoverTopParams.backgroundContainerClass)
   .find('img').remove(); // remove the image
   this.hideLoader();
   this.showCommonContainers();
}

// method that displays the background
EmmetBytesProfileCover.prototype.displayBackground = function(resp){
    var $this = this;
    this.topContainerObj
    .find('.' + this.profileCoverTopParams.backgroundImageContainerClass)
    .find('img').attr({'src' : resp.bg_image_cropped}).bind('load', function(){
        $(this).css({'top' : '0', 'left' : '0', 'height' : '100%'});
        $(this).draggable('destroy');
        $this.showCommonContainers();
        $this.hideLoader();
    });
    this.changeBackgroundMenuCaption(this.profileCoverTopParams.backgroundMenuChangeCaption);
}

// method that fetch the menu container
EmmetBytesProfileCover.prototype.fetchBackgroundMenuOptions = function(){
    var $this = this;
    if(this.profileCoverTopParams.menuOptionsRemoved){
        this.displayBackgroundMenuOptionsLoader();
        $.post(this.profileCoverTopParams.backgroundMenuOptionsUrl, function(resp){
            $this.hideBackgroundMenuOptionsLoader();
            $this.bgMenuCntrObj.append(resp);
            $this.initializeBackgroundSubmitButtons();
            $this.initializeBackgroundMenuOptionsActions();
        });
    }else{
        $('.' + $this.profileCoverTopParams.backgroundMainMenuContainerClass)
        .css({'background' : '#FFF', 'color' : '#000'})
        .find('.' + $this.profileCoverTopParams.backgroundMenuOptionsContainerClass)
        .css({'height' : 'auto', 'border' : '1px solid #717171' });
        $this.initializeBackgroundMenuOptionsActions();
    }
    $this.profileCoverTopParams.menuIsDisplayed = true;
}

// initialize the background menu submit buttons
EmmetBytesProfileCover.prototype.initializeBackgroundSubmitButtons = function(){
    this.initUploadBackgroundSubmit();
    this.initRepositionBackgroundSubmit();
    this.initRemoveBackgroundSubmit();
}

// display the background menu options
EmmetBytesProfileCover.prototype.initializeBackgroundMenuOptionsActions = function(){
    var $this = this;
    this.bgMenuCntrObj.css({'background' : '#3b5998', 'color' : '#FFF'})
    .find('.' + this.profileCoverTopParams.backgroundMenuOptionsContainerClass)
    .find('li').hover(function(){
        $(this).css({'background' : '#3b5998', 'color' : '#FFF'});
    }, function(){
        $(this).css({'background' : '#FFF', 'color' : '#000'});
    }).click(function(){
        $this.profileCoverTopParams.backgroundOptionsClicked = true;
    });
}

// display the menu options loader
EmmetBytesProfileCover.prototype.displayBackgroundMenuOptionsLoader = function(){
    this.bgMenuOptionsLoaderObj.show();
}

// hide the background menu options loader
EmmetBytesProfileCover.prototype.hideBackgroundMenuOptionsLoader = function(){
    this.bgMenuOptionsLoaderObj.hide();
}

// method that removes the menu container
EmmetBytesProfileCover.prototype.removeBackgroundMenuOptions = function(){
    this.profileCoverTopParams.menuIsDisplayed = false;
    this.profileCoverTopParams.menuOptionsHidden = false;
    this.profileCoverTopParams.menuOptionsRemoved = true;
    var $this = this;
    $('.' + $this.profileCoverTopParams.backgroundMainMenuContainerClass)
    .css({'background' : '#FFF', 'color' : '#000'})
    .find('.' + $this.profileCoverTopParams.backgroundMenuOptionsContainerClass)
    .remove();
}

// method that hide the menu container
EmmetBytesProfileCover.prototype.hideBackgroundMenuOptions = function(){
    this.profileCoverTopParams.menuIsDisplayed = false;
    this.profileCoverTopParams.menuOptionsHidden = true;
    this.profileCoverTopParams.menuOptionsRemoved = false;
    var $this = this;
    $('.' + $this.profileCoverTopParams.backgroundMainMenuContainerClass)
    .css({'background' : '#FFF', 'color' : '#000'})
    .find('.' + $this.profileCoverTopParams.backgroundMenuOptionsContainerClass)
    .css({'height' : '0px', 'border' : 'none'})
}

// the method that displays the error notification
EmmetBytesProfileCover.prototype.displayNotifier = function(message){
    $('.' + this.profileCoverNotifierParams.notifierContainerClass)
    .click(function(){
        $(this).hide();
    }).show()
    .find('.' + this.profileCoverNotifierParams.notifierTextContainerClass)
    .text(message);
    this.hideLoader();
}

// the method that initialize the avatar container
EmmetBytesProfileCover.prototype.initAvatarContainer = function(){
    this.avatarCntrObj = this.topContainerObj.find('.' + this.profileCoverTopParams.avatarContainerClass);
    this.initAvatarButtonContainer();
}

// initialize the avatar button
EmmetBytesProfileCover.prototype.initAvatarButtonContainer = function(){
    var $this = this;
    $('#' + this.profileCoverTopParams.avatarUploaderFormId).find('input')
    .change(function(){
        $(this).blur();
        $(this).focus();
        var _val = $(this).val();
        if(_val != ''){
            $this.displayLoader();
            $('#' + $this.profileCoverTopParams.avatarUploaderFormId).submit();
            $('#' + $this.profileCoverTopParams.avatarIframeId).unbind('load').load(function(){
                 iframeId = document.getElementById($this.profileCoverTopParams.avatarIframeId);
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
                     $this.setupAvatarContainer(content);
                     $this.removeBackgroundMenuOptions();
                 }
            });
        }
    });

}

// setup the avatar container
EmmetBytesProfileCover.prototype.setupAvatarContainer = function(content){
    var $this = this;
    var image_src = content.image_path;
    var image_name = content.image_name;
    var _imageContainer = $('<img/>').attr('src', image_src);
    _imageContainer.css({'left' : '0px', 'top' : '0px'}); 
    this.topContainerObj
    .find('.' + this.profileCoverTopParams.avatarContainerClass)
    .append(_imageContainer);
    this.setupAvatarImageAfterLoad(_imageContainer, image_name, content);
}

// avatar after image load setup
EmmetBytesProfileCover.prototype.setupAvatarImageAfterLoad = function(_imageContainer, image_name, content){
    var $this = this;
    var _formData = {
        'form_url' : this.profileCoverTopParams.avatarFormUrl,
        'image_name' : image_name,
        'pos_x' : content.left,
        'pos_y' : content.top,
        'fresh' : content.fresh,
        'type' : content.type,
        'callback' : this.profileCoverInsertAvatarCallback
    }
    _imageContainer.bind('load', function(){
        var _images = _imageContainer.parent().find('img');
        if(_images.length > 1){
            $(_images.get(0)).remove();
        }
        _imageContainer.draggable({
            start : function(){
                // getting the avatar container values
                $this.avatarCntrObjOffset = $this.avatarCntrObj.offset();
                $this.avatarCntrObjHeight = $this.avatarCntrObj.height();
                $this.avatarCntrObjWidth = $this.avatarCntrObj.width();
                $this.avatarCntrOffLeft = $this.avatarCntrObjOffset.left;
                $this.avatarCntrOffTop = $this.avatarCntrObjOffset.top;
                $this.avatarCntrOffRight = $this.avatarCntrOffLeft + $this.avatarCntrObjWidth;
                $this.avatarCntrOffBottom = $this.avatarCntrOffTop + $this.avatarCntrObjHeight;
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
                if($this.avatarCntrOffTop < _imgOffTop){
                    cPosition.top = 0;
                }
                // check for the bottom position
                if($this.avatarCntrOffBottom > _imgOffBottom){
                    cPosition.top = cPosition.top + $this.avatarCntrOffBottom - _imgOffBottom;
                }
                // check for the left position
                if($this.avatarCntrOffLeft < _imgOffLeft){
                    cPosition.left = 0;
                }
                // check for the right position
                if($this.avatarCntrOffRight > _imgOffRight){
                    cPosition.left = cPosition.left + $this.avatarCntrOffRight - _imgOffRight;
                }
                // insert the x position
                $('input#ebytes_profile_cover_x_position_input').val(cPosition.left);
                $('input#ebytes_profile_cover_y_position_input').val(cPosition.top);
                return cPosition;
            }
        })
        // hide the containers
        $this.hideCommonContainers('avatar');
        $this.createProfileCoverForm(_formData);
        $this.hideLoader();
    });
}

// insert avatar callback
EmmetBytesProfileCover.prototype.profileCoverInsertAvatarCallback = function(resp, obj){
    obj.displayAvatar(resp);
}

// display the avatar
EmmetBytesProfileCover.prototype.displayAvatar = function(resp){
    var $this = this;
    var _imagePath = resp.image_path;
    var imgTag = $('<img/>').attr({'src' : _imagePath});
    this.topContainerObj.find('.' + this.profileCoverTopParams.avatarContainerClass)
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
EmmetBytesProfileCover.prototype.initBottomContainerLinks = function(){
    var $this = this;
    this.bottomDataContainerObj.click(function(){
        if(!$this.popupClicked){
            var _link = $(this).find('.' + $this.bottomDataLinkClass).find('a').attr('href');
            if(_link != ''){ window.location = _link; }
        }
    });

}

// initialize the insertion of the informations
EmmetBytesProfileCover.prototype.initAddInfos = function(){
    var $this = this;

    // setup the displaying of the headline popup form
    this.informationsContainerObj
    .find('#' + this.infosParams.headlineContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.headlineContainerId, 'get_headline_popup');
    });

    // setup the displaying of the location popup form
    this.informationsContainerObj
    .find('#' + this.infosParams.locationContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.locationContainerId, 'get_location_popup');
    });

    // setup the displaying of the birthdate popup form
    this.informationsContainerObj
    .find('#' + this.infosParams.birthdateContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.birthdateContainerId, 'get_birthdate_popup');
    });

    // setup the displaying of the gender popup form
    this.informationsContainerObj
    .find('#' + this.infosParams.genderContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.genderContainerId, 'get_gender_popup');
    });

    // setup the displaying of the relationship popup form
    this.informationsContainerObj
    .find('#' + this.infosParams.relationshipContainerId)
    .find('a').click(function(){
        $this.popupClicked = true;
        $this.displayInsertPopup(this, $this.infosParams.relationshipContainerId, 'get_relationship_popup');
    });
}

// display the insert popup
EmmetBytesProfileCover.prototype.displayInsertPopup = function(obj, _class, urlsuffix){
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
        $.post($this.profileCoverParams.baseUrl + urlsuffix, function(resp){
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

// initialize the friends container popup informations
EmmetBytesProfileCover.prototype.initFriendsContainerPopup = function(){
    this.friendPopupIsHovered = this.friendIsHovered = false;
    this.friendsMainContainer = 'friend_popup_main_container';
    var $this = this;
    $('.' + this.friendsContainer.mainContainer)
    .find('.' + this.friendsContainer.perFriend)
    .hover(
        function(){
            $this.friendIsHovered = true;
            $('.' + $this.friendsMainContainer).hide();
            var _profileId = $(this).attr('eb_profile_id');
            $this.callFriendPopup(this, _profileId);
        }, function(){
            $this.friendIsHovered = false;
            var _profileId = $(this).attr('eb_profile_id');
            setTimeout(function(){
                $this.hideFriendPopupContainer(_profileId);
            }, 500);
        }
    );
}

// call the friend popup 
EmmetBytesProfileCover.prototype.callFriendPopup = function(obj, _profileId){
    var _mainContainer = $('#' + this.friendsMainContainer + '_' + _profileId);
    if(_mainContainer.length > 0){
        this.displayFriendPopupContainer(_profileId);
    }else{
        this.createFriendPopupContainer(obj, _profileId);
    }
}

// create the friend popup container
EmmetBytesProfileCover.prototype.createFriendPopupContainer = function(obj, _profileId){
    var _offset = $(obj).offset();
    var $this = this;

    var _mainContainer = $('<div>')
        .addClass(this.friendsMainContainer)
        .attr('id', this.friendsMainContainer + '_' + _profileId);
    var _backgroundContainer = $('<div>')
        .addClass('friend_popup_background_container');
    var _thumbnailContainer = $('<div>')
        .addClass('friend_popup_thumbnail_container');
    var _inputContainer = $('<div>')
        .addClass('friend_popup_info_container');
    var _ebLoaderContainer = $('<div>')
        .addClass('eb_friend_popup_loader_container');

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
            $this.friendPopupIsHovered = true;
        }, function(){
            $this.friendPopupIsHovered = false;
            $this.hideFriendPopupContainer(_profileId);
        })
        .append(_ebLoaderContainer)
        .append(_backgroundContainer)
        .append(_thumbnailContainer)
        .append(_inputContainer)
        .animate(
            {'left' : _popupOffsetLeft, 'top' : _offset.top - 140}, 
            0,
            function(){
                $this.getFriendsPopupDatas(_profileId);
            }
        )
    );
}

// display the friend popup container
EmmetBytesProfileCover.prototype.displayFriendPopupContainer = function(_profileId){
    $('#' + this.friendsMainContainer + '_' + _profileId).show();
}

// hide the friend popup container
EmmetBytesProfileCover.prototype.hideFriendPopupContainer = function(_profileId){
    if(!this.friendIsHovered && !this.friendPopupIsHovered){
        $('#' + this.friendsMainContainer + '_' + _profileId).hide();
    }
}


EmmetBytesProfileCover.prototype.getFriendsPopupDatas = function(_profileId){
    var $this = this;
    $.post(this.profileCoverParams.baseUrl + 'get_friend_popup_datas', {'profile_id' : _profileId}, function(resp){
        var _resp = eval('(' + resp + ')');
        _resp.profileId = _profileId;
        $this.appendFriendsPopupDatas(_resp);
    });
}

EmmetBytesProfileCover.prototype.appendFriendsPopupDatas = function(_friendsPopupDatas){
    var _mainContainer = $('#' + this.friendsMainContainer + '_' + _friendsPopupDatas.profileId);
    _mainContainer.find('.eb_friend_popup_loader_container').remove();
    _mainContainer.find('.friend_popup_thumbnail_container')
    .css({
        'backgroundImage' : "url('" + _friendsPopupDatas.avatar + "')"
    }).parent().find('.friend_popup_info_container').html('<a href="' + _friendsPopupDatas.link + '">' + _friendsPopupDatas.nickname + '</a>')

    if(_friendsPopupDatas.backgroundImage){
        _mainContainer.find('.friend_popup_background_container')
        .css({'backgroundImage' : "url('" + _friendsPopupDatas.backgroundImage + "')"})
    }else{
        _mainContainer.find('.friend_popup_background_container').addClass('friend_popup_background_container_empty');
    }
}

EmmetBytesProfileCover.prototype.initToggleActivitiesContainer = function(){
    var show  = false;
    var $this = this;
    this.toggleActvitiesObj.click(function(){
        if(!show){
            show = true;
            $this.displayActivitiesContainer(this);
        }else{
            show = false;
            $this.hideActivitiesContainer(this);
        }
    });
}

EmmetBytesProfileCover.prototype.displayActivitiesContainer = function(_obj){
    var _height = this.memberActivitiesContainerObj.find('.' + this.profileCoverBottomParams.memberActivitiesMainContainerClass).height();
    this.memberActivitiesContainerObj.animate({'height' : _height + 'px'});
    $(_obj).addClass('ebytes_hide_activities_container');
}

EmmetBytesProfileCover.prototype.hideActivitiesContainer = function(_obj){
    this.memberActivitiesContainerObj.animate({'height' : '103px'});
    $(_obj).removeClass('ebytes_hide_activities_container');
}
// EOF THE BOTTOM CONTAINER ACTIONS

// BOF THE LOADER CONTAINER METHODS
// display the loader
EmmetBytesProfileCover.prototype.displayLoader = function() {
    $('.' + this.profileCoverLoaderParams.loaderContainerClass).show();
}

// hide the loader
EmmetBytesProfileCover.prototype.hideLoader = function(){
    $('.' + this.profileCoverLoaderParams.loaderContainerClass).hide();
}
// EOF THE LOADER CONTAINER METHODS

// BOF THE PROFILE COVER FUNCTIONS
// submit the popup datas
function submitInformationPopupDatas(obj, container_id){
    var _containerClass = container_id.replace('insert_', '');
    var _errorContainer = $(obj).find('.ebytes_profile_cover_popup_form_errors_container');
    var _containerObj = $('#' + container_id);
    showInformationPopupLoader(_containerObj); // display the loader
    var _action = $(obj).attr('action');
    var _vals = $(obj).serialize();
    $.post(_action, _vals, function(resp){
        var _resp = eval('(' + resp + ')');
        if(_resp.error == 'true'){
            _errorContainer.show();
            _errorContainer.html(_resp.error_message);
        }else{
            var _closeObj = $(obj).find('.ebytes_profile_cover_popup_close_button_container').find('input');
            closeInformationPopup(_closeObj);
            _containerObj.replaceWith(_resp.content);
        }
        hideInformationPopupLoader(_containerObj); // display the loader
    });
    return false;
}

// show the loader
function showInformationPopupLoader(containerObj){
    containerObj.find('.ebytes_profile_cover_informations_loader').css({'display' : 'inline-block'});
}

// hide the loader
function hideInformationPopupLoader(containerObj){
    containerObj.find('.ebytes_profile_cover_informations_loader').hide();
}

// close the popup
function closeInformationPopup(obj){
    $(obj).parent().parent().parent().parent().parent().parent().parent().remove();
}

// friend button actions
function ebProfileCoverFriendButtonAction(obj, profileId){
    var _link = $(obj).attr('eb_link');
    var _action = $(obj).attr('eb_action');
    $.post(_link, {'profile_id' : profileId, 'action' : _action}, function(resp){
        var _resp = eval('(' + resp + ')');
        var _language = _resp.lang;
        var _action = _resp.action;
        $(obj).attr('eb_action', _action);
        $(obj).find('div')
        .text(_language)
        .attr('class', _resp.className);
    })
}
// EOF THE PROFILE COVER FUNCTIONS

