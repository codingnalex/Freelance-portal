<?php
global $wp_query, $ae_post_factory, $post, $user_ID;
$post_object    = $ae_post_factory->get( PROJECT );
$convert        = $project = $post_object->convert( $post );
$project_status = $project->post_status;
$author_id   = $project->post_author;
$rating      = Fre_Review::employer_rating_score( $author_id );

$hire_freelancer = fre_count_hire_freelancer( $author_id );

$user_data = get_userdata( $author_id );

$user_role = ae_user_role( $user_ID );

$et_expired_date = $convert->et_expired_date;
$bid_accepted    = $convert->accepted;
$project_status  = $convert->post_status;

$profile_id   = get_user_meta( $post->post_author, 'user_profile_id', true );
$project_link = get_permalink( $post->ID );
$currency     = ae_get_option( 'currency', array( 'align' => 'left', 'code' => 'USD', 'icon' => '$' ) );
$avg          = 0;


if ( is_user_logged_in() && ( ( fre_share_role() || $user_role == FREELANCER ) ) ) {
	$bidding_id  = 0;
	$child_posts = get_children(
		array(
			'post_parent' => $project->ID,
			'post_type'   => BID,
			'post_status' => 'publish',
			'author'      => $user_ID
		)
	);
	if ( ! empty( $child_posts ) ) {
		foreach ( $child_posts as $key => $value ) {
			$bidding_id = $value->ID;
		}
	}
}


$attachment = get_children( array(
	'numberposts' => - 1,
	'order'       => 'ASC',
	'post_parent' => $post->ID,
	'post_type'   => 'attachment'
), OBJECT );

?>

<?php
$author_ID = get_the_author_meta('ID');
$cur_user_id = get_current_user_id();
?>
<?php if($author_ID == $cur_user_id) { ?>
<div id="project-show-freelancer-select" class="project-detail-box no-padding">
	<div class="project-detail-extend">
		<div class="fre-input-field">
			<form id="selfree_form" novalidate="" enctype="multipart/form-data">
				<label class="fre-field-title" for="project_category">
					<?php _e('Invite more Freelancers', ET_DOMAIN);?>
				</label>
				<?php
				$users = get_users( [
					'meta_key' => 'zhc_capabilities',
					'meta_value' => 'a:1:{s:10:"freelancer";b:1;}',
					'exclude' => $project->et_freelancerselect,
				] );
				?>
				<input type="hidden" name="id_progect" id="id_progect" value="<?php echo $postID; ?>">
				<select id="freelancerSelectmultiple" class="fre-chosen-single" name="freelancerselectmultiple[]" data-placeholder="<?php _e('Select Freelancers', ET_DOMAIN);?>" data-chosen-width="100%" data-chosen-disable-search="" multiple>
					<option value=""></option>
					<?php
					foreach ( $users as $user ) {
						?>
					<option value="<?php echo $user->ID; ?>">
						<?php echo $user->first_name . ' ' . $user->last_name; ?>
					</option>
					<?php } ?>
				</select>
				<div class="freelancer-save">
					<input type="submit" class="fre-normal-btn fre-btn" name="" style="width: 100%" value="<?php _e('Invite', ET_DOMAIN);?>">
				</div>
			</form>
		</div>
	</div>
</div>
<?php } ?>
<div id="new_progect_block" class="project-detail-box  no-padding">
	<div class="left_progect">
		<h1 class="project-detail-title"><?php the_title(); ?></h1>
		<p class="project-detail-posted"><?php printf( __( 'Posted on %s', ET_DOMAIN ), $project->post_date ); ?></p>
		<div class="desk_project"><?php the_content(); ?></div>
		<h4><?php _e( 'Attachments', ET_DOMAIN ); ?></h4>
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
		<h4><?php _e( 'Activity on this Job', ET_DOMAIN ); ?></h4>
		<div class="project-list-info">
			<p><?php _e('Bids:', ET_DOMAIN);?> <span><?php echo $project->text_total_bid; ?></span></p>
			<p><?php _e('Budget:', ET_DOMAIN);?> <span><?php echo $project->budget; ?></span></p>
			<p><?php _e('Average Bid: ',ET_DOMAIN);?><span>
					<span class="secondary-color">
						<?php
						if ( $project->total_bids > 0 ) {
							$avg = get_total_cost_bids( $project->ID ) / $project->total_bids;
						}
						echo fre_price_format( $avg );
						?>
					</span></p>
		</div>
	</div>
	<div class="right_progect">
		<div class="project-detail-action">
			<?php
			if ( is_user_logged_in() ) {
				if ( $project_status == 'publish' ) {
					if ( ( fre_share_role() || $user_role == FREELANCER ) && $user_ID != $project->post_author ) {
						$has_bid = fre_has_bid( get_the_ID() );
						if ( $has_bid ) {
							echo '<a class="fre-normal-btn primary-bg-color bid-action" data-action="cancel" data-bid-id="' . $bidding_id . '">' . __( 'Cancel', ET_DOMAIN ) . '</a>';
						} else {
							fre_button_bid( $project->ID );
						}
					} else if ( ( ( fre_share_role() || $user_role == EMPLOYER ) || current_user_can( 'manage_options' ) ) && $user_ID == $project->post_author ) {
						echo '<a class="fre-action-btn  project-action" data-action="archive" data-project-id="' . $project->ID . '">' . __( 'Archive', ET_DOMAIN ) . '</a>';
					} else {
						echo '<a href="' . et_get_page_link( 'submit-project' ) . '" class="fre-normal-btn primary-bg-color">' . __( 'Post Project Like This', ET_DOMAIN ) . '</a>';
					}
				} else if ( $project_status == 'disputing' || $project_status == 'disputed' ) {
					$bid_accepted_author = get_post_field( 'post_author', $bid_accepted );
					if ( (int) $project->post_author == $user_ID || $bid_accepted_author == $user_ID || current_user_can( 'manage_options' ) ) {
						echo '<a class="fre-normal-btn" href="' . add_query_arg( array( 'dispute' => 1 ), $project_link ) . '">' . __( 'Dispute Page', ET_DOMAIN ) . '</a>';
					}
				} else if ( $project_status == 'close' ) {
					$bid_accepted_author = get_post_field( 'post_author', $bid_accepted );
					if ( (int) $project->post_author == $user_ID || $bid_accepted_author == $user_ID ) {
						echo '<a class="fre-normal-btn" href="' . add_query_arg( array( 'workspace' => 1 ), $project_link ) . '">' . __( 'Workspace', ET_DOMAIN ) . '</a>';
					}
				} else if ( $project_status == 'complete' ) {
					$bid_accepted_author = get_post_field( 'post_author', $bid_accepted );
					if ( (int) $project->post_author == $user_ID || $bid_accepted_author == $user_ID ) {
						echo '<a class="fre-normal-btn" href="' . add_query_arg( array( 'workspace' => 1 ), $project_link ) . '">' . __( 'Workspace', ET_DOMAIN ) . '</a>';
					} else if ( current_user_can( 'manage_options' ) && ae_get_option( 'use_escrow' ) ) {
						$bid_id_accepted = get_post_meta( $post->ID, 'accepted', true );
						$order           = get_post_meta( $bid_id_accepted, 'fre_bid_order', true );
						$order_status    = get_post_field( 'post_status', $order );
						$commission      = get_post_meta( $bid_id_accepted, 'commission_fee', true );
						if ( $commission ) {
							if ( $order_status != 'finish' ) {
								echo '<a class="fre-normal-btn primary-bg-color manual-transfer" data-project-id="' . $project->ID . '">' . __( "Transfer Money", ET_DOMAIN ) . '</a>';
							} else {
								if ( ae_get_option( 'manual_transfer', false ) ) {
									echo '<span class="fre-money-transfered">';
									_e( "Already transfered", ET_DOMAIN );
									echo '</span>';
								}
							}
						}
					}
				} else if ( $project_status == 'pending' ) {
					if ( ( fre_share_role() || $user_role == EMPLOYER ) && $user_ID == $project->post_author ) {
						echo '<a class="fre-action-btn" href="' . et_get_page_link( 'edit-project', array( 'id' => $project->ID ) ) . '">' . __( 'Edit', ET_DOMAIN ) . '</a>';
					} else if ( current_user_can( 'manage_options' ) ) {
						echo '<a class="fre-normal-btn primary-bg-color project-action" data-action="approve" data-project-id="' . $project->ID . '">' . __( 'Approve', ET_DOMAIN ) . '</a>';
						echo '<a class="fre-normal-btn primary-bg-color project-action" data-action="reject" data-project-id="' . $project->ID . '">' . __( 'Reject', ET_DOMAIN ) . '</a>';
					}
				} else if ( $project_status == 'reject' ) {
					if ( ( fre_share_role() || $user_role == EMPLOYER ) && $user_ID == $project->post_author ) {
						echo '<a class="fre-action-btn" href="' . et_get_page_link( 'edit-project', array( 'id' => $project->ID ) ) . '">' . __( 'Edit', ET_DOMAIN ) . '</a>';
					}
				} else if ( $project_status == 'draft' ) {
					if ( ( fre_share_role() || $user_role == EMPLOYER ) && $user_ID == $project->post_author ) {
						echo '<a class="fre-action-btn" href="' . et_get_page_link( 'submit-project', array( 'id' => $project->ID ) ) . '">' . __( 'Edit', ET_DOMAIN ) . '</a>';
						echo '<a class="fre-action-btn project-action" data-action="delete" data-project-id="' . $project->ID . '">' . __( 'Delete', ET_DOMAIN ) . '</a>';
					} else if ( current_user_can( 'manage_options' ) ) {
						echo '<a class="fre-action-btn project-action" data-action="delete" data-project-id="' . $project->ID . '">' . __( 'Delete', ET_DOMAIN ) . '</a>';
					}
				} else if ( $project_status == 'archive' ) {
					if ( ( fre_share_role() || $user_role == EMPLOYER ) && $user_ID == $project->post_author ) {
						echo '<a class="fre-action-btn" href="' . et_get_page_link( 'submit-project', array( 'id' => $project->ID ) ) . '">' . __( 'Renew', ET_DOMAIN ) . '</a>';
						echo '<a class="fre-action-btn project-action" data-action="delete" data-project-id="' . $project->ID . '">' . __( 'Delete', ET_DOMAIN ) . '</a>';
					} else if ( current_user_can( 'manage_options' ) ) {
						echo '<a class="fre-action-btn project-action" data-action="delete" data-project-id="' . $project->ID . '">' . __( 'Delete', ET_DOMAIN ) . '</a>';
					}
				}
			} else {
				if ( $project_status == 'publish' ) {
					echo '<a class="fre-normal-btn primary-bg-color" href="' . et_get_page_link( 'login', array( 'ae_redirect_url' => $project->permalink ) ) . '">Submit a Bid</a>';
				}
			}
			?>
		</div>
		<div class="content_dop">
			<div class="sbtl_pr slect_fr">
				<p><?php _e( 'Selected Freelancers', ET_DOMAIN ); ?></p>
				<?php
				$progect_freelancer = get_user_meta( $project->et_freelancerselect);
				$postID = get_the_ID();
				?>
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
			<div class="sbtl_pr scils">
				<div class="project-detail-skill">
					<?php list_tax_of_project( get_the_ID(), __( 'Skills Required', ET_DOMAIN ), 'skill' ); ?>
				</div>
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
			<div class="sbtl_pr employer">
				<p><?php _e('Employer Information', ET_DOMAIN);?></p>
				<span class="rate-it" data-score="<?php echo $rating['rating_score']; ?>"></span>
				<span><i class="fa fa-map-marker" aria-hidden="true"></i>United Arab Emirates, Dubai 06:09 pm</span>
				<span class="projectposted"><?php printf( __( '%s project(s) posted', ET_DOMAIN ), fre_count_user_posts_by_type( $author_id, 'project', '"publish","complete","close","disputing","disputed", "archive" ', true ) ); ?></span>
				<span><?php printf( __( 'hire %s freelancers', ET_DOMAIN ), $hire_freelancer ); ?></span>
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