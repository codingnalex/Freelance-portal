jQuery(function () {
	var height = jQuery(".left_box").height();
	jQuery(".right_box .right_progect").css('height', height);
});
/*-------TABS----------*/
jQuery(".tabs_workspace .tab").click(function() {
	jQuery(".tabs_workspace .tab").removeClass("active").eq(jQuery(this).index()).addClass("active");
	jQuery(".tab_item").hide().eq(jQuery(this).index()).fadeIn()
}).eq(0).addClass("active");
/*--------CONTINUE---------*/
jQuery(function () {
	jQuery("#continue button").on("click", function (e) {
		e.preventDefault();
		jQuery.ajax({
			url: "/wp-admin/admin-ajax.php",
			method: 'post',
			data: {
				action: 'ajax_continueform',
			},
			success: function (response) {
				jQuery('.submit-completed').html(response);
				if(jQuery('.submit-completed').html() === "Done") {
					window.location.href = '/profile-3/';
				}
			}
		});
	});
});

/*--------NOT FREELANCER---------*/
jQuery(function () {
	jQuery("#not_freelancer button").on("click", function (e) {
		e.preventDefault();
		jQuery.ajax({
			url: "/wp-admin/admin-ajax.php",
			method: 'post',
			data: {
				action: 'ajax_notfreelancer',
			},
			success: function (response) {
				jQuery('.submit-completed').html(response);
				if(jQuery('.submit-completed').html() === "Done") {
					window.location.href = '/profile-3/';
				}
			}
		});
	});
});

/*-------SELECT PROJECT---------*/
jQuery(document).ready(function () {
	jQuery('#fre-project-name').change(function () {
		var prtitle = jQuery('option:selected',this).data('prtitle');
		var activelimit = jQuery('option:selected',this).data('activelimit');
		var budget = jQuery('option:selected',this).data('budget');
		var groupseejob = jQuery('option:selected',this).data('groupseejob');
		var freelancerselect = jQuery('option:selected',this).data('freelancerselect');
		var groupwork = jQuery('option:selected',this).data('groupwork');
		var content = jQuery('option:selected',this).data('content');
		
		jQuery('#et_groupwork input').each(function() {
			var group_work = jQuery(this).val();
			if(group_work === groupwork) {
			   jQuery(this).attr('checked', 'checked');
			}
		});
		
		jQuery('#et_groupwork .block_radio').each(function () {
			if(jQuery(this).data('radio') === groupwork ) {
				jQuery(this).addClass('active_check');
			}
		});
		
		jQuery('#fre-project-title').val(prtitle);
		
		tinyMCE.activeEditor.setContent(content);
		
		jQuery('#post_content').val(content);
		
		jQuery('#et_group_see_job input').each(function() {
			var see_job = jQuery(this).val();
			if(see_job === groupseejob) {
			   jQuery(this).attr('checked', 'checked');
			}
		});
		
		jQuery('#et_group_see_job .block_radio').each(function () {
			if(jQuery(this).data('radio') === groupseejob ) {
				jQuery(this).addClass('active_check');
			}
		});
		
		jQuery('#project-budget').val(budget);
		
		if(activelimit) {
			jQuery('#active_limit').attr('checked', 'checked');
			jQuery('#hours_limit').val(activelimit);
		}
		
		if(jQuery('#active_limit').is(':checked')) {
			jQuery('.active_limit_p').addClass('limit_check');
			jQuery('#hours_limit').prop('disabled', false);
		}
		
		//jQuery("#freelancerSelect option").each(function(){
//			if (jQuery(this).val() === freelancerselect) {
//				jQuery(this).prop('selected', true);
//			}
//		});
		
		//jQuery("#freelancerSelect option[value='"+freelancerselect+"']").prop('selected', true);
		
		
	});
});
/*----------------------*/
jQuery(document).ready(function () {
	var data = jQuery(".project-detail-action .fre-normal-btn.primary-bg-color").data('toggle');
	if(data == 'modal') {
		jQuery(".project-detail-action .fre-normal-btn.primary-bg-color").html('Submit a Bid');
	}
});
/*--------REGISTER COMPLE---------*/
jQuery(function () {
	jQuery("#register_completed_form .last_btn").on("click", function (e) {
		e.preventDefault();
		var first_name_user = jQuery("#first_name_user").val();
		var last_name_user = jQuery("#last_name_user").val();
		var pass_user = jQuery("#pass_user").val();
		var location_user = jQuery("#location_user").val();
		var username_user = jQuery("#username_user").val();
		var user_role = jQuery("#user_role").val();
		var user_email = jQuery("#user_email").val();
		var user_ipadr = jQuery("#user_ipadr").val();
		if (first_name_user === '') {
			jQuery("#first_name_user").addClass('required');
		} else if (last_name_user === '') {
			jQuery("#last_name_user").addClass('required');
		} else if (pass_user === '') {
			jQuery("#pass_user").addClass('required');
		} else {
			jQuery.ajax({
				url: "/wp-admin/admin-ajax.php",
				method: 'post',
				data: {
					action: 'ajax_complform',
					first_name_user: first_name_user,
					last_name_user: last_name_user,
					pass_user: pass_user,
					location_user: location_user,
					username_user: username_user,
					user_role: user_role,
					user_email: user_email,
					user_ipadr: user_ipadr,
				},
				success: function (response) {
					jQuery('#submit-completed').html(response);
					if(jQuery('#submit-completed').html() === "Done") {
						window.location.href = '/profile-3/';
					}
				}
			});
		}
	});
});

jQuery(document).ready(function () {
	jQuery('#send_me').click(function(){
		if(jQuery('#send_me').is(':checked')) {
			jQuery(".sendme").addClass('active');
		} else {
			jQuery(".sendme").removeClass('active');
		}
	});
});

jQuery(document).ready(function () {
	jQuery('#privacy').click(function(){
		if(jQuery('#privacy').is(':checked')) {
			jQuery(".privacy").addClass('active');
		} else {
			jQuery(".privacy").removeClass('active');
		}
	});
});
	
jQuery(document).ready(function () {
	jQuery('.select_role .free').click(function(e){
		jQuery(".username").show();
		jQuery("#user_role").val('freelancer');
	});
	jQuery('.select_role .emp').click(function(e){
		jQuery(".username").hide();
		jQuery("#user_role").val('employer');
	});
});

jQuery(document).ready(function () {
	jQuery('.select_role p').click(function(e){
		e.preventDefault();
		jQuery(".select_role p").removeClass('active');
		jQuery(this).addClass('active');
	});
});

/*--------REGISTER EMAIL---------*/
jQuery(function () {
	jQuery("#register_email_form .last_btn").on("click", function (e) {
		e.preventDefault();
		var email = jQuery("#email_user").val();
		if (email === '') {
			jQuery("#email_user").addClass('required');
		} else {
			jQuery.ajax({
				url: "/wp-admin/admin-ajax.php",
				method: 'post',
				data: {
					action: 'ajax_emailform',
					email: email,
				},
				success: function (response) {
					jQuery('#submit-email').html(response);
					if(jQuery('#submit-email').html() === "Done") {
						jQuery('.fre-authen-social').hide();
						jQuery('#verif_block').show();
						jQuery('.page-template-page-register-social .footer-wrapper.fixed-bottom').css('position', 'relative');
						jQuery('.first_line span').html(email);
					}
				}
			});
		}
	});
});
/*--------CAHNGE EMAIL---------*/
jQuery(function () {
	jQuery("#change_email_form .last_btn").on("click", function (e) {
		e.preventDefault();
		var email = jQuery("#change_email_user").val();
		if (email === '') {
			jQuery("#change_email_user").addClass('required');
		} else {
			jQuery.ajax({
				url: "/wp-admin/admin-ajax.php",
				method: 'post',
				data: {
					action: 'ajax_changeemailform',
					email: email,
				},
				success: function (response) {
					jQuery('#change_email').html(response);
				}
			});
		}
	});
});
/*------------STEP PROJECT-------------*/
//jQuery(document).ready(function () {
//	jQuery("#select_step ul li").click(function(e) {
//		var val = jQuery(this).data('step');
//		e.preventDefault();
//		jQuery("#select_step ul li").removeClass('active');
//		jQuery(this).addClass('active');
//		
//		jQuery('#fre-post-project .step_custom').each(function () {
//			if(jQuery(this).hasClass(val)) {
//				jQuery(this).addClass('active_step');
//				jQuery(this).show();
//			} else {
//				jQuery(this).removeClass('active_step');
//				jQuery(this).hide();
//			}
//		});
//	});
//});

jQuery(document).ready(function () {
	jQuery(".button_step .cancel").click(function() {
		window.location.href = '/';
	});
});

jQuery('#et_groupwork input').change(function(){
	var name = jQuery(this).val();
	jQuery('#et_groupwork .block_radio').each(function () {
		if(jQuery(this).data('radio') === name ) {
			jQuery(this).addClass('active_check');
		} else {
			jQuery(this).removeClass('active_check');
		}
	});
});
/*--------STEP 1-------------*/
jQuery(document).ready(function () {
	jQuery('.step_1').click(function(){
		if(!jQuery('#et_groupwork input').is(':checked')) {
			jQuery('.err_radio').show();
			jQuery('#et_groupwork input').change(function(){
				jQuery('.err_radio').hide();
			});
			console.log('stop');
		//} else if(!jQuery('#fre-project-title').val()) {
			//jQuery('#fre-project-title').css('border', 'solid 1px #f44336');
			//jQuery('#fre-project-title').change(function(){
				//jQuery('#fre-project-title').css('border', 'solid 2px #c8c8c8');
			//});
			//console.log('stop');
		} else {
			jQuery('#fre-post-project .step_custom.step1').removeClass('active_step');
			jQuery('#fre-post-project .step_custom.step1').hide();
			jQuery('#fre-post-project .step_custom.step2').addClass('active_step');
			jQuery('#fre-post-project .step_custom.step2').show();
			
			jQuery('#select_step ul li').each(function () {
				if(jQuery(this).data('step') === 'step1' ) {
					jQuery(this).addClass('finish_step');
					jQuery(this).removeClass('active');
				} else if(jQuery(this).data('step') === 'step2' ) {
					jQuery(this).addClass('active');
				}
			});
			console.log('start');
		}
	});
});
/*--------STEP 2-------------*/
jQuery(document).ready(function () {
	jQuery('.to_step1').click(function(){
		jQuery('#fre-post-project .step_custom.step2').hide();
		jQuery('#fre-post-project .step_custom.step2').removeClass('active_step');
		jQuery('#fre-post-project .step_custom.step1').show();
		jQuery('#fre-post-project .step_custom.step1').addClass('active_step');
		
		jQuery('#select_step ul li').each(function () {
			if(jQuery(this).data('step') === 'step1' ) {
				jQuery(this).addClass('active');
			} else if(jQuery(this).data('step') === 'step2' ) {
				jQuery(this).removeClass('active');
			}
		});
	});
});

jQuery(document).ready(function () {
	jQuery('.step_2').click(function(){
		if(!jQuery('#fre-project-title').val()) {
			jQuery('#fre-project-title').css('border', 'solid 1px #f44336');
			jQuery('#fre-project-title').change(function(){
				jQuery('#fre-project-title').css('border', 'solid 2px #c8c8c8');
			});
			console.log('stop');
		} else if(!jQuery('#project_category').val()) {
			jQuery('.chosen-container-multi .chosen-choices').css('border', 'solid 1px #f44336');
			jQuery('#project_category').change(function(){
				jQuery('.chosen-container-multi .chosen-choices').css('border', 'solid 2px #c8c8c8');
			});
			console.log('stop');
		} else {
			jQuery('#fre-post-project .step_custom.step2').removeClass('active_step');
			jQuery('#fre-post-project .step_custom.step2').hide();
			jQuery('#fre-post-project .step_custom.step3').addClass('active_step');
			jQuery('#fre-post-project .step_custom.step3').show();
			
			jQuery('#select_step ul li').each(function () {
				if(jQuery(this).data('step') === 'step2' ) {
					jQuery(this).addClass('finish_step');
					jQuery(this).removeClass('active');
				} else if(jQuery(this).data('step') === 'step3' ) {
					jQuery(this).addClass('active');
				}
			});
			console.log('start');
		}
	});
});
/*--------STEP 3-------------*/
jQuery(document).ready(function () {
	jQuery('.to_step2').click(function(){
		jQuery('#fre-post-project .step_custom.step3').hide();
		jQuery('#fre-post-project .step_custom.step3').removeClass('active_step');
		jQuery('#fre-post-project .step_custom.step2').show();
		jQuery('#fre-post-project .step_custom.step2').addClass('active_step');
		
		jQuery('#select_step ul li').each(function () {
			if(jQuery(this).data('step') === 'step2' ) {
				jQuery(this).addClass('active');
			} else if(jQuery(this).data('step') === 'step3' ) {
				jQuery(this).removeClass('active');
			}
		});
	});
});

jQuery(document).ready(function () {
	jQuery('.step_3').click(function(){
		if(!jQuery('#post_content').val()) {
			jQuery('.content_step .wp-editor-container').css('border', 'solid 1px #f44336');
			jQuery('#post_content').change(function(){
				jQuery('.content_step .wp-editor-container').css('border', 'solid 2px #c8c8c8');
			});
			console.log('stop');
		} else {
			jQuery('#fre-post-project .step_custom.step3').removeClass('active_step');
			jQuery('#fre-post-project .step_custom.step3').hide();
			jQuery('#fre-post-project .step_custom.step4').addClass('active_step');
			jQuery('#fre-post-project .step_custom.step4').show();
			
			jQuery('#select_step ul li').each(function () {
				if(jQuery(this).data('step') === 'step3' ) {
					jQuery(this).addClass('finish_step');
					jQuery(this).removeClass('active');
				} else if(jQuery(this).data('step') === 'step4' ) {
					jQuery(this).addClass('active');
				}
			});
			console.log('start');
		}
	});
});
/*--------STEP 4-------------*/
jQuery(document).ready(function () {
	jQuery('.to_step3').click(function(){
		jQuery('#fre-post-project .step_custom.step4').hide();
		jQuery('#fre-post-project .step_custom.step4').removeClass('active_step');
		jQuery('#fre-post-project .step_custom.step3').show();
		jQuery('#fre-post-project .step_custom.step3').addClass('active_step');
		
		jQuery('#select_step ul li').each(function () {
			if(jQuery(this).data('step') === 'step3' ) {
				jQuery(this).addClass('active');
			} else if(jQuery(this).data('step') === 'step4' ) {
				jQuery(this).removeClass('active');
			}
		});
	});
});

jQuery(document).ready(function () {
	jQuery('.step_4').click(function(){
		if(!jQuery('#skill').val()) {
			jQuery('.fre-chosen-skill .chosen-choices').css('border', 'solid 1px #f44336');
			jQuery('#skill').change(function(){
				jQuery('.fre-chosen-skilli .chosen-choices').css('border', 'solid 2px #c8c8c8');
			});
			console.log('stop');
		} else {
			jQuery('#fre-post-project .step_custom.step4').removeClass('active_step');
			jQuery('#fre-post-project .step_custom.step4').hide();
			jQuery('#fre-post-project .step_custom.step5').addClass('active_step');
			jQuery('#fre-post-project .step_custom.step5').show();
			
			jQuery('#select_step ul li').each(function () {
				if(jQuery(this).data('step') === 'step4' ) {
					jQuery(this).addClass('finish_step');
					jQuery(this).removeClass('active');
				} else if(jQuery(this).data('step') === 'step5' ) {
					jQuery(this).addClass('active');
				}
			});
			console.log('start');
		}
	});
});
/*--------STEP 5-------------*/
jQuery(document).ready(function () {
	jQuery('.to_step4').click(function(){
		jQuery('#fre-post-project .step_custom.step5').hide();
		jQuery('#fre-post-project .step_custom.step5').removeClass('active_step');
		jQuery('#fre-post-project .step_custom.step4').show();
		jQuery('#fre-post-project .step_custom.step4').addClass('active_step');
		
		jQuery('#select_step ul li').each(function () {
			if(jQuery(this).data('step') === 'step4' ) {
				jQuery(this).addClass('active');
			} else if(jQuery(this).data('step') === 'step5' ) {
				jQuery(this).removeClass('active');
			}
		});
	});
});

jQuery(document).ready(function () {
	jQuery('.step5 .chosen-single.chosen-default span').html('Select Freelancers');
});

jQuery('#et_group_see_job input').change(function(){
	var name = jQuery(this).val();
	jQuery('#et_group_see_job .block_radio').each(function () {
		if(jQuery(this).data('radio') === name ) {
			jQuery(this).addClass('active_check');
		} else {
			jQuery(this).removeClass('active_check');
		}
	});
});

jQuery(document).ready(function () {
	jQuery('.step_5').click(function(){
		if(!jQuery('#et_group_see_job input').is(':checked')) {
			jQuery('.err_radio_job').show();
			jQuery('#et_group_see_job input').change(function(){
				jQuery('.err_radio_job').hide();
			});
			console.log('stop');
		//} else if(!jQuery('#freelancerSelect').val()) {
//			jQuery('.step5 .fre-chosen-single').css('border', 'solid 1px #f44336');
//			jQuery('#freelancerSelect').change(function(){
//				jQuery('.step5 .fre-chosen-single').css('border', 'solid 2px #c8c8c8');
//			});
//			console.log('stop');
		} else {
			jQuery('#fre-post-project .step_custom.step5').removeClass('active_step');
			jQuery('#fre-post-project .step_custom.step5').hide();
			jQuery('#fre-post-project .step_custom.step6').addClass('active_step');
			jQuery('#fre-post-project .step_custom.step6').show();
			
			jQuery('#select_step ul li').each(function () {
				if(jQuery(this).data('step') === 'step5' ) {
					jQuery(this).addClass('finish_step');
					jQuery(this).removeClass('active');
				} else if(jQuery(this).data('step') === 'step6' ) {
					jQuery(this).addClass('active');
				}
			});
			console.log('start');
		}
	});
});
/*--------STEP 5-------------*/
jQuery(document).ready(function () {
	jQuery('.to_step5').click(function(){
		jQuery('#fre-post-project .step_custom.step6').hide();
		jQuery('#fre-post-project .step_custom.step6').removeClass('active_step');
		jQuery('#fre-post-project .step_custom.step5').show();
		jQuery('#fre-post-project .step_custom.step5').addClass('active_step');
		
		jQuery('#select_step ul li').each(function () {
			if(jQuery(this).data('step') === 'step5' ) {
				jQuery(this).addClass('active');
			} else if(jQuery(this).data('step') === 'step6' ) {
				jQuery(this).removeClass('active');
			}
		});
	});
});

jQuery(document).ready(function () {
	jQuery('#active_limit').click(function(){
		if(jQuery('#active_limit').is(':checked')) {
			jQuery('.active_limit_p').addClass('limit_check');
			jQuery('#hours_limit').prop('disabled', false);
		} else {
			jQuery('.active_limit_p').removeClass('limit_check');
			jQuery('#hours_limit').prop('disabled', true);
			jQuery('#hours_limit').val('');
		}
	});
});
jQuery(document).ready(function () {
	jQuery('.info_limit').click(function(){
		jQuery('.info_limit_text').addClass('show_text');
	});
	jQuery('.info_limit').hover(
	  function() {
		jQuery('.info_limit_text').show();
	  }, function() {
		jQuery('.info_limit_text').hide();
	  }
	);
});

jQuery(document).ready(function () {
	jQuery('.step_6').click(function(){
		if(!jQuery('#project-budget').val()) {
			jQuery('#project-budget').css('border', 'solid 1px #f44336');
			jQuery('#project-budget').change(function(){
				jQuery('#project-budget').css('border', 'solid 2px #c8c8c8');
			});
			console.log('stop');
		} else {
			jQuery('#fre-post-project .step_custom.step6').removeClass('active_step');
			jQuery('#fre-post-project .step_custom.step6').hide();
			jQuery('#fre-post-project .step_custom.step7').addClass('active_step');
			jQuery('#fre-post-project .step_custom.step7').show();
			
			jQuery('#select_step ul li').each(function () {
				if(jQuery(this).data('step') === 'step6' ) {
					jQuery(this).addClass('finish_step');
					jQuery(this).removeClass('active');
				} else if(jQuery(this).data('step') === 'step7' ) {
					jQuery(this).addClass('active');
				}
			});
			console.log('start');
		}
	});
});
/*--------STEP 6-------------*/
jQuery(document).ready(function () {
	jQuery('.step_6').click(function(){
		var title = jQuery('#fre-project-title').val();
		var cat = jQuery('#project_category_chosen .chosen-choices').html();
		var desc = jQuery('#post_content').val();
		var skill = jQuery('#skill_chosen .chosen-choices').html();
		var visible = jQuery('#et_group_see_job input:checked').val();
		var freelance = jQuery('#freelancerSelect_chosen .chosen-single span').html();
		var budget = jQuery('#project-budget').val();
		var limit = jQuery('#hours_limit').val();
		
		jQuery('.title_pr').html(title);
		jQuery('.cat_pr').html(cat);
		jQuery('.desc_pr').html(desc);
		jQuery('.scils_pr').html(skill);
		jQuery('.visibl_pr').html(visible);
		jQuery('.selfree_pr').html(freelance);
		jQuery('.budget_pr').html(budget);
		jQuery('.limit_pr').html(limit);
	});
});
/*--------EDIT--------*/
jQuery(document).ready(function () {
	jQuery('.back_to_edit_1').click(function(){
		jQuery('#fre-post-project .step_custom.step7').hide();
		jQuery('#fre-post-project .step_custom.step2').show();
	});
});
jQuery(document).ready(function () {
	jQuery('.back_to_edit_2').click(function(){
		jQuery('#fre-post-project .step_custom.step7').hide();
		jQuery('#fre-post-project .step_custom.step3').show();
	});
});
jQuery(document).ready(function () {
	jQuery('.back_to_edit_3').click(function(){
		jQuery('#fre-post-project .step_custom.step7').hide();
		jQuery('#fre-post-project .step_custom.step4').show();
	});
});
jQuery(document).ready(function () {
	jQuery('.back_to_edit_4').click(function(){
		jQuery('#fre-post-project .step_custom.step7').hide();
		jQuery('#fre-post-project .step_custom.step5').show();
	});
});
jQuery(document).ready(function () {
	jQuery('.back_to_edit_5').click(function(){
		jQuery('#fre-post-project .step_custom.step7').hide();
		jQuery('#fre-post-project .step_custom.step6').show();
	});
});
/*----------END STEP----------*/


/*------------------------*/
jQuery(document).ready(function () {
	jQuery('.fre-select-package-btn .fre-post-project-next-btn').click(function () {
		jQuery(".footer-wrapper").removeClass("fixed-bottom");
	});
	
	jQuery('.page-template-page-my-project .fre-tabs .next').click(function () {
		jQuery(".footer-wrapper").removeClass("fixed-bottom");
	});
	jQuery('.page-template-page-my-project .fre-tabs li:first-child()').click(function () {
		jQuery(".footer-wrapper").addClass("fixed-bottom");
	});
});
/*--------------*/

jQuery(document).ready(function(){
	jQuery('.profile-video-edit').click(function () {
		jQuery('#ctn-edit-video').slideToggle();
		jQuery('.video_present').slideToggle();
	});
	jQuery('.profile-video-cancel').click(function () {
		jQuery('#ctn-edit-video').slideToggle();
		jQuery('.video_present').slideToggle();
	});
});
/*--------REMOVE VIDEO+ INPUT-------------*/
jQuery( document ).ready(function() { 
var ajaxurl = '/wp-admin/admin-ajax.php';
  jQuery('.profile-video-remove').click(function(event) {
    event.preventDefault();
    jQuery.ajax({
      url: ajaxurl + "?action=remove_video_form",
      type: 'post',
      success: function(data) {
		location.reload();
      },
      error: function(data) {
        alert("FAILURE!");
      }
    });
  });
});
/*--------ADD VIDEO+ INPUT-------------*/
jQuery(document).ready(function(){
	var form = "#video_form";
	jQuery(form).submit(function(event) {
	event.preventDefault();
	var ajaxurl = '/wp-admin/admin-ajax.php';
	var formData = new FormData();
	formData.append('title_video', jQuery("#title_video").val());
	formData.append('desc_video', jQuery("#desc_video").val());
	formData.append('file_video', jQuery("#file_video")[0].files[0]);
	formData.append('action', "videofreelance");
		jQuery.ajax({
		url: ajaxurl,
		type: "POST",
		data:formData,cache: false,
		processData: false,
		contentType: false,
			success:function(data) {
				location.reload();
			},
			error: function(data) {
			    alert("FAILURE!");
			}
		});
	});
});
/*--------ADD VIDEO BLOCK OVERLAY-------------*/
jQuery(document).ready(function(){
	jQuery('#video_form .fre-btn').click(function () {
		jQuery('#blockMyVideo .spinner-border').show();
		jQuery('#blockMyVideo .overlay').show();
	});
});
/*--------ADD FREELANCERS MULTIPLE-------------*/
jQuery(document).ready(function(){
	var form = "#selfree_form";
	jQuery(form).submit(function(event) {
	event.preventDefault();
	var ajaxurl = '/wp-admin/admin-ajax.php';
	var formData = new FormData();
	formData.append('id_progect', jQuery("#id_progect").val());
	formData.append('freelancerselectmultiple', jQuery("#freelancerSelectmultiple").val());
	formData.append('action', "multiplefreelancers");
		jQuery.ajax({
		url: ajaxurl,
		type: "POST",
		data:formData,cache: false,
		processData: false,
		contentType: false,
			success:function(data) {
				location.reload();
			},
			error: function(data) {
			    alert("FAILURE!");
			}
		});
	});
});
/*--------ADD Elapsed time-------------*/
jQuery(document).ready(function(){
	var form = "#add_time";
	jQuery(form).submit(function(event) {
	event.preventDefault();
	var ajaxurl = '/wp-admin/admin-ajax.php';
	var formData = new FormData();
	formData.append('id_project', jQuery("#id_project").val());
	formData.append('time_project', jQuery("#time_project").val());
	formData.append('action', "addtime");
		jQuery.ajax({
		url: ajaxurl,
		type: "POST",
		data:formData,cache: false,
		processData: false,
		contentType: false,
			success:function(data) {
				location.reload();
			},
			error: function(data) {
			    alert("FAILURE!");
			}
		});
	});
});
/*------------------------*/
function handleFileSelect(evt) {
	var files = evt.target.files; // FileList object

	// files is a FileList of File objects. List some properties.
	var output = [];
	for (var i = 0, f; f = files[i]; i++) {
		output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
			f.size, ' bytes, last modified: ',
			f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a',
			'</li>');
	}
	document.getElementById('list').innerHTML = '<ul>' + output.join('') + '</ul>';
}

document.getElementById('file_video').addEventListener('change', handleFileSelect, false);
/*--------------*/
function ValidateSize(file) {
	var FileSize = file.files[0].size / 1024 / 1024; // in MB
	if (FileSize > 25) {
		alert('File size exceeds 25 MB');
	} else {

	}
}