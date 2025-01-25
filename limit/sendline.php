<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	date_default_timezone_set("Asia/Bangkok");

    function SendLine($token,$msg){
        $chOne = curl_init(); 
        curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
        curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
        curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt( $chOne, CURLOPT_POST, 1); 
        curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$msg); 
        $headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$token.'', );
        curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
        $result = curl_exec( $chOne ); 

        //Result error 
        if(curl_error($chOne)) 
        { 
            echo 'error:' . curl_error($chOne); 
        } 
        else { 
            $result_ = json_decode($result, true); 
            echo "status : ".$result_['status']; echo "message : ". $result_['message'];
        } 
        curl_close( $chOne );   
    }

    $aToken = array('Mi7KPWZ3vSbUkIKbymuyS9I3a6vPzPeXR5Z9zrobeTt','6lq7a9EqCP45Z6sN8M6BFk4PHXwD7TpfJgRC4f8XeaE',
    'yhnG6BRS0WDHWKj7ORfB0i3CMWiPnyGVttgsXEqRb2i','tVajnYpmM5wxiDf19Vk0U0pjokzHW2bTtCEpCkJeeSF',
    'iGVMJdET6Q0wbJUOURADBfxS6G6YAxzlZ0EEyGV8b7V','gjQVFZYqfR4YfWEGvyHixopH0Cgc42TBeTa51f47Zqi',
    '7KcZCEaE3HtgrdG7Daiw3FoRJWwUd28gX219LTExuLM');

    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    foreach($data['customers'] as $sToken){
        SendLine($sToken,$data['msg']);
    }


?>
