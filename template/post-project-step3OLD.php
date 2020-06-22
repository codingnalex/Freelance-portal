<?php
    global $user_ID;
    $step = 3;
    $class_active = '';
    $disable_plan = ae_get_option('disable_plan', false);
    if( $disable_plan ) {
        $step--;
        $class_active = 'active';
    }
    if($user_ID) $step--;
    $post = '';
    $current_skills = '';

?>
<div id="fre-post-project-2 step-post" class="fre-post-project-step step-wrapper step-post <?php echo $class_active;?>">
    <?php
    	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        if( $id ) {
            $post = get_post($id);
            if($post) {
                global $ae_post_factory;
                $post_object = $ae_post_factory->get($post->post_type);
                $post_convert = $post_object->convert($post);
                echo '<script type="data/json"  id="edit_postdata">'. json_encode($post_convert) .'</script>';
            }
            //get skills
            $current_skills = get_the_terms( $_REQUEST['id'], 'skill' );
        }

        if( !$disable_plan ) {

            $total_package = ae_user_get_total_package($user_ID);
    ?>
            <div class="fre-post-project-box">
                <div class="step-change-package show_select_package">
                    <p class="package_title"><i class="fa fa-plus primary-color" aria-hidden="true"></i>&nbsp;<?php _e('You are selecting the package:', ET_DOMAIN);?> <strong></strong></p>
                    <p class="package_description pdl-10"></p>
                    <p class="pdl-10"><?php _e('The number of posts included in this package will be added to your total posts after this project is posted.',ET_DOMAIN) ?></p>
                    <br>

                        <?php // printf(__('1The premium package you purchased has <span class="post-number">%s</span> post(s) left', ET_DOMAIN), $total_package); ?>
                    </p>
	                <?php
	                ob_start();
	                ae_user_package_info($user_ID);
	                $package = ob_get_clean();

	                if($package != '') { ?>
                    <p><i class="fa fa-check primary-color" aria-hidden="true"></i>&nbsp;<?php _e('Your purchased package details.',ET_DOMAIN);?></p>
                    <p><?php
		                echo $package;
	                }
	                ?>
                    <a class="fre-btn-o fre-post-project-previous-btn fre-btn-previous primary-color" href="#"><?php _e('Change package', ET_DOMAIN);?></a>
                </div>
                <div class="step-change-package show_had_package" style="display:none;">

	                    <?php //printf(__('2The premium package you purchased has <span class="post-number">%s</span> post(s) left.', ET_DOMAIN), $total_package); ?>
                    </p>
                    <?php

                        if($package != '') { ?>
                          <p><i class="fa fa-check primary-color" aria-hidden="true"></i>&nbsp;<?php _e('Your purchased package details.',ET_DOMAIN);?></p>
                            <p>
                        <?php
                            echo $package;
                        }
                    ?>
                    <p><em><?php _e('You are choosing a package that still available to post or pending so can not buy again. If you want to get more posts, you can directly move on the posting project plan by clicking the next "Add more" button.', ET_DOMAIN);?></em></p>
                    <a class="fre-btn-o fre-post-project-previous-btn fre-btn-previous" href="#"><?php _e('Add more', ET_DOMAIN);?></a>
                </div>
            </div>
    <?php } ?>
	<div class="left_start_project">
		<div id="select_step">
			<ul>
				<li class="active" data-step="step1"><?php _e('Getting Started', ET_DOMAIN);?></li>
				<li class="" data-step="step2"><?php _e('TITLE', ET_DOMAIN);?></li>
				<li class="" data-step="step3"><?php _e('DESCRIPTION', ET_DOMAIN);?></li>
				<li class="" data-step="step4"><?php _e('EXPERTISE', ET_DOMAIN);?></li>
				<li class="" data-step="step5"><?php _e('VISIBILITY', ET_DOMAIN);?></li>
				<li class="" data-step="step6"><?php _e('BUDGET', ET_DOMAIN);?></li>
				<li class="" data-step="step7"><?php _e('REVIEW', ET_DOMAIN);?></li>
			</ul>
		</div>
	</div>
    <div class="fre-post-project-box right_start_project">
        <form class="post" role="form">
			<div class="step-post-project" id="fre-post-project">
				<!--STEP 1-->
				<div class="step1 step_custom active_step">
					<h2><?php _e('Getting Started', ET_DOMAIN);?></h2>
					<p class="subtitle_step"><?php _e('Step 1 of 7', ET_DOMAIN);?></p>
					<div class="content_step">
						<h3><?php _e('What would you like to do?', ET_DOMAIN);?></h3>
						<span class="orang"><?php _e('Create a new job post', ET_DOMAIN);?></span>
						<div class="group_radio_btn">
							<fieldset id="group_work">
								<div class="block_radio" data-radio="les">
									<p><?php _e('Short-term or part-time work', ET_DOMAIN);?></p>
									<span><?php _e('Less than 40 hrs/week Less than 3 months', ET_DOMAIN);?></span>
									<input type="radio" class="les" value="les" name="group_work">
								</div>
								<div class="block_radio" data-radio="more">
									<p><?php _e('Dedicated, long term work', ET_DOMAIN);?></p>
									<span><?php _e('More than 40 hrs/week 3+ months', ET_DOMAIN);?></span>
									<input type="radio" class="more" value="more" name="group_work">
								</div>
								<div class="err_radio" style="display: none;"><?php _e('This select is required.', ET_DOMAIN);?></div>
							</fieldset>
						</div>
						<span class="orang empty"><?php _e('Reuse a previous job post', ET_DOMAIN);?></span>
						<div class="fre-input-field project-title">
							<input class="input-item text-field" id="fre-project-title" placeholder="<?php _e('Project Title Here', ET_DOMAIN);?>" type="text" name="post_title">
						</div>
					</div>
					<div class="button_step">
						<p class="cancel first_btn"><?php _e('CANCEL', ET_DOMAIN);?></p>
						<p class="continue last_btn step_1"><?php _e('CONTINUE', ET_DOMAIN);?></p>
					</div>
				</div>
				<!--STEP 2-->
				<div class="step2 step_custom" style="display: none">
					<h2><?php _e('TITLE', ET_DOMAIN);?></h2>
					<p class="subtitle_step"><?php _e('Step 2 of 7', ET_DOMAIN);?></p>
					<div class="content_step">
						<h3 class="title_input"><?php _e('Enter the name of your job post', ET_DOMAIN);?></h3>
						<div class="fre-input-field project-name">
							<input class="input-item text-field" id="fre-project-name" type="text" name="project_name">
						</div>
						<div class="fre-input-field">
							<label class="fre-field-title" for="project_category"><?php _e('Select a job category', ET_DOMAIN);?></label>
							<span><?php _e("Let's categorize your job, which helps us personalize your job details and match your job to relevant freelancers and agencies.", ET_DOMAIN);?></span>
							<?php
								$cate_arr = array();
								if(!empty($post_convert->tax_input['project_category'])){
									foreach ($post_convert->tax_input['project_category'] as $key => $value) {
										$cate_arr[] = $value->term_id;
									};
								}
								ae_tax_dropdown( 'project_category' ,
								  array(  'attr' => 'data-chosen-width="100%" data-chosen-disable-search="" multiple data-placeholder="'.sprintf(__("Job Category,  maximum %s", ET_DOMAIN), ae_get_option('max_cat', 5)).'"',
										  'class' => 'fre-chosen-category',
										  //'class' => 'fre-chosen-multi',
										  'hide_empty' => false,
										  'hierarchical' => true ,
										  'id' => 'project_category' ,
										  'show_option_all' => false,
										  'selected'        => $cate_arr,
									  )
								);
							?>
						</div>
					</div>
					<div class="button_step">
						<p class="back first_btn to_step1"><?php _e('Back', ET_DOMAIN);?></p>
						<p class="continue last_btn step_2"><?php _e('CONTINUE', ET_DOMAIN);?></p>
					</div>
				</div>
				<!--STEP 3-->
				<div class="step3 step_custom" style="display: none">
					<h2><?php _e('DESCRIPTION', ET_DOMAIN);?></h2>
					<p class="subtitle_step"><?php _e('Step 3 of 7', ET_DOMAIN);?></p>
					<div class="content_step">
						<div class="fre-input-field desc_field">
							<h3 class="title_input desc"><?php _e('A good description includes:', ET_DOMAIN);?></h3>
							<ul class="desc_li">
								<li><?php _e('What the deliverable is', ET_DOMAIN);?></li>
								<li><?php _e("Type of freelancer you're looking for", ET_DOMAIN);?></li>
								<li><?php _e('Anything unique about the project, team, or your company', ET_DOMAIN);?></li>
							</ul>
							<?php wp_editor( '', 'post_content', ae_editor_settings() );  ?>
						</div>
						<div class="fre-input-field" id="gallery_place">
							<label class="fre-field-title" for=""><?php _e('Additional project files (optional)', ET_DOMAIN);?></label>
							<span><?php _e("You may attach up to 5 files under 100 MB each", ET_DOMAIN);?></span>
							<div class="edit-gallery-image" id="gallery_container">
								<ul class="fre-attached-list gallery-image carousel-list" id="image-list"></ul>
								<div  id="carousel_container">
									<a href="javascript:void(0)" style="display: block"
									   class="img-gallery fre-project-upload-file secondary-color" id="carousel_browse_button">
										<?php _e("Drag or upload project files", ET_DOMAIN); ?>
									</a>
									<span class="et_ajaxnonce hidden" id="<?php echo wp_create_nonce( 'ad_carousels_et_uploader' ); ?>"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="button_step">
						<p class="back first_btn to_step2"><?php _e('Back', ET_DOMAIN);?></p>
						<p class="continue last_btn step_3"><?php _e('CONTINUE', ET_DOMAIN);?></p>
					</div>
				</div>
				<!--STEP 4-->
				<div class="step4 step_custom" style="display: none">
					<h2><?php _e('EXPERTISE', ET_DOMAIN);?></h2>
					<p class="subtitle_step"><?php _e('Step 4 of 7', ET_DOMAIN);?></p>
					<div class="content_step">
						<div class="fre-input-field">
							<h3 class="title_input skils"><?php _e('What skills and expertise are most important to you', ET_DOMAIN);?></h3>
							<?php
								$c_skills = array();
								if(!empty($post_convert->tax_input['skill'])){
									foreach ($post_convert->tax_input['skill'] as $key => $value) {
										$c_skills[] = $value->term_id;
									};
								}
								ae_tax_dropdown( 'skill' , array(  'attr' => 'data-chosen-width="100%" data-chosen-disable-search="" multiple data-placeholder="'.sprintf(__("Choose maximum %s skills", ET_DOMAIN), ae_get_option('fre_max_skill', 5)).'"',
													'class' => ' fre-chosen-skill required',
													//'class' => ' fre-chosen-multi required',
													'hide_empty' => false,
													'hierarchical' => true ,
													'id' => 'skill' ,
													'show_option_all' => false,
													'selected' => $c_skills
											)
								);
							?>
						</div>
					</div>
					<div class="button_step">
						<p class="back first_btn to_step3"><?php _e('Back', ET_DOMAIN);?></p>
						<p class="continue last_btn step_4"><?php _e('CONTINUE', ET_DOMAIN);?></p>
					</div>
				</div>
				<!--STEP 5-->
				<div class="step5 step_custom" style="display: none">
					<h2><?php _e('Visibility', ET_DOMAIN);?></h2>
					<p class="subtitle_step"><?php _e('Step 5 of 7', ET_DOMAIN);?></p>
					<div class="content_step">
						<h3><?php _e('Who can see your job?', ET_DOMAIN);?></h3>
						<div class="group_radio_btn">
							<fieldset id="group_see_job">
								<div class="block_radio" data-radio="freelancers">
									<p><?php _e('Anyone', ET_DOMAIN);?></p>
									<span><?php _e('Freelancers using VirtualPM and public search engines can find this job.', ET_DOMAIN);?></span>
									<input type="radio" class="freelancers" value="freelancers" name="group_see_job">
								</div>
								<div class="block_radio" data-radio="virtual">
									<p><?php _e('Virtual PM Users', ET_DOMAIN);?></p>
									<span><?php _e('Only VirtualPM users can find this job.', ET_DOMAIN);?></span>
									<input type="radio" class="virtual" value="virtual" name="group_see_job">
								</div>
								<div class="block_radio" data-radio="invited">
									<p><?php _e('Invite Only', ET_DOMAIN);?></p>
									<span><?php _e('Only freelancers you have invited can find this job.', ET_DOMAIN);?></span>
									<input type="radio" class="invited" value="invited" name="group_see_job">
								</div>
								<div class="err_radio_job" style="display: none;"><?php _e('This select is required.', ET_DOMAIN);?></div>
							</fieldset>
						</div>
						<div class="fre-input-field">
							<span class="b_font"><?php _e('Do you have specific freelancers that you want to invite?', ET_DOMAIN);?></span>
							<?php
							$users = get_users( [
								'meta_key'     => 'zhc_capabilities',
								'meta_value'   => 'a:1:{s:10:"freelancer";b:1;}',
							] );
							?>
							<select id="freelancerSelect" class="fre-chosen-single" name="et_freelancerselect">
								<option value=""></option>
							<?php
							foreach( $users as $user ) {
							?>
								<option value="<?php echo $user->ID; ?>"><?php echo $user->first_name . ' ' . $user->last_name; ?></option>
							<?php } ?>
							</select>
						</div>
					</div>
					<div class="button_step">
						<p class="back first_btn to_step4"><?php _e('Back', ET_DOMAIN);?></p>
						<p class="continue last_btn step_5"><?php _e('CONTINUE', ET_DOMAIN);?></p>
					</div>
				</div>
				<!--STEP 6-->
				<div class="step6 step_custom" style="display: none">
					<h2><?php _e('BUDGET', ET_DOMAIN);?></h2>
					<p class="subtitle_step"><?php _e('Step 6 of 7', ET_DOMAIN);?></p>
					<div class="content_step">
						<div class="fre-input-field">
							<h3><?php _e('What is your estimated budget? (hourly)', ET_DOMAIN);?></h3>
							<div class="fre-project-budget">
								<input id="project-budget" step="5" required type="number" class="input-item text-field is_number numberVal" placeholder="<?php echo fre_currency_sign(false);?>" name="et_budget" min="1">
							</div>
							<div class="limit_hours">
								<p class="active_limit_p"><input type="checkbox" id="active_limit" name="active_limit"><?php _e('Limit hours logged to', ET_DOMAIN);?></p>
								<p class="hours_limit_p"><input type="text" id="hours_limit" name="hours_limit" disabled><?php _e('hrs/week', ET_DOMAIN);?></p>
								<span class="info_limit"></span>
								<p class="info_limit_text" style="display: none"><?php _e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', ET_DOMAIN);?></p>
							</div>
						</div>
					</div>
					<div class="button_step">
						<p class="back first_btn to_step5"><?php _e('Back', ET_DOMAIN);?></p>
						<p class="continue last_btn step_6"><?php _e('CONTINUE', ET_DOMAIN);?></p>
					</div>
				</div>
				<!--STEP 7-->
				<div class="step7 step_custom" style="display: none">
					<h2><?php _e('Review and post', ET_DOMAIN);?></h2>
					<p class="subtitle_step"><?php _e('Step 7 of 7', ET_DOMAIN);?></p>
					<div class="button_step top">
						<button class="last_btn fre-btn fre-post-project-next-btn primary-bg-color" type="submit"><?php _e("POST JOB NOW", ET_DOMAIN); ?></button>
					</div>
					<div class="content_step">
						<div class="block_step1_info block_step_info">
							<span class="back_to_edit_1"></span>
							<h2><?php _e('Title', ET_DOMAIN);?></h2>
							<h3><?php _e('Title', ET_DOMAIN);?></h3>
							<p class="title_pr">Selected choice will show here</p>
							<h3><?php _e('Job Category', ET_DOMAIN);?></h3>
							<p class="cat_pr">Selected choice will show here</p>
						</div>
						<div class="block_step2_info block_step_info">
							<span class="back_to_edit_2"></span>
							<h2><?php _e('Description', ET_DOMAIN);?></h2>
							<h3><?php _e('Description', ET_DOMAIN);?></h3>
							<p class="desc_pr">Selected choice will show here</p>
						</div>
						<div class="block_step3_info block_step_info">
							<span class="back_to_edit_3"></span>
							<h2><?php _e('Expertise', ET_DOMAIN);?></h2>
							<p class="scils_pr">Selected choice will show here</p>
						</div>
						<div class="block_step4_info block_step_info">
							<span class="back_to_edit_4"></span>
							<h2><?php _e('Visibility', ET_DOMAIN);?></h2>
							<h3><?php _e('Job Posting Visibility', ET_DOMAIN);?></h3>
							<p class="visibl_pr">Selected choice will show here</p>
							<h3><?php _e('Freelancers to Invite', ET_DOMAIN);?></h3>
							<p class="selfree_pr">Selected choice will show here</p>
						</div>
						<div class="block_step5_info block_step_info">
							<span class="back_to_edit_5"></span>
							<h2><?php _e('BUDGET', ET_DOMAIN);?></h2>
							<h3><?php _e('Estimated Hourly Budget', ET_DOMAIN);?></h3>
							<p class="budget_pr">Selected choice will show here</p>
							<h3><?php _e('Limit Weekly Hours', ET_DOMAIN);?></h3>
							<p class="limit_pr">Selected choice will show here</p>
						</div>
					</div>
					<div class="button_step">
						<button class="last_btn fre-btn fre-post-project-next-btn primary-bg-color" type="submit"><?php _e("POST JOB NOW", ET_DOMAIN); ?></button>
						<p class="save_exit"><?php _e('SAVE & EXIT', ET_DOMAIN);?></p>
					</div>
				</div>
				<!--END STEP-->
				
				
                <h2><?php _e('Your Project Details', ET_DOMAIN);?></h2>
                <div class="fre-input-field">
                    <label class="fre-field-title" for="project_category"><?php _e('What categories do your project work in?', ET_DOMAIN);?></label>
                    <?php
                        $cate_arr = array();
                        if(!empty($post_convert->tax_input['project_category'])){
                            foreach ($post_convert->tax_input['project_category'] as $key => $value) {
                                $cate_arr[] = $value->term_id;
                            };
                        }
                        ae_tax_dropdown( 'project_category' ,
                          array(  'attr' => 'data-chosen-width="100%" data-chosen-disable-search="" multiple data-placeholder="'.sprintf(__("Choose maximum %s categories", ET_DOMAIN), ae_get_option('max_cat', 5)).'"',
                                  'class' => 'fre-chosen-category',
                                  //'class' => 'fre-chosen-multi',
                                  'hide_empty' => false,
                                  'hierarchical' => true ,
                                  'id' => 'project_category' ,
                                  'show_option_all' => false,
                                  'selected'        => $cate_arr,
                              )
                        );
                    ?>
                </div>
                <div class="fre-input-field">
                    <label class="fre-field-title" for="fre-project-title"><?php _e('Your project title', ET_DOMAIN);?></label>
                    <input class="input-item text-field" id="fre-project-title" type="text" name="post_title">
                </div>
                <div class="fre-input-field">
                    <label class="fre-field-title" for="fre-project-describe"><?php _e('Describe what you need done', ET_DOMAIN);?></label>
                    <?php wp_editor( '', 'post_content', ae_editor_settings() );  ?>
                </div>
                <div class="fre-input-field" id="gallery_place">
                    <label class="fre-field-title" for=""><?php _e('Attachments (optional)', ET_DOMAIN);?></label>
                    <div class="edit-gallery-image" id="gallery_container">
                        <ul class="fre-attached-list gallery-image carousel-list" id="image-list"></ul>
                        <div  id="carousel_container">
                            <a href="javascript:void(0)" style="display: block"
                               class="img-gallery fre-project-upload-file secondary-color" id="carousel_browse_button">
                                <?php _e("Upload Files", ET_DOMAIN); ?>
                            </a>
                            <span class="et_ajaxnonce hidden" id="<?php echo wp_create_nonce( 'ad_carousels_et_uploader' ); ?>"></span>
                        </div>
                        <p class="fre-allow-upload"><?php _e('(Upload maximum 5 files with extensions including png, jpg, pdf, xls, and doc format)', ET_DOMAIN);?></p>
                    </div>
                </div>
                <div class="fre-input-field">
                    <label class="fre-field-title" for="skill"><?php _e('What skills do you require?', ET_DOMAIN);?></label>
                    <?php
                        $c_skills = array();
                        if(!empty($post_convert->tax_input['skill'])){
                            foreach ($post_convert->tax_input['skill'] as $key => $value) {
                                $c_skills[] = $value->term_id;
                            };
                        }
                        ae_tax_dropdown( 'skill' , array(  'attr' => 'data-chosen-width="100%" data-chosen-disable-search="" multiple data-placeholder="'.sprintf(__("Choose maximum %s skills", ET_DOMAIN), ae_get_option('fre_max_skill', 5)).'"',
                                            'class' => ' fre-chosen-skill required',
                                            //'class' => ' fre-chosen-multi required',
                                            'hide_empty' => false,
                                            'hierarchical' => true ,
                                            'id' => 'skill' ,
                                            'show_option_all' => false,
                                            'selected' => $c_skills
                                    )
                        );
                    ?>
                </div>
				<div class="fre-input-field">
                    <label class="fre-field-title" for="project-budget"><?php _e('What is your estimated budget (Hourly)', ET_DOMAIN);?></label>
                    <div class="fre-project-budget">
                        <input id="project-budget" step="5" required type="number" class="input-item text-field is_number numberVal" name="et_budget" min="1">
                        <span><?php echo fre_currency_sign(false);?></span>
                    </div>
                </div>
				<div class="fre-input-field">
                    <label class="fre-field-title" for="project-freelancerselect"><?php _e('Select Freelancer', ET_DOMAIN);?></label>
					<?php
					$users = get_users( [
						'meta_key'     => 'zhc_capabilities',
						'meta_value'   => 'a:1:{s:10:"freelancer";b:1;}',
					] );
					?>
					<select id="freelancerSelect" class="fre-chosen-single" name="et_freelancerselect">
						<option value=""></option>
					<?php
					foreach( $users as $user ) {
					?>
						<option value="<?php echo $user->ID; ?>"><?php echo $user->first_name . ' ' . $user->last_name; ?></option>
					<?php } ?>
					</select>
                </div>
                <div class="fre-input-field">
                    <label class="fre-field-title" for="project-location"><?php _e('Location (optional)', ET_DOMAIN);?></label>
                    <?php
                        ae_tax_dropdown( 'country' ,array(
                                'attr'            => 'data-chosen-width="100%" data-chosen-disable-search="" data-placeholder="'.__("Choose country", ET_DOMAIN).'"',
                                'class'           => 'fre-chosen-single',
                                'hide_empty'      => false,
                                'hierarchical'    => true ,
                                'id'              => 'country',
                                'show_option_all' => __("Choose country", ET_DOMAIN)
                            )
                        );
                    ?>
                </div>
                <?php
                    // Add hook: add more field
                    echo '<ul class="fre-custom-field">';
                    do_action( 'ae_submit_post_form', PROJECT, $post );
                    echo '</ul>';
                ?>
                <div class="fre-post-project-btn">
                    <button class="fre-btn fre-post-project-next-btn primary-bg-color" type="submit"><?php _e("Submit Project", ET_DOMAIN); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Step 3 / End -->
