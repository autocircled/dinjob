<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Dinjob
 */

?>

</div><!-- #page -->
<footer id="colophon" class="site-footer">
	<div class="site-info wrapper">
		<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'dinjob' ) ); ?>">
			<?php
			/* translators: %s: CMS name, i.e. WordPress. */
			printf( esc_html__( 'Proudly powered by %s', 'dinjob' ), 'WordPress' );
			?>
		</a>
		<span class="sep"> | </span>
			<?php
			/* translators: 1: Theme name, 2: Theme author. */
			printf( esc_html__( 'Theme: %1$s by %2$s.', 'dinjob' ), 'dinjob', '<a href="https://profiles.wordpress.org/autocircle/">AutoCircle</a>' );
			?>
	</div><!-- .site-info -->
</footer><!-- #colophon -->

<?php wp_footer(); ?>

</body>
</html>
