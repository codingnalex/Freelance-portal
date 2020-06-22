<?php

global $wp_query, $ae_post_factory, $post, $user_ID;
$post_object = $ae_post_factory->get( PROJECT );
$current     = $post_object->current_post;
$tax_input   = $current->tax_input;

$convert        = $project = $post_object->convert( $post );
$project_status = $project->post_status;

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

?>

<li class="project-item">
    <div class="project-list-wrap">
		<div class="left_proj_cont">
			<h2 class="project-list-title">
				<a  class="secondary-color" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
			</h2>
			<span class="posted"><?php printf( __( 'Posted %s', ET_DOMAIN ), get_the_date() ); ?></span>
			<div class="project-list-desc">
				<p><?php echo $current->post_content_trim; ?></p>
			</div>
			<div class="project-list-info">
				<p><?php _e('Bids:', ET_DOMAIN);?> <span><?php echo $current->text_total_bid; ?></span></p>
				<p><?php _e('Budget:', ET_DOMAIN);?> <span><?php echo $current->budget; ?></span></p>
				<p><?php _e('Average Bid: ',ET_DOMAIN);?><span>
                        <span class="secondary-color">
                            <?php
                            if ( $project->total_bids > 0 ) {
	                            $avg = get_total_cost_bids( $project->ID ) / $project->total_bids;
                            }
                            echo fre_price_format( $avg );
                            ?>
                        </span></p>
				<?php
				//if ( ! empty( $current->text_country ) ) {
//					echo "<span>";
//					echo $current->text_country;
//					echo "</span>";
//				}
				?>
			</div>
			<?php
			//echo $current->list_skills;
			?>
			<!-- <div class="project-list-bookmark">
				<a class="fre-bookmark" href="">Bookmark</a>
			</div> -->
		</div>
		<div class="right_proj_cont">
			<div class="content_dop">
				<div class="sbtl_pr">
					<p><?php _e('Short Term Work', ET_DOMAIN);?></p>
					<span>
						<?php if($current->et_groupwork == 'les') { ?>
							<?php _e('Less than 40 hrs/week', ET_DOMAIN);?>
						<?php } else { ?>
							<?php _e('More than 40 hrs/week', ET_DOMAIN);?>
						<?php } ?>
					</span>
				</div>
				<div class="sbtl_pr">
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
				<div class="sbtl_pr noborder">
					<p><?php _e('Employer Information', ET_DOMAIN);?></p>
					<span><i class="fa fa-map-marker" aria-hidden="true"></i>United Arab Emirates, Dubai 06:09 pm</span>
				</div>
			</div>
		</div>
    </div>
</li>
