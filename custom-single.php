<?php get_header();

$meta = get_post_meta($post->ID);

$image_load = wp_get_attachment_image_src($meta['mm_image'][0], 'mm-portfolio');
$color = isset($meta['mm_color']) ? $meta['mm_color'][0] : 'black';
$description =  isset($meta['mm_description']) ?  $meta['mm_description'][0] : '';

function display_member_taxonomy_terms($post_id, $color)
{
    //get all terms assigned to this post
    $member_terms = get_the_terms($post_id,'specification'); 

    //if we have member terms assigned to this post
    if($member_terms){
        echo '<div class="mm-terms">';
        echo '<h3 style="color:' . $color .'" class="term-title">';
        echo get_option('mm_name');
        echo '<br> </h3><ul>';
        foreach($member_terms as $term){
            echo '<li><span style="color:' . $color . '" class="dashicons dashicons-yes"></span>' . $term->name . '</li>';
        }
	    echo '</ul></div>';
    }
}
?>

<div class="mm-single-item">
    <div class="mm-menu">
        <div class="mm-close-button">
            <a href="<?php echo get_page_link(get_option('mm_page')); ?>">
                <span class="dashicons dashicons-no"></span>
            </a>
        </div>
        <div class="mm-title">
            <h1 style="color:<?php echo $color; ?>">
                <?php the_title(); ?>
            </h1>
        </div>
        <div class="mm-arrows">
            <?php
                $prev_post_id = get_adjacent_post(false,'',false);
                $next_post_id = get_adjacent_post(false,'',true);
                $prev_post = get_permalink($prev_post_id);
                $next_post = get_permalink($next_post_id);
                if(!empty($prev_post_id->ID)) echo '<div class="mm-right-arrow"><a href='.$prev_post.'><span class="dashicons dashicons-arrow-right-alt"></span></a></div>';
                if(!empty($next_post_id->ID)) echo '<div class="mm-left-arrow"><a href='.$next_post.'><span class="dashicons dashicons-arrow-left-alt"></span></a></div>';
            ?>
        </div>
    </div>
    <div class="mm-page-content">
        <div class="mm-half">
            <p>
                <?php echo $description ?>
            </p>
            <?php display_member_taxonomy_terms($post->ID, $color); ?>
        </div>
        <div class="mm-half">
            <img src="<?php echo($image_load[0]); ?>" alt="mm_item"/>
        </div>
    </div>
</div>
<?php get_footer(); ?>