<?php
global $wp_query, $ae_post_factory, $post, $user_ID;
$post_object = $ae_post_factory->get( PROJECT );
$convert     = $project = $post_object->current_post;
$project     = $post_object->convert( $post );
$author_id   = $project->post_author;
$rating      = Fre_Review::employer_rating_score( $author_id );

$user_data = get_userdata( $author_id );

$profile_id = get_user_meta( $author_id, 'user_profile_id', true );
$profile    = array();
if ( $profile_id ) {
	$profile_post = get_post( $profile_id );
	if ( $profile_post && ! is_wp_error( $profile_post ) ) {
		$profile = $post_object->convert( $profile_post );
	}
}

$hire_freelancer = fre_count_hire_freelancer( $author_id );

$attachment = get_children( array(
	'numberposts' => - 1,
	'order'       => 'ASC',
	'post_parent' => $post->ID,
	'post_type'   => 'attachment'
), OBJECT );

?>

