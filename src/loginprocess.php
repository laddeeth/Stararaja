<?php 
	include '../startconfig.php';
	include '../src/lang/' . $lang . '/start.php';
	//Kontroll av fiffel
	
	if(!verifyFormToken('loginform')) {
		die("Something went wrong. Admin has been notified.");
		writeLog('Formtoken');
		exit;
	}
	
	// Building a whitelist array with keys which will send through the form, no others would be accepted later on
	$whitelist = array('token', 'email', 'password');

	// Building an array with the $_POST-superglobal 
	foreach ($_POST as $key=>$item) {
        
	        // Check if the value $key (fieldname from $_POST) can be found in the whitelisting array, if not, die with a short message to the hacker
			if (!in_array($key, $whitelist)) {
				$keyerror = strip_tags($key);
				writeLog("Unknown form fields - $keyerror");
				die("Something went wrong. Admin has been notified.");
			
				}
	}
	
	$mail = strip_tags($_POST['email']);
	$password = strip_tags($_POST['password']);
	
	try {
	  $conn = new PDO($database['dsn'], $database['username'], $database['password'], $database['driver_options']);

		$sql = "SELECT id, email, password FROM user WHERE email=:mail LIMIT 1;";
		$sth = $conn->prepare($sql);
		$sth->bindParam(':mail', $mail, PDO::PARAM_STR); 
		$sth->execute();
		$result = $sth->fetchAll();
			}
	catch(Exception $e) {
		$error = $lang['unknownerror'];
		echo "<div class='regerror'><p>$error</p></div>";
		exit;
	  //throw $e; // For debug purpose, shows all connection details
	  throw new PDOException('Could not connect to database, hiding connection details.'); // Hide connection details.
	
	}
	if(empty($result)) {
		echo "?e=" . $mail;
	}
	else if(!password_verify($password, $result[0]['password'])){
		echo "?p=" . $mail;
	}
	else {
		$_SESSION['user'] = array('id' => $result[0]['id'], 'email' => $result[0]['email']);
		echo "profile.php";
	}
	