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

/**
 * Get additional Event details
 */
function getEventData($type, $employeeId, $client)
{


    $supervisorUrl = "employee/" . $employeeId . "/supervisor";

    $dependentUrl = "employee/" . $employeeId . "/dependent";

    $contactDetailUrl = "employee/" . $employeeId . "/contact-detail";

    $employeeDetailUrl = "employee/" . $employeeId;

    $jobDetailUrl = "employee/" . $employeeId . "/job-detail";

    /**
     * checking for event type to get the additional information
     * related to the event
     */
    try {
        $request = null;

        if ('supervisor' == $type) {
            $request = new HTTPRequest($supervisorUrl);
        } else {
            if ('employee' == $type) {
                $request = new HTTPRequest($employeeDetailUrl);
            } else {
                if ('contact' == $type) {

                    $request = new HTTPRequest($contactDetailUrl);

                } else {
                    if ('jobDetail' == $type) {

                        $request = new HTTPRequest($jobDetailUrl);

                    }
                }
            }
        }

        $result = $client->get($request)->getResult();

        echo json_encode($result);
    } catch (Exception $e) {

    }


}

/**
 * Create event data
 * @param $client
 */
function createEvents($client)
{


    $date = date("Y-m-d");

    $lastDay = date('Y-m-d', strtotime("30 days"));

    $tomorrow = date('Y-m-d', strtotime("+1 days"));

    $paramString = 'employee/event?fromDate=' . $lastDay . '&toDate=' . $tomorrow;

    $str2 = 'employee/event?fromDate=2017-04-01&toDate=2017-04-30&type=employee&event=SAVE';

    $request = new HTTPRequest($str2);

    $result = $client->get($request)->getResult();


    $leaveData = null;
    $eventData = null;

    try {

        $leaveRequestsUrl = 'leave/search?reject=false&cancelled=false&pendingApproval=true&scheduled=false&taken=false&pastEmployee&page=0&limit=10';

        $leaveRequest = new HTTPRequest($leaveRequestsUrl);

        $leaveResults = $client->get($leaveRequest)->getResult();

        $leavesUrl = 'leave/search?reject=false&cancelled=false&pendingApproval=false&scheduled=true&taken=true&pastEmployee&page=0&limit=10&fromDate=' . $date . '&toDate=' . $date;

        $leavesToday = new HTTPRequest($leavesUrl);

        $leavesInToday = $client->get($leavesToday)->getResult();

        $employeeEventUrl = 'employee/event';
        $eventRequest = new HTTPRequest($employeeEventUrl);
        $data = $client->get($eventRequest)->getResult();


        $rowId = 0;
        $dateTime = date("Y-m-d h:i:sa");

        foreach ($data['data'] as $employeeEvent) {

            $eventDataItem['id'] = $rowId;
            $eventDataItem['time'] = $dateTime;
            $eventDataItem['data'] = $employeeEvent;
            $eventData[] = $eventDataItem;
            $rowId++;

        }

        $events['data'] = $eventData;
        $events['leaveRequests'] = $leaveResults;
        $events['onLeave'] = $leavesInToday;
        $events['newMembers'] = $result;


        echo json_encode($events);

    } catch (Exception $e) {
        printf($e->getMessage());
    }
}


function logError()
{

}
