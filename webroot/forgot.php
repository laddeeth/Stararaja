<?php 
$title = "Forgot Password";
$bodyid = "forgotbody";
	include '../startconfig.php';
	include '../src/lang/' . $lang . '/start.php';
	include '../include/headerstart.php';
?>

			<div id="yuimage">
				<img src="img/yutest.png" id="yuimg" alt="Yu">
			</div>
			<div id="yulog">
				<div id="authinfo">
					<a href="index.php"><img id="backimage" src="img/back.png" alt="Back"></a><h2><?=$lang['forgot']?></h2>
					<p><?=$lang['forgottenpassword']?></p>
					<div id="formcontainerforgot">
						<?php
						   $newToken = generateFormToken('formforgotpassword');   
						?>
					<form id="formforgotpassword">
						<input type="hidden" name="token" value="<?php echo $newToken; ?>">
						<?=$lang['email']?>:<input type="text" name="email">
						<input type="button" name="dosend" id="dosend" value="<?=$lang['send']?>">
					</form>
					<div id="forgotmessage">
					</div>
				</div>				
				</div>
			</div>
<?php include '../include/footerstart.php';?>
<script src="js/xregexp-all-min.js"></script>
<script src="js/forgot.js"></script>