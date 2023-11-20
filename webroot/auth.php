<?php 

$title = "Authorization";
$bodyid = "authbody";
	include '../startconfig.php';
	include '../src/lang/' . $lang . '/start.php';
	include '../include/headerstart.php';
	$user = isset($_GET['user']) ? strip_tags($_GET['user']) : null;
	$auth = isset($_GET['auth']) ? strip_tags($_GET['auth']) : null;
	
	if($user == null || $auth == null) {
		$authheader = $lang['authfailed'];
		$html = $lang['authbrokenlink'];
	}
	else {
		try {
		  $conn = new PDO($database['dsn'], $database['username'], $database['password'], $database['driver_options']);

			$sql = "SELECT id, active, confirmation, registered FROM user WHERE email='$user';";
			$sth = $conn->prepare($sql);
		  $sth->execute();
			$result = $sth->fetchAll();
		}
		catch(Exception $e) {
			exit;
		}
		$test = $result[0]['registered'];
		$dt1 = new DateTime('now');
		$dt2 = new DateTime("$test");

		$diff = $dt2->diff($dt1);

		$hours = $diff->h;
		$hours = $hours + ($diff->days*24);
		
		if($result[0]['active'] !== null) {
			$html = $lang['authbrokenlink'];
			$authheader = $lang['authfailed'];
		}
		else if($hours > 48) {
			$html = $lang['authbrokenlink'];
			$authheader = $lang['authfailed'];
		}
		else if($result[0]['confirmation'] == $auth) {
			try {
			  $conn = new PDO($database['dsn'], $database['username'], $database['password'], $database['driver_options']);

				$sql = "UPDATE user SET active=NOW() WHERE email='$user'";
				$conn->exec($sql);
			}
			catch(Exception $e) {
				exit;
			}
			$authheader = $lang['authsuccessful'];
			$html = $lang['authsuccess1'] . "<b>" . $user . "</b>" . $lang['authsuccess2'];
		}
		else {
			$authheader = $lang['authfailed'];
			$html = $lang['authbrokenlink'];
		}
	}
?>

			<div id="yuimage">
				<img src="img/yutest.png" id="yuimg" alt="Yu">
			</div>
			<div id="yulog">
				<div id="authinfo">
					<h2><?=$authheader?></h2>
					<p><?=$html?></p>
					<p class="countdown" text1="<?=$lang['redirect1']?>" text2="<?=$lang['redirect2']?>"></p>
				</div>
			</div>



<?php include '../include/footerstart.php';?>
<script src="js/auth.js"></script>