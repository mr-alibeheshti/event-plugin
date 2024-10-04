<?php get_header(); ?>
<div class="event-archive-container">
    <h1 class="event-archive-title"><?php _e('Events', 'custom-events-plugin'); ?></h1>

    <div class="event-filter">
        <select id="event-sort">
            <option value="date"><?php _e('Newest', 'custom-events-plugin'); ?></option>
            <option value="title"><?php _e('Alphabetical', 'custom-events-plugin'); ?></option>
            <option value="popular"><?php _e('Most Popular', 'custom-events-plugin'); ?></option>
        </select>
    </div>

    <div class="event-grid">
        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'event',
            'posts_per_page' => 9,
            'paged' => $paged
        );

        // اگر فیلتر اعمال شده است، آرگومان‌های query را تغییر دهید
        if (isset($_GET['sort'])) {
            switch ($_GET['sort']) {
                case 'title':
                    $args['orderby'] = 'title';
                    $args['order'] = 'ASC';
                    break;
                case 'popular':
                    $args['meta_key'] = 'event_views';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                default:
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
            }
        }

        $query = new WP_Query($args);

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                $event_date = get_post_meta(get_the_ID(), '_event_date', true);
                $event_location = get_post_meta(get_the_ID(), '_event_location', true);
        ?>
                <a href="<?php the_permalink(); ?>" class="event-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="event-image">
                            <?php the_post_thumbnail('medium'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="event-details">
                        <h2 class="event-title"><?php the_title(); ?></h2>
                        <div class="event-meta">
                            <p><?php echo esc_html($event_date); ?> | <?php echo esc_html($event_location); ?></p>
                        </div>
                        <div class="event-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        <span class="event-link"><?php _e('Read More', 'custom-events-plugin'); ?></span>
                    </div>
                </a>
        <?php
            endwhile;
            wp_reset_postdata();

            // Pagination
            echo '<div class="pagination">';
            echo paginate_links(array(
                'total' => $query->max_num_pages,
                'current' => $paged
            ));
            echo '</div>';

        else :
            echo '<p>' . __('No events found', 'custom-events-plugin') . '</p>';
        endif;
        ?>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        $('#event-sort').on('change', function() {
            var sortValue = $(this).val();
            var currentUrl = window.location.href;
            var newUrl = updateQueryStringParameter(currentUrl, 'sort', sortValue);
            window.location.href = newUrl;
        });

        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            } else {
                return uri + separator + key + "=" + value;
            }
        }
    });
</script>

<?php get_footer(); ?>