<?php

/**
 * Popular posts plugin class
 */

/**
 * Create Popular Posts widget
 */
class brunomag_popular_widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'brunomag_popular_widget', // Base ID
            __('Popular Posts', 'brunomag_popular_posts_plugin'), // Name
            array( 'description' => __( 'Displays list of the most popular posts based on views.', 'brunomag_popular_posts_plugin' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );

        echo $args['before_widget'];
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
                echo '<ul>';
        $query_args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 5,
                        'meta_key' => 'views',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC',
                        'ignore_sticky_posts' => true
                );
                $the_query = new WP_Query( $query_args );
        if ( $the_query->have_posts() ) :

                    /* Start the Loop */
                    while ( $the_query->have_posts() ) : $the_query->the_post();
                        echo '<li><a href="' . get_the_permalink() . '" rel="bookmark">' . get_the_title() . ' (' . (int) get_post_meta(get_the_ID(), 'views', true) . ')</a></li>';
                    endwhile;
                endif;
                echo '</ul>';
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'Popular Posts', 'brunomag_popular_posts_plugin' );
        }
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

} // class brunomag_popular_widget

// register Popular Posts widget
function brunomag_register_popular_posts_widget() {
    register_widget( 'brunomag_popular_widget' );
}
add_action( 'widgets_init', 'brunomag_register_popular_posts_widget' );

