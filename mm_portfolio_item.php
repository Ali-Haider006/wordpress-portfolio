<?php
class mm_portfolio_item
{
    private $type = 'mm_portfolio_item';

    function __construct()
    {
        add_action('admin_print_styles', [$this,'mm_admin_styles']);
        add_action('admin_enqueue_scripts', [$this,'mm_admin_scripts']);
        add_action('add_meta_boxes', [$this,'mm_custom_meta']);
        add_action('save_post', [$this,'mm_custom_save']);
    }

    public function mm_admin_styles()
    {
        global $post_type;

        if($post_type == $this->type){
            wp_enqueue_style( 'mm_admin_styles', plugin_dir_url( __FILE__ ) . 'meta-box-styles.css' );
        }
    }

    public function mm_admin_scripts()
    {
        global $post_type;

        if($post_type == $this->type) {
            wp_enqueue_media();
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'meta-box-color-js', plugin_dir_url( __FILE__ ) . 'meta-box-color.js', [ 'wp-color-picker', 'jquery'] );
        }
    }

    public function mm_custom_meta()
    {
        add_meta_box( 'mm_meta', __( 'Portfolio Items'), [$this, 'mm_post_callback'], 'mm_portfolio_item' );
    }

    public function mm_post_callback($post)
    {
        wp_nonce_field( basename( __FILE__ ), 'mm_nonce' );
        $item = get_post_meta($post->ID);
        if(isset($item['mm_image'])) {
            $image = wp_get_attachment_image_src($item['mm_image'][0], 'mm-portfolio');
        } else {
            $image = false;
        }
        ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th><label for="mm_description"><?php _e( 'Full Discription' )?></label></th>
                <td>
                    <textarea rows="4" cols="50" name="mm_description" id="mm_description"><?php if (isset( $item['mm_description'] ) ) echo $item['mm_description'][0]; ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="mm_color"><?php _e( 'Single item Page color')?></label></th>
                <td>
                    <input name="mm_color" class="mm_color" data-default-color="#ffffff" type="text" value="<?php if (isset($item['mm_color'])) echo $item['mm_color'][0]; ?>"/>
                </td>
            </tr>
            <tr>
                <th><label for="mm_image"><?php _e( 'Upload thumbnail', 'prfx-textdomain' )?></label></th>
                <td>
                    <input type="hidden" name="mm_image" id="mm_image" value="<?php if ( isset ( $item['mm_image'] ) ) echo $item['mm_image'][0]; ?>" />
                    <input type="button" id="mm_button" class="button" value="<?php _e( 'Choose or Upload an Image')?>" />
                </td>
            </tr>
            <tr class="mm_image_div <?php if(!$image) { echo 'hidden'; }?>">
                <th>
                    <a class="mm-delete-image" href="#">
                        <?php _e('Remove this image') ?>
                    </a>
                </th>
                <td>
                    <div class="image-preview-wrapper">
                        <img id="image-preview" src="<?php if($image){echo $image[0]; } ?>" height="100">
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <?php
    }

    function mm_custom_save($post_id)
    {
        // Checks save status
        $is_autosave = wp_is_post_autosave( $post_id );
        $is_revision = wp_is_post_revision( $post_id );
        $is_valid_nonce = ( isset( $_POST[ 'mm_nonce' ] ) && wp_verify_nonce( $_POST[ 'mm_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

        // Exits script depending on save status
        if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
            return;
        }
        $items = ['mm_image', 'mm_description', 'mm_color'];
        foreach($items as $item){
            if(isset($_POST[$item])){
                update_post_meta($post_id, $item, sanitize_text_field( $_POST[$item]));
            }
        }
    }
}
?>