<?php 
	include '../startconfig.php';
	include '../src/lang/' . $lang . '/start.php';
	require '../apps/PHPMailer-master/PHPMailerAutoload.php';
	//Kontroll av fiffel
	
	if(!verifyFormToken('formforgotpassword')) {
		die("Something went wrong. Admin has been notified.");
		writeLog('Formtoken');
		exit;
	}
	
	// Building a whitelist array with keys which will send through the form, no others would be accepted later on
	$whitelist = array('token', 'email');

	// Building an array with the $_POST-superglobal 
	foreach ($_POST as $key=>$item) {
        
	        // Check if the value $key (fieldname from $_POST) can be found in the whitelisting array, if not, die with a short message to the hacker
			if (!in_array($key, $whitelist)) {
				$keyerror = strip_tags($key);
				writeLog("Unknown form fields - $keyerror");
				die("Something went wrong. Admin has been notified.");
			
				}
	}
	
	$formemail = strip_tags($_POST['email']);
	
	try {
	  $conn = new PDO($database['dsn'], $database['username'], $database['password'], $database['driver_options']);

		$sql = "SELECT email, active FROM user WHERE email='$formemail' LIMIT 1;";
		$sth = $conn->prepare($sql);
	  $sth->execute();
		$result = $sth->fetchAll();
	}
	catch(Exception $e) {
		$error = $lang['unknownerror'];
		echo "<div class='regerror'><p>$error</p></div>";
		exit;
	}
	
	if(empty($result)){
		echo "<img src='img/warning.png'>" . $lang['noactiveaccount1'] . "<b>" . $formemail . "</b>" . $lang['noactiveaccount2'];
	}
	else if($result[0]['active'] !== null) {
	  $conn = new PDO($database['dsn'], $database['username'], $database['password'], $database['driver_options']);
		$sql = "SELECT time FROM forgot WHERE mail='$formemail' LIMIT 1;";
		$sth = $conn->prepare($sql);
	  $sth->execute();
		$forgotuser = $sth->fetchAll();
		
		if(!empty($forgotuser)) {
			$test = $forgotuser[0]['time'];
			$dt1 = new DateTime('now');
			$dt2 = new DateTime("$test");

			$diff = $dt2->diff($dt1);

			$hours = $diff->h;
			$hours = $hours + ($diff->days*24);

			if($hours > 48) {
			  $conn = new PDO($database['dsn'], $database['username'], $database['password'], $database['driver_options']);
				$sql = "DELETE FROM forgot WHERE mail='$formemail' LIMIT 1;";
				$sth = $conn->prepare($sql);
			  $sth->execute();
			}
			else{
				echo "<img src='img/warning.png'>" . $lang['forgotlater'];
				exit;
			}
		}

			$generatedKey = sha1(mt_rand(10000,99999).time().$formemail);
		  $conn = new PDO($database['dsn'], $database['username'], $database['password'], 			$database['driver_options']);
			$sql = "INSERT INTO forgot (mail, code, time) VALUES ('$formemail', '$generatedKey', NOW())";
			$sth = $conn->prepare($sql);
		  $sth->execute();
			
			$mail = new PHPMailer;

			//$mail->SMTPDebug = 3;                               // Enable verbose debug output

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'mail.stararaja.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'admin@stararaja.com';                 // SMTP username
			$mail->Password = 'Mail461906!';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to

			$mail->From = 'noreply@stararaja.com';
			$mail->FromName = 'Stararaja Forgot Password';
			$mail->addAddress($formemail);     // Add a recipient
			$mail->addReplyTo('noreply@stararaja.com', 'Change Password');

			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = $lang['forgot'];
			$mail->Body    = $lang['mailforgot'] . "<a href=http://www.stararaja.com/change.php?user=" . $formemail. "&auth=" . $generatedKey . ">http://www.stararaja.com/change.php?user=" . $formemail . "&auth=" . $generatedKey . "</a>";

			$mail->send();

			
		echo "<img src='img/check.png'>" . $lang['forgotsuccess1'] . $formemail . $lang['forgotsuccess2'];
		
	}
	else {
		echo "<img src='img/warning.png'>" . $lang['noactiveaccount1'] . "<b>" . $formemail . "</b>" . $lang['noactiveaccount2'];
	}