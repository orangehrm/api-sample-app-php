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

        try {
            $request = null;

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
                    $request =$this->createRequest($contactDetailUrl);
                    break;
                case 'jobDetail':
                    $jobDetailUrl = "employee/" . $employeeId . "/job-detail";
                    $request = $this->createRequest($jobDetailUrl);
                    break;
            }

            $result = $this->client->get($request)->getResult();

            return json_encode($result);
        } catch (Exception $e) {
            $this->logError();
        }


    }

    /**
     * Create events ( employee events / Leave / new users )
     *
     * @return string
     */
    function createEvents()
    {

        $now = date("Y-m-d");
        $lastDay = date('Y-m-d', strtotime("-30 days"));
        $tomorrow = date('Y-m-d', strtotime("+1 days"));

        $paramString = 'employee/event?fromDate=' . $lastDay . '&toDate=' . $tomorrow;
        $leaveData = null;
        $eventData = null;

        try {

            /**
             * getting leave requests
             * End point : leave/search
             */
            $leaveRequestsUrl = 'leave/search?reject=false&cancelled=false&pendingApproval=true&scheduled=false&taken=false&pastEmployee&page=0&limit=10';
            $leaveRequest = $this->createRequest($leaveRequestsUrl);// move to seperate method
            $leaveResults = $this->client->get($leaveRequest)->getResult();

            /**
             * getting employees on leave for the day
             * End point : leave/search
             * scheduled:true
             * taken:true
             * date : today
             */
            $leavesUrl = 'leave/search?reject=false&cancelled=false&pendingApproval=false&scheduled=true&taken=true&pastEmployee&page=0&limit=10&fromDate=' . $now . '&toDate=' . $now;
            $leavesToday = $this->createRequest($leavesUrl);
            $leavesInToday = $this->client->get($leavesToday)->getResult();

            /**
             * getting employee events
             * end point : employee/event
             * date from yesterday
             * to current date
             */
            $employeeEventUrl = 'employee/event';
            $eventRequest = $this->createRequest($employeeEventUrl);
            $data = $this->client->get($eventRequest)->getResult();

            /**
             * Get newly joined employees
             * end point : employee/event
             * parameters : date range for 30 days
             * event = SAVE ( getting saved employees )
             */
            $newlyJoined = 'employee/event?fromDate=' . $lastDay . '&toDate=' . $tomorrow . '&type=employee&event=SAVE';
            $newlyJoinedRequest = $this->createRequest($newlyJoined);
            $newlyJoinedResults = $this->client->get($newlyJoinedRequest)->getResult();

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
            $events['newMembers'] = $newlyJoinedResults;

            return json_encode($events);

        } catch (Exception $e) {
            $this->logError();
        }
    }


    function logError()
    {

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

}