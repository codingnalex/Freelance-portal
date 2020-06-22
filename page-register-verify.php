<?php
/**
 * Template name: Register Сompleted 
 */
get_header();
?>
<?php
$url = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 's' : '') . '://';
$url = $url . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	
$parts = parse_url($url); 
parse_str($parts['query'], $query); 

$email =  $query['email'];
$key = $query['key'];

$user = get_user_by('email', $email);

if ($user->description == $key) {

?>
<div class="fre-page-wrapper">
	<div class="fre-page-section">
		<div class="container">
			<div class="fre-authen-wrapper">
				<div class="fre-authen-social">
					<h2><?php _e('Complete your free account setup', ET_DOMAIN);?></h2>
					<form role="form" id="register_completed_form" novalidate="novalidate">
						<div class="fre-input-field two_inp">
							<div class="icon_left user">
								<input type="text" name="first_name_user" id="first_name_user" placeholder="<?php _e('First Name', ET_DOMAIN);?>">
							</div>
							<div class="icon_left user">
								<input type="text" name="last_name_user" id="last_name_user" placeholder="<?php _e('Last Name', ET_DOMAIN);?>">
							</div>
						</div>
						<div class="fre-input-field icon_left pass">
							<input type="text" name="pass_user" id="pass_user" placeholder="<?php _e('Create A Password', ET_DOMAIN);?>">
						</div>
						<div class="fre-input-field icon_left location">
							<input type="text" name="location_user" id="location_user" placeholder="<?php _e('Select Your Location', ET_DOMAIN);?>">
						</div>
						<div class="bt_bl_compl">
							<span><?php _e('I WANT TO:', ET_DOMAIN);?></span>
							<div class="select_role">
								<p class="emp active"><?php _e('Hire for a project', ET_DOMAIN);?></p>
								<p class="free"><?php _e('WORK AS A FREELANCER', ET_DOMAIN);?></p>
								<div class="fre-input-field icon_left username" style="display: none;">
									<input type="text" name="username_user" id="username_user" placeholder="<?php _e('Username', ET_DOMAIN);?>">
								</div>
							</div>
						</div>
						<div class="check_bl">
							<label>
								<p class="ifcheck sendme"></p>
								<input type="checkbox" name="send_me" id="send_me">
								<span><?php _e('Yes! Send me genuinely useful emails every now and then to help me get the most out of Virtual PM', ET_DOMAIN);?></span>
							</label>
							<label>
								<p class="ifcheck privacy"></p>
								<input type="checkbox" name="privacy" id="privacy">
								<span><?php _e('Yes, I understand and agree to the Virtual PM', ET_DOMAIN);?><a href="#">Terms of Service</a><?php _e(', including the ', ET_DOMAIN);?><a href="#">User Agreement</a><?php _e(' and ', ET_DOMAIN);?><a href="#">Privacy Policy.</a></span>
							</label>
							<input type="hidden" name="user_role" id="user_role" value="employer">
							<input type="hidden" name="user_email" id="user_email" value="<?php echo $email; ?>">
						</div>
						<div class="fre-input-field">
							<button class="fre-submit-btn btn-submit last_btn"><?php _e('CREATE MY ACCOUNT', ET_DOMAIN);?></button>
						</div>
						<div id="submit-completed"></div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } else { ?>
<?php } ?>
<?php get_footer(); ?>