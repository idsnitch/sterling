function do3PointsUnit(num) {
    num = num.replace(" ", "");
    num = num.replace(",", ".");
    if (isNaN(num) || num == "") num = 0;
    return parseFloat(num).toFixed(3);
}

function do5PointsUnit(num) {
    num = num.replace(" ", "");
    num = num.replace(",", ".");
    if (isNaN(num) || num == "") num = 0;
    return parseFloat(num).toFixed(5);
}

function doUnitPunctuation(num) {
    if (isNaN(num) == false) {
        num = num.toString();
    }
    if (num == "") {
        num = "0.00";
        return num;
    }
    if (num.charAt(num.length - 2) == "." || num.charAt(num.length - 2) == ",") {
        num = num + "0";
    }
    if (num.charAt(num.length - 3) != "." && num.charAt(num.length - 3) != ",") {
        num = num + "00";
    }
    re = /\.|,/g;
    num = num.replace(re, "");
    UnitInteger = num.substring(0, num.length - 2);
    UnitDecimal = num.substring(num.length - 2, num.length);
    num = (UnitInteger + '.' + UnitDecimal);
    if (isNaN(num)) num = "0.00";
    return num;
}

function convertToJsDate(dateStr) {
    var jsDate = new Date();
    jsDate.setHours(0);
    jsDate.setMinutes(0);
    if (dateStr.substr(4, 1) == "-") {
        jsDate.setFullYear(dateStr.substr(0, 4), (dateStr.substr(5, 2) - 1), dateStr.substr(8, 2));
    } else {
        jsDate.setFullYear(dateStr.substr(6, 4), (dateStr.substr(3, 2) - 1), dateStr.substr(0, 2));
    }
    return jsDate;
}

function convertJsDateToStr(jsDate) {
    day = ((jsDate.getDate() + "").length == 1) ? "0" + jsDate.getDate() : jsDate.getDate();
    month = jsDate.getMonth() + 1;
    month = ((month + "").length == 1) ? "0" + month : month;
    return jsDate.getFullYear() + "-" + month + "-" + day;
}

function limitText(limitField, limitNum) {
    if (limitField.value.length > limitNum) {
        limitField.value = limitField.value.substring(0, limitNum);
    }
}

function confirmMessage(mId, forUser, isAdmin) {
    var url = '/customer/confirm_customer_message/' + mId + '/'+forUser;
    var divNameSuffix = 'ToSpecificUser';
    if(typeof forUser === "undefined" || forUser == 0) {
        url = '/customer/confirm_customer_message/' + mId;
        divNameSuffix = '';
    }
    if(isAdmin)
        url += '?isAdmin=1';

    //set spinner:
    $("#customerMessages"+divNameSuffix).html("<i class='fa fa-3x fa-refresh fa-spin' style='color: #ccc'></i>");

    //run ajax to update and and get resulting list
    $.get( url, function( data ) {
        $("#customerMessages"+divNameSuffix).html(data);
    });
}

function confirmMessagesByType (mTypeId, forUser, isAdmin) {
    var url = '/customer/confirm_customer_messagetype/' + mTypeId + '/'+forUser;
    var divNameSuffix = 'ToSpecificUser';
    if(typeof forUser === "undefined" || forUser == 0) {
        url = '/customer/confirm_customer_messagetype/' + mTypeId;
        divNameSuffix = '';
    }
    if(isAdmin)
        url += '?isAdmin=1';

    //set spinner:
    $("#customerMessages"+divNameSuffix).html("<i class='fa fa-3x fa-refresh fa-spin' style='color: #ccc'></i>");

    //run ajax to update and and get resulting list
    $.get( url, function( data ) {
        $("#customerMessages"+divNameSuffix).html(data);
    });
}

function updateBookingPinLabel(bId){
    // the only place we have .cal-booking- class is in calendar, but we will call this function on other places as well.
    // check that we really need to. Return if no classes found.
    var bookingSelector = $('.cal-booking-' + bId);
    if (!bookingSelector.length)
        return;

    //run ajax to get updated content for booking bar-pin
    $.get('/panes/get_booking_pin/' + bId, function(data) {
        var star = "";
        if (data.star > 0 )
            star = '<i class="fa fa-star fa-star-icon-' +data.star + '"></i>'
        var newContent = star+' ' + data.label;
        var newLabel = data.label;
        bookingSelector.html(newContent);
        bookingSelector.prop('title', newLabel);
    });
}

function updateBookingStatusColor(bId){
    // the only place we have .booking- class is in calendar, but we will call this function on other places as well.
    // check that we really need to. Return if no classes found.
    var bookingSelector = $('.booking-' + bId);
    if (!bookingSelector.length)
        return;

    $.get('/panes/get_booking_pin/' + bId, function(data) {
        //remove old class status on booking
        bookingSelector.removeClass(function(index, css) {
            //Below removes both cal-bg-{status}
            return (css.match (/(^|\s)cal-bg-(confirmed|checked-in|checked-out|preliminary|unconfirmed)/g) || []).join(' ');
        });
        bookingSelector.removeClass(function(index, css) {
            //Below removes both cal-bg-{status}-custom*
            return (css.match (/(^|\s)cal-bg-(confirmed|checked-in|checked-out|preliminary|unconfirmed)-custom\S+/g) || []).join(' ');
        });

        ////Now add the new classes
        bookingSelector.addClass('cal-bg-' + data.status);
        bookingSelector.addClass('cal-bg-' + data.status + '-custom-'+data.label_settings[data.status].color);
    });
}

function setStar(bId, newValue) {
    // do POST with new color
    $("#starDiv" + bId).load("/panes/booking_star/" + bId, { starColor: newValue }, function () {
        updateBookingPinLabel(bId);
    });
}

function setCheckInBox(bId, newValue) {
    // do POST with new checkIn value
    $(".checkinDiv" + bId).load("/panes/booking_check_in/" + bId, { value: newValue }, function () {
        updateBookingStatusColor(bId);
    });
}

function setCheckOutBox(bId, newValue) {
    // do POST with new checkOut value
    $(".checkoutDiv" + bId).load("/panes/booking_check_out/" + bId, { value: newValue }, function () {
        updateBookingStatusColor(bId);
    });
}

function showRoom(roomId) {
    sirvoyAjaxDialog("/settings/edit/block-room?showRoom&room_id=" + roomId, sirvoy_data.title_room_view, function(e){
        if (e.event === 'get-done') {
            // Remove confirm-button.. nothing to confirm
            e.swalParameters.showConfirmButton = false;
            return e.swalParameters;
        }
    });
}

function openDialogPopup(urlStr, title) {
    $('#dialog_popup_content').html(
         '<div style="text-align: center;"> <i class="fa fa-3x fa-refresh fa-spin" style="color: #ccc"></i> </div>'
    );
    $('#dialog_popup_content').load(encodeURI(urlStr));
    $("#dialog_popup").dialog("option", "title", title);
    $("#dialog_popup").dialog('open');
    $("#dialog_popup").height('auto');
}

function editRoom(roomId) {
    sirvoyAjaxDialog("settings/edit/rooms-edit?editRoom&room_id=" + roomId, sirvoy_data.title_room_edit, function(e){
        if (e.event === 'get-done') {
            e.swalParameters.width = 1100;
            return e.swalParameters;
        }
        // this event is after submit from the user, but before POST to server
        if (e.event === 'user-confirm') {
            //run custom validation-function
            if(!validate_room_edit())
            // return false so the form isn't POST:ed by ajaxDialog
                return false;
        }
        // this event is after post to server, contains the response
        if (e.event === 'post-done')
        {
            //we might have changed something .. and that need updating view
            location.reload();
        }

    });

}

function lockRoom(roomId, param) {
    sirvoyAjaxDialog("/settings/edit/block-room?lockRoom&room_id=" + roomId + param, sirvoy_data.title_room_block, function(e){
        // this event is after submit from the user, but before POST to server
        if (e.event === 'user-confirm') {
            //run custom validation-function
            if(!validate_lock_room())
            // return false so the form isn't POST:ed by ajaxDialog
                return false;
        }
        // this event is after post to server, contains the response
        if (e.event === 'post-done')
        {
            //we might have locked something .. and that need updating view
            location.reload();
        }

    });

}


function sirvoyAjaxDialog(url, title, callback)
{
    // if no callback function, we use an empty replacement
    callback = callback || function(e){};

    var swalParameters = {
        title: title,
        html: '<div style="text-align: center;"> <i class="fa fa-3x fa-refresh fa-spin" style="color: #ccc"></i> </div>',
        showCancelButton: true,
        showConfirmButton: false,
        closeOnConfirm: false,
        closeOnCancel: false,
    };
    // if the user hits cancel at any stage, then we don't proceed further
    var userCancelTriggered = false;

    // 'init' callback event
    // if we get a return value we use it as parameters (so we can modify them from callback)
    swalParameters = callback({
        event: 'init',
        url: url,
        swalParameters: swalParameters,
    }) || swalParameters;

    // show loader
    swal(swalParameters, function(isConfirm) {
        var button = isConfirm ? 'confirm' : 'cancel';
        if (button === 'cancel')
            userCancelTriggered = true;

        // 'user-confirm' and 'user-cancel' events
        var callback_result = callback({
            event: 'user-' + button,
            url: url,
        });

        // we don't close the dialog if callback returns false
        if (callback_result === false)
            return;

        // close dialog
        swal.closeModal();
    });

    // fetch
    $.get(url).done(function(data, textStatus, jqXHR) {
        // if user cancelled - then we don't do anything.
        if (userCancelTriggered)
            return;

        swalParameters = {
            title: title,
            html: data,
            showCancelButton: true,
            closeOnConfirm: false,
            closeOnCancel: false,
        };

        // 'get-done' callback event
        // if we get a return value we use it as parameters (so we can modify them from callback)
        swalParameters = callback({
            event: 'get-done',
            url: url,
            swalParameters: swalParameters,
            data: data,
            textStatus: textStatus,
            jqXHR: jqXHR,
        }) || swalParameters;

        // display dialog
        displayFormDialog(swalParameters);

    }).fail(function(jqXHR, textStatus, errorThrown) {
        // if has cancelled - then we don't do anything.
        if (userCancelTriggered)
            return;

        // 'get-fail' event
        var result = callback({
            event: 'get-fail',
            jqXHR: jqXHR,
            textStatus: textStatus,
            errorThrown: errorThrown,
        });

        // if we get false back from callback, then we are done. If now display generic error-msg
        if (result === false)
            return;

        // display error
        swal({
            type: 'error',
            title: 'An error occured (GET fail)',
            showCancelButton: true,
            showConfirmButton: false,
            html: '<p>Got error-response: <br>' + textStatus + ' (' + jqXHR.status + ') / ' + errorThrown +
                '<br> Details are logged to the console.</p>',
        });
        console.dir(jqXHR);
    });

    // display ajax dialog
    function displayFormDialog(swalParameters) {
        swal(swalParameters, function(isConfirm) {
            var action_url = $('.sweet-alert .sweet-content form').attr('action') || url;
            var data = $('.sweet-alert .sweet-content form').serialize();
            var button = isConfirm ? 'confirm' : 'cancel';
            var callback_result = callback({
                event: 'user-' + button,
                url: action_url,
                data: data,
            });

            // don't proceed if callback returned false
            if (callback_result === false)
                return;

            // default implementation - post data

            // 'cancel'-button
            if (button === 'cancel') {
                swal.closeModal();
                return;
            }

            // 'confirm'-button follows

            // show loader on confirm-button while we wait for response
            swal.disableButtons();

            $.post(action_url, data).done(function(data, textStatus, jqXHR) {
                // 'post' callback event, with response from server.
                var callback_result = callback({
                    event: 'post-done',
                    url: action_url,
                    data: data,
                    textStatus: textStatus,
                    jqXHR: jqXHR,
                });

                // don't proceed if callback returned false
                if (callback_result === false)
                    return;

                // close dialog
                swal.closeModal();
            }).fail(function(jqXHR, textStatus, errorThrown) {
                // 'post-fail' event
                var result = callback({
                    event: 'post-fail',
                    jqXHR: jqXHR,
                    textStatus: textStatus,
                    errorThrown: errorThrown,
                });

                // if we get false back from callback, then we are done. If now display generic error-msg
                if (result === false)
                    return;

                // generic implementation follows

                // http://tools.ietf.org/html/rfc4918#section-11.2
                // 11.2.  422 Unprocessable Entity
                //
                // The 422 (Unprocessable Entity) status code means the server
                // understands the content type of the request entity (hence a
                // 415(Unsupported Media Type) status code is inappropriate), and the
                // syntax of the request entity is correct (thus a 400 (Bad Request)
                // status code is inappropriate) but was unable to process the contained
                // instructions.  For example, this error condition may occur if an XML
                // request body contains well-formed (i.e., syntactically correct), but
                // semantically erroneous, XML instructions.
                if (jqXHR.status == 422)
                {
                    // 422 error is form validation failure - display the form again with updated contents
                    swalParameters.html = jqXHR.responseText;
                    displayFormDialog(swalParameters);
                    return;
                }

                // display error
                swal({
                    title: 'An error occured (POST fail)',
                    type: 'error',
                    showCancelButton: true,
                    showConfirmButton: false,
                    html: '<p>Got error-response: <br>' + textStatus + ' (' + jqXHR.status + ') / ' + errorThrown +
                    '<br> Details are logged to the console.</p>',
                });
                console.dir(jqXHR);

            });
        });
    }

    // handler for form submit (handle enter keypress automagically)
    // FIXME! Move this code to a module, and don't use a global variable here
    if (typeof sirvoyAjaxDialogDelegateKeypressDone === 'undefined')
    {
        // don't run again
        sirvoyAjaxDialogDelegateKeypressDone = true;
        $('.sweet-container .sweet-content').on('keypress', 'form input', function(e) {
            // click on confirm-button
            if (event.keyCode == 13)
                $('.sweet-container .sweet-confirm').click();
            // prevent submit if enter
            return event.keyCode != 13;
        });

    }


}

function sirvoyDisplayMessages(messages) {
    var swalMappingTypes = {notice: 'success', error: 'error', warning: 'warning'};

    function displayDialog() {
        var msg = messages.shift();
        swal('');
        swal({
            type: swalMappingTypes[msg.type],
            title: msg.title,
            text: msg.text,
            showCancelButton: false,
            closeOnConfirm: false,
        }, function () {
            // display next message
            if (messages.length > 0)
                displayDialog();
            // close dialog if no messages left to display
            else
                swal.closeModal();
        });
    }

    // display first message
    if (messages.length > 0)
        displayDialog();
}

function swalShowLoading(title, maxShowtime) {
    if (typeof maxShowtime === "undefined") {
        maxShowtime = null
    }

    //show loading process
    //you can close this swal after action is done by using swal.closeModal();
    swal({
        html: "<div class='cssload-container'><div class='cssload-whirlpool'></div></div><h2 style='margin-top: 20px;'>"+title+"</h2>",
        showConfirmButton: false,
        allowOutsideClick: false,
        timer: maxShowtime
    });
}

function validateEmail(email) {
    // http://emailregex.com/
    // regex used in type=”email” from W3C
    return /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email);
}

function validatePhone(number) {
    var re = /^[\ \-\+\.\(\)\d]{7,}$/;
    return re.test(number);
}

function returnPhoneWithCuntryCode(number) {
    number = returnOnlyDigits(number);
    number = "+"+number;
    return number;
}

function returnOnlyDigits(number) {
    number = number.replace(/\D/g,'');
    return number;
}

function validatePhoneWithCuntryCode(number) {
    var re = /^\+[\ \-\.\(\)\d]{10,}$/;
    if(re.test(number) === false) {
        return false
    }
    //ignore everything but the digits
    number = returnOnlyDigits(number);
    if(number.length >= 8 && number.length <=15) {
        return true;
    } else {
        return false;
    }
}

function changeSWALdimentions(width,height,x,y) {
    if(typeof width === "number") {
        var oldWidth = parseInt($(".sweet-alert").css("width"));
        newWidth = oldWidth + width;
        $(".sweet-alert").css("width", newWidth+"px");
    }
    if(typeof height === "number") {
        var oldHeight = parseInt($(".sweet-alert").css("height"));
        newHeight = oldHeight + height;
        $(".sweet-alert").css("height", newHeight+"px");
    }
    if(typeof x === "number") {
        var oldX = parseInt($(".sweet-alert").css("margin-left"));
        newX = oldX + x;
        $(".sweet-alert").css("margin-left", newX+"px");
    }
    if(typeof y === "number") {
        var oldY = parseInt($(".sweet-alert").css("margin-top"));
        newY = oldY + y;
        $(".sweet-alert").css("margin-top", newY+"px");
    }
}

//this function is used on payment/booking/invoice view.
var unlinkPayment = function unlinkPayment(paymentId, type, trans) {
    swal({
        title: trans.swal1.title,
        type: "warning",
        showCancelButton: true
    }, function () {
        $.ajax({
            url: "/payment/" + paymentId + "/unlink/" + type,
            method: "POST"
        }).done(function (data) {
            if (data.voided === false && ([9010,1000].indexOf(data.type) === -1)) {
                swal({
                    title: trans.swal2.title,
                    text: trans.swal2.text,
                    type: "info",
                    showCancelButton: true
                }, function (isConfirm) {
                    if(isConfirm) {
                        $.ajax({
                            url: "/payment/" + paymentId,
                            method: "POST",
                            data: {
                                submitPayment: true,
                                voidPaymentHidden: 1,
                                sentByJs: true
                            }
                        }).done(function (data) {
                            if(data.result === "OK") {
                                location.reload();
                            }
                        });
                    } else {
                        location.reload();
                    }
                });
            } else {
                location.reload();
            }
        });
    });
    return false;
};

$(function(){
    $(".body-wrapper").on("click", "i.editable.bookingCheckOut", function() {
        var bookingId = $(this).attr("data-booking-id");
        var value = $(this).attr("data-value");
        var newValue = 1 - value; //if value equals 0, newValue is 1

        setCheckOutBox(bookingId, newValue);
    });

    $(".body-wrapper").on("click", "i.editable.bookingCheckIn", function() {
        var bookingId = $(this).attr("data-booking-id");
        var value = $(this).attr("data-value");
        var warning = $(this).attr("data-warning");
        var warningTitle = $(this).attr("data-warning-title");
        var newValue = 1 - value; //if value equals 0, newValue is 1

        if (!warning) {
            setCheckInBox(bookingId, newValue);
        } else {
            swal({
                title: warningTitle,
                text: warning,
                type: 'warning',
                showCancelButton: true
            }, function(){
                setCheckInBox(bookingId, newValue);
            });
        }
    });

    //trying tp kill autosugest on all datepickerfields.
    $('input[id^="datepicker"]').attr('autocomplete','off');
});
