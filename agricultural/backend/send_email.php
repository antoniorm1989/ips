<?php

$message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<h2>Mensaje enviado del portal <b>MotorSports</b>:</h2>
<p>".$_GET["message"]."</p>
Atte. <b>".$_GET["name"]."</b>
</body>
</html>
";

// use wordwrap() if lines are longer than 70 characters
$message= wordwrap($message,70);

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <'.$_GET["mail"].'>' . "\r\n";
$headers .= 'Cc: sales@innopartserv.com' . "\r\n";

if(mail("mrios@innopartserv.com",$_GET["subject"],$message,$headers)){
  echo "success";
}else{
 echo "error";
}

?>