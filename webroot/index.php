<?php 
$title = "Start";
$bodyid = "startbody";
	include '../startconfig.php';
	include '../src/lang/' . $lang . '/start.php';
	include '../include/headerstart.php';
	$regex = '/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i';
	$p = isset($_GET['p']) ? strip_tags($_GET['p']) : null;
	$e = isset($_GET['e']) ? strip_tags($_GET['e']) : null;
	
	if(!preg_match($regex, $e)) {
			$e = null;
		}
		
	if(!preg_match($regex, $p)) {
		$p = null;
	}
	if(!empty($e) && !empty($p)){
		$e = $p = null;
	}
?>
			<div id="yuimage">
				<img src="img/yutest.png" id="yuimg" alt="Yu">
			</div>
			<div id="yulog">
				<div id="loginbutton" class="selectedlog"><?=$lang['login']?></div><div id="signupbutton" 				class="unselectedlog"><?=$lang['signup']?></div>
				<div id="rutan">
					<div id="login">
						<?php
						   $logintoken = generateFormToken('loginform');   
						?>
						<form id="loginform">
							<input type="hidden" name="token" value="<?php echo $logintoken; ?>">
							<table>
								<tr><td class="textright"><?=$lang['email']?></td><td class="inputboxtd"><input type="text" name="email" value="<?=$p?><?=$e?>" errortext="<?=$lang['loginmailerror']?>"></td><td class="tredje"></td></tr>
								<tr><td class="textright"><?=$lang['password']?></td><td class="inputboxtd"><input type="password" name="password" errortext="<?=$lang['loginpassworderror']?>"></td></tr>
								<tr><td></td><td class="inputboxtd"><input type="button" name="dologin" id="dologin" value="<?=$lang['login']?>"></td></tr>
								<tr><td></td><td><div id="loginmessage" <?php echo isset($p) ? "style='display:block;'": '';?><?php echo isset($e) ? "style='display:block;'": '';?>>
								<?php echo isset($p) ? "<img src='img/warning.png'>" . $lang['loginpassworderror'] : '';?><?php echo isset($e) ? "<img src='img/warning.png'>" . $lang['notregistered'] : '';?></div></td></tr>
								<tr><td></td><td class="inputboxtd"><a href="forgot.php"><?=$lang['forgot']?></a></td></tr>
							</table>
						</form>
					</div>
					
					<div id="register" class="hidden">
						<?php
						   $newToken = generateFormToken('register');   
						?>
						<form id="registerform">
							<input type="hidden" name="token" value="<?php echo $newToken; ?>">
							<table>
								<tr><td><?=$lang['email']?><br><input type="text" name="email" errortext="<?=$lang['emailerrortext']?>"></td><td><?=$lang['confirm']?> <?=$lang['email']?><br><input type="text" name="confirmemail" errortext="<?=$lang['confirmemailerrortext']?>"></td></tr>
								<tr><td><?=$lang['password']?><?=$lang['minsixchars']?><br><input type="password" name="password" errortext="<?=$lang['passworderrortext']?>"></td><td><?=$lang['confirm']?> <?=$lang['password']?><br><input type="password" name="confirmpassword" errortext="<?=$lang['confirmpassworderrortext']?>"></td></tr>
								<tr><td><?=$lang['firstname']?><br><input type="text" name="name" errortext="<?=$lang['firstnameerrortext']?>"></td><td><?=$lang['lastname']?><br><input type="text" name="lastname" errortext="<?=$lang['lastnameerrortext']?>"></td></tr>
								<tr><td style="padding-top:1%;"><?=$lang['birthdate']?><br><div id="birthday" month="<?=$lang['month']?>" day="<?=$lang['day']?>" year="<?=$lang['year']?>" errortext="<?=$lang['birthdayerrortext']?>"></div></td><td  style="padding-top:1%;" style="width:50%;"><?=$lang['gender']?><br><input type="radio" name="gender" value="male" errortext="<?=$lang['gendererrortext']?>"><?=$lang['male']?> <input type="radio" name="gender" value="female" errortext="<?=$lang['gendererrortext']?>"><?=$lang['female']?></td></tr>
							</table>
							
							<div id="countrybox"><p class="livewhere"><?=$lang['livenow']?></p>
							<p><span><?=$lang['country']?></span><select name="countryletter" id="countryletter" autocomplete="off"><option value="notselected">--</option><?php include '../src/geo/select/letter.inc';
							?></select><select name="country" id="country" autocomplete="off"><option value="notselected"><--<?=$lang['selectfirstletter']?></option></select></p>
							<p><span><?=$lang['city']?></span><select name="cityletter" id="cityletter" autocomplete="off"><option value="notselected">--</option></select><select name="city" id="city" autocomplete="off" errortext="<?=$lang['cityliveerrortext']?>"><option value="notselected"><?=$lang['selectcountry']?></option></select></p><br>
							
							
							<p class="livewhere"><?=$lang['livefrom']?></p>
														<p><span><?=$lang['country']?></span><select name="countryfrom" id="countryfrom" autocomplete="off"><option value="notselected">--</option><option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option><option value="Croatia">Croatia</option><option value="Macedonia">Macedonia</option><option value="Montenegro">Montenegro</option><option value="Serbia">Serbia</option><option value="Slovenia">Slovenia</option><option value="Other">Other</option></select></p>
														<p><span><?=$lang['city']?></span><select name="cityletterfrom" id="cityletterfrom" autocomplete="off"><option value="notselected">--</option></select><select name="cityfrom" id="cityfrom" autocomplete="off" errortext="<?=$lang['cityfromerrortext']?>"><option value="notselected"><?=$lang['selectcountry']?></option></select></p>
						</div>
						<input type="button" name="doregister" id="doregister" value="<?=$lang['signup']?> <?=$lang['now']?>!">
						</form><br>
						<div id="loadingimage"><img src="img/loader.gif"></div>
					</div>
				</div>
			</div>
		</div>
		<?php include '../include/footerstart.php';?>
		<script src="js/birthday.js"></script>
		<script src="js/xregexp-all-min.js"></script>
		<script src="js/unicode-base.js"></script>
		<script src="js/start.js"></script>
		