<?php $sidebar = gridlove_get_current_sidebar(); ?>

<?php if( $sidebar['position'] != 'none') : ?>

	<div class="gridlove-sidebar">

		<?php if ( $sidebar['standard'] != 'none' && is_active_sidebar( $sidebar['standard'] ) ) : ?>
				<?php dynamic_sidebar( $sidebar['standard'] ); ?>
		<?php endif; ?>

		<?php if ( $sidebar['sticky'] != 'none' && is_active_sidebar( $sidebar['sticky'] ) ) : ?>
				<div class="gridlove-sticky-sidebar">
					<?php dynamic_sidebar( $sidebar['sticky'] ); ?>
				</div>
		<?php endif; ?>

	</div>

<?php endif; ?>
