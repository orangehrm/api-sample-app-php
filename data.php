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



require_once 'config.php';

require_once 'lib/util.php';


use Orangehrm\API\Client;
use Orangehrm\API\HTTPRequest;


$client = new Client($config->host, $config->clientId, $config->clientSecret);

//Load the event Data

$event =$_POST["event"];
$type = $_POST["type"];
$employeeId = $_POST["id"];

// call the the util method
// based on the event type
if($event == 'getEventData'){
    getEventData($type,$employeeId,$client);
}else if ($event == 'createEvents'){
    createEvents($client);
}

?>

