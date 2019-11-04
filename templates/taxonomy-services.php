<?php
/**
 * The template for displaying custom library service locations
 * Based on the Screenr:archive template
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MAINLIB_MAGELLAN
 */

get_header(); ?>

<div id="content" class="site-content">

  <div id="content-inside" class="container <?php echo esc_attr( get_theme_mod( 'layout_settings', 'right' ) ); ?>-sidebar">
    <div id="primary" class="content-area">
      <main id="main" class="site-main" role="main">

        <?php if ( have_posts() ) : ?>

          <?php /* Start the Loop */ ?>
          <?php while ( have_posts() ) : the_post(); ?>
            <?php include("parts/content-taxonomy-services.php") ?>
          <?php endwhile; ?>

          <?php the_posts_navigation(); ?>

        <?php else : ?>

          <?php get_template_part( 'template-parts/content', 'none' ); ?>

        <?php endif; ?>

      </main><!-- #main -->
    </div><!-- #primary -->

    <?php get_sidebar(); ?>

  </div><!--#content-inside -->
</div><!-- #content -->

<?php get_footer(); ?>
