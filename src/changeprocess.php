<?php 
	include '../startconfig.php';
	include '../src/lang/' . $lang . '/start.php';
	require '../apps/PHPMailer-master/PHPMailerAutoload.php';
	//Kontroll av fiffel
	
	if(!verifyFormToken('changepassword')) {
		die("Something went wrong. Admin has been notified.");
		writeLog('Formtoken');
		exit;
	}
	
	// Building a whitelist array with keys which will send through the form, no others would be accepted later on
	$whitelist = array('token', 'mail', 'auth', 'password', 'confirmpassword');

	// Building an array with the $_POST-superglobal 
	foreach ($_POST as $key=>$item) {
        
	        // Check if the value $key (fieldname from $_POST) can be found in the whitelisting array, if not, die with a short message to the hacker
			if (!in_array($key, $whitelist)) {
				$keyerror = strip_tags($key);
				writeLog("Unknown form fields - $keyerror");
				die("Something went wrong. Admin has been notified.");
			
				}
	}
	
	$email = strip_tags($_POST['mail']);
	$password = strip_tags($_POST['password']);
	$passwordhash = password_hash($password, PASSWORD_DEFAULT);
	
/*	try {
	  $conn = new PDO($database['dsn'], $database['username'], $database['password'], $database['driver_options']);

		$sql = "DELETE FROM forgot WHERE mail='$email' LIMIT 1;";
		$sth = $conn->prepare($sql);
	  $sth->execute();
	}
	catch(Exception $e) {
		$error = $lang['unknownerror'];
		echo "<div class='regerror'><p>$error</p></div>";
		exit;
	}*/
	
	try {
		  $conn = new PDO($database['dsn'], $database['username'], $database['password'], $database['driver_options']);

			$sql = "UPDATE user SET password='$passwordhash' WHERE email='$email' LIMIT 1;";
			$sth = $conn->prepare($sql);
		  $sth->execute();
		}
		catch(Exception $e) {
			$error = $lang['unknownerror'];
			echo "<div class='regerror'><p>$error</p></div>";
			exit;
		}
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
		$mail->FromName = 'Stararaja Password Changed';
		$mail->addAddress($email);     // Add a recipient
		$mail->addReplyTo('noreply@stararaja.com', 'Password Changed');

		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = $lang['changepasswordsubject'];
		$mail->Body    = $lang['changepasswordmail'];

		$mail->send();
		
		try {
			  $conn = new PDO($database['dsn'], $database['username'], $database['password'], $database['driver_options']);

				$sql = "DELETE FROM forgot WHERE mail='$email' LIMIT 1;";
				$sth = $conn->prepare($sql);
			  $sth->execute();
			}
			catch(Exception $e) {
				$error = $lang['unknownerror'];
				echo "<div class='regerror'><p>$error</p></div>";
				exit;
			}
			
		echo $lang['changepasswordsuccess'];
	