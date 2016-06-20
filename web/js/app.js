$(function() {
	$('#form-create-artist').vaidate({
        submitHandler : function(form){
            $(form).ajaxSubmit({

            });
        }
    });
});