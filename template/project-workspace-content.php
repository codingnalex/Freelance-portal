<?php
global $wp_query, $ae_post_factory, $post, $user_ID;
$post_object = $ae_post_factory->get( PROJECT );
$convert     = $project = $post_object->current_post;

$date_format = get_option( 'date_format' );
$time_format = get_option( 'time_format' );

// Load milestone change log if ae-milestone plugin is active
if ( defined( 'MILESTONE_DIR_URL' ) ) {
	$query_args = array(
		'type'       => 'message',
		'post_id'    => $post->ID,
		'paginate'   => 'load',
		'order'      => 'DESC',
		'orderby'    => 'date',
		'meta_query' => array(
			array(
				'key'     => 'fre_comment_file',
				'compare' => 'NOT EXISTS'
			)
		)
	);
} else {
	$query_args = array(
		'type'       => 'message',
		'post_id'    => $post->ID,
		'paginate'   => 'load',
		'order'      => 'DESC',
		'orderby'    => 'date',
		'meta_query' => array(
			array(
				'key'     => 'changelog',
				'value'   => '',
				'compare' => 'NOT EXISTS'
			),
			array(
				'key'     => 'fre_comment_file',
				'compare' => 'NOT EXISTS'
			)
		)
	);
}
$query_args['text'] = __( "Load older message", ET_DOMAIN );
echo '<script type="data/json"  id="workspace_query_args">' . json_encode( $query_args ) . '</script>';
/**
 * count all reivews
 */
$total_args = $query_args;
$all_cmts   = get_comments( $total_args );

/**
 * get page 1 reviews
 */
$query_args['number'] = 10000;//get_option('posts_per_page');
$comments             = get_comments( $query_args );

$total_messages      = count( $all_cmts );
$comment_pages       = ceil( $total_messages / $query_args['number'] );
$query_args['total'] = $comment_pages;

$messagedata    = array();
$message_object = Fre_Message::get_instance();
$bid_id         = get_post_meta( $post->ID, "accepted", true );
$lock_file      = get_post_meta( $post->ID, "lock_file", true );
$bid            = get_post( $bid_id );

foreach ( $comments as $key => $message ) {
	$convert       = $message_object->convert( $message );
	$messagedata[] = $convert;
	$author_name   = get_the_author_meta( 'display_name', $message->user_id );
	$isAttach      = $message->isAttach;

}

$args = array(
	'post_type'      => 'ae_milestone',
	'posts_per_page' => - 1,
	'post_status'    => 'any',
	'post_parent'    => $project->ID,
	'orderby'        => 'meta_value',
	'order'          => 'ASC',
	'meta_key'       => 'position_order'
);

$query = new WP_Query( $args );

?>

<style>
    .conversation-send-file-btn input {
        display: block;
    }
</style>
<div class="tabs_workspace">
    <div class="tabs">
        <span class="tab"><?php _e( 'Time Track', ET_DOMAIN ); ?></span>
        <span class="tab"><?php _e( 'Messages & Files', ET_DOMAIN ); ?></span>       
    </div>
    <div class="tab_content">
        <div class="tab_item">
			<div class="workspace-files-wrap">
				<div id="workspace-time" class="workspace-time tab-pane fade">
					<?php 
					global $wp_query, $ae_post_factory, $post, $current_user, $user_ID;
					$user_role = ae_user_role( $current_user->ID );
					$post_object    = $ae_post_factory->get( PROJECT );
					$convert        = $project = $post_object->convert( $post );
					$project_status = $project->post_status;
					if($project_status == 'close') {
					if ( fre_share_role() || $user_role == FREELANCER ) { ?>
					<h2 class="workspace-title"><span>+</span> <?php echo __( "Add manual time", ET_DOMAIN ); ?></h2>
					<form id="add_time" novalidate="" enctype="multipart/form-data">
						<?php 
						$postID = get_the_ID();
						?>
						<div class="freelancer-save">
							<input type="hidden" name="id_project" id="id_project" value="<?php echo $postID; ?>">
							<label class="fre-field-title">
								<p><?php _e('hh.mm', ET_DOMAIN);?></p>
								<input type="number" name="time_project" id="time_project" required>
							</label>
							<input type="submit" class="fre-normal-btn fre-btn" name="" style="width: 100%" value="<?php _e('Add', ET_DOMAIN);?>">
						</div>
					</form>
					<?php } ?>
					<?php } ?>
					<?php 
					$postID = get_the_ID();
					$addTimeDate = get_post_meta( $postID, 'date_time_project');
					if($addTimeDate) { 
					?>
					<div class="added_time">
						<h3 class="workspace-title"><?php echo __( "Project added time", ET_DOMAIN ); ?></h3>
						<?php 
						if($addTimeDate) {
							foreach ( $addTimeDate as $key => $value ) {
							$time = stristr($value, "|");
							$time=str_replace('|','',$time);
							$time = stristr($time, "-", true);
							$timeDigit = $time;
							$timePay = stristr($value, "-");
							?>
								<?php if($timePay[1] == 'U') { ?>
								<p><span class="time"><?php echo $time; ?>/h</span> - <span class="paytime unpaid"><?php _e('Not paid', ET_DOMAIN);?></span></p>
								<?php } elseif($timePay[1] == 'P') { ?>
								<p><span class="time"><?php echo $time; ?>/h</span> - <span class="paytime paid"><?php _e('Paid', ET_DOMAIN);?></span></p>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
					<?php } ?>
				</div>

				<?php
				if ( function_exists( 'ae_query_milestone' ) && $query->have_posts() ) { ?>
                    <div id="workspace-milestone" class="workspace-milestone tab-pane fade">
                        <h2 class="workspace-title"><?php echo __( "Project milestones", ET_DOMAIN ); ?></h2>
						<?php do_action( 'after_sidebar_single_project_workspace', $post ); ?>
                    </div>
				<?php } ?>

            </div>
		
		</div>
        <div class="tab_item">
			<div id="workspace-conversation"
                 class="project-workplace-details workplace-details workspace-conversation tab-pane fade in active">
                <div class="message-container">
                    <div class="list-chat-work-place-wrap fre-conversation-wrap fre-conversation">
                        <ul class="fre-conversation-list list-chat-work-place new-list-message-item upload_file_file_list">
							<?php
							$comments = array_reverse( $comments );
							if ( ! empty( $comments ) ) {
								foreach ( $comments as $key => $message ) {
									$convert       = $message_object->convert( $message );
									$messagedata[] = $convert;
									$author_name   = get_the_author_meta( 'display_name', $message->user_id );
									$isAttach      = $message->isAttach;
									$today         = date( $date_format );
									$yesterday     = date( $date_format, strtotime( "yesterday" ) );
									if ( $key == 0 ) {
										$message_date = date( $date_format, strtotime( $comments[ $key ]->comment_date ) );
										if ( $message_date === $today ) {
											echo '<li class="message-time" id="message-time-today">';
											_e( 'Today', ET_DOMAIN );
											echo '</li>';
										} else if ( $message_date === $yesterday ) {
											echo '<li class="message-time">';
											_e( 'Yesterday', ET_DOMAIN );
											echo '</li>';
										} else {
											echo '<li class="message-time">';
											echo $message_date;
											echo '</li>';
										}
									} else {
										$message_date        = date( $date_format, strtotime( $comments[ $key ]->comment_date ) );
										$message_date_before = date( $date_format, strtotime( $comments[ $key - 1 ]->comment_date ) );
										if ( $message_date != $message_date_before ) {
											if ( $message_date === $today ) {
												echo '<li class="message-time" id="message-time-today">';
												_e( 'Today', ET_DOMAIN );
												echo '</li>';
											} else if ( $message_date === $yesterday ) {
												echo '<li class="message-time">';
												_e( 'Yesterday', ET_DOMAIN );
												echo '</li>';
											} else {
												echo '<li class="message-time">';
												echo $message_date;
												echo '</li>';
											}
										}
									}
									if ( ! $message->isFile ) {
										if ( $message->changed_milestone_id != '' ) {
											echo '<li class="milestone-item-noti">' . get_the_author_meta( 'display_name', $message->user_id ) . ' ' . $convert->comment_content . '</li>';
										} else {
											?>
                                            <li class="<?php echo $message->user_id == $user_ID ? '' : 'partner-message' ?>"
                                                id="comment-<?php echo $message->comment_ID; ?>">
                                                <span class="message-avatar"><?php echo $message->avatar; ?></span>
												<?php if ( $isAttach ) {
													$file_type = wp_check_filetype( get_attached_file( $message->attachId ) );
													?>
                                                    <div class="message-item message-item-file">
                                                        <p>
                                                            <a href="<?php echo wp_get_attachment_url( $message->attachId ); ?>"
                                                               download>
																<?php
																if ( $convert->file_type == 'png' || $convert->file_type == 'jpg' || $convert->file_type == 'jpeg' || $convert->file_type == 'gif' ) {
																	echo '<i class="fa fa-file-image-o"></i>';
																} else {
																	echo '<i class="fa fa-file-text-o"></i>';
																}
																?>
                                                                <span><?php echo $convert->file_name; ?></span>
                                                                <span><?php echo $convert->file_size; ?></span>
                                                            </a>
                                                        </p>
                                                    </div>
												<?php } else { ?>
                                                    <div class="message-item">
														<?php echo $convert->comment_content; ?>
                                                    </div>
												<?php } ?>
                                            </li>
										<?php }
									}
								}
							} else {
								echo '<li class="message-none">' . __( 'No messages were received during the working process', ET_DOMAIN ) . '</li>';
							} ?>
                        </ul>
                    </div>
                    <div class="conversation-typing-wrap">
						<?php if ( $post->post_status == 'close' && ( $user_ID == $post->post_author || $user_ID == $bid->post_author ) ) { ?>
                            <form class="fre-workspace-form">
								<div class="conversation-submit-btn file_cust">
									<label class="conversation-send-file-btn" for="conversation-send-file">
                                        <div id="upload_file_container">
                                        <span class="et_ajaxnonce"
                                              id="<?php echo wp_create_nonce( 'file_et_uploader' ) ?>"></span>
                                            <span class="project_id" data-project="<?php echo $post->ID ?>"></span>
                                            <span class="author_id" data-author="<?php echo $user_ID ?>"></span>
                                            <a href="#" class="attack attach-file"
                                               id="upload_file_browse_button"><i class="fa fa-paperclip"
                                                                                 aria-hidden="true"></i></a>
                                        </div>
                                    </label>
								</div>
                                <div class="conversation-typing mess_cust">
									<textarea name="comment_content" class="content-chat"
                                              placeholder="<?php _e( 'Your message here...', ET_DOMAIN ); ?>"></textarea>
                                    <input type="hidden" name="comment_post_ID" value="<?php echo $post->ID; ?>"/>
                                </div>
                                <div class="conversation-submit-btn send_cust">

                                    <label class="conversation-send-message-btn disabled"
                                           for="conversation-send-message">
										<?php _e( 'Send', ET_DOMAIN ); ?>
                                        <input id="conversation-send-message" type="submit">
                                    </label>
                                </div>
                            </form>
						<?php } ?>
                        <script type="application/json"
                                class="ae_query"><?php echo json_encode( $query_args ); ?></script>
                    </div>
                </div>
            </div>
		</div>
    </div>
</div>

























<div class="workspace-project-box">
    <div class="row">
        <div class="col-md-8">
            
        </div>
        <div class="col-md-4">
            
        </div>
    </div>
</div>