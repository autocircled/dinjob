<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Dinjob
 */

if ( ! function_exists( 'dinjob_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function dinjob_posted_on() {
		

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		

		$post_date = get_the_time('U');
        $delta = time() - $post_date;
		$publish_time = '';
        if ( $delta < 60 ) {
            $publish_time = 'Less than a minute ago';
        }
        elseif ($delta > 60 && $delta < 120){
            $publish_time = 'About a minute ago';
        }
        elseif ($delta > 120 && $delta < (60*60)){
            $publish_time = strval(round(($delta/60),0)) . ' minutes ago';
        }
        elseif ($delta > (60*60) && $delta < (120*60)){
            $publish_time = 'About an hour ago';
        }
        elseif ($delta > (120*60) && $delta < (24*60*60)){
            $publish_time = strval(round(($delta/3600),0)) . ' hours ago';
        }
        elseif ($delta > (24*60*60) && $delta < (48*60*60)){
            $publish_time = '1 day ago';
        }
        elseif ($delta > (48*60*60) && $delta < (7*24*60*60)){
            $publish_time = strval(round(($delta/86400),0)) . ' days ago';
        }
        elseif ($delta > (7*24*60*60) && $delta < (14*24*60*60)){
            $publish_time = '1 week ago';
        }
        elseif ($delta > (14*24*60*60) && $delta < (28*24*60*60)){
            $publish_time = strval(round(($delta/604800),0)) . ' weeks ago';
        }
        elseif ($delta > (28*24*60*60) && $delta < (30*24*60*60)){
            $publish_time = strval(round(($delta/86400),0)) . ' days ago';
        }
        elseif ($delta > (30*24*60*60) && $delta < (60*24*60*60)){
            $publish_time = '1 month ago';
        }
        elseif ($delta > (60*24*60*60) && $delta < (12*30*24*60*60)){
            $publish_time = strval(round(($delta/2592000),0)) . ' months ago';
        }
        else {
            $publish_time = 'Published on ' . get_the_time('j M y g:i a');
        }

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			// esc_html( get_the_date() ),
			esc_html( $publish_time ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);
		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( '%s', 'post date', 'dinjob' ),
			'<span class="publish-time">' . $time_string . '</span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'dinjob_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function dinjob_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'dinjob' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'dinjob_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function dinjob_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'dinjob' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'dinjob' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'dinjob' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'dinjob' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'dinjob' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'dinjob' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'dinjob_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function dinjob_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;
