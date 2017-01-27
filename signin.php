<?php
/*
    SQL-Editor - An SQl table editor
    Copyright (C) 2016 Andrew S
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published
    by the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.
    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


include("config.php");
include("SQLHandler.php");

    
@session_start();

if (isset($_POST["user"] && isset($_POST["pass"]) {
$dbHandler = new SQLConnector($CONFIG["dbHost"],$CONFIG["dbUsername"],$CONFIG["dbPassword"],$CONFIG["dbName"],$CONFIG["dbPort"])
$data = $dbHandler.query("SELECT * FROM `SQL_Editor_Users` WHERE `user` = '".$_POST["user"]."' AND `pass` = '".$_POST["pass"]."'");
$user = $data->next();
  if ($user) {
   $_SESSION["data"] = $user;
    echo '{"Message":"Logged in"}';
  } else {
   echo '{"error":"Wrong username or pass"}'; 
  }
  
$dbHandler->close();
}
          
?>
