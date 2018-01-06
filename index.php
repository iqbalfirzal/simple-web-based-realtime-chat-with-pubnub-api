<?php
	session_start();
	error_reporting(0);
	include "int/connect.php";
	$user = (isset($_SESSION['user'])) ? $_SESSION['user'] : null;
	$inputuser = $_POST['username'];
	$inputpassword = $_POST['password'];
	$cek_terdaftar=mysql_query("SELECT * FROM user WHERE user='$inputuser' && password='$inputpassword'");
	$jumlah=mysql_num_rows($cek_terdaftar);
	if(isset($_POST['login']) AND $jumlah==1){
		$_SESSION['user'] = $inputuser;
		header("Refresh:0");
	}
	$room = @$_GET['room'];
	
	echo "
		<!DOCTYPE html>
		<html>
			<head>
				<meta charset='utf-8'/>
				<meta http-equiv='X-UA-Compatible' content='IE=edge'/>
				<title>Private Chat Novi Iqbal</title>
				<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'/>
				<link rel='shortcut icon' href='img/favicon.png'/>
				<link rel='stylesheet' href='assets/css/bootstrap.min.css'/>
				<link rel='stylesheet' href='assets/plugin/font-awesome.min.css'/>
				<link rel='stylesheet' href='assets/plugin/ionicons.min.css'/>
				<link rel='stylesheet' href='assets/css/AdminLTE.min.css'/>
				<link rel='stylesheet' href='assets/css/all-skins.min.css'/>
				<link rel='stylesheet' href='assets/css/custom-style.css'/>
				<style>
					.direct-chat-messages {
						height:450px !important;
					}
				</style>
			</head>
			<body class='hold-transition skin-blue layout-top-nav fixed' ",($room<>'') ? "onLoad='get_subscribe(&quot;$room&quot;);get_history(&quot;$room&quot;);'":''," >
				<div class='wrapper'>
					<header class='main-header'>
						<nav class='navbar navbar-static-top'>
							<div class='container'>
								<div class='navbar-custom-menu'>
									<ul class='nav navbar-nav'>
										<li class='dropdown tasks-menu'>
											<a href='http://www.facebook.com/novikhofiyanti' class='dropdown-toggle'>
												&nbsp; FB NOVI
											</a>
										</li>
										<li class='dropdown tasks-menu'>
											<a href='http://www.facebook.com/iqbal.firzzal' class='dropdown-toggle'>
												&nbsp; FB IQBAL
											</a>
										</li>
									</ul>
								</div>
							</div>
						</nav>
					</header>
					<div class='content-wrapper'>
						<div class='container'>
							<section class='content'>";
								if($user==null) {
									echo "
										<div class='row'>
											<div class='col-md-3'></div>
											<div class='col-md-6'>
												<form action='' method='post'>
													<p><b>Webchat pribadi kita, khusus kita berdua. Novi Iqbal.</b><p>
													<div class='box box-solid'>
														<div class='box-header with-border'>
															<h3 class='box-title'>Login</h3>
														</div>
														<div class='box-body'>
															<div class='form-group'>
																<label>Username</label>
																<input type='text' name='username' class='form-control' required='true'/>
															</div>
															<div class='form-group'>
																<label>Password</label>
																<input type='password' name='password' class='form-control' required='true'/>
															</div>
														</div>
														<div class='box-footer'>
															<button type='submit' name='login' class='btn btn-sm btn-success'> Masuk </button>
														</div>
													</div>
												</form>
											</div>
										</div>
									";
								} else {
									if($room=='') {
										echo "
											<div class='row'>
												<div class='col-md-3'></div>
												<div class='col-md-6'>
													<div class='box box-solid'>
														<div class='box-header'>
															<h3 class='box-title'>Ruang Chat</h3>
														</div>
														<div class='box-footer no-padding'>
															<ul class='nav nav-stacked'>
																<li><a href='?room=generalchat'> Chat Biasa </a></li>
																<li><a href='?room=ehemchat'> Chat Ehemmm </a></li>
																<li><a href='int/logout.php' class='text-red'> Keluar </a></li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										";
									} else {
										echo "
											<div class='row'>
												<div class='col-md-3'></div>
												<div class='col-md-6'>
													<div class='box box-solid direct-chat direct-chat-primary'>
														<div class='box-header with-border'>
															<h3 class='box-title'>Private Chat ($room)</h3>
															<div class='box-tools pull-right' >
																<a href='index.php'><img src=img/exit.gif hight=8% width=8% align=right></a>
															</div>
														</div>
														<div class='box-body'>
															<div class='direct-chat-messages'>
																<div id='chatspace1'>
																	<span id='loader'><img draggable=false onmousedown=return false src=img/wait.gif hight=10% width=10%> Loading...</span>
																</div>																
															</div>
														</div>
														<div class='box-footer'>
															<form action='' method='post' id='form1' onSubmit='post_publish(&quot;$room&quot;,&quot;$user&quot;,message.value);return false;' autocomplete='off'>
																<div class='input-group'>
																	<input type='text' name='message' id='msg1' placeholder='Type Message ...' class='form-control' required='true'/>
																	<span class='input-group-btn'>
																		<button type='submit' class='btn btn-primary btn-flat'>Send</button>
																	</span>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										";
									}
								}
								echo "
							</section>
						</div>
					</div>
				</div>
				<script src='assets/plugin/jquery-2.2.3.min.js'></script>
				<script src='assets/plugin/bootstrap.min.js'></script>
				<script src='assets/plugin/jquery.autoresize.js'></script>
				<script src='assets/plugin/jquery.slimscroll.min.js'></script>
				<script src='assets/plugin/fastclick.js'></script>
				<script src='assets/plugin/app.min.js'></script>
				<script src='assets/plugin/demo.js'></script>
				
				<!--PubNub Library-->
				
				<script src='https://cdn.pubnub.com/pubnub.min.js'></script>
				<script charset='utf-8'>
				
					var pubdemo = new PUBNUB.init({
						subscribeKey: 'sub-c-c4dcdccc-7da8-11e7-a9fe-0619f8945a4f',
						publishKey: 'pub-c-49a933bc-b637-4a0f-a081-f1e81395adc0',
						origin: 'pubsub.pubnub.com',
						ssl: true
					});
					
					function get_subscribe(room_name) {
						pubdemo.subscribe({
							channel: room_name,
							callback: function(m) {
								console.log(m);
								$('#chatspace1').append('<div class=direct-chat-msg><div class=direct-chat-info clearfix><span class=direct-chat-name pull-left>' +m.name+ '</span></div><img class=direct-chat-img src=img/avatar.png><div class=direct-chat-text>' +m.msg+ '</div></div>');
								$('#loader').hide();
								geserbawah();
							}
						});
					}
					
					function post_publish(room_name,user_name,user_msg) {
						$('#msg1').val('');
						pubdemo.publish({
							channel: room_name,
							message: {'name':user_name,'msg':user_msg},
							callback: function(m) {
								console.log(m);
								geserbawah();
							}
						});
					}
					
					function get_history(room_name) {
						pubdemo.history({
							channel : room_name,
							callback : function(m){
								console.log(m);
								for(r=0;r<m[0].length;r++) {
									$('#chatspace1').append('<div class=direct-chat-msg><div class=direct-chat-info clearfix><span class=direct-chat-name pull-left>' +m[0][r].name+ '</span></div><img class=direct-chat-img src=img/avatar.png ><div class=direct-chat-text>' +m[0][r].msg+ '</div></div>');
								}
								$('#loader').hide();
								geserbawah();
							},
							count : 100, // 100 is the default
							reverse : false // false is the default
						});
					}
					
					function geserbawah() {
						$('.direct-chat-messages').scrollTop(($('.direct-chat-messages').height()+500));
					}
					
				</script>
			</body>
		</html>
	";
?>