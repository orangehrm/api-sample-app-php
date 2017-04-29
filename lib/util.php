<?php
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

use Orangehrm\API\Client;
use Orangehrm\API\HTTPRequest;


function getEventEndPoint(){

    $yesterday = date('Y-m-d', strtotime("-1 days"));

    $tomorrow = date('Y-m-d', strtotime("+1 days"));

    return 'employee/event?fromDate=' . $yesterday . '&toDate=' . $tomorrow;
}

function saveEventData($data) {
    $db = new DataBase();
    $insert = $db->insertData($data);
}


/**
 * Get additional Event details
 */
function getEventData($type,$employeeId,$client) {

    $supervisorUrl = "employee/" . $employeeId . "/supervisor";

    $dependentUrl = "employee/" . $employeeId . "/dependent";

    $contactDetailUrl = "employee/" . $employeeId . "/contact-detail";

    $employeeDetailUrl = "employee/" . $employeeId;

    $jobDetailUrl = "employee/" . $employeeId . "/job-detail";

    $request = null;

    if ('supervisor' == $type) {
        $request = new HTTPRequest($this->supervisorUrl);
    }
    else if ('employee' == $type) {
        $request = new HTTPRequest($this->employeeDetailUrl);
    }
    else if ('contact' == $type) {

        $request = new HTTPRequest($this->contactDetailUrl);

    }else if ('jobDetail' == $type) {

        $request = new HTTPRequest($this->jobDetailUrl);

    }

    $result = $client->get($request)->getResult();

    echo json_encode($result);
}



function logError() {

}
