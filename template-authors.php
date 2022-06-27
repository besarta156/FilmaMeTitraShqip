<?php
/**
 * Template Name: Authors
 */
?>
<?php get_header(); ?>

<?php if ( post_password_required() ) : ?>
    <?php get_template_part( 'template-parts/page/protected' ); ?>
<?php else: ?>

    <?php $layout = gridlove_get_page_layout(); ?>

    <?php if( $layout['cover'] ): ?>
        <div id="cover" class="gridlove-cover-area">
            <?php get_template_part( 'template-parts/page/cover-' . $layout['cover'] ); ?>
        </div>
    <?php endif; ?>

    <?php get_template_part('template-parts/ads/below-header'); ?>

    <?php $sidebar = gridlove_get_current_sidebar(); ?>

    <div id="content" class="gridlove-site-content container gridlove-sidebar-<?php echo esc_attr( $sidebar['position']); ?>">

        <div class="row">

            <div class="gridlove-content gridlove-page-layout-<?php echo esc_attr( $layout['content'] ); ?>">


                <?php while( have_posts() ) : the_post(); ?>
                    
                <article id="post-<?php the_ID(); ?>" <?php post_class('gridlove-box box-vm'); ?>>

                    <?php get_template_part('template-parts/page/content-'.$layout['content']); ?>

                    </article>

                <?php endwhile; ?>

                <?php comments_template(); ?> 


            </div>
            
            <?php get_sidebar(); ?>
            
        </div>

    </div>

<?php endif; ?>

<?php get_footer(); ?>
