<?php

function login($fname, $lname, $email, $country, $ip){
    //Debug
    // $message = sprintf('You are trying to login with username %s and password %s', $username, $password);

    $pdo = Database::getInstance()->getConnection();
    // Check existance
    // $check_exist_query = 'SELECT COUNT(*) FROM tbl_user WHERE user_name="'.$username.'" ';

        // $sql = "INSERT INTO tbl_user (user_fname, user_lname, user_email, user_country) VALUES ('$fname', '$lname', '$email', '$country') ON DUPLICATE KEY UPDATE user_fname = '$fname'";

    // $pdo = Database::getInstance()->getConnection();
    // $user_sql = $pdo->prepare($sql);

    // $user_sql->execute();

    //Check existance
    // $check_exist_query = 'SELECT COUNT(*) FROM tbl_user WHERE user_name="'.$username.'" ';
    $check_exist_query = 'SELECT COUNT(*) FROM tbl_user WHERE user_email= :email ';

    $user_set = $pdo->prepare($check_exist_query);
    // $user_set->prepare($check_exist_query);
    
    // $user_set = $pdo->query($check_exist_query);
    
    // $user_set = $pdo->query($check_exist_query);

    $user_set->execute(
        array(
            ':email' => $email,
        )
    );

    $message="Same email used!";

   


    if($user_set->fetchColumn()>0){
        //user exists
        // $message = 'User exists!';
        $get_user_query = 'SELECT * FROM tbl_user WHERE user_fname = :fname';
        $get_user_query .= ' AND user_lname = :lname';
        $user_check = $pdo->prepare($get_user_query);
        $user_check->execute(
            array(
                ':fname'=>$fname,
                ':lname'=>$lname
            )
         );
        //log user in

        while($found_user = $user_check->fetch(PDO::FETCH_ASSOC)){
            $id = $found_user['user_id'];

            $date = date_default_timezone_set("America/Toronto");
            $date = date("Y-m-d h:i:s");
            // $date =date( string "Y-m-d h:i:s" [, int $timestamp = time() ]);
            // $date = $date->format('UTC');
          
            
            //logged in!
            $message = 'You just logged in!';

            // echo $date;
            // exit;

            //TODO: finish the following lines so that when user logged in
            //The user_ip column get updated by the $ip
            $update_query = 'UPDATE tbl_user SET user_ip = :ip WHERE user_id = :id';
            $update_set = $pdo->prepare($update_query);
            $update_set->execute(
                array(
                    ':id'=>$id,
                    ':ip'=>$ip
                    )
            );
            // echo $update_query;
            // exit;
        

            $update_time_query = 'UPDATE tbl_user SET user_lastdate = :curr_date WHERE user_id = :id';
            $update_time_set = $pdo->prepare($update_time_query);
            $update_time_set->execute(
                array(
                    ':id'=>$id,
                    ':curr_date'=>$date
                    )
            );
            // echo $update_time_query;
            // exit;
        }

        if(isset($id)){
            redirect_to('index.php');
        }
        
    }else{
        // User doesn't exist
        // 
        $message = 'New profile created!';

        $sql = "INSERT INTO tbl_user (user_fname, user_lname, user_email, user_country) VALUES ('$fname', '$lname', '$email', '$country')";

        $user_sql = $pdo->prepare($sql);

        $user_sql->execute();

        redirect_to('index.php');

        // echo $sql;
        // exit;

                    

    }

    return $message;
}