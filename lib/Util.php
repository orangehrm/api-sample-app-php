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

class Util
{
    private $client;

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * Get event data
     *
     * @param $type
     * @param $employeeId
     *
     * checking for event type to get the additional information
     * related to the event
     */
    function getEventData($type, $employeeId)
    {
        $request = null;

        try {

            switch ($type) {
                case 'supervisor':
                    $supervisorUrl = "employee/" . $employeeId . "/supervisor";
                    $request = $this->createRequest($supervisorUrl);
                    break;
                case 'employee':
                    $employeeDetailUrl = "employee/" . $employeeId;
                    $request = $this->createRequest($employeeDetailUrl);
                    break;
                case 'contact':
                    $contactDetailUrl = "employee/" . $employeeId . "/contact-detail";
                    $request = $this->createRequest($contactDetailUrl);
                    break;
                case 'jobDetail':
                    $jobDetailUrl = "employee/" . $employeeId . "/job-detail";
                    $request = $this->createRequest($jobDetailUrl);
                    break;
            }

            $result = $this->client->get($request)->getResult();
            $result['success'] = 1;
            return json_encode($result);

        } catch (Exception $e) {
            print($e->getMessage());
            $this->logError($e->getMessage());
            $result['success'] = 0;
            $result['msg'] = $e->getMessage();
            return json_encode($result);
        }

    }

    /**
     * Create events ( employee events / Leave / new users )
     *
     * @return string
     */
    function createEvents()
    {
        $currentDate = date("Y-m-d");
        $leaveData = null;
        $eventData = null;

        try {

            /**
             * getting leave requests
             * End point : leave/search
             */
            $leaveRequestParamArray = array(
                'reject' => 'false',
                'cancelled' => 'false',
                'pendingApproval' => 'true',
                'scheduled' => 'false',
                'taken' => 'false',
                'page' => 0,
                'limit' => 20
            );
            $leaveRequestParams = $this->buildUrlParameters($leaveRequestParamArray);
            $leaveRequestsUrl = 'leave/search?' . $leaveRequestParams;
            $leaveRequest = $this->createRequest($leaveRequestsUrl);
            $LeaveRequestResponse = $this->client->get($leaveRequest);

            if ($LeaveRequestResponse->getStatusCode() == 200) {

                $leaveResults['status'] = 200;
                $leaveResults['response'] = $LeaveRequestResponse->getResult();

            } else {

                $leaveResults['status'] = 400;
            }

            /**
             * getting employees on leave for the day
             * End point : leave/search
             * scheduled:true
             * taken:true
             * date : today
             */
            $onLeaveUrlParamArray = array(
                'reject' => 'false',
                'cancelled' => 'false',
                'pendingApproval' => 'false',
                'scheduled' => 'true',
                'taken' => 'false',
                'page' => 0,
                'limit' => 20,
                'fromDate' => $currentDate,
                'toDate' => $currentDate  // searching for the same day

            );

            $onLeaveTodayParams = $this->buildUrlParameters($onLeaveUrlParamArray);
            $leavesUrl = 'leave/search?' . $onLeaveTodayParams;
            $leavesToday = $this->createRequest($leavesUrl);
            $leaveTodayResponse = $this->client->get($leavesToday);

            if ($leaveTodayResponse->getStatusCode() == 200) {

                $leavesInToday['status'] = 200;
                $leavesInToday['response'] = $leaveTodayResponse->getResult();

            } else {

                $leavesInToday['status'] = 400;
            }

            /**
             * getting employee events
             * end point : employee/event
             * date from yesterday
             * to current date
             */
            $employeeEventParamArray = array(
                'fromDate' => date('Y-m-d', strtotime("-1 days")),
                'toDate' => date('Y-m-d', strtotime("+1 days"))

            );
            $employeeEventUrl = 'employee/event?' . $this->buildUrlParameters($employeeEventParamArray);
            $eventRequest = $this->createRequest($employeeEventUrl);
            $dataResponse = $this->client->get($eventRequest);
            if ($dataResponse->getStatusCode() == 200) {

                $data = $dataResponse->getResult();

            } else {

                $data['status'] = 400;
            }

            /**
             * Get newly joined employees
             * end point : employee/event
             * parameters : date range for 30 days
             * event = SAVE ( getting saved employees )
             */
            $newlyJoinedParamArray = array(
                'fromDate' => date('Y-m-d', strtotime("+30 days")), // last 30 days
                'toDate' => date('Y-m-d', strtotime("+1 days")),
                'type' => 'employee',
                'event' => 'SAVE'

            );


            $newlyJoinedParamUrl = $this->buildUrlParameters($newlyJoinedParamArray);
            $newlyJoined = 'employee/event?' . $newlyJoinedParamUrl;
            $newlyJoinedRequest = $this->createRequest($newlyJoined);
            $newlyJoinedResponse = $this->client->get($newlyJoinedRequest);

            if ($newlyJoinedResponse->getStatusCode() == 200) {

                $newlyJoinedResults['status'] = 200;
                $newlyJoinedResults['response'] = $newlyJoinedResponse->getResult();

            } else {

                $newlyJoinedResults['status'] = 400;
            }

            $rowId = 0;
            $dateTime = date("Y-m-d h:i:sa");

            foreach ($data['data'] as $employeeEvent) {

                $eventDataItem['id'] = $rowId;
                $eventDataItem['time'] = $dateTime;
                $eventDataItem['data'] = $employeeEvent;
                $eventData[] = $eventDataItem;
                $rowId++;

            }
            $events['success'] = '1';
            $events['data'] = $eventData;
            $events['leaveRequests'] = $leaveResults;
            $events['onLeave'] = $leavesInToday;
            $events['newMembers'] = $newlyJoinedResults;

            return json_encode($events);

        } catch (Exception $e) {

            $events['success'] = '0';
            $events['msg'] = $e->getMessage();
            return json_encode($events);

        }
    }


    function logError($msg)
    {
        $_SESSION["errorMsg"] = '$msg';
    }

    /**
     * Create the request
     *
     * @param $url
     * @return HTTPRequest
     */
    private function createRequest($url)
    {
        return new HTTPRequest($url);
    }

    /**
     * Building the url parameters
     *
     * @param $paramArray
     * @return string
     */
    private function buildUrlParameters($paramArray)
    {
        return http_build_query($paramArray);

    }


}
