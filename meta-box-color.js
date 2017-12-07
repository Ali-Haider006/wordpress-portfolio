jQuery(document).ready(function($){
	$('.mm_color').wpColorPicker();

    // Instantiates the variable that holds the media library frame.
    var imageFrame;
    var imageDiv = $('.mm_image_div');


    $('#mm_button').click(function(e){

        // Prevents the default action from occuring.
        e.preventDefault();

        // If the frame already exists, re-open it.
        if (imageFrame) {
            imageFrame.open();
            return;
        }

        // Sets up the media library frame
        imageFrame = wp.media.frames.imageFrame = wp.media({
            title: 'Select an image to upload',
            button: { text:  'Use this image' },
            library: { type: 'image' }
        });

        // Runs when an image is selected.
        imageFrame.on('select', function(){

            // Grabs the attachment selection and creates a JSON representation of the model.
            var attachment = imageFrame.state().get('selection').first().toJSON();

            $('#image-preview').attr( 'src', attachment.url ).css( 'width', 'auto' );
            $('#mm_image').val( attachment.id );
            imageDiv.removeClass('hidden');

        });
        // Opens the media library frame.
        imageFrame.open();
    });

    // DELETE IMAGE LINK
    $('.mm-delete-image').on( 'click', function( event ){

        event.preventDefault();
        imageDiv.addClass('hidden');
        // Clear out the preview image
        $( '#image-preview' ).src('');

        // Delete the image id from the hidden input
        $( '#image_attachment_id' ).val( '' );

    });
});