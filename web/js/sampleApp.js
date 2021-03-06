/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */


eventItems = null;
additionalEventDetails = null;
onLeavetoday = null;
leaveRequests = null;
newUsers = null;

$(document).ready(
    function () {
        getEmployeeEventData();
        setInterval(function () {
            refreshEvents();
        }, 60000);

    });

/**
 * get event data
 */
function getEmployeeEventData() {

    $.ajax({
        url: "data.php",
        method: "POST",
        data: {event: 'createEvents'},
        dataType: "json",
        success: function (data) {

            if (data.success == 1) {

                if (data.data != null && data.data.status == 200) {
                    setNotifications(data.data.response.reverse());
                    eventItems = data.data.response.reverse();
                }
                updateData(data);
                onLeavetoday = data.onLeave.response;
                leaveRequests = data.leaveRequests.response;
                newUsers = data.newMembers;
            } else {
                $.notify("Warning:" + data.msg, "warn");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#info').html(textStatus + ", " + errorThrown);
        }
    });

}
/**
 * Updating events
 */
function refreshEvents() {
    getEmployeeEventData();
}
/**
 * Initiating employee notifications
 * @param data
 */
function setNotifications(data) {

    var eventData = createNotificationItems(data);
    var template = $.templates("#employeeEvents");
    var htmlOutput = template.render(eventData);
    $("#notificationItemsContainer").html(htmlOutput);
}

/**
 * Getting the EMPLOYEE notification message according to the
 * event type
 * @param dataItem
 * @returns {string}
 */
function getNotificationMessage(dataItem) {

    var msgString = ' ';
    var employeeName = dataItem.employeeName;
    var event = dataItem.event;
    var type = dataItem.type;

    msgString = msgString + employeeName + ' ';

    switch (type) {
        case 'employee':

            if ('UPDATE' == event) {
                msgString = msgString + 'Updated Personal Details'
            } else if ('SAVE' == event) {
                msgString = msgString + 'Joined';
            }
            break;
        case 'contact':
            if ('UPDATE' === event) {
                msgString = msgString + 'Updated Contact Details'
            }
            break;
        case 'supervisor':
            if ('UPDATE' === event) {
                msgString = msgString + 'Updated Supervisor Details'
            } else if ('SAVE' === event) {
                msgString = msgString + 'Assigned a Supervisor'
            }
            break;
        case 'jobDetail':
            if ('UPDATE' === event) {
                msgString = msgString + 'Updated Job Details'
            }
            break;
    }

    return msgString;
}


function goBack() {
    console.log(eventItems);
    setNotifications(eventItems);
}
/**
 * Getting additional notification details
 * once event is clicked
 * @param empId
 * @param type
 */
function getNotificationDetails(empId, type) {

    $.ajax({
        url: "data.php",
        method: "POST",
        data: {id: empId, type: type, event: 'getEventData'},
        dataType: "json",
        success: function (data) {
            if (data.success == 1) {
                getAdditionalNotificationDetails(data, type);
            } else {
                window.alert(data.msg);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#info').html(textStatus + ", " + errorThrown);
        }

    });
}
/**
 * Get Additional event information
 * @param eventData
 * @param type
 */
function getAdditionalNotificationDetails(eventData, type) {

    var template = null;
    var htmlOutput = null;

    if ('employee' === type) {

        template = $.templates("#employeeNotification");
        htmlOutput = template.render(eventData.data);

    } else if ('contact' === type) {

        template = $.templates("#employeeNotification");
        htmlOutput = template.render(eventData.data);

    } else if ('supervisor' === type) {

        template = $.templates("#supervisorNotification");
        htmlOutput = template.render(eventData.data);


    } else if ('jobDetail' === type) {

        template = $.templates("#jobDetailsNotification");
        htmlOutput = template.render(eventData.data);

    }

    $("#notificationItemsContainer").html(htmlOutput);
}
/**
 * Update Notification count on Top Icons
 * @param data
 */
function updateData(data) {

    if (data.leaveRequests.status == 200) {
        var total = data.leaveRequests.response.data;
        $("#leaveRequests").text(total.length);
    } else {
        $("#leaveRequests").text(0);
    }
    if (data.onLeave.status == 200) {
        var today = data.onLeave.response.data;
        $("#leaveToday").text(today.length);
    } else {
        $("#leaveToday").text(0);
    }
    if (data.data != null && data.data.status == 200) {
        $("#empEvents").text(data.data.response.length);
    } else {
        $("#empEvents").text(0);
    }
    if (data.newMembers.status == 200) {
        $("#newlyJoined").text(data.newMembers.response.data.length);
    } else {
        $("#newlyJoined").text(0);
    }


}
/**
 * show new Users
 */
function showNewMembers() {

    if (newUsers.status == 200) {

        var template = $.templates("#newUsers");
        var htmlOutput = template.render(newUsers.response.data);
        $("#notificationItemsContainer").html(htmlOutput);
    } else {

    }
}
/**
 * show leave request notifications
 */
function showLeaveRequests() {

    if ($("#leaveRequests").text() != 0) {
        var template = $.templates("#empLeaveRequests");
        var htmlOutput = template.render(leaveRequests.data.reverse());
        $("#notificationItemsContainer").html(htmlOutput);
    }


}
/**
 * Show today leave notifications
 */
function showOnLeaveToday() {

    if ($("#leaveToday").text() != 0) {
        var template = $.templates("#onLeave");
        var htmlOutput = template.render(onLeavetoday.data);

        $("#notificationItemsContainer").html(htmlOutput);
    }
}

/**
 * Creating the notification object from event details
 * @param data
 * @returns {Array}
 */
function createNotificationItems(data) {

    var eventsArray = [];
    for (var i in data) {

        eventsArray.push({
            id: data[i].data.employeeId,
            sortable: data[i].time,
            msg: getNotificationMessage(data[i].data),
            name: data[i].data.employeeName,
            event: data[i].data.type
        });
    }

    return eventsArray;
}



