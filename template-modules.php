<?php
/**
 * Template Name: Modules
 */
?>
<?php get_header(); ?>

<?php if ( post_password_required() ) : ?>
    <?php get_template_part( 'template-parts/page/protected' ); ?>
<?php else: ?>

    <?php if( $cover = gridlove_get_cover_layout() ) : ?>
        <?php get_template_part( 'template-parts/cover/layout-' . $cover ); ?>
    <?php endif; ?>

    <?php get_template_part('template-parts/ads/below-header'); ?>

    <div id="content" class="gridlove-site-content container">

        <?php $modules = gridlove_get_modules(); ?>

        <?php if( !empty( $modules ) ): ?>

            <?php foreach( $modules as $m_ind => $module ) : $module = gridlove_parse_args( $module, gridlove_get_module_defaults( $module['type'] ) ); ?>
                    
                    <?php if ($module['active']): ?>
                        <?php $module_template = isset( $module['cpt']) ? 'cpt' : $module['type']; ?>
                        <?php include( locate_template('template-parts/modules/'.$module_template.'.php') ); ?>
                    <?php endif ?>

            <?php endforeach; ?>

        <?php else: ?>
            
            <?php include( locate_template('template-parts/modules/empty.php') ); ?>

        <?php endif; ?>

    </div>

<?php endif; ?>

<?php get_footer(); ?>
