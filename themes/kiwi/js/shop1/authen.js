$(document).ready(function() {
	$('#account-creation_form input').blur(function(){
		$(this).validationEngine({promptPosition: "centerRight"});
		$(this).validationEngine('validateField',this);
	});
	$('#account-creation_form input').focus(function(){
		$(this).validationEngine('hide');
	});
	$('#submitAccount').click(function() {
		$('#account-creation_form').validationEngine({promptPosition: "centerRight"});
		if (!$('#account-creation_form').validationEngine('validate')) {
			return false;
		};
	});
});
