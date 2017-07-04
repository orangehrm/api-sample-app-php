<?php session_start(); /* Starts the session */

require_once 'config.php';
require_once 'lib/Util.php';
use Orangehrm\API\Client;

/* Check Login form submitted */
if(isset($_POST['Submit'])){

    /* Check and assign submitted Username and Password to new variable */
    $username = isset($_POST['Username']) ? $_POST['Username'] : '';
    $password = isset($_POST['Password']) ? $_POST['Password'] : '';
    $client = new Client($config->host, $config->clientId, $config->clientSecret);
    $util  = new Util();

    $util->setClient($client);
    $response = $util->validateUser($username,$password);

    /* Check Username and Password existence in defined array */
    if ($response['login']){
        /* Success: Set session variables and redirect to Protected page  */
        $_SESSION['UserData']['Username']= $username;
        header("location:index.php");
        exit;
    } else {
        /*Unsuccessful attempt: Set error message */
        $msg="<span style='color:red'>Invalid Login Details</span>";
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notifications Login</title>
    <link href="orangeApp/orange/dist/css/login.css" rel="stylesheet">
<!--    <link rel="stylesheet" href="orangeApp/orange/dist/css/sampleApp.min.css">-->
</head>
<body>
<div id="Frame0">


    <table>
        <tr>
            <th>  <img src="orangeApp/orange/dist/img/orange.png" class="img-circle" alt="User Image"> </th>
            <th>  <h1>Notifications Dashboard Login</h1></th>
        </tr>

    </table>




</div>
<br>
<form action="" method="post" name="Login_Form">
    <table width="400" border="0" align="center" cellpadding="5" cellspacing="1" class="Table">
        <?php if(isset($msg)){?>
            <tr>
                <td colspan="2" align="center" valign="top"><?php echo $msg;?></td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="2" align="left" valign="top"><h3>Login</h3></td>
        </tr>
        <tr>
            <td align="right" valign="top">Username</td>
            <td><input name="Username" type="text" class="Input"></td>
        </tr>
        <tr>
            <td align="right">Password</td>
            <td><input name="Password" type="password" class="Input"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input name="Submit" type="submit" value="Login" class="Button3"></td>
        </tr>
    </table>
</form>
<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        Notification Dashboard
    </div>
    <!-- Default to the left -->
    <strong>Notifications dashboard © 2005 - <script>document.write(new Date().getFullYear())</script> <br> <a target="_blank" href="https://www.orangehrm.com/">OrangeHRM, Inc</a></strong>.All rights reserved

</footer>
</body>
</html>