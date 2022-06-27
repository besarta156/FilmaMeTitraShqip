<?php
/**
 * Template Name: Blank Page (full width)
 */
?>

<?php get_header(); ?>

<?php get_template_part('template-parts/ads/below-header'); ?>

<div id="content" class="gridlove-site-content container">

    	<div class="gridlove-content gridlove-full-width">

            <?php while( have_posts() ) : the_post(); ?>
                
               <article id="post-<?php the_ID(); ?>" <?php post_class('gridlove-box box-vm'); ?>>

					<div class="box-inner-p-bigger box-single">

					    <?php get_template_part('template-parts/page/entry-header'); ?>

					    <?php get_template_part('template-parts/page/entry-content'); ?>

					</div>

                </article>

            <?php endwhile; ?>

            <?php comments_template(); ?>

        </div>  	

</div>

<?php get_footer(); ?>
