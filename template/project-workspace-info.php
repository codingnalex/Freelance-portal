<?php

global $wp_query, $wpdb, $ae_post_factory, $post, $user_ID;
$post_object         = $ae_post_factory->get( PROJECT );
$convert             = $project = $post_object->convert( $post );
$et_expired_date     = $convert->et_expired_date;
$bid_accepted        = $convert->accepted;
$project_status      = $convert->post_status;
$project_link        = get_permalink( $post->ID );
$role                = ae_user_role();
$bid_accepted_author = get_post_field( 'post_author', $bid_accepted );
$bid_accepted_author = get_post_field( 'post_author', $bid_accepted );
$profile_id          = $post->post_author;
if ( ( fre_share_role() || $role != FREELANCER ) ) {
	$profile_id = $bid_accepted_author;
}
$currency               = ae_get_option( 'currency', array( 'align' => 'left', 'code' => 'USD', 'icon' => '$' ) );
$comment_for_freelancer = get_comments( array(
	'type'    => 'em_review',
	'status'  => 'approve',
	'post_id' => $bid_accepted
) );

$comment_for_employer   = get_comments( array(
	'type'    => 'fre_review',
	'status'  => 'approve',
	'post_id' => get_the_ID()
) );


$attachment = get_children( array(
	'numberposts' => - 1,
	'order'       => 'ASC',
	'post_parent' => $post->ID,
	'post_type'   => 'attachment'
), OBJECT );

$user_location   = get_user_meta( $post->post_author, 'user_location', true );
$user_ipadr   = get_user_meta( $post->post_author, 'user_ipadr', true );


$freelancer_info = get_userdata($bid_accepted_author);
$ae_users  = AE_Users::get_instance();
$freelancer_data = $ae_users->convert( $freelancer_info->data );

if ( ( fre_share_role() || $role == FREELANCER ) && $project_status == 'complete' && ! empty( $comment_for_freelancer ) ) { ?>
    <div class="project-detail-box">
        <div class="project-employer-review">
            <span class="employer-avatar-review"><?php echo $convert->et_avatar; ?></span>
            <h2><a href="<?php echo $convert->author_url; ?>" target="_blank"><?php echo $convert->author_name; ?></a></h2>
            <p><?php echo '"' . $comment_for_freelancer[0]->comment_content . '"'; ?></p>
            <div class="rate-it"
                 data-score="<?php echo get_comment_meta( $comment_for_freelancer[0]->comment_ID, 'et_rate', true ); ?>"></div>
			<?php if ( empty( $comment_for_employer ) ) { ?>
                <a href="#" id="<?php the_ID(); ?>"
                   class="fre-normal-btn btn-complete-project"> <?php _e( 'Review for Employer', ET_DOMAIN ); ?></a>
			<?php } ?>
        </div>
    </div>
<?php } else if ( ( fre_share_role() || $role == EMPLOYER ) && $project_status == 'complete' && ! empty( $comment_for_employer ) ) { ?>
    <div class="project-detail-box">
        <div class="project-employer-review">
            <span class="employer-avatar-review"><?php echo $freelancer_data->avatar; ?></span>
            <h2><a href="<?php echo $freelancer_data->author_url; ?>" target="_blank"><?php echo $freelancer_data->display_name; ?></a>
            </h2>
            <p><?php echo '"' . $comment_for_employer[0]->comment_content . '"'; ?></p>
            <div class="rate-it"
                 data-score="<?php echo get_comment_meta( $comment_for_employer[0]->comment_ID, 'et_rate', true ); ?>"></div>
        </div>
    </div>
<?php } ?>


<div class="project-detail-box custom_detail_box_top">
    <div class="project-detail-info">
        <div class="row">
            <div class="col-lg-9 col-md-8">
				<div class="top_info_user">
					<div class="avatar_user">
						<?php if ( fre_share_role() || $role == FREELANCER ) { ?>
						<span class="employer-avatar-review"><?php echo $convert->et_avatar; ?></span>
						<?php } ?>
						<?php if ( fre_share_role() || $role == EMPLOYER ) { ?>
						<span class="employer-avatar-review"><?php echo $freelancer_data->avatar; ?></span>
						<?php } ?>
					</div>
					<div class="last_first_user">
						<?php 
						$cur_user_id = get_current_user_id(); 
						$user_info = get_userdata($cur_user_id);
						?>
						<?php if ( ( fre_share_role() || $role == FREELANCER ) && $user_ID != $project->post_author ) { ?>
                            <a href="<?php echo $convert->author_url; ?>" target="_blank"><p><?php echo $user_info->first_name; ?> <?php echo $user_info->last_name; ?></p></a>
						<?php } else if ( ( fre_share_role() || $role == EMPLOYER ) && $user_ID == $project->post_author ) { ?>
                            <a href="<?php echo $freelancer_data->author_url; ?>" target="_blank"><p><?php echo $user_info->first_name; ?> <?php echo $user_info->last_name; ?></p></a>
						<?php } ?>
						<?php
						$ip = $user_ipadr;
						$ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
						$ipInfo = json_decode($ipInfo);
						$timezone = $ipInfo->timezone;
						date_default_timezone_set($timezone);
						?>
						<p class="us_loc_inf"><span class="locat_user"><?php if($user_location) { echo $user_location; } ?></span><span> - <?php echo date('g:i a'); ?> Local Time</span></p>
					</div>
				</div>
            </div>
            <div class="col-lg-3 col-md-4">
                <span class="project-detail-status">
                    <?php
                    $status_arr = array(
	                    'close'     => __( "Processing", ET_DOMAIN ),
	                    'complete'  => __( "Completed", ET_DOMAIN ),
	                    'disputing' => __( "Disputed", ET_DOMAIN ),
	                    'disputed'  => __( "Resolved", ET_DOMAIN ),
	                    'publish'   => __( "Active", ET_DOMAIN ),
	                    'pending'   => __( "Pending", ET_DOMAIN ),
	                    'draft'     => __( "Draft", ET_DOMAIN ),
	                    'reject'    => __( "Rejected", ET_DOMAIN ),
	                    'archive'   => __( "Archived", ET_DOMAIN ),
                    );
                    echo $status_arr[ $post->post_status ];
                    ?>
                </span>
                <div class="project-detail-action">
					<?php
					if ( $post->post_status == 'close' ) {
						if ( (int) $project->post_author == $user_ID ) { ?>
                            <a title="<?php _e( 'Finish', ET_DOMAIN ); ?>" href="#" id="<?php the_ID(); ?>"
                               class="fre-action-btn btn-complete-project"> <?php _e( 'Finish', ET_DOMAIN ); ?></a>
							<?php if ( ae_get_option( 'use_escrow' ) ) { ?>
                                <a title="<?php _e( 'Close', ET_DOMAIN ); ?>" href="#" id="<?php the_ID(); ?>"
                                   class="fre-action-btn btn-close-project"><?php _e( 'Close', ET_DOMAIN ); ?></a>
							<?php }
						} else {
							if ( $bid_accepted_author == $user_ID && ae_get_option( 'use_escrow' ) ) { ?>
                                <a title="<?php _e( 'Discontinue', ET_DOMAIN ); ?>" href="#" id="<?php the_ID(); ?>"
                                   class="fre-action-btn btn-quit-project"><?php _e( 'Discontinue', ET_DOMAIN ); ?></a>
							<?php }
						}
					} else if ( $post->post_status == 'disputing' ) { ?>
                        <a href="<?php echo add_query_arg( array( 'dispute' => 1 ), $project_link ) ?>"
                           class="fre-normal-btn"><?php _e( 'Dispute Page', ET_DOMAIN ) ?></a>
					<?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="right_progect project_cuctom_work">
		<div class="content_dop">
			<?php
			$progect_freelancer = get_user_meta( $project->et_freelancerselect);
			$postID = get_the_ID();
			?>
			<?php if($progect_freelancer) { ?>
				<div class="sbtl_pr slect_fr">
					<p><?php _e( 'Selected Freelancers', ET_DOMAIN ); ?></p>
					<span><?php echo $progect_freelancer['first_name'][0] . ' ' .$progect_freelancer['last_name'][0]; ?></span>
					<?php
					$selectFree = get_post_meta($postID, 'freelancerselectmultiple', true);
					$nameFreelancer = get_users( [
						'include' => $selectFree,
					] );
					if($selectFree) {
					foreach( $nameFreelancer as $user ) {
					?>
						<span><?php echo  ', ' . $user->first_name . ' ' . $user->last_name; ?></span>
						<?php } ?>
					<?php } ?>
				</div>
			<?php } ?>
			<div class="sbtl_pr short">
				<p><?php _e('Short Term Work', ET_DOMAIN);?></p>
				<span>
					<?php if($project->et_groupwork == 'les') { ?>
						<?php _e('Less than 40 hrs/week', ET_DOMAIN);?>
					<?php } else { ?>
						<?php _e('More than 40 hrs/week', ET_DOMAIN);?>
					<?php } ?>
				</span>
			</div>
			<div class="sbtl_pr ctpr">
				<p><?php _e('Category', ET_DOMAIN);?></p>
				<?php
				$cur_terms = get_the_terms( $post->ID, 'project_category' );
				if( is_array( $cur_terms ) ){
					foreach( $cur_terms as $cur_term ){
						echo '<a href="http://staging2.virtualpmsolutions.com/projects/?category_project='.$cur_term->slug.'">'. $cur_term->name .'</a>';
					}
				}
				?>
			</div>
			<div class="sbtl_pr work_r_bl">
				<p><?php _e('Project Title', ET_DOMAIN);?></p>
				<span><?php the_title(); ?></span>
			</div>
			<div class="sbtl_pr work_r_bl">
				<p><?php _e('Description', ET_DOMAIN);?></p>
				<span><?php the_excerpt(); ?></span>
			</div>
			<div class="sbtl_pr work_r_bl">
				<p><?php _e( 'Attachments', ET_DOMAIN ); ?></p>
				<?php
				if ( ! empty( $attachment ) ) {
					echo '<ul class="project-detail-attach">';
					foreach ( $attachment as $key => $att ) {
						$file_type = wp_check_filetype( $att->post_title, array(
								'jpg'  => 'image/jpeg',
								'jpeg' => 'image/jpeg',
								'gif'  => 'image/gif',
								'png'  => 'image/png',
								'bmp'  => 'image/bmp'
							)
						);
						echo '<li><a href="' . $att->guid . '"><i class="fa fa-paperclip" aria-hidden="true"></i>' . $att->post_title . '</a></li>';
					}
					echo '</ul>';
				}
				?>
				<span class="project-detail-posted"><?php printf( __( 'Posted on %s', ET_DOMAIN ), $project->post_date ); ?></span>
			</div>
		</div>
	</div>