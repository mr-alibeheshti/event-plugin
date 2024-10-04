<?php get_header(); ?>

<div class="single-event-container">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            $event_date = get_post_meta(get_the_ID(), '_event_date', true);
            $event_time = get_post_meta(get_the_ID(), '_event_time', true);
            $event_location = get_post_meta(get_the_ID(), '_event_location', true);
    ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-event'); ?>>
                <header class="event-header">
                    <h1 class="event-title"><?php the_title(); ?></h1>
                </header>

                <div class="event-main-content">
                    <div class="event-meta">
                        <p><strong><?php _e('Date:', 'custom-events-plugin'); ?></strong> <?php echo esc_html($event_date); ?></p>
                        <p><strong><?php _e('Time:', 'custom-events-plugin'); ?></strong> <?php echo esc_html($event_time); ?></p>
                        <p><strong><?php _e('Location:', 'custom-events-plugin'); ?></strong> <?php echo esc_html($event_location); ?></p>
                    </div>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="event-featured-image">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="event-separator"></div>

                <div class="event-content">
                    <?php the_content(); ?>
                </div>

                <footer class="event-footer">
                    <?php
                    $event_categories = get_the_term_list(get_the_ID(), 'event_category', '<p>' . __('Event Categories: ', 'custom-events-plugin'), ', ', '</p>');
                    if ($event_categories) {
                        echo $event_categories;
                    }
                    ?>
                </footer>
            </article>
    <?php
        endwhile;
    else :
        echo '<p>' . __('No event found', 'custom-events-plugin') . '</p>';
    endif;
    ?>
</div>

<?php get_footer(); ?>