<?php

$curl_cliq = curl_init();
        
        curl_setopt_array($curl_cliq, array(
          CURLOPT_URL => 'https://cliq.zoho.com/api/v2/channelsbyname/logduplicadoscw/message?zapikey=##################',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{"text":"MENSAJE"}',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Cookie: 11cd092e0d=39bcf21c3333f32d36b34a4ea603ad59; CT_CSRF_TOKEN=de46c591-6166-4535-864b-32237e8a2a4d; _zcsr_tmp=de46c591-6166-4535-864b-32237e8a2a4d'
          ),
        ));
        
        $response_cliq = curl_exec($curl_cliq);        
 curl_close($curl_cliq);

?>
