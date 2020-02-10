<?php
    require_once '../load.php';

    $ip = $_SERVER['REMOTE_ADDR'];

    // $conn = mysqli_connect('localhost', 'root', '');
    //  $date = $_SERVER['REQUEST_TIME_FLOAT'];

//     if (empty($_SESSION['failed_login'])) 
// {
//     $_SESSION['failed_login'] = 1;
// } 
// elseif (isset($_POST['submit'])) 
// {
//     $_SESSION['failed_login']++;
// }


// // if login fail 3 times
// if ($_SESSION['failed_login'] > 3) {
// 	$message = 'U failed to login 3 times ' .$_SESSION['failed_login'];
// }

	// if (isset($_POST["username"]) && isset($_POST["password"]))
	// {
	// 	// This checks if the value has ever been set, if not, declares it as zero.
	// 	if (!isset($_SESSION["attempts"]))
	// 		$_SESSION["attempts"] = 0;
			
	// 	if ($_SESSION["attempts"] < 3)
	// 	{
	// 		$username = $_POST["username"];
	// 		$password = $_POST["password"];
			
	// 		if ($username = "test" && $password == "test")
	// 		{
	// 			echo "Hello, you are logged in.";
	// 		}
	// 		else
	// 		{
	// 			echo "You failed to log-in, try again";
	// 			$_SESSION["attempts"] = $_SESSION["attempts"] + 1;
	// 		}
			
	// 	}
	// 	else
	// 	{
	// 		echo "You've failed too many times, dude.";
	// 	}
	// }


    if(isset($_POST['submit'])){



        // if (!isset($_SESSION["attempts"]))
		// 	$_SESSION["attempts"] = 0;
			
		// if ($_SESSION["attempts"] < 3)
		// {
            $fname = trim($_POST['fname']);
            $lname = trim($_POST['lname']);
            $email = trim($_POST['email']);
            $country = trim($_POST['country']);

			
			if(!empty($fname) && !empty($lname) && !empty($email) && !empty($country))
			{
                 $message = login($fname, $lname, $email, $country, $ip);

                //   echo $sql;
                //  exit;

                $apiKey = '03a527ee441a392f6e289a4c7afdb535-us4';
                $listID = '78f599c2c7';
                
                 
                // MailChimp API URL
            $memberID = md5(strtolower($email));
            $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;
                 
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
        <input type="text" name="fname" id="fname" value="">

        <label for="">Last name:</label>
        <input type="text" name="lname" id="lname" value="">

        <label for="">Email:</label>
        <input type="email" name="email" id="email" value="">

        <label for="">Country</label>
        <input type="text" name="country" id="country" value="">

        <div class="g-recaptcha" data-sitekey="your_site_key"></div>

        <button name="submit">Submit</button>
    </form>
</body>
</html>