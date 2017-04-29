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

require_once 'util.php';


/**
 * Class DataBase
 */
class DataBase extends \SQLite3
{
    function __construct()
    {
        $this->open('event.db');

    }

    public function purgeTable()
    {

        $sql = <<<EOF
      DELETE FROM EMPLOYEE_EVENT_TABLE
EOF;

        $ret = $this->exec($sql);
        if (!$ret) {
            logError("Failed to connect");
        } else {

        }
        $this->close();
    }

    public function getData()
    {

        $sql = <<<EOF
      SELECT * from EMPLOYEE_EVENT_TABLE;
EOF;

        $ret = $this->query($sql);
        $data = null;

        while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {

            $item['id'] = $row['ID'];
            $item['time'] = $row['TIME'];
            $item['data'] = json_decode($row['DATA']);
            $data[] = $item;

        }
        $this->close();
        return $data;
    }

    /**
     * Insert Data
     * @param $data
     */
    public function insertData($data)
    {
        try {

            $insert = "INSERT INTO EMPLOYEE_EVENT_TABLE (TIME,DATA) 
                VALUES (:time, :data)";

            $dateTime = date("Y-m-d h:i:sa");
            $stmt = $this->prepare($insert);
            $eventData = $data['data'];


            $stmt->bindParam(':time', $dateTime);
            $stmt->bindParam(':data', $eventItem);


            foreach ($eventData as $event) {
                $eventItem = json_encode($event, JSON_PRETTY_PRINT);
                $stmt->execute();
            }

        } catch (\Exception $ex) {
            logError($ex->getMessage());
        }

        $this->close();
    }

}