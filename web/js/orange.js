eventItems = null;
additionalEventDetails = null
onLeavetoday = null;
leaveRequests = null;
newUsers = null;

$(document).ready(
    function () {

        getEmployeeEventData();
        setInterval(function () {
            updateLocalDB();
            getEmployeeEventData();
        }, 60000);

    });

/**
 * get event data
 */
function getEmployeeEventData() {

    $.ajax({
        url: "data.php",
        method: "POST",
        data: { event:'createEvents'},
        dataType: "json",
        success: function (data) {
            console.log('tt');
            eventItems = data.data;
            setEvents(data.data);
            updateData(data);
            onLeavetoday = data.onLeave;
            leaveRequests = data.leaveRequests;
            newUsers = data.newMembers.data;

        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#info').html(textStatus + ", " + errorThrown);
        }
    });

}
/**
 * Ajax call to Update DB
 */
// function updateLocalDB() {
//
//     $.ajax({
//         url: "index.php",
//         method: "POST",
//         dataType: "json",
//         success: function (data) {
//
//         },
//         error: function (jqXHR, textStatus, errorThrown) {
//             $('#info').html(textStatus + ", " + errorThrown);
//         }
//     });
//
// }

function setEvents(data) {
    data.reverse();
    var sb = '';
    for (var i in data) {

        var id = data[i].id;

        var time = data[i].time;

        var dataItem = data[i].data;

        var msg = getEventMsg(dataItem);


        sb = sb + " <div class=\"item\" id ='item_" + id + "' >" +
            "            <img src=\"orangeApp/orange/dist/img/notification_icon.png\" alt=\"user image\" class=\"offline\">" +
            "            <p class=\"message\">" +
            "              <a href=\"javascript:getEventDetails(" + dataItem.employeeId + ",'" + dataItem.type + "')\" class=\"name\" >" +
            "                <small class=\"text-muted pull-right\"><i class=\"fa fa-clock-o\"></i>" + time + "</small>" +
            "               " + dataItem.employeeName +
            "              </a>" +
            msg +
            "            </p>" +
            "          </div>";


    }

    $("#notificationItemsContainer").html(sb);


}


function getEventMsg(dataItem) {

    var str = ' ';

    var employeeName = dataItem.employeeName;
    var event = dataItem.event;
    var type = dataItem.type;


    str = str + employeeName + ' ';

    if ('employee' === type) {

        if ('UPDATE' == event) {
            str = str + 'Updated Personal Details'
        } else if ('SAVE' == event) {
            str = str + 'Joined';


        }

    } else if ('contact' === type) {

        if ('UPDATE' === event) {
            str = str + 'Updated Contact Details'
        }

    } else if ('supervisor' === type) {

        if ('UPDATE' === event) {
            str = str + 'Updated Supervisor Details'
        } else if ('SAVE' === event) {
            str = str + 'Assigned a Supervisor'
        }

    } else if ('jobDetail' === type) {

        if ('UPDATE' === event) {
            str = str + 'Updated Job Details'
        }

    }


    return str;


}


function getEventDetails(empId, type) {


    getEmployeeDetails(empId, type);


}

function goBack() {
    setEvents(eventItems);
}

function getEmployeeDetails(empId, type) {

    console.log($(location).attr('pathname'));
    $.ajax({
        url: "data.php",
        method: "POST",
        data: {id: empId, type: type , event:'getEventData'},
        dataType: "json",
        success: function (data) {
            getEventAdditionalDetails(data, type);
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
function getEventAdditionalDetails(eventData, type) {


    var str = '';

    if ('employee' === type) {
        str = "<ul>" +
            "  <li>" + "Name : " + eventData.data.fullName + "</li>" +
            "  <li>" + "Gender: " + eventData.data.gender + "</li>" +
            "  <li>" + "Job Title : " + eventData.data.jobTitle + "</li>" +
            "  <li>" + "Nationality : " + eventData.data.nationality + "</li>" +
            "  <li>" + "DOB : " + eventData.data.dob + "</li>" +
            // "  <li>"+"State : " + eventData.data.state +"</li>" +

            "</ul>";

    } else if ('contact' === type) {

        str = "<ul>" +
            "  <li>" + "Name : " + eventData.data.fullName + "</li>" +
            "  <li>" + "Mobile : " + eventData.data.mobile + "</li>" +
            "  <li>" + "Work Email : " + eventData.data.workEmail + "</li>" +
            "  <li>" + "Other Email : " + eventData.data.otherEmail + "</li>" +
            "  <li>" + "Work Phone : " + eventData.data.workTelephone + "</li>" +
            // "  <li>"+"State : " + eventData.data.state +"</li>" +

            "</ul>";


    } else if ('supervisor' === type) {// console.log(data);

        str = "<ul>" +
            "  <li>" + "Supervisor Name : " + eventData.data[0].name + "</li>" +
            "  <li>" + "Reporting Method : " + eventData.data[0].reportingMethod + "</li>" +


            "</ul>";

    } else if ('jobDetail' === type) {

        str = "<ul>" +
            "  <li>" + "Status : " + eventData.data.status + "</li>" +
            "  <li>" + "Job Title : " + eventData.data.title + "</li>" +
            "  <li>" + "Unit : " + eventData.data.subunit + "</li>" +
            "  <li>" + "Category : " + eventData.data.category + "</li>" +
            "  <li>" + "Location: " + eventData.data.location + "</li>" +
            // "  <li>"+"State : " + eventData.data.state +"</li>" +

            "</ul>";

    }


    $htmlPanel = " <div class=\"panel panel-info\">" +
        "      <div class=\"panel-heading\">Notification</div>" +
        "      <div class=\"panel-body\">" + str + "</div>" +
        "    </div>";


    var sb = '<button class="backbutton button1"  onclick="goBack()" >Back</button>';
    $htmlPanel = $htmlPanel + sb;
    $("#notificationItemsContainer").html($htmlPanel);

}
/**
 * Update Notification count on Top Icons
 * @param data
 */
function updateData(data) {


    var today = data.onLeave.data;
    var total = data.leaveRequests.data;


    $("#leaveToday").text(today.length);
    $("#leaveRequests").text(total.length);
    $("#empEvents").text(eventItems.length);
    $("#newlyJoined").text(data.newMembers.data.length);


}
/**
 * show new Users
 */
function showNewUsers() {


    console.log(newUsers);
    var sb = '';
    for (var i in newUsers) {

        $usersString = newUsers[i].employeeName + " has joined on " + newUsers[i].createdDate;

        var id = newUsers[i].employeeId;


        sb = sb + " <div class=\"item\" id ='item_" + id + "' >" +
            "            <img src=\"orangeApp/orange/dist/img/notification_icon.png\" alt=\"user image\" class=\"offline\">" +
            "            <p class=\"message\">" +
            "              <a href=\"#\" class=\"name\" >" +
            "                <small class=\"text-muted pull-right\"><i class=\"fa fa-clock-o\"></i>" + "</small>" +
            "               " + newUsers[i].employeeName +
            "              </a>" +
            $usersString +
            "            </p>" +
            "          </div>";


    }
    var backButton = '<button class="backbutton button1"  onclick="goBack()" >Back</button>';
    sb = sb + backButton;

    $("#notificationItemsContainer").html(sb);

}
/**
 * show leave request notifications
 */
function showLeaveRequests() {

    var sb = '';
    for (var i in leaveRequests.data) {


        $leaveString = leaveRequests.data[i].employeeName + " is applied for " + leaveRequests.data[i].type + " Leave from " + leaveRequests.data[i].fromDate + " to " + leaveRequests.data[i].toDate;
        console.log(leaveRequests.data[i]);


        var id = leaveRequests.data[i].employeeId;


        sb = sb + " <div class=\"item\" id ='item_" + id + "' >" +
            "            <img src=\"orangeApp/orange/dist/img/notification_icon.png\" alt=\"user image\" class=\"offline\">" +
            "            <p class=\"message\">" +
            "              <a href=\"#\" class=\"name\" >" +
            "                <small class=\"text-muted pull-right\"><i class=\"fa fa-clock-o\"></i>" + "</small>" +
            "               " + leaveRequests.data[i].employeeName +
            "              </a>" +
            $leaveString +
            "            </p>" +
            "          </div>";


    }
    var backButton = '<button class="backbutton button1"  onclick="goBack()" >Back</button>';
    sb = sb + backButton;

    $("#notificationItemsContainer").html(sb);

}
/**
 * Show today leave notifications
 */
function showTodayLeave() {

    var sb = '';
    for (var i in onLeavetoday.data) {


        $leaveString = onLeavetoday.data[i].employeeName + " is on " + onLeavetoday.data[i].type + " Leave from " + onLeavetoday.data[i].fromDate + " to " + onLeavetoday.data[i].toDate;
        console.log(onLeavetoday.data[i]);


        var id = onLeavetoday.data[i].employeeId;


        sb = sb + " <div class=\"item\" id ='item_" + id + "' >" +
            "            <img src=\"orangeApp/orange/dist/img/notification_icon.png\" alt=\"user image\" class=\"offline\">" +
            "            <p class=\"message\">" +
            "              <a href=\"#\" class=\"name\" >" +
            "                <small class=\"text-muted pull-right\"><i class=\"fa fa-clock-o\"></i>" + "</small>" +
            "               " + onLeavetoday.data[i].employeeName +
            "              </a>" +
            $leaveString +
            "            </p>" +
            "          </div>";


    }
    var backButton = '<button class="backbutton button1"  onclick="goBack()" >Back</button>';
    sb = sb + backButton;

    $("#notificationItemsContainer").html(sb);

}
