<?php
/**
 * Template Name: Member Profile Page
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage FreelanceEngine
 * @since FreelanceEngine 1.0
 */
global $wp_query, $ae_post_factory, $post, $current_user, $user_ID;
//convert current user
$ae_users  = AE_Users::get_instance();
$user_data = $ae_users->convert( $current_user->data );
$user_role = ae_user_role( $current_user->ID );
//convert current profile
$post_object = $ae_post_factory->get( PROFILE );

$profile_id = get_user_meta( $user_ID, 'user_profile_id', true );

$profile = array();
if ( $profile_id ) {
	$profile_post = get_post( $profile_id );
	if ( $profile_post && ! is_wp_error( $profile_post ) ) {
		$profile = $post_object->convert( $profile_post );
	}
}

//get profile skills
$current_skills = get_the_terms( $profile, 'skill' );
//define variables:
$skills         = isset( $profile->tax_input['skill'] ) ? $profile->tax_input['skill'] : array();
$job_title      = isset( $profile->et_professional_title ) ? $profile->et_professional_title : '';
$hour_rate      = isset( $profile->hour_rate ) ? $profile->hour_rate : '';
$currency       = isset( $profile->currency ) ? $profile->currency : '';
$experience     = isset( $profile->et_experience ) ? $profile->et_experience : '';
$hour_rate      = isset( $profile->hour_rate ) ? $profile->hour_rate : '';
$about          = isset( $profile->post_content ) ? $profile->post_content : '';
$display_name   = $user_data->display_name;
$user_available = isset( $user_data->user_available ) && $user_data->user_available == "on" ? 'checked' : '';
$country        = isset( $profile->tax_input['country'][0] ) ? $profile->tax_input['country'][0]->name : '';
$category       = isset( $profile->tax_input['project_category'][0] ) ? $profile->tax_input['project_category'][0]->slug : '';

get_header();
// Handle email change requests
$user_meta = get_user_meta( $user_ID, 'adminhash', true );

if ( ! empty( $_GET['adminhash'] ) ) {
	if ( is_array( $user_meta ) && $user_meta['hash'] == $_GET['adminhash'] && ! empty( $user_meta['newemail'] ) ) {
		$confirm_new_email = wp_update_user( array(
			'ID'         => $user_ID,
			'user_email' => $user_meta['newemail']
		) );

        do_action('confirm_new_email', $confirm_new_email, $user_meta['newemail'] );
		delete_user_meta( $user_ID, 'adminhash' );
	}
	echo "<script> window.location.href = '" . et_get_page_link( "profile" ) . "'</script>";
} elseif ( ! empty( $_GET['dismiss'] ) && 'new_email' == $_GET['dismiss'] ) {
	delete_user_meta( $user_ID, 'adminhash' );
	echo "<script> window.location.href = '" . et_get_page_link( "profile" ) . "'</script>";
}

$rating        = Fre_Review::employer_rating_score( $user_ID );
$role_template = 'employer';
if ( fre_share_role() || ae_user_role( $user_ID ) == FREELANCER ) {
	$rating        = Fre_Review::freelancer_rating_score( $user_ID );
	$role_template = 'freelance';
}

$projects_worked = get_post_meta( $profile_id, 'total_projects_worked', true );
$project_posted  = fre_count_user_posts_by_type( $user_ID, 'project', '"publish","complete","close","disputing","disputed", "archive" ', true );
$hire_freelancer = fre_count_hire_freelancer( $user_ID );

$currency = ae_get_option( 'currency', array(
	'align' => 'left',
	'code'  => 'USD',
	'icon'  => '$'
) );

?>
<div id="my_profile" class="fre-page-wrapper list-profile-wrapper">
	<div class="container">
		<div class="left_box">
			<div class="project-detail-box custom_detail_box_top">
				<div class="project-detail-info">
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<div class="top_info_user">
								<div class="profile-<?php echo $role_template; ?>-info-wrap active">
									<div class="profile-freelance-info cnt-profile-hide" id="cnt-profile-default" style="display: block">
										<div class="avatar_user">
											<span class="employer-avatar-review"><?php echo get_avatar( $user_data->ID, 125 ) ?></span>
										</div>
										<div class="last_first_user">
											<?php 
											$cur_user_id = get_current_user_id(); 
											$user_info = get_userdata($cur_user_id);

											$user_location   = get_user_meta( $user_info->ID, 'user_location', true );
											$user_ipadr   = get_user_meta( $post->post_author, 'user_ipadr', true );
											?>
												<a href="<?php echo $user_info->author_url; ?>" target="_blank"><p><?php echo $user_info->first_name; ?> <?php echo $user_info->last_name; ?></p></a>
											<?php
											$ip = $user_ipadr;
											$ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
											$ipInfo = json_decode($ipInfo);
											$timezone = $ipInfo->timezone;
											date_default_timezone_set($timezone);
											?>
											<p class="us_loc_inf"><span class="locat_user"><?php if($user_location) { echo $user_location; } ?></span><span> - <?php echo date('g:i a'); ?> Local Time</span></p>
										</div>
										<?php if ( fre_share_role() || $user_role == FREELANCER ) { ?>
										<div class="free_hour">
											 <span><?php echo isset( $profile->hourly_rate_price ) ? $profile->hourly_rate_price : '';?></span>
										</div>
										<?php } ?>
										<div class="employer-info-edit top_edit">
											<a href="javascript:void(0)"
											   class="fre-normal-btn-o employer-info-edit-btn profile-show-edit-tab-btn"
											   data-ctn_edit="ctn-edit-profile"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										</div>
									</div>
									<div class="profile-employer-info-edit cnt-profile-hide" id="ctn-edit-profile" style="display: none">
										<div class="employer-info-avatar avatar-profile-page">
											<span class="employer-avatar img-avatar image">
												<?php echo get_avatar( $user_ID, 125 ) ?>
											</span>
											<a href="#" id="user_avatar_browse_button">
												<?php _e( 'Change', ET_DOMAIN ) ?>
											</a>
										</div>
										<div class="fre-employer-info-form" id="accordion" role="tablist"
											 aria-multiselectable="true">
											<form id="profile_form" class="form-detail-profile-page" action="" method="post"
												  novalidate>
												<div class="fre-input-field">
													<input type="text" value="<?php echo $display_name ?>"
														   name="display_name" id="display_name"
														   placeholder="<?php _e( 'Your name', ET_DOMAIN ) ?>">
												</div>

												<?php if ( fre_share_role() || $user_role == FREELANCER ) { ?>
													<div class="fre-input-field">
														<input type="text" name="et_professional_title"
															<?php if ( $job_title ) {
																echo 'value= "' . esc_attr( $job_title ) . '" ';
															} ?>
															   placeholder="<?php _e( "Professional Title", ET_DOMAIN ) ?>">
													</div>
												<?php } ?>

												<!--<div class="fre-input-field">-->
													<?php
													//$country_arr = array();
//													if ( ! empty( $profile->tax_input['country'] ) ) {
//														foreach ( $profile->tax_input['country'] as $key => $value ) {
//															$country_arr[] = $value->term_id;
//														};
//													}
//													$validate_country = 0;
//													if ( fre_share_role() || $user_role == FREELANCER ) {
//														$validate_country = 1;
//													}
//													ae_tax_dropdown( 'country',
//														array(
//															'attr'            => 'data-chosen-width="100%" data-validate_filed = "' . $validate_country . '" data-chosen-disable-search="" data-placeholder="' . __( "Choose country", ET_DOMAIN ) . '"',
//															'class'           => 'fre-chosen-single',
//															'hide_empty'      => 0,
//															'hierarchical'    => false,
//															'id'              => 'country',
//															'selected'        => $country_arr,
//															'show_option_all' => __( "Select country", ET_DOMAIN ),
//														)
//													);
													?>
												<!--</div>-->

												<?php if ( fre_share_role() || $user_role == FREELANCER ) { ?>
													<div class="fre-input-field fre-experience-field">
														<input type="number" value="<?php echo $experience; ?>" name="et_experience"
															   id="et_experience" min="0"
															   placeholder="<?php _e( 'Total', ET_DOMAIN ) ?>">
														<span><?php _e( 'years experience', ET_DOMAIN ) ?></span>
													</div>
													<div class="fre-input-field fre-hourly-field">
														<input type="number" <?php if ( $hour_rate ) {
															echo "value= $hour_rate ";
														} ?> name="hour_rate" id="hour_rate" step="5" min="0"
															   placeholder="<?php _e( 'Hour rate', ET_DOMAIN ) ?>">
														<span>
															<?php echo $currency['icon'] ?><?php _e( '/hr', ET_DOMAIN ) ?></span>
													</div>

													<div class="fre-input-field">
														<?php
														$c_skills = array();
														if ( ! empty( $current_skills ) ) {
															foreach ( $current_skills as $key => $value ) {
																$c_skills[] = $value->term_id;
															};
														}
														ae_tax_dropdown( 'skill',
															array(
																'attr'            => 'data-chosen-width="100%" data-chosen-disable-search="" multiple data-placeholder="' . sprintf( __( " Skills (max is %s)", ET_DOMAIN ), ae_get_option( 'fre_max_skill', 5 ) ) . '"',
																'class'           => ' edit-profile-skills',
																'hide_empty'      => false,
																'hierarchical'    => false,
																'id'              => 'skill',
																'show_option_all' => false,
																'selected'        => $c_skills
															)
														);

														?>
													</div>

													<div class="fre-input-field">
														<?php
														$email_skill = isset( $profile->email_skill ) ? (int) $profile->email_skill : 0;
														?>
														<label class="fre-checkbox no-margin-bottom" for="email-skill">
															<input id="email-skill" type="checkbox" name="email_skill"
																   value="1" <?php checked( $email_skill, 1 ); ?> >
															<span></span>
															<?php _e( 'Email me jobs that are relevant to my skills', ET_DOMAIN ) ?>
														</label>
													</div>

												<?php } ?>

												<div class="fre-input-field">
													<?php wp_editor( '', 'post_content', ae_editor_settings() ); ?>
												</div>

												<?php if ( ( fre_share_role() || ae_user_role( $user_ID ) == FREELANCER ) ) {
													do_action( 'ae_edit_post_form', PROFILE, $profile );
												} ?>

												<div class="employer-info-save btn-update-profile btn-update-profile-top">
													<span class="employer-info-cancel-btn profile-show-edit-tab-btn" data-ctn_edit="cnt-profile-default"><?php _e( 'Cancel', ET_DOMAIN ) ?> &nbsp; </span>
													<input type="submit" class="fre-normal-btn btn-submit" value="<?php _e( 'Save', ET_DOMAIN ) ?>">
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
							<div class="fre_about">
								<?php if ( ! empty( $profile ) ) { ?>
										<?php echo $about; ?>

									<?php if ( function_exists( 'et_the_field' ) && ( fre_share_role() || ae_user_role( $user_ID ) == FREELANCER ) ) {
										et_render_custom_field( $profile );
									}
									?>
								<?php } ?>
							</div>
							<?php if ( fre_share_role() || $user_role == FREELANCER ) { ?>
							<div class="fre-profile-box" id="blockMyVideo">
								<h2 class="freelance-portfolio-title"><?php _e( 'Video Resume', ET_DOMAIN ); ?></h2>
								<div class="video_present">
									<div class="col-lg-7 col-md-12 vidoe_container">
										<?php
										$cur_user_id = get_current_user_id();
										$file_video = get_user_meta( $cur_user_id, 'file_video', true );
										$video = wp_get_attachment_url( $file_video );
										$attachment_meta = wp_get_attachment_metadata( $file_video, true );
										$date = get_the_date( 'j/m/y', $file_video );
										if($video) {
										?>
											<video height="420" src="<?php echo $video; ?>" controls></video> 
										<?php } else { ?>
											<img src="<?php echo get_theme_file_uri( '/img/no_video.jpg' ); ?>" alt="">
										<?php } ?>
									</div>
									<div class="col-lg-5 col-md-12 vidoe_info">
										<?php
										$title_video = get_user_meta( $cur_user_id, 'title_video', true );
										$desc_video = get_user_meta( $cur_user_id, 'desc_video', true );
										?>
										<div>
											<span><?php if($title_video) { echo $title_video; } else { echo ''; } ?></span>
										</div>
										<div>
											<span><?php if($desc_video) { echo $desc_video; } else { echo ''; }  ?></span>
										</div>
										<div class="btn_group_video">
											<a href="javascript:void(0)" class="fre-normal-btn-o profile-video-edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										</div>
									</div>
								</div>
								<div class="profile-employer-video-edit cnt-profile-hide" id="ctn-edit-video" style="display: none;">
									<form id="video_form" novalidate="" enctype="multipart/form-data">
										<div class="fre-input-field">
											<label><?php _e( 'Title', ET_DOMAIN ); ?></label>
											<input type="text" class="" id="title_video" name="title_video" value="<?php echo $title_video; ?>" placeholder="Enter title">
										</div>
										<div class="fre-input-field">
											<label><?php _e( 'Description', ET_DOMAIN ); ?></label>
											<textarea class="" id="desc_video" name="desc_video" placeholder="Enter description"><?php echo $desc_video; ?></textarea>
										</div>
										<div class="fre-input-field video_up">
											<label><?php _e( 'Video', ET_DOMAIN ); ?></label>
											<input type="file" class="" id="file_video" name="file_video" value="" onchange="ValidateSize(this)" accept="video/*">
											<a href="javascript:void(0)" class="fre-normal-btn-o profile-video-upload"><?php _e( 'UPLOAD NEW', ET_DOMAIN ) ?></a>
											<a href="javascript:void(0)" class="fre-normal-btn-o profile-video-remove"><?php _e( 'REMOVE', ET_DOMAIN ) ?></a>
											<span class="sizefile"><?php _e( 'The file size should not exceed 25MB', ET_DOMAIN ) ?></span>
											<output id="list"></output>
										</div>
										<div class="employer-info-save btn-update-video">
											<input type="submit" class="fre-normal-btn fre-btn" name="" style="width: 100%" value="Save">
											<span class="employer-info-cancel-btn profile-video-cancel">Cancel</span>
										</div>
										<div class="spinner-border text-info" style="display: none">
										  <span class="sr-only">Loading...</span>
										</div>
									</form>
								</div>
								<div class="overlay" style="display: none"></div>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		
			<div class="other_box">
				<?php if ( fre_share_role() || $user_role == FREELANCER ) { ?>
				<?php do_action('after_my_account_block', $user_role);?>
				<?php } ?>
				<?php
				if ( fre_share_role() || $user_role == FREELANCER ) {
					get_template_part( 'list', 'portfolios' );
					wp_reset_query();
				} ?>

				<?php if ( fre_share_role() || $user_role == FREELANCER ) {
					get_template_part( 'list', 'experiences' );
					get_template_part( 'list', 'certifications' );
					get_template_part( 'list', 'educations' );
					wp_reset_query();
				} ?>
                <?php do_action('multi_currencies_profile_setting', $user_role);?>

                <?php do_action('my_profile_section', $user_role);?>
			</div>
		</div>
		
		
		
		<div class="right_box">
			<div class="right_progect project_cuctom_work">
				<div class="content_dop">
					<?php if ( fre_share_role() || $user_role == FREELANCER ) { ?>
					<div class="sbtl_pr short">
						<div class="profile-freelance-available">
							<p><?php _e( 'Available for hire', ET_DOMAIN ) ?></p>
							<div class="fre-input-field my_switch">
								<label for="fre-switch-user-available" class="fre-switch">
									<input id="fre-switch-user-available"
										   type="checkbox" <?php echo $user_available ? 'checked' : ''; ?>>
									<div class="fre-switch-slider">
									</div>
								</label>
							</div>
							<span><?php _e( 'Turn on to display an "Invite me”  button on your profile, allowing potential employers to suggest projects for you.', ET_DOMAIN ) ?></span>
						</div>
					</div>
					<?php } ?>
					<div class="sbtl_pr ctpr">
						<div class="freelance-rating <?php if ( fre_share_role() || $user_role == FREELANCER ) { ?><?php } else { ?>blackspan<?php } ?>">
								<span class="rate-it"
									  data-score="<?php echo $rating['rating_score']; ?>"></span>
							<?php if ( fre_share_role() || $user_role == FREELANCER ) { ?>
							<div class="freelance-hourly">
								<span>
									<?php echo ! empty( $profile->earned ) ? $profile->earned : price_about_format( 0 ) . ' ' . __( 'earned', ET_DOMAIN ) ?>
								</span>
							</div>
							<?php } ?>
							<?php if ( fre_share_role() || ae_user_role( $user_ID ) == FREELANCER ) { ?>
								<span class="freelance-empty-info">
									<?php echo ! empty( $profile->experience ) ? $profile->experience : '<i>' . __( 'No year experience information', ET_DOMAIN ) . '</i>'; ?>
								</span>


								<span class="total_progect"><?php printf( __('%s projects worked', ET_DOMAIN ), intval( $projects_worked ) ) ?> </span>
							<?php } else { ?>
								<span><?php printf( __('%s projects posted', ET_DOMAIN ), $project_posted ) ?></span>
								<span class="total_progect"> <?php printf(__( 'hire %s freelancers', ET_DOMAIN), $hire_freelancer ) ?></span>
							<?php } ?>
						</div>
					</div>
					<div class="sbtl_pr work_r_bl">
						<a href="<?php echo $user_data->author_url ?>" class="fre-view-as-others"><?php _e( 'View my profile as others', ET_DOMAIN ) ?></a>
					</div>
					<?php if ( fre_share_role() || $user_role == FREELANCER ) { ?>
					<div class="sbtl_pr scils">
						<div class="project-detail-skill">
							<?php
							if ( isset( $profile->tax_input['skill'] ) && $profile->tax_input['skill'] ) {
								//echo '<div class="freelance-skill">';
								foreach ( $profile->tax_input['skill'] as $tax ) {
									echo '<span class="fre-label">' . $tax->name . '</span>';
								}
								//echo '</div>';
							} else { ?>
								<span class="freelance-empty-skill"><?php _e( 'No skill information', ET_DOMAIN ) ?></span>
							<?php } ?>
						</div>
					</div>
					<?php } ?>
					<div class="sbtl_pr work_r_bl">
						<div id="blockMyAccount">
							<div class="profile-employer-secure-wrap active">
								<div class="profile-employer-secure cnt-profile-hide" id="cnt-account-default"
									 style="display: block">
									<p><?php _e( 'Email address', ET_DOMAIN ) ?></p><span><?php echo $user_data->user_email ?></span>

									<?php
									if ( ! empty( $user_meta['newemail'] ) ) {
										printf( __( '<p class="noti-update">There is a pending change of the email to %1$s. <!--<a href="%2$s">Cancel</a>--></p>', ET_DOMAIN ),
											'<code>' . esc_html( $user_meta['newemail'] ) . '</code>',
											esc_url( et_get_page_link( "profile" ) . '?dismiss=new_email' )
										);
									}
									?>
									<?php if ( use_paypal_to_escrow() ) { ?>
										<p><?php _e( 'Paypal account', ET_DOMAIN ) ?></p>

											<span><?php
											$paypal = get_user_meta( $user_ID, 'paypal', true );
											if ( ! empty( $paypal ) ) {
												echo $paypal;
											} else { ?>
												<span class="freelance-empty-info"><?php _e( 'No yet update', ET_DOMAIN ) ?></span>
											<?php } ?></span>

									<?php } ?>

									<?php
									$escrow_stripe_api = ae_get_option( 'escrow_stripe_api', false );
									$use_escrow        = ae_get_option( 'use_escrow', false );
									if ( ! empty( $escrow_stripe_api ) && $use_escrow && function_exists( 'ae_stripe_recipient_field' ) ) {
										if ( ! empty( $escrow_stripe_api['use_stripe_escrow'] ) ) {
											?>
											<p><?php _e( "Stripe account ", ET_DOMAIN ) ?></p>
												<span><?php do_action( 'ae_escrow_stripe_user_field' ); ?></span>

										<?php }
									} ?>

									<div class="employer-secure-edit">
										<a href="javascript:void(0)" class="fre-normal-btn-o profile-show-edit-tab-btn"
										   data-ctn_edit="ctn-edit-account"><i class="fa fa-pencil" aria-hidden="true"></i></a>
									</div>
								</div>

								<div class="profile-employer-secure-edit cnt-profile-hide" id="ctn-edit-account"
									 style="display: none">
									<form id="account_form" novalidate>
										<div class="fre-input-field">
											<input type="email" class="" id="user_email" name="user_email"
												   value="<?php echo $user_data->user_email ?>"
												   placeholder="<?php _e( 'Enter email', ET_DOMAIN ) ?>">
										</div>

										<?php
										$use_escrow = ae_get_option( 'use_escrow', false );
										if ( $use_escrow ) {
											do_action( 'ae_escrow_recipient_field' );
										} ?>

										<div class="employer-info-save btn-update-profile">
											<input type="submit" class="fre-normal-btn fre-btn" name="" style="width: 100%"
												   value="<?php _e( 'Save', ET_DOMAIN ) ?>">
											<span class="employer-info-cancel-btn profile-show-edit-tab-btn"
												  data-ctn_edit="cnt-account-default"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
										</div>
									</form>
								</div>
							</div>
							<div class="profile-secure-code-wrap" id="blockSecureCode">
								<p><?php _e( "Password", ET_DOMAIN ) ?></p>
								<a href="#" class="change-password"><?php _e( "Change Password", ET_DOMAIN ) ?></a>
								<?php if ( function_exists( 'fre_credit_add_request_secure_code' ) ) {
									$fre_credit_secure_code = ae_get_option( 'fre_credit_secure_code' );
									if ( ! empty( $fre_credit_secure_code ) ) {
										?>
										<ul class="fre-secure-code">
											<li>
												<span><?php _e( "Secure code", ET_DOMAIN ) ?></span>
											</li>
											<?php do_action( 'fre-profile-after-list-setting' ); ?>
										</ul>
									<?php }
								} ?>
							</div>
						</div>
					</div>
					<div class="sbtl_pr pay_info">
						<p><?php _e( 'Payment Method', ET_DOMAIN ) ?></p>
						<div class="pay_edit paypal">
							<p><?php _e( 'Paypal', ET_DOMAIN ) ?></p>
							<span>web@gmail.com</span>
							<a href="javascript:void(0)" class="fre-normal-btn-o profile-show-edit-tab-btn" data-ctn_edit="ctn-edit-account"><i class="fa fa-pencil" aria-hidden="true"></i></a>
						</div>
						<div class="pay_edit stripe">
							<p><?php _e( 'Stripe Account', ET_DOMAIN ) ?></p>
							<span>web@gmail.com</span>
							<a href="javascript:void(0)" class="fre-normal-btn-o profile-show-edit-tab-btn" data-ctn_edit="ctn-edit-account"><i class="fa fa-pencil" aria-hidden="true"></i></a>
						</div>
					</div>
					<div class="sbtl_pr pay_info">
						<span class="since">
							<?php _e( 'Member since: ', ET_DOMAIN );
							if ( isset( $user_data->user_registered ) ) {
								echo date_i18n( get_option( 'date_format' ), strtotime( $user_data->user_registered ) );
							} ?>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- CURRENT PROFILE -->
<?php if ( $profile_id && $profile_post && ! is_wp_error( $profile_post ) ) { ?>
    <script type="data/json" id="current_profile">
    <?php echo json_encode( $profile ) ?>

    </script>
<?php } ?>
<!-- END / CURRENT PROFILE -->

<!-- CURRENT SKILLS -->
<?php if ( ! empty( $current_skills ) ) { ?>
    <script type="data/json" id="current_skills">
    <?php echo json_encode( $current_skills ) ?>

    </script>
<?php } ?>
<!-- END / CURRENT SKILLS -->

<?php
get_footer();
?>