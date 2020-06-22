<script type="text/template" id="ae-project-loop">
	
	<div class="project-list-wrap">
		<div class="left_proj_cont">
			<h2 class="project-list-title">
				<a  class="secondary-color" href="{{= permalink }}" title="{{= post_title }}">{{= post_title }}</a>
			</h2>
			<span class="posted"><?php _e('Posted', ET_DOMAIN); ?> {{= post_date }}</span>
			<div class="project-list-desc">
				<p>{{= post_content_trim}}</p>
			</div>
			<div class="project-list-info">
				<p><?php _e('Bids:', ET_DOMAIN);?> <span>{{= text_total_bid}}</span></p>
				<p><?php _e('Budget:', ET_DOMAIN);?> <span>{{= budget}}</span></p>
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
					<# if( et_groupwork == 'les' ) { #>
						<?php _e('Less than 40 hrs/week', ET_DOMAIN);?>
					<# } else { #>
						<?php _e('More than 40 hrs/week', ET_DOMAIN);?>
					<# } #>
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

</script>