<?php
class mm_settings
{
    function __construct()
    {
        $this->mm_setup_settings();
        add_option("mm_amount", '4', '', 'yes');
        add_option("mm_name", 'Specifications', '', 'yes');

    }

    public function mm_setup_settings() {
        if(isset($_POST['updated'])) {
            if ($_POST['updated'] === 'true') {
                $this->handle_form();
            }
        }
        $amount = get_option('mm_amount' ,4)
        ?>
        <div class="wrap">
            <h2>Portfolio Settings</h2>
            <h3>Shortcode</h3>
            <p><b>Shortcode:</b> [mm_portfolio]</p>
            <h3>Options</h3>
            <table>
                <thead>
                <tr>
                    <td><b>Code</b</td>
                    <td><b>Example</b></td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>amount</td>
                    <td>[mm_portfolio amount="4"]</td>
                </tr>
                <tr>
                    <td>showfilters</td>
                    <td>[mm_portfolio showfilters="1"]</td>
                </tr>
                </tbody>
            </table>
            <p>The options can be combined into one single shortcode</p>
            <h3>Settings</h3>
            <form method="POST">
                <input type="hidden" name="updated" value="true" />
                <?php wp_nonce_field( 'mm_update', 'mm_settings_form' ); ?>
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th><label for="mm_amount">Amount of items per row</label></th>
                        <td>
                            <select name="mm_amount" id="mm_amount" class="regular-text">
                                <option<?php if($amount==2){ ?> selected <?php } ?>>2</option>
                                <option<?php if($amount==3){ ?> selected <?php } ?>>3</option>
                                <option<?php if($amount==4){ ?> selected <?php } ?>>4</option>
                                <option<?php if($amount==5){ ?> selected <?php } ?>>5</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="mm_name">Specifications name</label></th>
                        <td><input name="mm_name" id="mm_name" type="text" value="<?php echo get_option('mm_name'); ?>" class="regular-text" /></td>
                    </tr>
                    <tr>
                        <th><label for="mm_page">Main portfolio page</label></th>
                        <td>
                            <?php wp_dropdown_pages([
                                'depth'                 => 0,
                                'child_of'              => 0,
                                'selected'              => get_option('mm_page'),
                                'echo'                  => 1,
                                'name'                  => 'mm_page',
                            ]); ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
               <?php submit_button() ?>
            </form>
        </div> <?php
    }

    public function handle_form() {
        if( ! isset( $_POST['mm_settings_form'] ) || ! wp_verify_nonce( $_POST['mm_settings_form'], 'mm_update' ) ){ ?>
            <div class="error">
                <p>Sorry, your nonce was not correct. Please try again.</p>
            </div> <?php
            exit;
        } else {
            $values = ['mm_amount', 'mm_name', 'mm_page'];
            foreach ($values as $value){
                $field = sanitize_text_field($_POST[$value]);
                if($field){
                    update_option($value,$field);
                } else { ?>
                    <div class="error">
                        <p><?php echo substr($value, 3) ?> can not be empty.</p>
                    </div> <?php
                }
            }
        }
    }
}
?>
