<?php
    function emailservice($email, $body, $subject){
        #$email = "valjuniel123@gmail.com";
        $name = "<NO NAME>";
        #$body = "Hey man, how are you? <br><br><a href='https://google.com'>Google</a>";
        #$subject = "Test email";
        
        //export SENDGRID_API_KEY='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'

        $headers = array(
            'Authorization: Bearer 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',
            'Content-Type: application/json'
        );

        $data = array(
            "personalizations" => array(
                array(
                    "to" => array(
                        array(
                            "email" => $email,
                            "name" => $name
                        )
                    )
                )
            ),
            "from" => array(
                "email" => "spartanmail@spot-checking.com"
            ),
            "subject" => $subject,
            "content" => array(
                array(
                    "type" => "text/html",
                    "value" => $body
                )
            )
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;
    }
?>