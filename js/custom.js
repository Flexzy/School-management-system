//Sidebar Toggle
function minimize() {
	$("#sidebar").toggleClass("slide_in");
	$(".nav_items").toggleClass("slide_in");
	$("#content").toggleClass("slide_in");
	$(".child-nav").toggleClass("show");
}

//Menu toggle on small screen
function slide_menu() {
	$(".sidebar_content").toggleClass("menu");
}

//Main Navigation slideToggle
$('.has-children').not('.open').find('.child-nav').slideUp('100');
$('.has-children>a').on('click', function(event){
	event.preventDefault();
	$('.has-children').removeClass('open');
	$('.child-nav').slideUp('100');
	$(this).parent().toggleClass('open');
	$(this).parent().find('.child-nav').slideToggle('500');
});

//Trigger Acad Modal
function acad_modal(session, term, active, id) {
	if(session != "" && term != "" && id != "") {
		var msession = $("#msession");
		var mid 	= $("#mid");
		var mterm 	= $("#mterm");
		msession.val(session);
		mid.val(id);
		mterm.val(term);
		if(active == 0) {
			$("#mactive").prop("checked", false);
		}else if(active == 1) {
			$("#mactive").prop("checked", true);
		}
		$("#mactive").val(active);
		$("#acadModal").modal();
	}
}

//Fetch academic year details
function get_acad() {
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: {get_acad: 'yes'},
		success: function(data) {
			$("#acad_div").html(data);
			$("#session_tb").DataTable();
		}
	})
}

//Update academic year details
function update_acad_yr() {
	var form = new FormData($("#edit_acad_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			if(data != "") {
				if(data == "success") {
					bootbox.alert("<b><div class='alert alert-success customized' style='text-align: center;'><i class='fa fa-check'></i> Updated successfully</div></b>", function() {
						get_acad();
						//$("#acadModal").modal("hide");
					});
				}else{
					bootbox.alert(data);
				}
			}
		},
		error: function(err) {}
	});
}

//remove academic year record
function remove_acad_yr(id) {
	if(id != "") {
			bootbox.confirm("Are you sure you want to remove this record?", function(result) {
			if(result == true) {
				$.ajax({
					url: 'parse.php',
					type: 'post',
					data: {acad_to_delete: id},
					success: function(data) {
						if(data == "success") {
							get_acad();
						}else{
							bootbox.alert(data);
						}
					}
				});
			}
		});
	}
}

//Trigger Subject Bank Modal
function sub_bank_modal(subject, abbr, id) {
	if(subject != "" && abbr != "" && id != "") {
		var msubject 	= $("#msubject");
		var mabbr 		= $("#mabbr");
		var msub_id 	= $("#msub_id");
		msubject.val(subject);
		mabbr.val(abbr);
		msub_id.val(id);
		$("#subModal").modal();
	}
}

//Fetch academic year details
function get_sub() {
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: {get_sub_bank: 'yes'},
		success: function(data) {
			$("#sub_div").html(data);
			$("#subject_tb").DataTable();
		}
	})
}

//Update academic year details
function update_sub() {
	var form = new FormData($("#edit_sub_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			if(data != "") {
				if(data == "success") {
					bootbox.alert("<b><div class='alert alert-success customized' style='text-align: center;'><i class='fa fa-check'></i> Updated successfully</div></b>", function() {
						get_sub();
					});
				}else{
					bootbox.alert(data);
				}
			}
		},
		error: function(err) {}
	});
}

//remove academic year record
function remove_subject(id) {
	if(id != "") {
			bootbox.confirm("Are you sure you want to remove this record?", function(result) {
			if(result == true) {
				$.ajax({
					url: 'parse.php',
					type: 'post',
					data: {sub_to_delete: id},
					success: function(data) {
						if(data == "success") {
							get_sub();
						}else{
							bootbox.alert(data);
						}
					}
				});
			}
		});
	}
}

//Show selected image on select
function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		
		reader.onload = function (e) {
			$('#imageView')
				.attr('src', e.target.result)
				.width(180)
				.height(180);
		};
		
		reader.readAsDataURL(input.files[0]);
	}
}

//Load local governments using state
function load_lga() {
	var mState = $("#state").val();
	if(mState != "") {
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {mstate: mState},
			success: function(data) {
				$("#lga").html(data);
			}
		});
	}else{
		$("#lga").html("<option value=''>Select</option>");
	}
	
}

//Load staff records from the database
function load_staff() {
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: {get_staff: 'yes'},
		success: function(data) {
			$("#staff_div").html(data);
			$("#staff_tb").DataTable();
		}
	});
}

//New Staff Entry
function sub_staff() {
	//alertify.error("<div style='font-size: 15px'><i class='fa fa-warning'></i> You have been warned and found wanting</div>");
	var form = new FormData($("#add_staff_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			if(data == "Operation successful") {
				document.getElementById("add_staff_form").reset();
				$('#imageView').attr('src', 'img/img.png');
				create_id();
				load_staff()
				alertify.success("<div style='font-size: 15px'><i class='fa fa-check'></i> "+data);
			}else{
				alertify.alert(data);
				$(".ajs-header").html("Notice");
			}
		},
		error: function(err) {}
	});
}

//Create staff ID
function create_id() {
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: {make_id: 'yes'},
		success: function(data) {
			$("#staff_id").val(data);
		}
	});
}

//Remove a staff record
function remove_staff(staff_id, id) {
	if(staff_id != "" && id != "") {
		alertify.confirm("Are you sure you want to remove this staff record?.",
			function(){
				$.ajax({
					url: 'parse.php',
					type: 'post',
					data: {staffID_to_delete: staff_id, ID_to_delete: id},
					success: function(data) {
						if(data == "Operation successful") {
							load_staff();
							alertify.success("<div style='font-size: 15px'><i class='fa fa-check'></i> "+data);
						}else{
							alertify.error("<div style='font-size: 15px'><i class='fa fa-warning'></i> "+data);
						}
					}
				});
			},
			function(){
				
			}
		);
		$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
	}
}

//Update Staff Detail
function edit_staff() {
	//alertify.error("<div style='font-size: 15px'><i class='fa fa-warning'></i> You have been warned and found wanting</div>");
	var form = new FormData($("#edit_staff_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			if(data == "Operation successful") {
				alertify.success("<div style='font-size: 15px'><i class='fa fa-check'></i> "+data);
			}else{
				alertify.alert(data);
				$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
			}
		},
		error: function(err) {}
	});
}

//Fetch class level using section_id
function get_level() {
	$("#mloader").addClass("mloader");
	var section = $("#section").val();
	if(section != "") {
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {_level: section},
			success: function(data) {
				$("#mloader").removeClass("mloader");
				$("#class_level").html(data);
			}
		});
	}else{
		$("#class_level").html("<option value=''>Select</option>");
		$("#mloader").removeClass("mloader");
	}
}

//Load fee head records from the database
function load_fee() {
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: {get_fee_head: 'yes'},
		success: function(data) {
			$("#fee_div").html(data);
			$("#fee_tb").DataTable();
		}
	});
}

//Create new fee head
function sub_fee_head() {
	var form = new FormData($("#fee_head_form")[0]);
	$("#mloader").addClass("mloader");
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			$("#mloader").removeClass("mloader");
			if(data == "Operation successful") {
				document.getElementById("fee_head_form").reset();
				load_fee();
				alertify.success("<div style='font-size: 15px'><i class='fa fa-check'></i> "+data);
			}else{
				alertify.alert(data);
				$(".ajs-header").html("<i class='fa fa-info'></i> Notice");
			}
		}
	});
}

//Remove a fee head configuration
function remove_fee_head(id) {
	if(id != "") {
		alertify.confirm("Are you sure you want to remove this fee head record?.",
			function(){
				$.ajax({
					url: 'parse.php',
					type: 'post',
					data: {feeID_to_delete: id},
					success: function(data) {
						if(data == "Operation successful") {
							load_fee();
							alertify.success("<div style='font-size: 15px'><i class='fa fa-check'></i> "+data);
						}else{
							alertify.error("<div style='font-size: 15px'><i class='fa fa-warning'></i> "+data);
						}
					}
				});
			},
			function(){
				
			}
		);
		$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
	}
}

//Fetch class level using section_id
function get_level_edit() {
	$("#emloader").addClass("emloader");
	var section = $("#msection").val();
	if(section != "") {
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {_level: section},
			success: function(data) {
				$("#emloader").removeClass("mloader");
				$("#mclass_level").html(data);
			}
		});
	}else{
		$("#class_level").html("<option value=''>Select</option>");
		$("#emloader").removeClass("mloader");
	}
}

//Trigger Fee Modal
function fee_modal(section, class_level, fee_name, amount, fee_id) {
	if(section != "" && class_level != "" && fee_name != "" && fee_id != "") {
		var msection = $("#msection");
		var mclass_level = $("#mclass_level");
		var mid 		= $("#fid");
		var mfee_name 	= $("#mfee_name");
		var mamount 	= $("#mamount");
		var mfee_id 	= $("#mfee_id");
		msection.val(section);
		mid.val(fee_id);
		mfee_name.val(fee_name);
		mamount.val(amount);
		
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {_section_edit: section, _level_edit: class_level},
			success: function(data) {
				mclass_level.html(data);
			}
		});
		
		$("#feeModal").modal();
	}
}

//Update Fee configuration details
function update_fee() {
	$("#emloader").addClass("emloader");
	var form = new FormData($("#edit_fee_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			$("#emloader").removeClass("emloader");
			if(data != "") {
				if(data == "success") {
					load_fee();
					alertify.success("<div><i class='fa fa-check'></i> Updated successfully</div>");
				}else{
					alertify.alert(data);
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}
			}
		},
		error: function(err) {}
	});
}

//Load fee head records from the database
function load_student() {
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: {get_students: 'yes'},
		success: function(data) {
			$("#student_div").html(data);
			$("#student_tb").DataTable();
		}
	});
}

//Add a new student
function sub_student() {
	alertify.confirm("Please make sure that you have provided all necessary information including the Basic details, Academic details, Guardian Information and Contact Details before proceeding with the submission.",
		function(){
			var form = new FormData($("#add_student_form")[0]);
			$.ajax({
				url: 'parse.php',
				type: 'post',
				data: form,
				processData: false,
				contentType: false,
				success: function(data) {
					if(data == "Operation successful") {
						document.getElementById("add_student_form").reset();
						$('#imageView').attr('src', 'img/img.png');
						create_id();
						load_student();
						alertify.success("<div style='font-size: 15px'><i class='fa fa-check'></i> "+data);
					}else{
						alertify.alert(data);
						$(".ajs-header").html("Notice");
					}
				},
				error: function(err) {}
			});
		},
		function(){
			
		}
	);
	$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
}

//Update Student Detail
function edit_student() {
	var form = new FormData($("#edit_student_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			if(data == "Operation successful") {
				alertify.success("<div style='font-size: 15px'><i class='fa fa-check'></i> "+data);
			}else{
				alertify.alert(data);
				$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
			}
		},
		error: function(err) {}
	});
}

//Show pay fee modal
function pay_fee_modal(admission_no, section, class_level) {
	if(admission_no != "" && section != "" && class_level) {
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {fee_adm_no: admission_no, fee_section: section, fee_level: class_level},
			success: function(data) {
				if(data == "cleared") {
					alertify.alert("<b><div class='alert alert-success customized text-center'><i class='fa fa-check'></i> Student has already been clear for this session and term. To collect fees of previous sessions, go to collect fees page</div></b>");
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}else if(data == "academic year not found") {
					alertify.alert("<b><div class='alert alert-danger customized text-center'><i class='fa fa-warning'></i> You have not created your academic year session and term or there is no set academic year session and term. Please try again</div></b>");
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}else if(data == "no fee head") {
					alertify.alert("<b><div class='alert alert-danger customized text-center'><i class='fa fa-warning'></i> You have not created a fee configuration for the selected class. Please try again</div></b>");
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}else{
					$("#fee_body").html(data);
					$("#payfeeModal").modal();
				}
			}
		});
	}
}

//verify mode of fee payment
function verify_mode() {
	var mode = $("#mode").val().toLowerCase();
	if(mode == "bank") {
		$("#slip_no").attr("disabled", false);
		$("#slip_no").attr("required", true);
	}else if(mode == "cash") {
		$("#slip_no").attr("disabled", true);
		$("#slip_no").attr("required", false);
	}
}

function toggle_total(input_id, input_price) {
	var check = document.getElementById(input_id);
	var check_pr = document.getElementById(input_price);
	if(check.checked) {
		var total_dis = (parseInt($("#total").val()) + parseInt(check_pr.value));
		$("#total_sp").html(total_dis);
		$("#total").val(total_dis);
	}else {
		var total_dis = ($("#total").val() - check_pr.value);
		$("#total_sp").html(total_dis);
		$("#total").val(total_dis);
	}
}

function pay_fee() {
	$("#mloader").addClass("mloader");
	var form = new FormData($("#pay_fee_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			$("#mloader").removeClass("mloader");
			if(data != "") {
				if(data.substr(0, 7) == "receipt") {
					window.open(data);
				}else{
					alertify.alert(data);
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}
			}
		},
		error: function(err) {}
	})
}

function fetch_students() {
	var session 	= $("#session").val();
	var term 		= $("#term").val();
	var section 	= $("#section").val();
	var level 		= $("#class_level").val();
	var alpha 		= $("#alpha").val();
	
	if(session != "" && term != "") {
		$("#mloader").addClass("mloader");
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {csession: session, cterm: term, csection: section, clevel: level, calpha: alpha},
			success: function(data) {
				$("#mloader").removeClass("mloader");
				$("#fetched_stud").html(data);
			}
		});
	}else{
		alertify.error("<i class='fa fa-warning'></i> You must select an Academic session and term");
	}
	
}

//Show pay fee modal
function collect_fee_modal(admission_no, session, term, section, class_level) {
	if(admission_no != "" && session != "" && term != "" && section != "" && class_level) {
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {f_adm_no: admission_no, f_session: session, f_term: term, f_section: section, f_level: class_level},
			success: function(data) {
				if(data == "cleared") {
					alertify.alert("<b><div class='alert alert-success customized text-center'><i class='fa fa-check'></i> Student has already been clear for this session and term. To collect fees of previous sessions, go to collect fees page</div></b>");
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}else if(data == "academic year not found") {
					alertify.alert("<b><div class='alert alert-danger customized text-center'><i class='fa fa-warning'></i> You have not created your academic year session and term or there is no set academic year session and term. Please try again</div></b>");
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}else if(data == "no fee head") {
					alertify.alert("<b><div class='alert alert-danger customized text-center'><i class='fa fa-warning'></i> You have not created a fee configuration for the selected class. Please try again</div></b>");
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}else{
					$("#collect_body").html(data);
					$("#collectFeeModal").modal();
				}
			}
		});
	}
}

function collect_fee() {
	$("#ploader").addClass("mloader");
	var form = new FormData($("#collect_fee_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			$("#ploader").removeClass("mloader");
			if(data != "") {
				if(data.substr(0, 7) == "receipt") {
					window.open(data);
				}else{
					alertify.alert(data);
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}
			}
		},
		error: function(err) {}
	})
}

//Fetch class level using section_id for subject teacher assignment
function get_level_two() {
	$("#aloader").addClass("mloader");
	var section = $("#section_ass").val();
	if(section != "") {
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {ass_level: section},
			success: function(data) {
				$("#aloader").removeClass("mloader");
				$("#class_level_div").html(data);
			}
		});
	}else{
		$("#class_level_div").html("<b><div class='alert alert-danger customized text-center'><i class='fa fa-warning'></i> You have not selected a section yet</div></b>");
		$("#aloader").removeClass("mloader");
	}
}

//Fetch all assigned subjects 
function load_ass_sub() {
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: {get_ass_sub: 'yes'},
		success: function(data) {
			$("#ass_sub_div").html(data);
		}
	});
}

//Assign subject teacher
function assign_subject() {
	$("#aloader").addClass("mloader");
	var form = new FormData($("#ass_sub_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			$("#aloader").removeClass("mloader");
			if(data != "") {
				if(data == "success") {
					load_ass_sub();
					document.getElementById("ass_sub_form").reset();
					$("#class_level_div").html("<b><div class='alert alert-success customized'><i class='fa fa-warning'></i> Classes will display here for selection when you have selected a section</div></b>");
					alertify.success("Assignment successful");
				}else{
					alertify.alert(data);
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}
			}
		},
		error: function(err) {}
	});
}

//Edit assigned subject modal
function edit_sub_ass_modal(staff, subject,  section) {
	if(staff != "" && subject != "" && section) {
		$("#esloader").addClass("mloader");
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {staff_ass: staff, sub_ass: subject, section_ass: section},
			success: function(data) {
				$("#esloader").removeClass("mloader");
				if(data != "") {
					$("#etch_id").val(staff);
					$("#esubject").val(subject);
					$("#esection_ass").val(section);
					$("#etch_id_hide").val(staff);
					$("#eass_subject_hide").val(subject);
					$("#eass_section_hide").val(section);
					$("#class_level_div_edit").html(data);
					$("#subAssModal").modal();
				}
			}
		});
	}
}

//Fetch class level using section_id for subject teacher assignment update
function get_level_two_edit() {
	$("#esloader").addClass("mloader");
	var staff = $("#etch_id_hide").val();
	var subject = $("#esubject").val();
	var section = $("#esection_ass").val();
	if(staff != "" && subject != "" && section != "") {
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {staff_ass: staff, sub_ass: subject, section_ass: section},
			success: function(data) {
				$("#esloader").removeClass("mloader");
				$("#class_level_div_edit").html(data);
			}
		});
	}else{
		$("#class_level_div_edit").html("<b><div class='alert alert-danger customized text-center'><i class='fa fa-warning'></i> You have not selected a section yet</div></b>");
		$("#esloader").removeClass("mloader");
	}
}

//Update assigned subjects and teachers
function edit_sub_ass() {
	$("#esloader").addClass("mloader");
	var form = new FormData($("#edit_sub_ass_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			$("#esloader").removeClass("mloader");
			if(data != "") {
				if(data == "success") {
					load_ass_sub();
					$("#subAssModal").modal("hide");
					document.getElementById("edit_sub_ass_form").reset();
					$("#class_level_div_edit").html("<b><div class='alert alert-success customized'><i class='fa fa-warning'></i> Classes will display here for selection when you have selected a section</div></b>");
					alertify.success("Assignment successfully updated");
				}else{
					alertify.alert(data);
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}
			}
		},
		error: function(err) {}
	});
}

//Fetch class level using section_id for class teacher assignment
function get_level_two_cl() {
	$("#cloader").addClass("mloader");
	var section = $("#section_cl").val();
	if(section != "") {
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {cl_level: section},
			success: function(data) {
				$("#cloader").removeClass("mloader");
				$("#class_level_div_cl").html(data);
			}
		});
	}else{
		$("#class_level_div_cl").html("<b><div class='alert alert-danger customized text-center'><i class='fa fa-warning'></i> You have not selected a section yet</div></b>");
		$("#aloader").removeClass("mloader");
	}
}

//Remove an assigned subject record
function remove_sub_assigned(staff_id, subject) {
	if(staff_id != "" && subject != "") {
		alertify.confirm("Are you sure you want to remove this subject assignment record?.",
			function(){
				$.ajax({
					url: 'parse.php',
					type: 'post',
					data: {staffID_ass_to_remove: staff_id, sub_ass_to_remove: subject},
					success: function(data) {
						if(data == "Operation successful") {
							load_ass_sub();
							alertify.success("<div style='font-size: 15px'><i class='fa fa-check'></i> "+data);
						}else{
							alertify.error("<div style='font-size: 15px'><i class='fa fa-warning'></i> "+data);
						}
					}
				});
			},
			function(){
				
			}
		);
		$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
	}
}

//Fetch all assigned class teachers 
function load_ass_cl() {
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: {get_ass_cl: 'yes'},
		success: function(data) {
			$("#ass_cl_div").html(data);
		}
	});
}

//Assign class teacher
function assign_class() {
	$("#cloader").addClass("mloader");
	var form = new FormData($("#ass_class_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			$("#cloader").removeClass("mloader");
			if(data != "") {
				if(data == "success") {
					load_ass_cl();
					document.getElementById("ass_class_form").reset();
					$("#class_level_div_cl").html("<b><div class='alert alert-success customized'><i class='fa fa-warning'></i> Classes will display here for selection when you have selected a section</div></b>");
					alertify.success("Assignment successful");
				}else{
					load_ass_cl();
					alertify.alert(data);
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}
			}
		},
		error: function(err) {}
	});
}

//Edit assigned class modal
function edit_cl_ass_modal(staff, section) {
	if(staff != "" && section) {
		$("#ecloader").addClass("mloader");
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {staff_ass_cl: staff, section_ass_cl: section},
			success: function(data) {
				$("#ecloader").removeClass("mloader");
				if(data != "") {
					$("#ectch_id").val(staff);
					$("#esection_cl").val(section);
					$("#ectch_id_hide").val(staff);
					$("#ecl_section_hide").val(section);
					$("#class_level_div_edit_cl").html(data);
					$("#classAssModal").modal();
				}
			}
		});
	}
}

//Fetch class level using section_id for class teacher assignment update
function get_level_two_edit_cl() {
	$("#ecloader").addClass("mloader");
	var staff = $("#ectch_id_hide").val();
	var section = $("#esection_cl").val();
	if(staff != "" && section != "") {
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {staff_ass_cl: staff, section_ass_cl: section},
			success: function(data) {
				$("#ecloader").removeClass("mloader");
				$("#class_level_div_edit_cl").html(data);
			}
		});
	}else{
		$("#class_level_div_edit").html("<b><div class='alert alert-danger customized text-center'><i class='fa fa-warning'></i> You have not selected a section yet</div></b>");
		$("#esloader").removeClass("mloader");
	}
}

//Update assigned class teachers
function edit_cl_ass() {
	$("#ecloader").addClass("mloader");
	var form = new FormData($("#edit_cl_ass_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			$("#ecloader").removeClass("mloader");
			if(data != "") {
				if(data == "success") {
					load_ass_cl();
					$("#classAssModal").modal("hide");
					document.getElementById("edit_cl_ass_form").reset();
					$("#class_level_div_edit_cl").html("<b><div class='alert alert-success customized'><i class='fa fa-warning'></i> Classes will display here for selection when you have selected a section</div></b>");
					alertify.success("Assignment successfully updated");
				}else{
					alertify.alert(data);
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}
			}
		},
		error: function(err) {}
	});
}

//Remove an assigned subject record
function remove_cl_assigned(staff_id, id) {
	if(staff_id != "" && id != "") {
		alertify.confirm("Are you sure you want to remove this class assignment record?.",
			function(){
				$.ajax({
					url: 'parse.php',
					type: 'post',
					data: {staffID_ass_to_remove_cl: staff_id, ID_ass_to_remove_cl: id},
					success: function(data) {
						if(data == "Operation successful") {
							load_ass_cl();
							alertify.success("<div style='font-size: 15px'><i class='fa fa-check'></i> "+data);
						}else{
							alertify.error("<div style='font-size: 15px'><i class='fa fa-warning'></i> "+data);
						}
					}
				});
			},
			function(){
				
			}
		);
		$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
	}
}

//Get all classes taking a subject using staffID
function get_classes_sub() {
	var subject = $("#sub_mark").val();
	var staffID = $("#staff_mark").val();
	if(subject != "" && staffID != "") {
		$("#amloader").addClass("mloader");
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {sub_marks_cl: subject, staff_marks_cl: staffID},
			success: function(data) {
				$("#amloader").removeClass("mloader");
				if(data != "") {
					$("#cl_mark").html(data);
				}
			}
		});
	}
}

//Fetch students offering subjects for marks allocation
function query_students_sub() {
	$("#amloader").addClass("mloader");
	var form = new FormData($("#sub_mark_query_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			$("#amloader").removeClass("mloader");
			$("#save_sub_marks_form").html(data);
		}
	});
}

//Get term using session
function get_term() {
	var session = $("#session_mark").val();
	if(session != "") {
		$("#amloader").addClass("mloader");
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {session_mark_cl: session},
			success: function(data) {
				$("#amloader").removeClass("mloader");
				if(data != "") {
					$("#term_mark").html(data);
				}
			}
		});
	}
}

//Get term using session for class teacher allocate marks
function get_term_two() {
	var session = $("#session_mark_two").val();
	if(session != "") {
		$("#acloader").addClass("mloader");
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {session_mark_cl: session},
			success: function(data) {
				$("#acloader").removeClass("mloader");
				if(data != "") {
					$("#term_mark_two").html(data);
				}
			}
		});
	}
}

//Get term using session export modal
function get_term_thr() {
	var session = $("#session_mark_thr").val();
	if(session != "") {
		$("#mcloader").addClass("mloader");
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {session_mark_cl: session},
			success: function(data) {
				$("#mcloader").removeClass("mloader");
				if(data != "") {
					$("#term_mark_thr").html(data);
				}
			}
		});
	}
}


function sum_scores(ca1, ca2, exam, total, grade) {
	var ca_one 	= parseInt($("#"+ca1).val());
	var ca_two 	= parseInt($("#"+ca2).val());
	var exam 	= parseInt($("#"+exam).val());
	var flag	= true;
	var mgrade	= "";
	
	if(ca_one == "" || ca_one < 0) {
		ca_one = 0;
	}
	
	if(ca_two == "" || ca_two < 0) {
		ca_two = 0;
	}
	
	if(exam == "" || exam < 0) {
		exam = 0;
	}
	
	if(ca_one > 20) {
		flag = false;
		alertify.error("<i class='fa fa-warning'></i> First C.A. score cannot be more than 20 marks");
	}else{
		flag = true;
	}
	
	if(flag != false) {
		if(ca_two > 20) {
			flag = false;
			alertify.error("<i class='fa fa-warning'></i> Second C.A. score cannot be more than 20 marks");
		}else{
			flag = true;
		}
	}
	
	if(flag != false) {
		if(exam > 60) {
			flag = false;
			alertify.error("<i class='fa fa-warning'></i> Exam score cannot be more than 60 marks");
		}else{
			flag = true;
		}
	}
	
	if(flag != false) {
		var mtotal = ca_one + ca_two + exam;
		if(mtotal < 40 && mtotal > 0) {
			mgrade = "F";
			$("#"+grade).addClass("text-danger");
		}else if(mtotal >= 40 && mtotal < 50) {
			mgrade = "D";
			$("#"+grade).removeClass("text-danger");
			$("#"+grade).addClass("text-custom");
		}else if(mtotal >= 50 && mtotal < 60) {
			mgrade = "C";
		}else if(mtotal < 70 && mtotal >= 60) {
			mgrade = "B";
			$("#"+grade).removeClass("text-danger");
			$("#"+grade).removeClass("text-custom");
			$("#"+grade).addClass("text-primary");
		}else if(mtotal >= 70 && mtotal <= 100) {
			mgrade = "A";
			$("#"+grade).removeClass("text-danger");
			$("#"+grade).removeClass("text-custom");
			$("#"+grade).addClass("text-success");
		}
	}
	
	if(flag != false) {
		$("#"+total).val(ca_one + ca_two + exam);
		$("#"+grade).css("font-weight", "bolder");
		$("#"+grade).val(mgrade);
	}
	/*$.ajax({
		url: 'parse.php',
		type: 'post',
		data: {CA1: ca_one, CA1: ca_two, EXAM: exam},
		success: function(data) {
			$("#"+total).val(data);
		}
	});*/
	
}

//Save subject marks
function save_sub_marks() {
	var form = new FormData($("#save_sub_marks_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			if(data != "") {
				if(data == "Marks successfully updated") {
					alertify.success("<div style='font-size: 15px'><i class='fa fa-check'></i> "+data+"</div>");
				}else{
					alertify.alert(data);
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}
			}
		}
	});
}

//Show class marks modal 
function cl_mark_modal(admission_no, session, term, name, gender) {
	if(admission_no != "" && session != "" && term != "" && name != "" && gender != "") {
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {cl_admin_no: admission_no, cl_session: session, cl_term: term},
			success: function(data) {
				if(data != "") {
					//alertify.alert(data);
					$("#cl_mark_holder_name").html("<div style='padding-left: 7px;'><h4>Fullname: "+name+"</h4><h4>Gender: "+gender+"</h4></div>");
					$("#cl_mark_holder").html(data);
					$("#classMarks").modal();
				}
			}
		});
	}
}

//fetch marks on session or term change for student via class teacher
function cl_mark_session() {
	var adm_no = $("#sm_admin_no").val();
	var session = $("#session_mark_two").val();
	var term = $("#term_mark_two").val();
	
	if(adm_no != "" && session != "" && term != "") {
		$("#cl_mark_holder").html("<br/><br/><div class='ploader'></div>");
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {cl_admin_no: adm_no, cl_session: session, cl_term: term},
			success: function(data) {
				if(data != "") {
					//alertify.alert(data);
					$("#cl_mark_holder").html(data);
				}
			}
		});
	}
	
}

//Save class marks
function save_cl_marks() {
	var form = new FormData($("#save_cl_marks_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			if(data != "") {
				if(data == "Marks successfully updated") {
					alertify.success("<div style='font-size: 15px'><i class='fa fa-check'></i> "+data+"</div>");
				}else{
					alertify.alert(data);
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}
			}
		}
	});
}

//show export marks in excel or pdf modal ///////////////////////////////////////////////////////////////////Not Completed
function export_mark_options(doc_type) {
	if(doc_type != "") {
		$("#doc_type").val(doc_type);
		$("#exportMarks").modal();
	}
}

//function check emolument & Deduction div height
function check_ed_container() {
	var emol_height = $(".emolument").height();
	var deduce_height = $(".deduction").height();
	
	if(emol_height > deduce_height) {
		$(".deduction").height(emol_height);
	}else if(deduce_height > emol_height) {
		$(".emolument").height(deduce_height);
	}
}

//Fetch all emoluments / deductions
function get_emol_deduce() {
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: {get_ed: 'yes'},
		success: function(data) {
			if(data != "") {
				$("#message").html("");
				$("#fin_holder").html(data);
				check_ed_container();
				$("#fin_holder").css("display", "block");
			}
		}
	});
}

//Add Emolument or Deduction Modal
function add_emol_modal() {
	$("#addEmolument").modal();
}

//add_emolument
function add_emolument() {
	var form = new FormData($("#ed_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			if(data != "") {
				if(data == "success") {
					get_emol_deduce();
					alertify.success("<i class='fa fa-check'></i> Entry successfully added");
				}else{
					alertify.alert(data);
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}
			}
		},
		error: function(er) {}
	});
}

//Fetch staff financial details on staff name select using staff ID
function fetch_staff_finance() {
	var ed_staff = $("#ed_staff").val();
	if(ed_staff != "") {
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {ed_staff_fin: ed_staff},
			success: function(data) {
				if(data != "") {
					var jsonData = JSON.parse(data)
					for(var i in jsonData) {
						document.getElementById(i).value = jsonData[i];
					}
				}else{
					document.getElementById("staff_finance_form").reset();
					$("#ed_staff").val(ed_staff);
				}
			}
		})
	}
}

//Add Staff financial details
function add_staff_fin_details() {
	var form = new FormData($("#staff_finance_form")[0]);
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			if(data != "") {
				if(data == "success") {
					alertify.success("<i class='fa fa-check'></i> Financial details successfully saved");
				}else{
					alertify.alert(data);
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}
			}
		},
		error: function(er) {}
	});
}

//Fetch staff salary details on staff name select using staff ID
function fetch_staff_salary() {
	var ed_staff = $("#ed_staff_salary").val();
	if(ed_staff != "") {
		$("#fin_holder").html("<br/><br/><br/><div class='ploader'></div>")
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {ed_staff_salary: ed_staff},
			success: function(data) {
				if(data != "") {
					$("#fin_holder").html(data);
				}
			}
		})
	}else{
		$("#fin_holder").html('');
	}
}

//Toggle total emoluments
function toggle_total_emol(input_id, staff_id, input_price, span_view, emol_total, deduce_total, emol_view, deduce_view, total_view) {
	var check = document.getElementById(input_id);
	var check_pr = document.getElementById(input_price);
	var em_total = document.getElementById(emol_total);
	var de_total = document.getElementById(deduce_total);
	if(check.checked) {
		$("#"+input_price).attr("disabled", false);
		$.ajax({
			url: 'parse_op.php',
			type: 'post',
			data: {num1: em_total.value, num2: check_pr.value, num3: de_total.value, operation: 'Add'},
			success: function(data) {
				if(data != "") {
					var jsonData = JSON.parse(data);
					$("#emol_view").html(jsonData.em_dis);
					$("#total_view").html(jsonData.tot_dis);
					em_total.value = jsonData.em;
					check_pr.style.background = "#ddd";
					check_pr.style.color = "#000";
					check_pr.style.borderColor = "#ddd";
				}
			}
		});
	}else {
		$("#"+input_price).attr("disabled", true);
		$.ajax({
			url: 'parse_op.php',
			type: 'post',
			data: {num1: em_total.value, num2: check_pr.value, num3: de_total.value, operation: 'Subtract'},
			success: function(data) {
				if(data != "") {
					var jsonData = JSON.parse(data);
					$("#emol_view").html(jsonData.em_dis);
					$("#total_view").html(jsonData.tot_dis);
					em_total.value = jsonData.em;
					check_pr.style.background = "#2D3945";
					check_pr.style.color = "#2D3945";
					check_pr.style.borderColor = "#2D3945";
				}
			}
		});
	}
}

//Toggle total emoluments
function toggle_total_deduce(input_id, staff_id, input_price, span_view, emol_total, deduce_total, emol_view, deduce_view, total_view) {
	var check = document.getElementById(input_id);
	var check_pr = document.getElementById(input_price);
	var em_total = document.getElementById(emol_total);
	var de_total = document.getElementById(deduce_total);
	if(check.checked) {
		$("#"+input_price).attr("disabled", false);
		$.ajax({
			url: 'parse_op.php',
			type: 'post',
			data: {num_1: de_total.value, num_2: check_pr.value, num_3: em_total.value, operation: 'Add'},
			success: function(data) {
				if(data != "") {
					var jsonData = JSON.parse(data);
					$("#deduce_view").html(jsonData.de_dis);
					$("#total_view").html(jsonData.tot_dis);
					de_total.value = jsonData.de;
					check_pr.style.background = "#ddd";
					check_pr.style.color = "#000";
					check_pr.style.borderColor = "#ddd";
				}
			}
		});
	}else {
		$("#"+input_price).attr("disabled", true);
		$.ajax({
			url: 'parse_op.php',
			type: 'post',
			data: {num_1: de_total.value, num_2: check_pr.value, num_3: em_total.value, operation: 'Subtract'},
			success: function(data) {
				if(data != "") {
					var jsonData = JSON.parse(data);
					$("#deduce_view").html(jsonData.de_dis);
					$("#total_view").html(jsonData.tot_dis);
					de_total.value = jsonData.de;
					check_pr.style.background = "#2D3945";
					check_pr.style.color = "#2D3945";
					check_pr.style.borderColor = "#2D3945";
				}
			}
		});
	}
}

//Fetch previous salary slip details
function prev_salary_slip(staff_id, month, year) {
	if(staff_id != "" && month != "" && year != "") {
		$("#fin_holder").html("<br/><br/><br/><div class='ploader'></div>")
		$.ajax({
			url: 'parse.php',
			type: 'post',
			data: {salary_staff: staff_id, salary_month: month, salary_year: year},
			success: function(data) {
				if(data != "") {
					$("#fin_holder").html(data);
				}
			}
		});
	}else{
		alertify.error("Problem encountered. Incomplete form data");
	}
}

//Submit Salary Slip Details
function gen_salary_slip() {
	var form = new FormData($("#salary_slip_form")[0]);
	var staff_id = $("#ed_staff_salary").val();
	var month = $("#salary_month").val();
	var year = $("#salary_year").val();
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			if(data != "") {
				if(data == "found") {
					alertify.confirm("Salary slip has been generated for this staff for selected month and year. Click OK to view details, print salary slip or delete salary slip details?.",
						function(){
							prev_salary_slip(staff_id, month, year);
						},
						function(){
							
						}
					);
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}else if(data == "success") {
					alertify.confirm("Salary slip successfully generated. Click OK to view details & print salary slip or discard salary slip.",
						function(){
							prev_salary_slip(staff_id, month, year);
						},
						function(){
							
						}
					);
					$(".ajs-header").html("<i class='fa fa-warning'></i> Notice");
				}else{
					alertify.alert(data);
				}
			}
		}
	});
}

//Discard Previous Payment
function discard_payment(staff_id, month, year) {
	if(staff_id != "" && month != "" && year != "") {
		alertify.confirm("Are you sure you want to remove this salary slip details?.",
			function(){
				$.ajax({
					url: 'parse.php',
					type: 'post',
					data: {staff_to_discard: staff_id, month_to_discard: month, year_to_discard: year},
					success: function(data) {
						if(data != "") {
							if(data == "success") {
								alertify.success("<i class='fa fa-check'></i> Operation successful: Salary slip successfully discarded");
								$("#fin_holder").html('');
								$("#ed_staff_salary").val('');
							}else{
								alertify.alert(data);
							}
						}
					}
				});
			},
			function(){
				
			}
		);
	}
}

//Fetch attendance calendar using date
function fetch_att() {
	var form = new FormData($("#att_form")[0]);
	$("#feloader").addClass('mloader');
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			$("#feloader").removeClass('mloader');
			if(data != "") {
				$("#att_div").html(data);
				var BootstrapTable = document.getElementById('att_table_async');
					new TableExport(BootstrapTable, {
					bootstrap: true
				});
			}
		}
	})
}

//Submit students attendance
function students_att() {
	var form = new FormData($("#stu_att_form")[0]);
	$("#atloader").addClass('mloader');
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			$("#atloader").removeClass('mloader');
			if(data != "") {
				if(data == "success") {
					alertify.success("Attendance successfully submitted");
					fetch_att();
				}else if(data == "no changes"){
					alertify.error("No changes made");
				}else{
					alertify.alert(data);
				}
			}
		}
	});
}

//Fetch broadsheet using session and term
function query_broadsheet() {
	var form = new FormData($("#query_broadsheet_form")[0]);
	//$("#atloader").removeClass('mloader');
	$.ajax({
		url: 'parse.php',
		type: 'post',
		data: form,
		processData: false,
		contentType: false,
		success: function(data) {
			//$("#atloader").removeClass('mloader');
			if(data != "") {
				$("#marks_br_div").html(data);
			}
		}
	});
}