(function ($, root, undefined) {
	
	$(".option-remove").click(function(e) {
		e.preventDefault();
		var option_number = $(this).attr('data-remove');
		var option_name = $(this).attr('data-option');
		var option_wrapper = $(this).parent();
		//console.log(option_number);
		/*var data = {
			'action': 'the_photo_remove_option',
			'name': option_name,
			'option': option_number,
		};
		$.post(ajaxurl, data, function(response) {
			if(response == 'ok'){
				option_wrapper.remove();
			}
		}); */
		$(option_wrapper).html('<input type="hidden" name="the_photo_remove_option[' + option_number + ']" value="' + option_number + '">');
	});
	
	$(".option-add").click(function(e) {
		e.preventDefault();
		var option_number = $(this).attr('data-add');
		var option_name = $(this).attr('data-option');
		var input = '<p><input type="text" placeholder="Input metadata field" name="' + option_name + '[' + option_number + ']" value=""><a href="#" class="option-remove" data-option="the_photo_options_metadata" data-remove="' + option_number + '"> X </a></p>';
		$(input).insertBefore( $(this) );
		option_number++;
		$(this).attr('data-add', option_number);
	});
	
})(jQuery, this);
