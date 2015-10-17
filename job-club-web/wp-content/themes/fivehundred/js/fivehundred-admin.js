jQuery(document).ready(function() {
	// This is the script for uploading logos to the settings menu
	var _custom_media = true;
	if (wp.media) {
		_orig_send_attachment = wp.media.editor.send.attachment;
	}
	jQuery('.uploader .button').click(function(e) {
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = jQuery(this);
		var id = button.attr('id').replace('_button', '');
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
		  if ( _custom_media ) {
		    jQuery("#logo-input").val(attachment.url);
		    jQuery("#logo-preview img").replaceWith('<img id="logo-image" src="' + attachment.url + '"/>');
		    //console.log(attachment.url);
		  } else {
		    return _orig_send_attachment.apply( this, [props, attachment] );
		  };
		}
	wp.media.editor.open(button);
	return false;
	});
	jQuery('.add_media').click(function() {
		_custom_media = false;
	});
	jQuery("#logo-input").change(function() {
		var input = jQuery("#logo-input").val();
		console.log(input);
		jQuery("#logo-preview").html('<img src="' + input + '"/>');
	});

	jQuery('.fh_media_button').click(function(e){
		e.preventDefault();
		var button = jQuery(this);
		//console.log('click');
		fhOpenFrame(button);
	});
	// This is the script for adding background images to widgets
	jQuery('.fh_media_button2').click(function(e){
		e.preventDefault();
		var button = jQuery(this);
		//console.log('click');
		fhOpenFrame(button);
	});
	jQuery(document).bind('widget-updated', function(event, widget) {
		jQuery('.fh_media_button').click(function(e){
			e.preventDefault();
			var button = jQuery(this);
			//console.log('click');
			fhOpenFrame(button);
		});
		jQuery('.fh_media_button2').click(function(e){
			e.preventDefault();
			var button = jQuery(this);
			//console.log('click');
			fhOpenFrame(button);
		});
	});
});
function fhOpenFrame(button) {
	// Create the media frame.
	file_frame = wp.media.frames.file_frame = wp.media({
		title: jQuery( this ).data( 'uploader_title' ),
		button: {
		text: jQuery( this ).data( 'uploader_button_text' ),
		},
		multiple: false  // Set to true to allow multiple files to be selected
	});

	// When an image is selected, run a callback.
	file_frame.on( 'select', function() {
		// We set multiple to false so only get one image from the uploader
		attachment = file_frame.state().get('selection').first().toJSON();

		// Do something with attachment.id and/or attachment.url here
		jQuery(button).siblings('input').val(attachment.url);
	});

	// Finally, open the modal
	file_frame.open();
}