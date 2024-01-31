<?php
    include_once '_common.php';
    include_once DOZZK_ROOT.'/config.php';

    if ($is_guest) {
        http_response_code(401);
        die;
    }

    $postData = array(
        "id" => $member["mb_id"],
        "name" => $member["mb_nick"],
        "secret" => DOZZK_API_SECRET
    );

    // Setup cURL
    $ch = curl_init(DOZZK_API_HOST.'chat/register');
    curl_setopt_array($ch, array(
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_HTTPHEADER => array(
            // 'Authorization: '.$authToken,
            'Content-Type: application/json'
        ),
        CURLOPT_POSTFIELDS => json_encode($postData)
    ));

    // Send the request
    $response = curl_exec($ch);

    // Check for errors
    if($response === FALSE){
        http_response_code(502);
        die;
    }

    // Decode the response
    // $responseData = json_decode($response, TRUE);

    // Close the cURL handler
    curl_close($ch);

    echo $response;
?>