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

require_once 'vendor/autoload.php';

use Orangehrm\API\Client;
use Orangehrm\API\HTTPRequest;



$client = new Client('http://localhost/','123','hrm');

$date = date("Y-m-d");

$latDay = date('Y-m-d',strtotime("30 days"));

$tomorrow = date('Y-m-d',strtotime("+1 days"));

$paramString = 'employee/event?fromDate='.$latDay.'&toDate='.$tomorrow;

$str2 ='employee/event?fromDate=2017-04-01&toDate=2017-04-30&type=employee&event=SAVE';

$request = new HTTPRequest($str2);
$result = $client->get($request)->getResult();

$leaveData = null;

try {

  $db = new DataBase();

  //  $createDB = new CreateTable();
  // $createDB->createTable($db);
//  $insert = new InsertData();
//  $insert->insertTable($db,$result);
//  $eventData ;

  // get Leave data

  $leaveRequestsUrl = 'leave/search?reject=false&cancelled=false&pendingApproval=true&scheduled=false&taken=false&pastEmployee&page=0&limit=10';


  $leaveRequest = new HTTPRequest($leaveRequestsUrl);

  $leaveResults = $client->get($leaveRequest)->getResult();

  $leavesUrl      = 'leave/search?reject=false&cancelled=false&pendingApproval=false&scheduled=true&taken=true&pastEmployee&page=0&limit=10&fromDate='.$date.'&toDate='.$date;

  $leavesToday = new HTTPRequest($leavesUrl);

  $leavesInToday = $client->get($leavesToday)->getResult();


  $data = new RetrieveData();
  $eventData =  $data->getData($db);

  $events['data'] = $eventData;
  $events['leaveRequests'] = $leaveResults;
  $events['onLeave'] = $leavesInToday;
  $events['newMembers']   = $result;

  echo json_encode($events);

} catch (Exception $e) {
  printf($e->getMessage());
}

?>

