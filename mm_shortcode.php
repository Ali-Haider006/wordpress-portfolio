<?php
class mm_shortcode
{
    public static function mm_portfolio_items($atts)
    {
        $atts = shortcode_atts([
                'showfilters' => 0,
                'amount' => '',
                'columns' => '3',
            ],
            $atts,
            'mm_portfolio'
        );

        if(!$atts['amount']){
            $args = ['post_type'=> 'mm_portfolio_item', 'order' => 'ASC', 'posts_per_page'  => '-1'];
        }
        else {
            $args = ['post_type'=> 'mm_portfolio_item', 'order' => 'ASC', 'posts_per_page'  => $atts['amount']];
        }

        $items = get_posts($args);

       // global $post;

        ob_start();?>

        <div class="mm-portfolio-container">
            <?php
            if($atts['showfilters'] == "1"){
                $terms = get_terms('specification');
                if (!empty( $terms ) && ! is_wp_error( $terms ) ){
                    echo '<div class="mm-controls">';
                    foreach ( $terms as $term ) {
                        if (!$term->parent) {
                            echo '<button class="mm_filter" data-filter=".mm-cat-' . strtolower($term->name) . '">' . $term->name . '</button>';
                        }
                    }
                    echo '<button class="mm_filter" data-filter="all">All</button>';
                    echo "</div>";
                }
            }
            foreach($items as $item) {
                $meta = get_post_meta($item->ID);
                $meta_term = wp_get_post_terms($item->ID, 'specification', array("fields" => "all"));
                $amount = 'mm-size-'.get_option('mm_amount');
                $string = "";
                foreach($meta_term as $value) {
                    if (!$value->parent) {
                        $string .= 'mm-cat-'.strtolower($value->slug) . ' ';
                    }
                }
                $string = substr($string, 0, -1);
                $image_load = wp_get_attachment_image_src($meta['mm_image'][0], 'mm-portfolio');
                ?>
                <div  class="mm-item <?php echo $amount; echo $string ? ' '.$string : '';?>" style="background-color:<?php echo $meta['mm_color'][0] ?>;">
                    <img class="mm-cover" src="<?php echo($image_load[0]); ?>" alt="portfolio-item">
                    <div class="mm-info">
                        <h2><?php echo get_the_title($item); ?></h2>
                        <a href="<?php the_permalink($item); ?>"></a>
                    </div>
                </div>

            <?php } ?>

        </div>
        <?php
        return ob_get_clean();
    }

}
?>
