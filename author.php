<?php
/**
 * The Template for displaying a user profile
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
<div id="my_profile" class="fre-page-wrapper list-profile-wrapper authorpage">
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

											$user_location   = get_user_meta( $cur_user_id, 'user_location', true );
											$user_ipadr   = get_user_meta( $cur_user_id, 'user_ipadr', true );
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
										<div class="free_hour">
											 <span><?php echo isset( $profile->hourly_rate_price ) ? $profile->hourly_rate_price : '';?></span>
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
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		
			<div class="other_box">
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

<?php
get_footer();