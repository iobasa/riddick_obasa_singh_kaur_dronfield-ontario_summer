<?php
    require_once '../load.php';

    $ip = $_SERVER['REMOTE_ADDR'];


    if(isset($_POST['submit'])){



            $fname = trim($_POST['fname']);
            $lname = trim($_POST['lname']);
            $email = trim($_POST['email']);
            $country = trim($_POST['country']);

			
			if(!empty($fname) && !empty($lname) && !empty($email) && !empty($country))
			{

                $apiKey = '03a527ee441a392f6e289a4c7afdb535-us4';
                $listID = '78f599c2c7';
                
                 
                // MailChimp API URL
            $memberID = md5(strtolower($email));
            $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
            $url = 'https://'.$dataCenter .'.api.mailchimp.com/3.0/lists/'.$listID.'/members/'.$memberID.'';
                 
                $json = json_encode([
                    'email_address' => $email,
                    'status'        => 'subscribed',
                    'merge_fields'  => [
                        'FNAME'     => $fname,
                        'LNAME'     => $lname
                    ]
                ]);
             
                    // send a HTTP POST request with curl
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
                if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
                {
                      $secret = '6Le2o9cUAAAAAFgmZ3lVZcKJAPr_dH9c8XpT-OII';
                      $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
                      $responseData = json_decode($verifyResponse);

                      if($responseData->success)
                      {
                          $message = 'Your contact request have submitted successfully.';
                      }
                      else
                      {
                          $message = 'Robot verification failed, please try again.';
                      }
                 }


                 $message = login($fname, $lname, $email, $country, $ip);

                //   echo $sql;
                //  exit;

              

    
            
        }else{
            $message = 'Please fill out the required field';
        }

}

        /* This sets the $time variable to the current hour in the 24 hour clock format */
        $time = date("H");
        /* Set the $timezone variable to become the current timezone */
        $timezone = date("e");
        /* If the time is less than 1200 hours, show good morning */
        if ($time < "12") {
            echo "Good morning";
        } else
        /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
        if ($time >= "12" && $time < "17") {
            echo "Good afternoon";
        } else
        /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
        if ($time >= "17" && $time < "19") {
            echo "Good evening";
        } else
        /* Finally, show good night if the time is greater than or equal to 1900 hours */
        if ($time >= "19") {
            echo "Good night";
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <h2>Login Page</h2>
    <?php echo !empty($message)? $message: ''; ?>
    <!-- post foes not reveal information on the site, and get does-->
    <form action="admin_login.php" method="post">
        <label for="">First name:</label>
        <input type="text" name="fname" id="fname" value="" required>

        <label for="">Last name:</label>
        <input type="text" name="lname" id="lname" value="" required>

        <label for="">Email:</label>
        <input type="email" name="email" id="email" value="" required>

        <label for="">Country</label>
        <input type="text" name="country" id="country" value="" required>

        <div class="g-recaptcha" data-sitekey="6Le2o9cUAAAAALBJ8-GMgwVOJm8kzwHGR2nuIjcD"></div>

        <button name="submit">Submit</button>
    </form>
</body>
</html>