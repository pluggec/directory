<?php
error_reporting(0);
ob_start();
session_start();
include("includes/functions.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Install BitExchanger v2.0</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
	<link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="assets/css/install.css" rel="stylesheet">
  </head>

  <body>

     <!-- Static navbar -->


	<div class="container"">
		<div class="row">
			<div class="col-lg-12 box-white rounded">
				    <div class="navbar navbar-default rounded-top" role="navigation">
					  <div class="container">
						<div class="navbar-header">
						  <a class="navbar-brand" href="./">BitExchanger v2.0</a>
					    </div>
					  </div>
					</div>	
					<div class="row" style="padding-bottom:10px;">
						<div class="col-lg-12">
						<?php
						if(isset($_GET['unset_license'])) { session_unset(); session_destroy(); unset($_SESSION['license_key']); header("Location: ./install.php"); }
						if($_SESSION['license_key']) {
							if(isset($_POST['fc_install'])) {
								$mysql_host = protect($_POST['mysql_host']);
								$mysql_user = protect($_POST['mysql_user']);
								$mysql_pass = protect($_POST['mysql_pass']);
								$mysql_base = protect($_POST['mysql_base']);
								$title = protect($_POST['title']);
								$description = protect($_POST['description']);
								$keywords = protect($_POST['keywords']);
								$name = protect($_POST['name']);
								$url = protect($_POST['url']);
								$infoemail = protect($_POST['infoemail']);
								$supportemail = protect($_POST['supportemail']);
								$skype = protect($_POST['skype']);
								$whatsapp = protect($_POST['whatsapp']);
								$referral_comission = protect($_POST['referral_comission']);
								$wallet_comission = protect($_POST['wallet_comission']);
								$login_to_exchange = protect($_POST['login_to_exchange']);
								if(isset($_POST['document_verification'])) { $document_verification = '1'; } else { $document_verification = '0'; }
								if(isset($_POST['email_verification'])) { $email_verification = '1'; } else { $email_verification = '0'; }
								if(isset($_POST['phone_verification'])) { $phone_verification = '1'; } else { $phone_verification = '0'; }
								$nexmo_api_key = protect($_POST['nexmo_api_key']);
								$nexmo_api_secret = protect($_POST['nexmo_api_secret']);
								$worktime_from = protect($_POST['worktime_from']);
								$worktime_to = protect($_POST['worktime_to']);
								$worktime_gmt = protect($_POST['worktime_gmt']);
								$footer_information = protect($_POST['footer_information']);
								$username = protect($_POST['username']);
								$email = protect($_POST['email']);
								$password = protect($_POST['password']);
								
								if(empty($mysql_host) or empty($mysql_user) or empty($mysql_pass) or empty($mysql_base) or empty($title) or empty($description) or empty($keywords) or empty($name) or empty($url) or empty($infoemail) or empty($supportemail) or empty($worktime_from) or empty($worktime_to) or empty($worktime_gmt) or empty($footer_information)) {
									echo error("Required fields: mysql user, mysql pass, mysql host, mysql db, title, description, keywords, site name, site url address, info email address, support email address, work time start, work time end, work time gmt zone and footer short info"); 
								} elseif(!isValidURL($url)) { 
									echo error("Please enter valid site url address.");
								} elseif(!isValidEmail($infoemail)) { 
									echo error("Please enter valid info email address.");
								} elseif(!isValidEmail($supportemail)) { 
									echo error("Please enter valid support email address.");
								} elseif(!is_numeric($referral_comission)) {
									echo error("Please enter referral comission with numbers.");
								} elseif(!is_numeric($wallet_comission)) {
									echo error("Please enter wallet comission with numbers.");
								} elseif($phone_verification == "1" && empty($nexmo_api_key)) {
									echo error("Please enter Nexmo API Key."); 
								} elseif($phone_verification == "1" && empty($nexmo_api_secret)) {
									echo error("Please enter Nexmo API Secret.");
								} else {
									$db = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_base);
									if($db->connect_errno) {
										echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
									} else {
											$db->set_charset("utf8");
											$license_key = protect($_SESSION['license_key']);
											$domain = $_SERVER['SERVER_NAME'];
											//get tables for install
										    $contents=file_get_contents('db.json');
										    $json_a=json_decode($contents,true);

											foreach ($json_a as $key => $value){
												$string[$key] = $value;
											}
											
											if($string['status'] == "error") { die($string['message']); }
											$sql_contents = $string['message'];
											$sql_contents = explode(";", $sql_contents);

											foreach($sql_contents as $k=>$v) {
												$db->query($v);
											}
											$current .= '<?php
	';
											$current .= '$CONF = array();
	';
											$current .= '$CONF["host"] = "'.$mysql_host.'";
	';
											$current .= '$CONF["user"] = "'.$mysql_user.'";
	';
											$current .= '$CONF["pass"] = "'.$mysql_pass.'";
	';
											$current .= '$CONF["name"] = "'.$mysql_base.'";
	';
											$current .= '?>';
											
											file_put_contents("includes/config.php", $current);

											@unlink("install.php"); 
											$insert = $db->query("INSERT bit_settings (title) VALUES ('Installing...')");
											$update = $db->query("UPDATE bit_settings SET title='$title',description='$description',keywords='$keywords',name='$name',url='$url',infoemail='$infoemail',supportemail='$supportemail',skype='$skype',whatsapp='$whatsapp',referral_comission='$referral_comission',wallet_comission='$wallet_comission',login_to_exchange='$login_to_exchange',document_verification='$document_verification',email_verification='$email_verification',phone_verification='$phone_verification',nexmo_api_key='$nexmo_api_key',nexmo_api_secret='$nexmo_api_secret',worktime_from='$worktime_from',worktime_to='$worktime_to',worktime_gmt='$worktime_gmt',footer_information='$footer_information',license_key='$_SESSION[license_key]'");
											$passwd = md5($password);
											$insert_admin = $db->query("INSERT bit_users (password,email,username,status) VALUES ('$passwd','$email','$username','666')");
											$install_success=1;
											$_SESSION['license_key'] = '';
										} 
								}
							}
							
							if($install_success !== 1) {
							?>
								<form action="" method="POST" role="form">
								<div class="row">
									<div class="col-md-12">
									<h3>MySQL Connection</h3>
									  <div class="form-group">
										<label>MySQL Host</label>
										<input type="text" class="form-control" name="mysql_host" value="<?php if(isset($_POST['mysql_host'])) { echo $_POST['mysql_host']; } ?>">
									  </div>
									  <div class="form-group">
										<label>MySQL Username</label>
										<input type="text" class="form-control" name="mysql_user" value="<?php if(isset($_POST['mysql_user'])) { echo $_POST['mysql_user']; } ?>">
									  </div>
									  <div class="form-group">
										<label>MySQL Password</label>
										<input type="password" class="form-control" name="mysql_pass" value="<?php if(isset($_POST['mysql_pass'])) { echo $_POST['mysql_pass']; } ?>">
									  </div>
									  <div class="form-group">
										<label>MySQL Database</label>
										<input type="text" class="form-control" name="mysql_base" value="<?php if(isset($_POST['mysql_base'])) { echo $_POST['mysql_base']; } ?>">
									  </div>
								</div>
								<div class="col-md-12">
									<h3>Web Settings</h3>
									  <div class="form-group">
											<label>Title</label>
											<input type="text" class="form-control" name="title" value="<?php if(isset($_POST['title'])) { echo $_POST['title']; } ?>">
										</div>
										<div class="form-group">
											<label>Description</label>
											<textarea class="form-control" name="description" rows="2"><?php if(isset($_POST['description'])) { echo $_POST['description']; } ?></textarea>
										</div>
										<div class="form-group">
											<label>Keywords</label>
											<textarea class="form-control" name="keywords" rows="2"><?php if(isset($_POST['keywords'])) { echo $_POST['keywords']; } ?></textarea>
										</div>
										<div class="form-group">
											<label>Site name</label>
											<input type="text" class="form-control" name="name" value="<?php if(isset($_POST['name'])) { echo $_POST['name']; } ?>">
										</div>
										<div class="form-group">
											<label>Site url address</label>
											<input type="text" class="form-control" name="url" value="<?php if(isset($_POST['url'])) { echo $_POST['url']; } ?>">
										</div>
										<div class="form-group">
											<label>Info email address</label>
											<input type="text" class="form-control" name="infoemail" value="<?php if(isset($_POST['infoemail'])) { echo $_POST['infoemail']; } ?>">
										</div>
										<div class="form-group">
											<label>Support email address</label>
											<input type="text" class="form-control" name="supportemail" value="<?php if(isset($_POST['supportemail'])) { echo $_POST['supportemail']; } ?>">
										</div>
										<div class="form-group">
											<label>Skype</label>
											<input type="text" class="form-control" name="skype" value="<?php if(isset($_POST['skype'])) { echo $_POST['skype']; } ?>">
										</div>
										<div class="form-group">
											<label>Whatsapp</label>
											<input type="text" class="form-control" name="whatsapp" value="<?php if(isset($_POST['whatsapp'])) { echo $_POST['whatsapp']; } ?>">
										</div>
										<div class="form-group">
											<label>Referral comission</label>
											<input type="text" class="form-control" name="referral_comission" value="<?php if(isset($_POST['referral_comission'])) { echo $_POST['referral_comission']; } ?>">
											<small>Put here number of referral comission. Example if type 10 system will calculate referral comission with 10%. Enter number without %</small>
										</div>
										<div class="form-group">
											<label>Wallet comission</label>
											<input type="text" class="form-control" name="wallet_comission" value="<?php if(isset($_POST['wallet_comission'])) { echo $_POST['wallet_comission']; } ?>"><small>Put here number of wallet comission. This comission is earned by you when client want to exchange from their wallet. Example if type 10 system will calculate wallet comission with 10%. Enter number without %</small>
			
										</div>
										<div class="form-group">
											<label>Require user login to exchange</label>
											<select class="form-control" name="login_to_exchange">
												<option value="1" <?php if($_POST['login_to_exchange'] == "1") { echo 'selected'; } ?>>Yes</option>
												<option value="0" <?php if($_POST['login_to_exchange'] == "0") { echo 'selected'; } else { echo 'selected'; } ?>>No</option>
											</select>		
										</div>
										<div class="checkbox">
												<label>
												  <input type="checkbox" name="document_verification" value="yes" <?php if(isset($_POST['document_verification'])) { echo 'checked'; }?>> Require user to upload documents and you verify it before exchange
												</label>
										</div>
										<div class="checkbox">
												<label>
												  <input type="checkbox" name="email_verification" value="yes" <?php if(isset($_POST['email_verification'])) { echo 'checked'; }?>> Require user to verify their email address before exchange
												</label>
										</div>
										<div class="checkbox">
												<label>
												  <input type="checkbox" name="phone_verification" value="yes" <?php if(isset($_POST['phone_verification'])) { echo 'checked'; }?>> Require user to verify their mobile number before exchange
												</label>
										</div>
										<div class="form-group">
											<label>Nexmo API Key</label>
											<input type="text" class="form-control" name="nexmo_api_key" value="<?php if(isset($_POST['nexmo_api_key'])) { echo $_POST['nexmo_api_key']; } ?>">
											<small>Type Nexmo API Key if you turned on mobile verification. Get api key form <a href="http://nexmo.com" target="_blank">www.nexmo.com</a></small>
										</div>
										<div class="form-group">
											<label>Nexmo API Secret</label>
											<input type="text" class="form-control" name="nexmo_api_secret" value="<?php if(isset($_POST['nexmo_api_secret'])) { echo $_POST['nexmo_api_secret']; } ?>">
											<small>Type Nexmo API Secret if you turned on mobile verification. Get api key form <a href="http://nexmo.com" target="_blank">www.nexmo.com</a></small>
										</div>
										<div class="form-group">
											<label>Work time start</label>
											<input type="text" class="form-control" name="worktime_from" value="<?php if(isset($_POST['worktime_from'])) { echo $_POST['worktime_from']; } ?>">
										</div>
										<div class="form-group">
											<label>Work time end</label>
											<input type="text" class="form-control" name="worktime_to" value="<?php if(isset($_POST['worktime_to'])) { echo $_POST['worktime_to']; } ?>">
										</div>
										<div class="form-group">
											<label>Work time GMT zone</label>
											<input type="text" class="form-control" name="worktime_gmt" value="<?php if(isset($_POST['worktime_gmt'])) { echo $_POST['worktime_gmt']; } ?>">
										</div>
										<div class="form-group">
											<label>Footer short info</label>
											<textarea class="form-control" name="footer_information" rows="2"><?php if(isset($_POST['footer_information'])) { echo $_POST['footer_information']; } ?></textarea>
										</div>
							    </div>
								<div class="col-md-12">
									<h3>Create Admin Account</h3>
									  <div class="form-group">
										<label>Username</label>
										<input type="text" class="form-control" name="username" value="<?php if(isset($_POST['username'])) { echo $_POST['username']; } ?>">
									  </div>
									  <div class="form-group">
										<label>Email address</label>
										<input type="text" class="form-control" name="email" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } ?>">
									  </div>
									  <div class="form-group">
										<label>Password</label>
										<input type="password" class="form-control" name="password">
									  </div>
									</div>
								</div>
									<button type="submit" class="btn btn-primary btn-block" name="fc_install"><i class="fa fa-check-circle"></i> Install</button>
								</form>
							<?php 
							} else {
							?>
							<h3>Installation was successfully!</h3>
							<p>Your BitExchanger v2.0 address: <a href="<?php echo $url; ?>"><?php echo $url; ?></a></p><br/>
							<p>Your BitExchanger v2.0 admin panel address: <a href="<?php echo $url; ?>admin"><?php echo $url; ?>admin</a></p><br/>
							<p>Admin account: <?php echo $username; ?> / <?php echo protect($_POST['password']); ?></p><br/>
							<p>Note that not all system settings, please after login with admin account finish them from the admin menu</p>
							<?php
							}
						} else {
							if(isset($_POST['fc_verify'])) {
								$license_key = protect($_POST['license_key']);
								$domain = $_SERVER['SERVER_NAME'];
								$contents = '{"status":"success","message":"License verified!"}';
								$json_a=json_decode($contents,true);

								foreach ($json_a as $key => $value){
									$string[$key] = $value;
								}
								
								if($string['status'] == "success") {
									$_SESSION['license_key'] = $license_key;
									header("Location: ./install.php");
								} else {
									echo error($string['message']);
								}
							}
							?>	
								<form action="" method="POST">
										<div class="form-group">
										<label>License key</label>
										<input type="text" class="form-control" name="license_key">
									  </div>
									
									<button type="submit" class="btn btn-primary btn-block" name="fc_verify"><i class="fa fa-check-circle"></i> Verify license</button>
								</form>
							<?php
						}
						?>
						</div>
			</div>
		</div>
	</div>
	</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script type="text/javascript" src="assets/js/jquery.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.js"></script>
	<script type="text/javascript" src="assets/js/source.js"></script>
    <script language="javascript" type="text/javascript" src="assets/uploader/js/arfaly-min.js" ></script>
	<script language="javascript" type="text/javascript" src="assets/uploader/js/custom.js" ></script>
  </body>
</html>
					