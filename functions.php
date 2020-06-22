<?php
add_action('wp_footer', 'add_scripts');
if (!function_exists('add_scripts')) {
	function add_scripts() {
	    if(is_admin()) return false;
	    wp_enqueue_script('main', get_theme_file_uri().'/js/custom_freelance.js','','',true);
	}
}
add_action('wp_print_styles', 'add_styles');
if (!function_exists('add_styles')) {
	function add_styles() {
	    if(is_admin()) return false;
	    wp_enqueue_style( 'custom_freelance', get_theme_file_uri().'/css/custom_freelance.css' );
	}
}

/*------------UPDATE FREELANCE VIDEO----------*/
add_action( 'wp_ajax_videofreelance', 'videofreelance_update' );
add_action( 'wp_ajax_nopriv_videofreelance', 'videofreelance_update' );
function videofreelance_update() {
	$cur_user_id = get_current_user_id();
	$title_video = $_POST['title_video'];
	$desc_video = $_POST['desc_video'];
	if ( $_FILES ) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		$file_handler = 'file_video';
		$attach_id = media_handle_upload($file_handler,$pid );
		update_user_meta( $cur_user_id,'file_video', $attach_id);
	}
	update_user_meta( $cur_user_id,'title_video', $title_video);
	update_user_meta( $cur_user_id,'desc_video', $desc_video);
	wp_die();
}
/*------------REMOVE FREELANCE VIDEO----------*/
add_action( 'wp_ajax_remove_video_form', 'remove_video_form' );
add_action( 'wp_ajax_nopriv_remove_video_form', 'remove_video_form' );
function remove_video_form() {
	$cur_user_id = get_current_user_id();
	update_user_meta( $cur_user_id,'file_video', '');
	update_user_meta( $cur_user_id,'title_video', '');
	update_user_meta( $cur_user_id,'desc_video', '');
	wp_die();
}
/*------------ADD FREELANCERS MULTIPLE----------*/
add_action( 'wp_ajax_multiplefreelancers', 'multiplefreelancers_update' );
add_action( 'wp_ajax_nopriv_multiplefreelancers', 'multiplefreelancers_update' );
function multiplefreelancers_update() {
	$cur_user_id = get_current_user_id();
	$id_progect = $_POST[ 'id_progect' ];
	$freelancerselectmultiple = $_POST[ 'freelancerselectmultiple' ];

	$select = explode( ',', $freelancerselectmultiple );

	foreach ( $select as $key => $value ) {
		fre_create_invite( $value, $id_progect );
		do_action( 'fre_new_invite', $value, $cur_user_id, $id_progect );

		$user_email = get_the_author_meta( 'user_email', $value );
		$user = get_userdata( $value );

		// mail subject
		$subject = sprintf( __( "You have a new invitation to join project from %s.", ET_DOMAIN ), get_option( 'blogname' ) );

		// get mail template
		$message = ae_get_option( 'invite_mail_template' );
		$message = str_replace( '[blogname]', get_bloginfo( 'name' ), $message );
		$message = str_replace( '[display_name]', $user->display_name, $message );
		$message = str_replace( '[link]', get_post_permalink( $id_progect ), $message );
		$headers = array(
			'From: ' . get_option( 'blogname' ) . ' <' . get_option( 'admin_email' ) . '>',
			'content-type: text/html',
		);
		// send mail
		wp_mail( $user_email, $subject, $message, $headers );

	}

	update_post_meta( $id_progect, 'id_progect', $id_progect );
	update_post_meta( $id_progect, 'freelancerselectmultiple', $freelancerselectmultiple );
	wp_die();
}
/*------------ADD TIME----------*/
add_action( 'wp_ajax_addtime', 'addtime_update' );
add_action( 'wp_ajax_nopriv_addtime', 'addtime_update' );
//function addtime_update() {
//	$cur_user_id = get_current_user_id();
//	$id_project = $_POST[ 'id_project' ];
//	$time_project = $_POST['time_project'].'-U';
//	$addDateTime = date("d/m/Y");
//	
//	$addedTime = get_post_meta( $id_project, 'time_project', true );
//	$addedDateTime = get_post_meta( $id_project, 'date_time_project', true );
//	
//	if($addedTime) {
//		$addnewTime = $addedTime.', '.$time_project;
//		$addedDateTime = $addedDateTime.', '.$addDateTime;
//		update_post_meta( $id_project,'time_project', $addnewTime );
//		update_post_meta( $id_project,'date_time_project', $addedDateTime );
//		
//	} else {
//		update_post_meta( $id_project,'time_project', $time_project );
//		update_post_meta( $id_project,'date_time_project', $addDateTime );
//	}
//	
//	wp_die();
//}


function addtime_update() {
	$cur_user_id = get_current_user_id();
	$id_project = $_POST[ 'id_project' ];
	$time_project = $_POST['time_project'].'-U';
	$addDateTime = date("d/m/Y");
	
	$addTimeDate = $addDateTime.'|'.$time_project;
	
	add_post_meta( $id_project,'date_time_project', $addTimeDate );
	
	wp_die();
}

/*-------------FORM EMAIL----------*/
function emailform(){
	
	$email = $_REQUEST['email'];
	if($email) {
		$email = $_REQUEST['email'];
	} else {
		global $email;
	}
    $response = '';


    if(!email_exists($email)){
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$key = substr(str_shuffle($permitted_chars), 0, 10);
		
		$userdata = array(
			'user_login'      => $email,
			'user_email'      => $email,
			'description'     => $key,
		);
		
		//global $email;
		
		$blogname = get_bloginfo('name');
		$blogurl = get_bloginfo('url');
		
		wp_insert_user( $userdata );
		
		$thm  = 'Confirmation of registration';
		$thm  = "=?utf-8?b?". base64_encode($thm) ."?=";
		$msg = '
		<body>
<style type="text/css">
table p {
    margin-bottom: 0;
    font-family: sans-serif;
}
.table_soc {
    padding-bottom: 30px;
}
.table_soc a {
    margin: 0 5px;
}
.table_bottom p {
    color: #fff;
    font-size: 14px;
}
.table_bottom a, .table_bottom span {
    color: #fff;
    font-size: 14px;
    font-family: sans-serif;
}
</style>
<table cellpadding="0" cellspacing="0" border="0" width="100%" style="background: #14316F; min-width: 320px; font-size: 1px; line-height: normal;">
	<tr>
		<td align="center" valign="top">
			<table cellpadding="30" cellspacing="0" border="0" width="700" class="table700" style="max-width: 700px; min-width: 320px; background: #14316F;">
				<tr>
					<td align="center" valign="top"><img src="'.$blogurl.'/wp-content/uploads/2020/06/m_logo.png"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" valign="top">
			<table cellpadding="30" cellspacing="0" border="0" width="700" class="table700" style="text-align: left; font-size: 18px; max-width: 700px; min-width: 320px; background: #ffffff;">
				<tr>
					<td align="left" valign="top">
						<div>
							<p>Hello,</p>
							<br>
							<p>To continue registration, please follow this link:</p>
							<br>
							<p><a href="'.$blogurl.'/registration-completed/?key='.$key.'&email='.$email.'">Link</a></p>
							<br>
							<br>
							<p>Thanks for your time,</p>
							<p>"'.$blogname.'".</p>
							<br>
							<br>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" valign="top">
			<table cellpadding="0" cellspacing="20" border="0" width="700" class="table700" style="max-width: 700px; min-width: 320px; background: #FF5C16;">
				<tr>
					<td align="center" valign="top" style="font-size: 18px; color: #fff; font-weight: 600;">FOLLOW US</td>
				</tr>
				<tr>
					<td align="center" valign="top">
						<a target="_blank" href="#" style="margin: 0 5px;"><img src="'.$blogurl.'/wp-content/uploads/2020/06/m_fb.png"></a>
						<a target="_blank" href="#" style="margin: 0 5px;"><img src="'.$blogurl.'/wp-content/uploads/2020/06/m_tw.png"></a>
						<a target="_blank" href="#" style="margin: 0 5px;"><img src="'.$blogurl.'/wp-content/uploads/2020/06/m_in.png"></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" valign="top">
			<table cellpadding="30" cellspacing="0" border="0" width="700" class="table_bottom" style="color: #fff; font-size: 14px; max-width: 700px; min-width: 320px; background: #14316F;">
				<tr>
					<td align="center" valign="top">
						<div><a target="_blank" href="#" style="color: #fff; font-size: 14px;">Privacy Policy</a><span>  |  </span><a target="_blank" href="#" style="color: #fff; font-size: 14px;">Contact Support</a></div>
						<p>Address Here</p>
						<p>© 2020 Virtual Property Management Solutions.</p>
						<p>All Rights Reserved.</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
		
		';
		
		$blogemail = get_bloginfo('admin_email');
		$mail_to = $email;
		$headers = "Content-Type: text/html; charset=utf-8\n";
		$headers .= "From: VPM <".$blogemail.">" . "\r\n";

		wp_mail($mail_to, $thm, $msg, $headers);
		
		$response = 'Done';
		
    } else {
		  $response = 'This email already exists.';
	}

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ){
        echo $response;
        wp_die();
    }
}

add_action('wp_ajax_nopriv_ajax_emailform', 'emailform' );
add_action('wp_ajax_ajax_emailform', 'emailform' );

/*-------------FORM CHNGE EMAIL----------*/
function changeemailform(){
	$email = $_REQUEST['email'];
    $response = '';


    if(!email_exists($email)){
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$key = substr(str_shuffle($permitted_chars), 0, 10);
		
		$userdata = array(
			'user_login'      => $email,
			'user_email'      => $email,
			'description'     => $key,
		);
		
		$blogname = get_bloginfo('name');
		$blogurl = get_bloginfo('url');

		wp_insert_user( $userdata );
		
		$thm  = 'Confirmation of registration';
		$thm  = "=?utf-8?b?". base64_encode($thm) ."?=";
		$msg = '
		<body>
<style type="text/css">
table p {
    margin-bottom: 0;
    font-family: sans-serif;
}
.table_soc {
    padding-bottom: 30px;
}
.table_soc a {
    margin: 0 5px;
}
.table_bottom p {
    color: #fff;
    font-size: 14px;
}
.table_bottom a, .table_bottom span {
    color: #fff;
    font-size: 14px;
    font-family: sans-serif;
}
</style>
<table cellpadding="0" cellspacing="0" border="0" width="100%" style="background: #14316F; min-width: 320px; font-size: 1px; line-height: normal;">
	<tr>
		<td align="center" valign="top">
			<table cellpadding="30" cellspacing="0" border="0" width="700" class="table700" style="max-width: 700px; min-width: 320px; background: #14316F;">
				<tr>
					<td align="center" valign="top"><img src="'.$blogurl.'/wp-content/uploads/2020/06/m_logo.png"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" valign="top">
			<table cellpadding="30" cellspacing="0" border="0" width="700" class="table700" style="text-align: left; font-size: 18px; max-width: 700px; min-width: 320px; background: #ffffff;">
				<tr>
					<td align="left" valign="top">
						<div>
							<p>Hello,</p>
							<br>
							<p>To continue registration, please follow this link:</p>
							<br>
							<p><a href="'.$blogurl.'/registration-completed/?key='.$key.'&email='.$email.'">Link</a></p>
							<br>
							<br>
							<p>Thanks for your time,</p>
							<p>"'.$blogname.'".</p>
							<br>
							<br>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" valign="top">
			<table cellpadding="0" cellspacing="20" border="0" width="700" class="table700" style="max-width: 700px; min-width: 320px; background: #FF5C16;">
				<tr>
					<td align="center" valign="top" style="font-size: 18px; color: #fff; font-weight: 600;">FOLLOW US</td>
				</tr>
				<tr>
					<td align="center" valign="top">
						<a target="_blank" href="#" style="margin: 0 5px;"><img src="'.$blogurl.'/wp-content/uploads/2020/06/m_fb.png"></a>
						<a target="_blank" href="#" style="margin: 0 5px;"><img src="'.$blogurl.'/wp-content/uploads/2020/06/m_tw.png"></a>
						<a target="_blank" href="#" style="margin: 0 5px;"><img src="'.$blogurl.'/wp-content/uploads/2020/06/m_in.png"></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" valign="top">
			<table cellpadding="30" cellspacing="0" border="0" width="700" class="table_bottom" style="color: #fff; font-size: 14px; max-width: 700px; min-width: 320px; background: #14316F;">
				<tr>
					<td align="center" valign="top">
						<div><a target="_blank" href="#" style="color: #fff; font-size: 14px;">Privacy Policy</a><span>  |  </span><a target="_blank" href="#" style="color: #fff; font-size: 14px;">Contact Support</a></div>
						<p>Address Here</p>
						<p>© 2020 Virtual Property Management Solutions.</p>
						<p>All Rights Reserved.</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
		
		';
		$blogemail = get_bloginfo('admin_email');
		$mail_to = $email;
		$headers = "Content-Type: text/html; charset=utf-8\n";
		$headers .= "From: VPM <".$blogemail.">" . "\r\n";

		wp_mail($mail_to, $thm, $msg, $headers);
		
		$response = 'Done';
		
    } else {
		  $response = 'This email already exists.';
	}

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ){
        echo $response;
        wp_die();
    }
}

add_action('wp_ajax_nopriv_ajax_changeemailform', 'changeemailform' );
add_action('wp_ajax_ajax_changeemailform', 'changeemailform' );

/*-------------FORM COMPL REG----------*/
function complform(){
	
	$first_name_user = $_REQUEST['first_name_user'];
	$last_name_user = $_REQUEST['last_name_user'];
	$pass_user = $_REQUEST['pass_user'];
	$location_user = $_REQUEST['location_user'];
	$username_user = $_REQUEST['username_user'];
	$user_role = $_REQUEST['user_role'];
	$user_email = $_REQUEST['user_email'];
	
	$user = get_user_by('email', $user_email);
	
    $response = '';


    if($pass_user){
		
		$userdata = array(
			'ID'      		  => $user->ID,
			'first_name'      => $first_name_user,
			'last_name'       => $last_name_user,
			'description'     => '',
			'nickname'        => '',
			'role'            => $user_role,
		);
		
		$blogname = get_bloginfo('name');
		$blogurl = get_bloginfo('url');

		wp_update_user( $userdata );
		
		wp_set_password($pass_user, $user->ID);
		
		$thm  = 'Registration is complete.';
		$thm  = "=?utf-8?b?". base64_encode($thm) ."?=";
				$msg = '
		<body>
<style type="text/css">
table p {
    margin-bottom: 0;
    font-family: sans-serif;
}
.table_soc {
    padding-bottom: 30px;
}
.table_soc a {
    margin: 0 5px;
}
.table_bottom p {
    color: #fff;
    font-size: 14px;
}
.table_bottom a, .table_bottom span {
    color: #fff;
    font-size: 14px;
    font-family: sans-serif;
}
</style>
<table cellpadding="0" cellspacing="0" border="0" width="100%" style="background: #14316F; min-width: 320px; font-size: 1px; line-height: normal;">
	<tr>
		<td align="center" valign="top">
			<table cellpadding="30" cellspacing="0" border="0" width="700" class="table700" style="max-width: 700px; min-width: 320px; background: #14316F;">
				<tr>
					<td align="center" valign="top"><img src="'.$blogurl.'/wp-content/uploads/2020/06/m_logo.png"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" valign="top">
			<table cellpadding="30" cellspacing="0" border="0" width="700" class="table700" style="text-align: left; font-size: 18px; max-width: 700px; min-width: 320px; background: #ffffff;">
				<tr>
					<td align="left" valign="top">
						<div>
							<p>Hello '.$first_name_user.',</p>
							<br>
							<p>You have successfully registered an account with '.$blogname.'. Here is your account information:</p>
							<br>
							<p>Username: '.$user_email.'</p>
							<p>Password: '.$pass_user.'</p>
							<br>
							<br>
							<p>Thanks for your time,</p>
							<p>'.$blogname.'.</p>
							<br>
							<br>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" valign="top">
			<table cellpadding="0" cellspacing="20" border="0" width="700" class="table700" style="max-width: 700px; min-width: 320px; background: #FF5C16;">
				<tr>
					<td align="center" valign="top" style="font-size: 18px; color: #fff; font-weight: 600;">FOLLOW US</td>
				</tr>
				<tr>
					<td align="center" valign="top">
						<a target="_blank" href="#" style="margin: 0 5px;"><img src="'.$blogurl.'/wp-content/uploads/2020/06/m_fb.png"></a>
						<a target="_blank" href="#" style="margin: 0 5px;"><img src="'.$blogurl.'/wp-content/uploads/2020/06/m_tw.png"></a>
						<a target="_blank" href="#" style="margin: 0 5px;"><img src="'.$blogurl.'/wp-content/uploads/2020/06/m_in.png"></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="center" valign="top">
			<table cellpadding="30" cellspacing="0" border="0" width="700" class="table_bottom" style="color: #fff; font-size: 14px; max-width: 700px; min-width: 320px; background: #14316F;">
				<tr>
					<td align="center" valign="top">
						<div><a target="_blank" href="#" style="color: #fff; font-size: 14px;">Privacy Policy</a><span>  |  </span><a target="_blank" href="#" style="color: #fff; font-size: 14px;">Contact Support</a></div>
						<p>Address Here</p>
						<p>© 2020 Virtual Property Management Solutions.</p>
						<p>All Rights Reserved.</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
		
		';
		
		$blogemail = get_bloginfo('admin_email');
		$mail_to = $user_email;
		$headers = "Content-Type: text/html; charset=utf-8\n";
		$headers .= "From: VPM <".$blogemail.">" . "\r\n";

		wp_mail($mail_to, $thm, $msg, $headers);
		
		$response = 'Done';
		
    } else {
		  $response = 'This email already exists.';
	}

    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ){
        echo $response;
        wp_die();
    }
}

add_action('wp_ajax_nopriv_ajax_complform', 'complform' );
add_action('wp_ajax_ajax_complform', 'complform' );
