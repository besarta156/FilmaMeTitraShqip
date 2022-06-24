<?php get_header(); ?>

<?php $layout = gridlove_get_single_layout(); ?>

<?php if( $layout['cover'] ): ?>
	<div id="cover" class="gridlove-cover-area gridlove-cover-single">
		<?php get_template_part( 'template-parts/single/cover-' . $layout['cover'] ); ?>
	</div>
<?php endif; ?>

<?php get_template_part('template-parts/ads/below-header'); ?>

<?php $sidebar = gridlove_get_current_sidebar(); ?>

<div id="content" class="gridlove-site-content container gridlove-sidebar-<?php echo esc_attr( $sidebar['position'] ); ?>">

    <div class="row">

    	<?php get_template_part('template-parts/single/share');  ?>

        <div class="gridlove-content gridlove-single-layout-<?php echo esc_attr($layout['content']); ?>">

            <?php while( have_posts() ) : the_post(); ?>
                
                <?php get_template_part('template-parts/single/content-'.$layout['content']); ?>

            <?php endwhile; ?>
                

            <?php get_template_part('template-parts/ads/below-single'); ?>

            <?php comments_template(); ?>
            
            <?php get_template_part('template-parts/single/related'); ?>

        </div>

        <?php get_sidebar(); ?>
    </div>
        
</div>

<?php get_template_part('template-parts/single/infinite-scroll'); ?>

<?php get_footer(); ?>
