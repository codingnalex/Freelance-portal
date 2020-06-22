<?php
/**
 * Template name: Register With Social
 */
get_header();
?>
<?php
require_once 'vendor/autoload.php';
 
// init configuration
$clientID = '628542627241-v87bi3467ms86ng9j27q8msfeti0ensh.apps.googleusercontent.com';
$clientSecret = 'f9YhP8rmuQ1QgDF2hKNLUuQW';
$redirectUri = 'http://staging2.virtualpmsolutions.com/registration/';
 
// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
?>
<div class="fre-page-wrapper">
	<div class="fre-page-section">
		<div class="container">
			<div class="fre-authen-wrapper">
				<div class="fre-authen-social">
					<h2><?php _e('SIGN UP FOR A FREE ACCOUNT', ET_DOMAIN);?></h2>
					<form role="form" id="register_email_form">
						<?php
						if (isset($_GET['code'])) {
							$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
							$client->setAccessToken($token['access_token']);
							
							$google_oauth = new Google_Service_Oauth2($client);
							$google_account_info = $google_oauth->userinfo->get();
							$email =  $google_account_info->email;
							$name =  $google_account_info->name;
							
							if(!email_exists($email)) { 
							emailform()
							?>
								<script>
									jQuery('.fre-authen-social').hide();
									setTimeout(function() {
										jQuery('#verif_block').show();
										jQuery('.page-template-page-register-social .footer-wrapper.fixed-bottom').css('position', 'relative');
									}, 500);
								</script>
							<?php } else { ?>
								<script>
									jQuery('.fre-authen-social').hide();
									jQuery('#verif_block').hide();
									jQuery('.fre-authen-wrapper').append('<div id="notice"><h3 style="color: #fff;text-align: center;font-size: 32px;font-weight: 300;">This email already exists.</h3></div>');
								</script>
							<?php }

						} else {
							
							echo "<a href='".$client->createAuthUrl()."'><img src='/wp-content/uploads/2020/06/Google-Signup.png'></a>";
							
						}
						?>
						<span><?php _e('or', ET_DOMAIN);?></span>
						<div class="fre-input-field icon_left">
							<input type="text" name="email_user" id="email_user" placeholder="<?php _e('Work Email Address', ET_DOMAIN);?>">
							<!-- <div class="message">This field is required.</div> -->
						</div>
						<div class="fre-input-field">
							<button class="fre-submit-btn btn-submit last_btn"><?php _e('Sign Up with Email', ET_DOMAIN);?></button>
						</div>
						<div id="submit-email"></div>
					</form>
				</div>
				<div id="verif_block" style="display: none;">
					<div class="top_bl">
						<img src="/wp-content/uploads/2020/06/verif_icon.png">
						<h3><?php _e('Verify your email to proceed', ET_DOMAIN);?></h3>
						<p class="first_line"><?php _e('We just sent an email to the address: ', ET_DOMAIN);?><span><?php if($google_account_info->email) { echo $google_account_info->email; } ?></span></p>
						<p><?php _e('Please check your email and click on the link provided to verify your address.', ET_DOMAIN);?></p>
						<p class="white_bt"><?php _e('RESEND VERIFICATION EMAIL', ET_DOMAIN);?></p>
					</div>
					<div class="bottom">
						<div class="left_bot">
							<p class="title_bot"><?php _e('Change email', ET_DOMAIN);?></p>
							<form id="change_email_form">
								<input type="text" name="change_email_user" id="change_email_user" placeholder="<?php _e('Email', ET_DOMAIN);?>">
								<div class="fre-input-field">
									<button class="fre-submit-btn btn-submit last_btn"><?php _e('UPDATE & RESEND', ET_DOMAIN);?></button>
								</div>
								<div id="change_email"></div>
							</form>
						</div>
						<div class="right_bot">
							<p class="title_bot"><?php _e('NEED HELP?', ET_DOMAIN);?></p>
							<p><?php _e('Why do we ask for email confirmation?', ET_DOMAIN);?></p>
							<span><?php _e('Email confirmation is an important security check that helps prevent other people from signing up for an Virtual PM account using your email address.', ET_DOMAIN);?></span>
							<p><?php _e('How do I confirm my email address?', ET_DOMAIN);?></p>
							<span><?php _e("We sent you an email with a link to click on. If you aren't able to click the link, copy the full URL from the email and paste it into a new web browser window.", ET_DOMAIN);?></span>
							<p><?php _e("If you haven't received the confirmation email, please:", ET_DOMAIN);?></p>
							<span><?php _e("Check the junk mail folder or spam filter in your email account. Make sure your email address is entered correctly.", ET_DOMAIN);?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>