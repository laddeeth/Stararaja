<?php 
	include '../startconfig.php';
	include '../src/lang/' . $lang . '/start.php';
	require '../apps/PHPMailer-master/PHPMailerAutoload.php';
	//Kontroll av fiffel
	
	if(!verifyFormToken('register')) {
		die("Something went wrong. Admin has been notified.");
		writeLog('Formtoken');
		exit;
	}
	
	// Building a whitelist array with keys which will send through the form, no others would be accepted later on
	$whitelist = array('countryletter', 'cityletter', 'cityletterfrom', 'birth', 'token', 'email', 'confirmemail', 'password', 'confirmpassword', 'name','lastname', 'birthDay', 'gender', 'country', 'city', 'countryfrom', 'cityfrom');

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
	$formconfirmemail = strip_tags($_POST['confirmemail']);
	$formpassword = strip_tags($_POST['password']);
	$formconfirmpassword = strip_tags($_POST['confirmpassword']);
	$formpassword = password_hash($formpassword, PASSWORD_DEFAULT);
	$formfirstname = strip_tags($_POST['name']);
	$formlastname = strip_tags($_POST['lastname']);
	$formbirthday = strip_tags($_POST['birthDay']);
	$formgender = strip_tags($_POST['gender']);
	$formcountrynow = strip_tags($_POST['country']);
	$formcitynow = strip_tags($_POST['city']);
	$formcountrythen = strip_tags($_POST['countryfrom']);
	$formcitythen = strip_tags($_POST['cityfrom']);
	$generatedKey = sha1(mt_rand(10000,99999).time().$formemail);
	
	
	try {
	  $conn = new PDO($database['dsn'], $database['username'], $database['password'], $database['driver_options']);

		$sql = "SELECT registered, active FROM user WHERE email='$formemail' LIMIT 1;";
		$sth = $conn->prepare($sql);
	  $sth->execute();
		$result = $sth->fetchAll();
		if(!empty($result)){
			if($result[0]['active'] !== null){
				$alreadyregistered = $lang['alreadyregistered1'] . "<b>" . $formemail . "</b>" . $lang['alreadyregistered2'];
				echo "<div class='regerror'><img src='img/warning.png'>$alreadyregistered</div>";
				exit;
			}
			$test = $result[0]['registered'];
			$dt1 = new DateTime('now');
			$dt2 = new DateTime("$test");

			$diff = $dt2->diff($dt1);

			$hours = $diff->h;
			$hours = $hours + ($diff->days*24);

			if($result[0]['active'] == null && $hours < 48) {
				$needtoconfirm = $lang['needtoconfirm1'] . "<u>" . $formemail . "</u>" . $lang['needtoconfirm2'] . "<u>" . (48 - $hours) . "</u>" . $lang['needtoconfirm3'];
				
				echo "<div class='regerror'><img src='img/warning.png'>$needtoconfirm</div>";
				exit;
			}
			else if ($result[0]['active'] == null) {
			  $conn = new PDO($database['dsn'], $database['username'], $database['password'], $database['driver_options']);

				$sql = "DELETE FROM user WHERE email='$formemail' LIMIT 1;";
				$sth = $conn->prepare($sql);
				$sth->execute();
			}
		}
			}
	catch(Exception $e) {
		$error = $lang['unknownerror'];
		echo "<div class='regerror'><p>$error</p></div>";
		exit;
	}
	
	try {
	  $conn = new PDO($database['dsn'], $database['username'], $database['password'], $database['driver_options']);

		$sql = "INSERT INTO user (email, password, firstname, lastname, birthday, gender, countrynow, citynow, countrythen, citythen, confirmation, registered)
		    VALUES ('$formemail', '$formpassword', '$formfirstname', '$formlastname', '$formbirthday', '$formgender', '$formcountrynow', '$formcitynow', '$formcountrythen', '$formcitythen', '$generatedKey', NOW())";
		    // use exec() because no results are returned
				$sth = $conn->prepare($sql);
				$sth->execute();
			}
	catch(Exception $e) {
		$error = $lang['unknownerror'];
		echo "<div class='regerror'><p>$error</p></div>";
		exit;
	  //throw $e; // For debug purpose, shows all connection details
	  throw new PDOException('Could not connect to database, hiding connection details.'); // Hide connection details.
	
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
	$mail->FromName = 'Stararaja Registration';
	$mail->addAddress($formemail, $formfirstname . " " . $formlastname);     // Add a recipient
	$mail->addReplyTo('noreply@stararaja.com', 'Registration');

	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = $lang['confirmregistrationsubject'];
	$mail->Body    = $lang['mailauthorisation'] . "<a href=http://www.stararaja.com/auth.php?user=" . $formemail. "&auth=" . $generatedKey . ">http://www.stararaja.com/auth.php?user=" . $formemail . "&auth=" . $generatedKey . "</a>";

	$mail->send();
	
	echo "<div class='regsuccess'><img src='img/check.png'>" . $lang['registersuccess1'] . $formemail . $lang['registersuccess2'] . "</div>";
	
?>