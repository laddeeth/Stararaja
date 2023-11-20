<?php 

$title = "Change Password";
$bodyid = "changebody";
	include '../startconfig.php';
	include '../src/lang/' . $lang . '/start.php';
	include '../include/headerstart.php';
	$user = isset($_GET['user']) ? strip_tags($_GET['user']) : null;
	$auth = isset($_GET['auth']) ? strip_tags($_GET['auth']) : null;
	
	if($user == null || $auth == null) {
		$authheader = $lang['changefailed'];
		$redirect1 = $lang['redirect1'];
		$redirect2 = $lang['redirect2'];
		$html = $lang['authbrokenlink'];
	}
	else {
		try {
		  $conn = new PDO($database['dsn'], $database['username'], $database['password'], $database['driver_options']);

			$sql = "SELECT mail, code, time FROM forgot WHERE mail='$user';";
			$sth = $conn->prepare($sql);
		  $sth->execute();
			$result = $sth->fetchAll();
		}
		catch(Exception $e) {
			exit;
		}
		if(empty($result)) {
			$authheader = $lang['changefailed'];
			$redirect1 = $lang['redirect1'];
			$redirect2 = $lang['redirect2'];
			$html = $lang['authbrokenlink'];
		}
		else if($auth == $result[0]['code']){
			$test = $result[0]['time'];
			$dt1 = new DateTime('now');
			$dt2 = new DateTime("$test");

			$diff = $dt2->diff($dt1);

			$hours = $diff->h;
			$hours = $hours + ($diff->days*24);
		
			if($hours < 48) {
				$authheader = $lang['changepassword'];
				$submit = $lang['submit'];
				$newToken = generateFormToken('changepassword'); 
				$html = $lang['changetext'] . "<br>" . "<form id='changepassword'>" . "<input type='hidden' name='token' value='$newToken'><input type='hidden' name='mail' value='$user'><input type='hidden' name='auth' value='$auth'>" . $lang['password'] . $lang['minsixchars'] . "<input type='password' name='password'>" . $lang['confirm'] . " " . $lang['password'] . "<input type='password' name='confirmpassword'>" . "<input type='button' name='dochange' id='dochange' value='$submit'>";
			}
			else {
				try {
					  $conn = new PDO($database['dsn'], $database['username'], $database['password'], $database['driver_options']);

						$sql = "DELETE FROM forgot WHERE mail=:mail LIMIT 1;";
						$sth = $conn->prepare($sql);
					  $sth->execute(array(':mail' => $user));
					}
					catch(Exception $e) {
						$error = $lang['unknownerror'];
						echo "<div class='regerror'><p>$error</p></div>";
						exit;
					}
					$authheader = $lang['changefailed'];
					$redirect1 = $lang['redirect1'];
					$redirect2 = $lang['redirect2'];
					$html = $lang['authbrokenlink'];
			}
		}
		else {
			$authheader = $lang['changefailed'];
			$redirect1 = $lang['redirect1'];
			$redirect2 = $lang['redirect2'];
			$html = $lang['authbrokenlink'];
		}
	}
?>

			<div id="yuimage">
				<img src="img/yutest.png" id="yuimg" alt="Yu">
			</div>
			<div id="yulog">
				<div id="authinfo">
					<a href='index.php'><img id='backimage' src='img/back.png' alt='Back'></a>
					<h2><?=$authheader?></h2>
					<div id="changemain" text1="<?=$lang['redirect1']?>" text2="<?=$lang['redirect2']?>"><p><?=$html?></p></div>
					<div id="forgotmessage">
					</div>
					<div id="loadingimage"><img src="img/loader.gif"></div>
				</div>
			</div>



<?php include '../include/footerstart.php';?>
<script src="js/change.js"></script>