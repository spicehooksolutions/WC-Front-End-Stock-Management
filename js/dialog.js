(function(jQuery) {
    if ("undefined" == typeof jQuery)
        throw new Error("")
    jQuery.fn.q_dialog = function(action) {
        // open dialog in aniamted manner
        var target = this;
        if (action === "open") {
            this.fadeIn();
            return this.each(function() {
                jQuery(".dialog-content", this).addClass("animated fadeInRightBig");
                jQuery(".dialog-content", this).removeClass("fadeOutRightBig");
            });
        }

        // close dialog in animated manner; 
        if (action === "close") {
            this.fadeOut();
            return this.each(function() {
                jQuery(".dialog-content", this).removeClass("fadeInRightBig");
                jQuery(".dialog-content", this).addClass("animated fadeOutRightBig");
            });
        }

        // open dialog without animation [funtion - show]
        if (action == "show") {
            return this.show();
        }

        // closing dialog withoug animation [function - close]

        if (action == "hide") {
            return this.hide();
        }

    };

    // showing side-bar programatically

    jQuery.fn.sideBar = function(action) {
        // open dialog in aniamted manner
        var target = this;
        if (action === "open") {
            this.fadeIn();
            return this.each(function() {
                jQuery(".side-bar-content", this).addClass("animated slideInRight");
                jQuery(".side-bar-content", this).removeClass("slideOutRight");
            });
        }

        // close dialog in animated manner; 
        if (action === "close") {
            this.fadeOut();
            return this.each(function() {
                jQuery(".dialog-content", this).removeClass("slideInRight");
                jQuery(".dialog-content", this).addClass("animated slideOutRight");
            });
        }

        // open dialog without animation [funtion - show]
        if (action == "show") {
            return this.show();
        }

        // closing dialog withoug animation [function - close]

        if (action == "hide") {
            return this.hide();
        }

    };



    // end of showing sidebar programatically

    jQuery.fn.niceSound = function() {
        return this.each(function() {
            var audio = jQuery(this)[0];
            audio.play();
        });
    };


    // getting system base url


}(jQuery));

function appendUsefulStuffsToDom() {

    // close the inbuild dialogs
    jQuery("body").on("click", "#error .close", function() {
        jQuery("#error").fadeOut();
    });
    jQuery("body").on("click", "#success .close", function() {
        jQuery("#success").fadeOut();
    });
    jQuery("body").on("click", "#error .close", function() {
        jQuery("#loading").fadeOut();
    });
    // inbuild dialogs are done for now
}

function resizeWindow() {
    // this script is going to take care of the mobile menu System
    // System is going to break at width of 750px; 
    // this is where smartphones and tablets will fall;
    jQuery(window).resize(function() {
        var systemWidth = jQuery(this).width();
        if (systemWidth <= 750) {
            jQuery("ul.nav li.dropdown ul.dropdown-menu").removeClass("drop-them-all");
        }
    });
}

function loadMobile() {
    var systemWidth = jQuery(window).width();
    if (systemWidth <= 750) {
        jQuery("ul.nav li.dropdown ul.dropdown-menu").removeClass("drop-them-all");
    }
}

function openSideBar() {
    jQuery("body").on("click", "[data-open='side-bar']", function(event) {
        var target = jQuery(this).attr("q-target");
        jQuery(target).fadeIn("slow");
        //jQuery("[data-role='dialog']").fadeIn();
        //jQuery("body").addClass("open-customs");
        jQuery(target + " .side-bar-content").addClass("animated slideInRight");
        jQuery(target + " .side-bar-content").removeClass("slideOutRight");
        event.preventDefault();
    });
}

function openDialog() {
    // show diloag
    jQuery("body").on("click", "[data-open='dialog']", function(event) {
        var target = jQuery(this).attr("q-target");
        jQuery(target).fadeIn("slow");
        //jQuery("[data-role='dialog']").fadeIn();
        //jQuery("body").addClass("open-customs");
        jQuery(target + " .dialog-content").addClass("animated bounceInRight");
        jQuery(target + " .dialog-content").removeClass("fadeOutrightBig");
        event.preventDefault();
    });

    // close dialog
    jQuery("body").on("click", "[data-dismiss=dialog]", function(event) {
        if (event.target != this) {
            return;
        }
        var closeId = jQuery(this).attr("data-close");

        //jQuery("body").removeClass("open-customs"); 
        jQuery(closeId + " .dialog-content").removeClass("bounceInRight");
        jQuery(closeId + " .dialog-content").addClass("animated fadeOutrightBig");
        jQuery(closeId).fadeOut("slow");
        return false;
    });

    /*=============================================================================*/
    // show diloag zooming in
    jQuery("body").on("click", "[data-zoom-in='dialog']", function(event) {
        //alert( 10 )
        var target = jQuery(this).attr("q-target");
        jQuery(target).fadeIn("slow");
        //jQuery("[data-role='dialog']").fadeIn();
        //jQuery("body").addClass("open-customs");
        jQuery(target + " .dialog-content").addClass("animated fadeInRightBig").removeClass("fadeOutRightBig")
        event.preventDefault();
    });

    // close dialog zooming out
    jQuery("body").on("click", "[data-zoom-out='dialog']", function(event) {
        if (event.target != this) {
            return;
        }
        var closeId = jQuery(this).attr("data-close");

        //jQuery("body").removeClass("open-customs"); 
        jQuery(closeId + " .dialog-content").addClass("animated fadeOutRightBig").removeClass("zoomIn")
        jQuery(closeId).fadeOut("slow");
        return false;
    });
    /*======================================================================================*/

    // closing side-bar-programatically

    jQuery("body").on("click", "[data-dismiss='side-bar']", function(event) {
        if (event.target != this) {
            return;
        }
        var closeId = jQuery(this).attr("data-close");

        //jQuery("body").removeClass("open-customs"); 
        jQuery(closeId + " .side-bar-content").addClass("animated slideOutRight").removeClass("slideInRight")
        jQuery(closeId).fadeOut("slow");
        return false;
    });



    // optimizing modal for mobile and tablets devies 
    // when resizing the client resizes window ... 

    jQuery(window).resize(function() {
        var widthDim = jQuery(this).width();
        //console.log(widthDim);
        if (widthDim > 1038) {
            //
            jQuery(".dialog-content").css({ "right": "0" });
            jQuery(".loading-data").css("right", "37%");
            jQuery(".band-connection").css("right", "0");
        }

        if (widthDim >= 850 && widthDim <= 1038) {
            jQuery(".dialog-content").css({ "right": "0" });
            jQuery(".loading-data").css({ "right": "37%" });
            jQuery(".band-connection").css({ "right": "0" });
        }

        if (widthDim >= 760 && widthDim <= 849) {
            jQuery(".dialog-content").css({ "right": "0" });
            jQuery(".loading-data").css({ "right": "37%" });
            jQuery(".band-connection").css({ "right": "0" });
        }

        if (widthDim < 660) {
            jQuery(".dialog-content").css({ "right": "0", "width": "88%" });
            jQuery(".loading-data").css({ "right": "5%", "min-width": "88%" });
            jQuery(".band-connection").css({ "right": "5%", "width": "88%" });
            jQuery(".loading-data").css({ "font-size": "16px" });
            jQuery(".band-connection").css({ "font-size": "16px" });
        }

        if (widthDim > 660) {
            jQuery(".dialog-content").css({ "width": "450px" });
            jQuery(".band-connection").css({ "width": "450px" });
            jQuery(".loading-data").css({ "font-size": "21px" });
            jQuery(".band-connection").css({ "font-size": "21px" });
            jQuery(".loading-data").css({ "min-width": "300px", "max-width": "400px" });
        }

    });

    // when client loads the page on mobile this is what will happen 

    var dim = jQuery(window).width();
    if (dim > 1038) {
        jQuery(".dialog-content").css({ "right": "0" });
        jQuery(".loading-data").css("right", "37%");
        jQuery(".band-connection").css("right", "0");
        jQuery(".right").css("right", "24%");

    }

    if (dim >= 850 && dim <= 1038) {
        jQuery(".dialog-content").css({ "right": "0" });
        jQuery(".loading-data").css({ "right": "37%" });
        jQuery(".band-connection").css({ "right": "0" });
        //jQuery(".system-title").html("Murang'a University Classes Management Stystem (MCMS)");
        //jQuery(".right").css("right","24%");
    }

    if (dim >= 760 && dim <= 849) {
        jQuery(".dialog-content").css({ "right": "0" });
        jQuery(".loading-data").css({ "right": "37%" });
        jQuery(".band-connection").css({ "right": "0" });
    }

    if (dim < 660) {
        jQuery(".dialog-content").css({ "right": "0", "width": "88%" });
        jQuery(".loading-data").css({ "right": "5%", "min-width": "88%" });
        jQuery(".band-connection").css({ "right": "5%", "width": "88%" });
        jQuery(".loading-data").css({ "font-size": "16px" });
        jQuery(".band-connection").css({ "font-size": "16px" });
    }

    if (dim > 660) {
        jQuery(".dialog-content").css({ "width": "450px" });
        jQuery(".band-connection").css({ "width": "450px" });
        jQuery(".band-connection").css({ "font-size": "21px" });
        jQuery(".loading-data").css({ "min-width": "300px", "max-width": "400px" });
    }

}

function showAlert() {
    jQuery("body").on("click", "[data-open='alert']", function() {
        var destiny = jQuery(this).attr("q-target");
        //jQuery("body").addClass("open-customs"); 
        jQuery(destiny).fadeIn("slow");
        jQuery("[data-role='alert']").fadeIn();
        jQuery("[data-show='alert']").removeClass("zoomOutDown");
        jQuery("[data-show='alert']").addClass("animated zoomIn");
    });


    jQuery(".negative").click(function() {
        jQuery("[data-role='alert']").fadeOut();
        //jQuery("body").removeClass("open-customs"); 
        jQuery("[data-show='alert']").fadeOut();
        jQuery("[data-show='alert']").removeClass("zoomIn");
        jQuery("[data-show='alert']").addClass("animated zoomOutDown");
    });
}

function closeBandConnectionError() {
    jQuery('[data-dismiss="connection"]').click(function(event) {
        if (event.target != this) {
            return;
        }
        jQuery(".band-connection").removeClass("lightSpeedIn");
        jQuery(".band-connection").addClass("animated bounceOutDown");
        //jQuery("body").removeClass("open-customs"); 
        jQuery("[data-role='ajax_error']").fadeOut();
        jQuery("[data-show='connection-error']").fadeOut("slow");

    });
}

function dialogFooter() {
    jQuery(".dialog-footer").attr("align", "right");
}

function errorInterface() {
    jQuery("[data-target='errors']").click(function() {
        var contents = jQuery(this).attr("data-content");
        var id = jQuery(this).attr("q-target");
        var toolkit = jQuery(this).attr("toolkit");

        jQuery(id + " .error-content").html(contents);
        if (toolkit == "true") {
            fakeLoader(id);
        }

    });
}

function fakeLoader(idTarget) {
    jQuery(idTarget + " .waiting-toolkit").show();
    jQuery(idTarget + " .toolkit").hide();
    var time = 0;

    function fake() {
        var id = setTimeout(fake, 1000);
        if (time == 5) {
            clearTimeout(id);
            jQuery(idTarget + " .toolkit").slideDown("slow");
            jQuery(idTarget + " .waiting-toolkit").hide();
        }
        time++;
    }
    fake();
}


function clickSound() {
    jQuery(".sound-active").click(function() {
        var audio = jQuery("#click-audio")[0];
        audio.play();
    });
}

/*

function closeThisDialog(){
    jQuery("[data-hide]").click(function(){
        var closeId = jQuery(this).attr("data-hide");
        jQuery(closeId).fadeOut();
        //jQuery(".overlay").fadeOut();
        jQuery(closeId+" .dialog-content").removeClass("bounceInRight");
        jQuery(closeId+" .dialog-content").addClass("animated fadeOutrightBig");

    });
} 

*/

function showSearchFrag() {
    jQuery("body").on("click", "[data-open='search']", function() {
        var idToOpen = jQuery(this).attr("q-target");
        jQuery(idToOpen).fadeIn("slow");
        jQuery(idToOpen + " .search-content").addClass("animated fadeInUpBig");
        jQuery(idToOpen + " .search-content").removeClass("fadeOutRightBig");
        //jQuery("body").addClass("open-customs");
        return false;
    });

    jQuery("body").on("click", "[data-dismiss='search']", function(event) {
        if (event.target != this) {
            return;
        }

        var closeId = jQuery(this).attr("data-close");
        jQuery(closeId + " .search-content").removeClass("fadeInDownBig");
        jQuery(closeId + " .search-content").addClass("animated fadeOutRightBig");
        jQuery(closeId).fadeOut("slow");
        //jQuery("body").removeClass("open-customs");

    });
}


//mobile menu

function openMenuThemAll() {
    jQuery("body").on("click", "[data-open='mobile-menu']", function() {
        var id = jQuery(this).attr("q-target");
        jQuery(id).fadeIn("slow");
        jQuery(id + " .mobile-menu-content").addClass("animated fadeInRightBig").removeClass("fadeOutRightBig");
        //jQuery("body").addClass("open-customs");
    });

    jQuery("body").on("click", "[data-dismiss='mobile-menu']", function(e) {
        if (e.target != this) {
            return;
        }
        var id = jQuery(this).attr("data-close");
        jQuery(id).fadeOut("slow");
        jQuery(id + " .mobile-menu-content").removeClass("fadeInRightBig").addClass("animated fadeOutRightBig");
        //jQuery("body").removeClass("open-customs");
    });
}
/* programming for the notification */

function notificationManager() {
    jQuery("body").on("click", "[data-dismiss='notification']", function(event) {
        if (event.target != this) {
            return;
        }

        var closeId = jQuery(this).attr("data-close");
        jQuery(closeId).fadeOut("slow");
        jQuery(closeId + " .notification-content").addClass("animated slideOutRight").removeClass("slideInright");
    });

    // attention on clicking outside notication
    jQuery("body").on("click", ".notification-manager", function(event) {
        if (event.target != this) {
            return;
        }
        var attentionAudio = jQuery("#attention")[0];
        attentionAudio.play();
        var time = 0;

        function realTime() {
            var id = setTimeout(realTime, 1000);
            jQuery(".notification-manager .notification-content").addClass("animated wobble infinite").removeClass("slideInright");
            if (time == 1) {
                jQuery(".notification-manager .notification-content").removeClass("wobble infinite");
                clearTimeout(id);
            }
            time++;
        }
        realTime();
    });
    // opening notification dynamically; 
    jQuery("body").on("click", '[data-open="notification"]', function() {
        var id = jQuery(this).attr("q-target");
        jQuery(id).fadeIn("slow");
        jQuery(id + " .notification-content").addClass("animated slideInright").removeClass("slideOutRight");
    });

    // chat idicator;
    // you may not deltet this part though is not that important; 

}

function chatIdicator() {
    jQuery("body").on("click", "[data-open='chat-idicator']", function(event) {
        if (event.target != this) {
            return;
        }
        var id = jQuery(this).attr("q-target");
        jQuery(id).show("slow");
    });

    jQuery("body").on("click", "[data-finish='chat-idicator']", function(event) {
        if (event.target != this) {
            return;
        }

        var id = jQuery(this).attr("data-end");
        jQuery(id).hide("slow");
    });
}

function overFlowDialog() {
    var time = 0;

    function realTime() {
        setTimeout(realTime, 1000);
        if (time == 1) {
            //alert("workig")
            if (jQuery("[data-show='dialog']").is(":visible")) {
                jQuery("[data-show='dialog']").css("overflow-y", "hidden");
                time = 0;
            } else {
                jQuery("[data-show='dialog']").css("overflow-y", "hidden");
                time = 0;
            }
        }

        time++;
    }
    realTime();
}

function openAllDialogTypes() {
    var time = 0;

    function realTime() {
        setTimeout(realTime, 0);
        if (time = 1) {
            if (jQuery("[data-show='dialog']").is(":visible") || jQuery("[data-show='mobile-menu']").is(":visible") || jQuery("[data-show='side-bar']").is(":visible") || jQuery(".marketing-popup-overlay").is(":visible")) {
                jQuery("body").addClass("open-customs");
            } else {
                jQuery("body").removeClass("open-customs");
            }
            time = 0;
        }
        time++;
    }
    realTime();
}

function passwords() {
    jQuery("body").on("click", "[data-show='password']", function() {
        var targetId = jQuery(this).attr("q-show");
        jQuery(targetId).attr("type", "text");
        jQuery("[q-hide='" + targetId + "']").fadeIn();
        jQuery(this).hide();
    });

    jQuery("body").on("click", "[data-hide='password']", function() {
        var targetId = jQuery(this).attr("q-hide");
        jQuery(targetId).attr("type", "password");
        jQuery("[q-show='" + targetId + "']").fadeIn();
        jQuery(this).hide();
    });
}

function tabs() {
    // this function handles all the functionality of the tabs; 
    jQuery("body").on("click", "[data-open='tab']", function() {
        jQuery(this).removeAttr("inactive");
        var id = jQuery(this).attr("q-target");
        jQuery("[active]").removeAttr("active").attr("inactive", "inactive");
        jQuery(this).attr("active", "active");
        jQuery(".active-tab").removeClass("active-tab").addClass("in-active-tab").hide();
        jQuery(id).addClass("active-tab").removeClass("in-active-tab").hide().fadeIn();
        return false;
    });

    function tabMobile() {
        var systemWidth = jQuery(window).width();

        if (systemWidth < 938) {
            jQuery("[inactive]").hide();
            jQuery('[data-open="mobile-tab"]').fadeIn('slow');
        } else {
            jQuery("[inactive]").fadeIn('slow');
            jQuery('[data-open="mobile-tab"]').fadeOut('slow');
        }

        jQuery(window).resize(function() {
            var width = jQuery(window).width();
            //console.log(width);
            if (width < 938) {
                jQuery("[inactive]").hide();
                jQuery('[data-open="mobile-tab"]').fadeIn('slow');
            } else {
                jQuery("[inactive]").fadeIn('slow');
                jQuery('[data-open="mobile-tab"]').fadeOut('slow');
            }
        });

        // programming for the mobile tabs 
        jQuery("body").on("click", "[data-open='mobile-tab']", function(event) {
            //jQuery("[data-open='mobile-tab'] ul").html(jQuery(".tabs .tab-title [inactive]").html());
            event.preventDefault();
        });
    }
    tabMobile();
}

function createTextarea() {

    jQuery(".text-area").attr("contentEditable", "true");
    var holder = jQuery(".text-area").attr("data-holder");
    jQuery(".text-area").html("<span class='holder'>" + holder + "</span>");


    jQuery("body").on("focus", ".text-area", function() {
        jQuery("span.holder").remove();
    });

    jQuery("body").on("focusout", ".text-area", function() {
        var textData = jQuery(this).text();
        if (textData == "") {
            var holderData = jQuery(this).attr("data-holder");
            jQuery(".text-area").html("<span class='holder'>" + holderData + "</span>");
        }
    });
}

function inputHints() {
    jQuery("body").on("focus", "input", function() {
        var id = jQuery(this).attr("id");
        jQuery("[data-hint='" + id + "']").fadeIn().addClass("animated bounceIn");

    });

    jQuery("body").on("focusout", "input", function() {
        var id = jQuery(this).attr("id");
        jQuery("[data-hint='" + id + "']").hide().removeClass("bounceIn");

    });
}

function selectWidget() {
    jQuery("body").on("focus", "[data-input='select']", function() {
        var id = jQuery(this).attr("id");
        jQuery("[data-select='" + id + "']").fadeIn("slow").addClass("animated bounceIn").removeClass("bounceOut");
    });
    jQuery("body").on("click", "[data-select] li", function() {
        var text = jQuery(this).html();
        if (text == "Null") {
            jQuery("[data-input='select']").val("");
            return;
        }
        var id = jQuery(this).attr("parent");
        jQuery("#" + id).val(text);
    });
    jQuery("body").on("focusout", "[data-input='select']", function() {
        var id = jQuery(this).attr("id");
        //jQuery("[data-select='"+id+"']").hide("slow").removeClass("bounceIn");
        jQuery("[data-select='" + id + "']").fadeOut("fast").addClass("animated bounceOut").removeClass("bounceIn");
    });


    // prevent user keyboard typing
    jQuery("body").on("keypress", "[data-input='select']", function(event) {
        event.preventDefault();
    });
}

function checkJavaScriptEnabled() {}


function clickMobileMenuButton() {
    jQuery("body").on("click", "[data-role='mobile-menu']", function() {
        var mobileHtml = jQuery("[data-model='mobile']").html();
        jQuery(".menu-them-all ul").html(mobileHtml);
    });
}

function passwordSeeable() {
    jQuery('.show-the-password').click(function() {
        jQuery(this).hide();
        jQuery(this).parent().find('input').attr('type', 'text');
        jQuery(this).parent().find('.hide-the-password').fadeIn();
    });

    jQuery('.hide-the-password').click(function() {
        jQuery(this).hide();
        jQuery(this).parent().find('input').attr('type', 'password');
        jQuery(this).parent().find('.show-the-password').fadeIn();
    });
}

jQuery(document).ready(function() {
    resizeWindow();
    loadMobile();
    openDialog();
    showAlert();
    closeBandConnectionError();
    dialogFooter();
    errorInterface();
    //closeThisDialog();
    clickSound();
    showSearchFrag();
    openMenuThemAll();
    notificationManager();
    chatIdicator();
    openAllDialogTypes();
    passwords();
    tabs();
    createTextarea();
    inputHints();
    selectWidget();
    checkJavaScriptEnabled();
    appendUsefulStuffsToDom();
    clickMobileMenuButton();
    openSideBar();
    overFlowDialog();
    passwordSeeable();
});