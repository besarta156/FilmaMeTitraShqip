<?php get_header(); ?>

<div id="content" class="gridlove-site-content container">

	<div class="row">
        
        <div class="gridlove-content gridlove-not-found">

	    		<div class="box-inner-p-bigger box-single gridlove-box">

		    		<?php echo gridlove_get_heading( array('title' => '<h1 class="entry-title h2">'.__gridlove( '404_title').'</h1>') );?>

					<div class="entry-content">
						<p><?php echo __gridlove( '404_text'); ?></p>
						<?php get_search_form(); ?>
					</div>

				</div>

        </div>
    	
    </div>

</div>

<?php get_footer(); ?>
