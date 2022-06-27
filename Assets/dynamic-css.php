<?php

/* Font styles */

$main_font = gridlove_get_font_option( 'main_font' );
$h_font = gridlove_get_font_option( 'h_font' );
$nav_font = gridlove_get_font_option( 'nav_font' );
$font_size_p = absint( gridlove_get_option( 'font_size_p' ) ); 
$font_size_h1 = absint( gridlove_get_option( 'font_size_h1' ) );
$font_size_h2 = absint( gridlove_get_option( 'font_size_h2' ) );
$font_size_h3 = absint( gridlove_get_option( 'font_size_h3' ) );
$font_size_h4 = absint( gridlove_get_option( 'font_size_h4' ) );
$font_size_h5 = absint( gridlove_get_option( 'font_size_h5' ) );
$font_size_h6 = absint( gridlove_get_option( 'font_size_h6' ) );
$font_size_small = absint( gridlove_get_option( 'font_size_small' ) );
$font_size_nav = absint( gridlove_get_option( 'font_size_nav' ) );
$font_size_module = absint( gridlove_get_option( 'font_size_module' ) );


/* Top header styles */

$color_header_top_bg = esc_attr( gridlove_get_option( 'color_header_top_bg' ) );
$color_header_top_txt = esc_attr( gridlove_get_option( 'color_header_top_txt' ) );
$color_header_top_acc = esc_attr( gridlove_get_option( 'color_header_top_acc' ) );


/* Middle header styles */

$color_header_main_bg = esc_attr( gridlove_get_option( 'color_header_main_bg' ) );
$color_header_main_txt = esc_attr( gridlove_get_option( 'color_header_main_txt' ) );
$color_header_main_acc = esc_attr( gridlove_get_option( 'color_header_main_acc' ) );
$header_height = esc_attr( gridlove_get_option( 'header_height' ) );
$color_logo_bg = esc_attr( gridlove_get_option( 'color_logo_bg' ) );


/* Bottom header styles */

$color_header_bottom_bg = esc_attr( gridlove_get_option( 'color_header_bottom_bg' ) );
$color_header_bottom_txt = esc_attr( gridlove_get_option( 'color_header_bottom_txt' ) );
$color_header_bottom_acc = esc_attr( gridlove_get_option( 'color_header_bottom_acc' ) );


/* Sticky header styles */

$sticky_colors_from = gridlove_get_option('header_sticky_colors');
$color_header_sticky_bg = esc_attr( gridlove_get_option( 'color_header_'.$sticky_colors_from.'_bg' ) );
$color_header_sticky_txt = esc_attr( gridlove_get_option( 'color_header_'.$sticky_colors_from.'_txt' ) );
$color_header_sticky_acc = esc_attr( gridlove_get_option( 'color_header_'.$sticky_colors_from.'_acc' ) );


/* General styles */

$color_body_bg = esc_attr( gridlove_get_option( 'color_body_bg' ) );
$background_image = gridlove_get_bg_option( 'background_image' );
$color_module_h = esc_attr( gridlove_get_option( 'color_module_h' ) );
$color_content_bg = esc_attr( gridlove_get_option( 'color_content_bg' ) );
$color_content_h = esc_attr( gridlove_get_option( 'color_content_h' ) );
$color_content_txt = esc_attr( gridlove_get_option( 'color_content_txt' ) );
$color_content_acc = esc_attr( gridlove_get_option( 'color_content_acc' ) );
$color_content_meta = esc_attr( gridlove_get_option( 'color_content_meta' ) );


/* Highlight styles */

$color_highlight_bg = esc_attr( gridlove_get_option( 'color_highlight_bg' ) );
$color_highlight_txt = esc_attr( gridlove_get_option( 'color_highlight_txt' ) );
$color_highlight_acc = esc_attr( gridlove_get_option( 'color_highlight_acc' ) );


/* Footer styles */

$color_footer_bg = esc_attr( gridlove_get_option( 'color_footer_bg' ) );
$color_footer_txt = esc_attr( gridlove_get_option( 'color_footer_txt' ) );
$color_footer_acc = esc_attr( gridlove_get_option( 'color_footer_acc' ) );

/* Cover  styles */
$cover_h = esc_attr( gridlove_get_option( 'cover_h' ) );
$cover_type = esc_attr( gridlove_get_option( 'cover_type' ) );
$cover_w = esc_attr( gridlove_get_option( 'cover_w' ) );

?>

body{
    font-size: <?php echo $font_size_p; ?>px;
}
h1, .h1 {
  font-size: <?php echo $font_size_h1; ?>px;
}

h2, .h2,
.col-lg-12 .gridlove-post-b .h3 {
  font-size: <?php echo $font_size_h2; ?>px;
}

h3, .h3 {
  font-size: <?php echo $font_size_h3; ?>px;
}

h4, .h4 {
  font-size: <?php echo $font_size_h4; ?>px;
}

h5, .h5 {
  font-size: <?php echo $font_size_h5; ?>px;
}

h6, .h6 {
  font-size: <?php echo $font_size_h6; ?>px;
}

.widget, .gridlove-header-responsive .sub-menu, .gridlove-site-header .sub-menu{
  font-size: <?php echo $font_size_small; ?>px;
}

.gridlove-main-navigation {
  font-size: <?php echo $font_size_nav; ?>px;
}

.gridlove-post {
  font-size: <?php echo $font_size_module; ?>px;
}

body{
  background-color: <?php echo $color_body_bg; ?>;

  <?php echo $background_image; ?>

  color: <?php echo $color_content_txt; ?>;
  font-family: <?php echo $main_font['font-family']; ?>;
  font-weight: <?php echo $main_font['font-weight']; ?>;
  <?php if ( isset( $main_font['font-style'] ) && !empty( $main_font['font-style'] ) ):?>
    font-style: <?php echo $main_font['font-style']; ?>;
  <?php endif; ?>
}

h1, h2, h3, h4, h5, h6,
.h1, .h2, .h3, .h4, .h5, .h6,
blockquote,
thead td,
.comment-author b,
q:before,
#bbpress-forums .bbp-forum-title, 
#bbpress-forums .bbp-topic-permalink{
  color: <?php echo $color_content_h; ?>;
  font-family: <?php echo $h_font['font-family']; ?>;
  font-weight: <?php echo $h_font['font-weight']; ?>;
  <?php if ( isset( $h_font['font-style'] ) && !empty( $h_font['font-style'] ) ):?>
  font-style: <?php echo $h_font['font-style']; ?>;
  <?php endif; ?>
}

.gridlove-main-nav a,
.gridlove-posts-widget a{
  font-family: <?php echo $nav_font['font-family']; ?>;
  font-weight: <?php echo $nav_font['font-weight']; ?>;
  <?php if ( isset( $nav_font['font-style'] ) && !empty( $nav_font['font-style'] ) ):?>
  font-style: <?php echo $nav_font['font-style']; ?>;
  <?php endif; ?>
}

/* Header top  */

.gridlove-header-top{
  background-color: <?php echo $color_header_top_bg; ?>;
  color: <?php echo $color_header_top_txt; ?>;
}
.gridlove-header-top a{
  color: <?php echo $color_header_top_txt; ?>;
}
.gridlove-header-top a:hover{
  color: <?php echo $color_header_top_acc; ?>;
}


/* Header main */
.gridlove-header-wrapper,
.gridlove-header-middle .sub-menu,
.gridlove-header-responsive,
.gridlove-header-responsive .sub-menu{
  background-color:<?php echo $color_header_main_bg; ?>;
 }

.gridlove-header-middle,
.gridlove-header-middle a,
.gridlove-header-responsive,
.gridlove-header-responsive a{
  color: <?php echo $color_header_main_txt; ?>;
}
.gridlove-header-middle a:hover,
.gridlove-header-middle .gridlove-sidebar-action:hover,
.gridlove-header-middle .gridlove-actions-button > span:hover,
.gridlove-header-middle .current_page_item > a,
.gridlove-header-middle .current_page_ancestor > a,
.gridlove-header-middle .current-menu-item > a,
.gridlove-header-middle .current-menu-ancestor > a,
.gridlove-header-middle .gridlove-category-menu article:hover a,
.gridlove-header-responsive a:hover {
  color: <?php echo $color_header_main_acc; ?>;  
}
.gridlove-header-middle .active>span,
.gridlove-header-middle .gridlove-main-nav>li.menu-item-has-children:hover>a,
.gridlove-header-middle .gridlove-social-icons:hover>span,
.gridlove-header-responsive .active>span{
  background-color: <?php echo gridlove_hex2rgba( $color_header_main_txt , 0.05); ?>
}
.gridlove-header-middle .gridlove-button-search{
  background-color: <?php echo $color_header_main_acc; ?>;  
}

.gridlove-header-middle .gridlove-search-form input{
  border-color: <?php echo gridlove_hex2rgba( $color_header_main_txt , 0.1); ?>
}
.gridlove-header-middle .sub-menu,
.gridlove-header-responsive .sub-menu{
  border-top: 1px solid <?php echo gridlove_hex2rgba( $color_header_main_txt , 0.05); ?>
}
.gridlove-header-middle{
    height: <?php echo $header_height; ?>px;
}
.gridlove-logo{
    max-height: <?php echo $header_height; ?>px;  
}
.gridlove-branding-bg{
  background:<?php echo $color_logo_bg; ?>;
}
.gridlove-header-responsive .gridlove-actions-button:hover >span{
  color:<?php echo $color_header_main_acc ?>;  
}
.gridlove-sidebar-action .gridlove-bars:before,
.gridlove-sidebar-action .gridlove-bars:after{
  background:<?php echo $color_header_main_txt; ?>;
}
.gridlove-sidebar-action:hover .gridlove-bars:before,
.gridlove-sidebar-action:hover .gridlove-bars:after{
  background:<?php echo $color_header_main_acc ?>;
}
.gridlove-sidebar-action .gridlove-bars{
    border-color: <?php echo $color_header_main_txt; ?>;
}
.gridlove-sidebar-action:hover .gridlove-bars{
    border-color:<?php echo $color_header_main_acc; ?>;
}

.gridlove-header-bottom .sub-menu{
   background-color:<?php echo $color_header_main_bg; ?>;
}
.gridlove-header-bottom .sub-menu a{
  color:<?php echo $color_header_main_txt; ?>;
}
.gridlove-header-bottom .sub-menu a:hover,
.gridlove-header-bottom .gridlove-category-menu article:hover a{
  color:<?php echo $color_header_bottom_acc; ?>;
}

/* Header bottom  */

.gridlove-header-bottom{
  background-color:<?php echo $color_header_bottom_bg; ?>;
}


.gridlove-header-bottom,
.gridlove-header-bottom a{
  color: <?php echo $color_header_bottom_txt; ?>;
}
.gridlove-header-bottom a:hover,
.gridlove-header-bottom .gridlove-sidebar-action:hover,
.gridlove-header-bottom .gridlove-actions-button > span:hover,
.gridlove-header-bottom .current_page_item > a,
.gridlove-header-bottom .current_page_ancestor > a,
.gridlove-header-bottom .current-menu-item > a,
.gridlove-header-bottom .current-menu-ancestor > a {
  color: <?php echo $color_header_bottom_acc; ?>;  
}

.gridlove-header-bottom .active>span,
.gridlove-header-bottom .gridlove-main-nav>li.menu-item-has-children:hover>a,
.gridlove-header-bottom .gridlove-social-icons:hover>span{
  background-color: <?php echo gridlove_hex2rgba( $color_header_bottom_txt , 0.05); ?>
}
.gridlove-header-bottom .gridlove-search-form input{
  border-color: <?php echo gridlove_hex2rgba( $color_header_bottom_txt , 0.1); ?>
}
.gridlove-header-bottom,
.gridlove-header-bottom .sub-menu{
  border-top: 1px solid <?php echo gridlove_hex2rgba( $color_header_bottom_txt , 0.07); ?>
}
.gridlove-header-bottom .gridlove-button-search{
  background-color: <?php echo $color_header_bottom_acc; ?>;  
}


/* Header Sticky  */

.gridlove-header-sticky,
.gridlove-header-sticky .sub-menu{
  background-color:<?php echo $color_header_sticky_bg; ?>;
 }

.gridlove-header-sticky,
.gridlove-header-sticky a{
  color: <?php echo $color_header_sticky_txt; ?>;
}
.gridlove-header-sticky a:hover,
.gridlove-header-sticky .gridlove-sidebar-action:hover,
.gridlove-header-sticky .gridlove-actions-button > span:hover,
.gridlove-header-sticky .current_page_item > a,
.gridlove-header-sticky .current_page_ancestor > a,
.gridlove-header-sticky .current-menu-item > a,
.gridlove-header-sticky .current-menu-ancestor > a,
.gridlove-header-sticky .gridlove-category-menu article:hover a{
  color: <?php echo $color_header_sticky_acc; ?>;  
}

.gridlove-header-sticky .active>span,
.gridlove-header-sticky .gridlove-main-nav>li.menu-item-has-children:hover>a,
.gridlove-header-sticky .gridlove-social-icons:hover>span{
  background-color: <?php echo gridlove_hex2rgba( $color_header_sticky_txt , 0.05); ?>
}
.gridlove-header-sticky .gridlove-search-form input{
  border-color: <?php echo gridlove_hex2rgba( $color_header_sticky_txt , 0.1); ?>
}
.gridlove-header-sticky .sub-menu{
  border-top: 1px solid <?php echo gridlove_hex2rgba( $color_header_sticky_txt , 0.05); ?>
}
.gridlove-header-sticky .gridlove-button-search{
  background-color: <?php echo $color_header_sticky_acc; ?>;  
}


.gridlove-cover-area,
.gridlove-cover{
  height:<?php echo absint($cover_h); ?>px; 
}

<?php if($cover_type == 'fixed'): ?>
.gridlove-cover-area .gridlove-cover-bg img{
  width:<?php echo absint($cover_w); ?>px; 
}
<?php endif; ?>

.gridlove-box,
#disqus_thread{
  background: <?php echo $color_content_bg; ?>;
}



/* Links color */
a{
  color: <?php echo $color_content_txt; ?>;  
}
.entry-title a{
   color: <?php echo $color_content_h; ?>;  
}
a:hover,
.comment-reply-link,
#cancel-comment-reply-link,
.gridlove-box .entry-title a:hover,
.gridlove-posts-widget article:hover a{
  color: <?php echo $color_content_acc; ?>;  
}

.entry-content p a,
.widget_text a,
.entry-content ul a,
.entry-content ol a,
.gridlove-text-module-content p a{
  color: <?php echo $color_content_acc; ?>; 
  border-color: <?php echo gridlove_hex2rgba( $color_content_acc , 0.8); ?>;
}
.entry-content p a:hover,
.widget_text a:hover,
.entry-content ul a:hover,
.entry-content ol a:hover,
.gridlove-text-module-content p a:hover{
  border-bottom: 1px solid transparent;
}

.comment-reply-link:hover,
.gallery .gallery-item a:after, 
.wp-block-gallery .blocks-gallery-item a:after, 
.entry-content a.gridlove-popup-img {
  color: <?php echo $color_content_txt; ?>;   
}

/* Special cases */
.gridlove-post-b .box-col-b:only-child .entry-title a,
.gridlove-post-d .entry-overlay:only-child .entry-title a{
  color: <?php echo $color_content_h; ?>;
}
.gridlove-post-b .box-col-b:only-child .entry-title a:hover,
.gridlove-post-d .entry-overlay:only-child .entry-title a:hover,
.gridlove-content .entry-header .gridlove-breadcrumbs a,
.gridlove-content .entry-header .gridlove-breadcrumbs a:hover,
.module-header .gridlove-breadcrumbs a,
.module-header .gridlove-breadcrumbs a:hover {
   color: <?php echo $color_content_acc; ?>; 
}
.gridlove-post-b .box-col-b:only-child .meta-item,
.gridlove-post-b .box-col-b:only-child .entry-meta a,
.gridlove-post-b .box-col-b:only-child .entry-meta span,
.gridlove-post-d .entry-overlay:only-child .meta-item,
.gridlove-post-d .entry-overlay:only-child .entry-meta a,
.gridlove-post-d .entry-overlay:only-child .entry-meta span {
  color: <?php echo $color_content_meta; ?>;
}
/* Entry meta */

.entry-meta .meta-item, .entry-meta a, .entry-meta span,
.comment-metadata a{
    color: <?php echo $color_content_meta; ?>;  
}
blockquote{
  color: <?php echo gridlove_hex2rgba( $color_content_h, 0.8); ?>;
}
blockquote:before{
  color: <?php echo gridlove_hex2rgba( $color_content_h, 0.15); ?>;
}
.entry-meta a:hover{
  color: <?php echo $color_content_h; ?>;  
}
.widget_tag_cloud a,
.entry-tags a{
   background: <?php echo gridlove_hex2rgba( $color_content_txt, 0.1); ?>;
   color: <?php echo $color_content_txt; ?>; 
}

/* Buttons pills and links */

.submit,
.gridlove-button,
.mks_autor_link_wrap a,
.mks_read_more a,
input[type="submit"],
.gridlove-cat, 
.gridlove-pill,
.gridlove-button-search{
  color:#FFF;
  background-color: <?php echo $color_content_acc; ?>;  
}
.wp-block-button__link{
  background-color: <?php echo $color_content_acc; ?>;   
}
.gridlove-button:hover{
  color:#FFF;  
}
.gridlove-share a:hover{
  background:rgba(17, 17, 17, .8);
  color:#FFF;
}
.gridlove-pill:hover,
.gridlove-author-links a:hover,
.entry-category a:hover{
  background: #111;
  color: #FFF;
}
.gridlove-cover-content .entry-category a:hover,
.entry-overlay .entry-category a:hover,
.gridlove-highlight .entry-category a:hover,
.gridlove-box.gridlove-post-d .entry-overlay .entry-category a:hover,
.gridlove-post-a .entry-category a:hover,
.gridlove-highlight .gridlove-format-icon{
  background: #FFF;
  color: #111;  
}
.gridlove-author, .gridlove-prev-next-nav,
.comment .comment-respond{
  border-color: <?php echo gridlove_hex2rgba( $color_content_txt, 0.1); ?>;
}


/* Pagination */

.gridlove-load-more a,
.gridlove-pagination .gridlove-next a,
.gridlove-pagination .gridlove-prev a,
.gridlove-pagination .next,
.gridlove-pagination .prev,
.gridlove-infinite-scroll a,
.double-bounce1, .double-bounce2,
.gridlove-link-pages > span,
.module-actions ul.page-numbers span.page-numbers{
  color:#FFF;
  background-color: <?php echo $color_content_acc; ?>;
}
.gridlove-pagination .current{
  background-color:<?php echo gridlove_hex2rgba( $color_content_txt, 0.1); ?>;
}


/* Highlight */

.gridlove-highlight{
  background: <?php echo $color_highlight_bg; ?>;
}
.gridlove-highlight,
.gridlove-highlight h4,
.gridlove-highlight a{
  color: <?php echo $color_highlight_txt; ?>;
}
.gridlove-highlight .entry-meta .meta-item, 
.gridlove-highlight .entry-meta a, 
.gridlove-highlight .entry-meta span,
.gridlove-highlight p{
  color: <?php echo gridlove_hex2rgba( $color_highlight_txt, 0.8); ?>;
}
.gridlove-highlight .gridlove-author-links .fa-link,
.gridlove-highlight .gridlove_category_widget .gridlove-full-color li a:after{
  background: <?php echo $color_highlight_acc; ?>;
}

.gridlove-highlight .entry-meta a:hover{
  color: <?php echo $color_highlight_txt; ?>;
}

.gridlove-highlight.gridlove-post-d .entry-image a:after{
  background-color: <?php echo gridlove_hex2rgba( $color_highlight_bg, 0.7); ?>;
}
.gridlove-highlight.gridlove-post-d:hover .entry-image a:after{
  background-color: <?php echo gridlove_hex2rgba( $color_highlight_bg, 0.9); ?>;
}
.gridlove-highlight.gridlove-post-a .entry-image:hover>a:after, 
.gridlove-highlight.gridlove-post-b .entry-image:hover a:after{
  background-color: <?php echo gridlove_hex2rgba( $color_highlight_bg, 0.2); ?>;
}
.gridlove-highlight .gridlove-slider-controls > div{
  background-color: <?php echo gridlove_hex2rgba( $color_highlight_txt, 0.1); ?>;
  color: <?php echo $color_highlight_txt; ?>; 
}

.gridlove-highlight .gridlove-slider-controls > div:hover{
  background-color: <?php echo gridlove_hex2rgba( $color_highlight_txt, 0.3); ?>;
  color: <?php echo $color_highlight_txt; ?>; 
}



.gridlove-highlight.gridlove-box .entry-title a:hover{
  color: <?php echo $color_highlight_acc; ?>;
}
.gridlove-highlight.widget_meta a, 
.gridlove-highlight.widget_recent_entries li, 
.gridlove-highlight.widget_recent_comments li, 
.gridlove-highlight.widget_nav_menu a, 
.gridlove-highlight.widget_archive li, 
.gridlove-highlight.widget_pages a{
  border-color: <?php echo gridlove_hex2rgba( $color_highlight_txt, 0.1); ?>;  
}

.gridlove-cover-content .entry-meta .meta-item, 
.gridlove-cover-content .entry-meta a, 
.gridlove-cover-content .entry-meta span,
.gridlove-cover-area .gridlove-breadcrumbs,
.gridlove-cover-area .gridlove-breadcrumbs a,
.gridlove-cover-area .gridlove-breadcrumbs .breadcrumb_last,
body .gridlove-content .entry-overlay .gridlove-breadcrumbs {
  color: rgba(255, 255, 255, .8);
}
.gridlove-cover-content .entry-meta a:hover,
.gridlove-cover-area .gridlove-breadcrumbs a:hover {
  color: rgba(255, 255, 255, 1);
}


.module-title h2,
.module-title .h2{
   color: <?php echo $color_module_h; ?>; 
}
.gridlove-action-link,
.gridlove-slider-controls > div,
.module-actions ul.page-numbers .next.page-numbers,
.module-actions ul.page-numbers .prev.page-numbers{
    background: <?php echo gridlove_hex2rgba( $color_module_h, 0.1); ?>; 
    color: <?php echo $color_module_h; ?>;   
}
.gridlove-slider-controls > div:hover,
.gridlove-action-link:hover,
.module-actions ul.page-numbers .next.page-numbers:hover,
.module-actions ul.page-numbers .prev.page-numbers:hover{
  color: <?php echo $color_module_h; ?>;
  background: <?php echo gridlove_hex2rgba( $color_module_h, 0.3); ?>; 
}

.gridlove-pn-ico,
.gridlove-author-links .fa-link{
    background: <?php echo gridlove_hex2rgba( $color_content_txt, 0.1); ?>; 
    color: <?php echo $color_content_txt; ?>;    
}

.gridlove-prev-next-nav a:hover .gridlove-pn-ico{
    background: <?php echo gridlove_hex2rgba( $color_content_acc, 1); ?>; 
    color: <?php echo $color_content_bg; ?>;    
}


/* Widget elements */

.widget_meta a,
.widget_recent_entries li,
.widget_recent_comments li,
.widget_nav_menu a,
.widget_archive li,
.widget_pages a,
.widget_categories li,
.gridlove_category_widget .gridlove-count-color li,
.widget_categories .children li,
.widget_archiv .children li{
  border-color: <?php echo gridlove_hex2rgba( $color_content_txt, 0.1); ?>;  
}
.widget_recent_entries a:hover,
.menu-item-has-children.active > span,
.menu-item-has-children.active > a,
.gridlove-nav-widget-acordion:hover,
.widget_recent_comments .recentcomments a.url:hover{
  color: <?php echo $color_content_acc; ?>;
}
.widget_recent_comments .url,
.post-date,
.widget_recent_comments .recentcomments,
.gridlove-nav-widget-acordion,
.widget_archive li,
.rss-date,
.widget_categories li,
.widget_archive li{
  color:<?php echo $color_content_meta; ?>;
}
.widget_pages .children,
.widget_nav_menu .sub-menu{
  background:<?php echo $color_content_acc; ?>;
  color:#FFF;
}
.widget_pages .children a,
.widget_nav_menu .sub-menu a,
.widget_nav_menu .sub-menu span,
.widget_pages .children span{
  color:#FFF;
}
.widget_tag_cloud a:hover,
.entry-tags a:hover{
  background: <?php echo $color_content_acc; ?>;
  color:#FFF;  
}


/* Footer */

.gridlove-footer{
  background: <?php echo $color_footer_bg; ?>;
  color: <?php echo $color_footer_txt; ?>;
}
.gridlove-footer .widget-title{
  color: <?php echo $color_footer_txt; ?>;
}
.gridlove-footer a{
  color: <?php echo gridlove_hex2rgba( $color_footer_acc, 0.8); ?>;  
}
.gridlove-footer a:hover{
  color: <?php echo $color_footer_acc; ?>;
}
.gridlove-footer .widget_recent_comments .url, 
.gridlove-footer .post-date, 
.gridlove-footer .widget_recent_comments .recentcomments, 
.gridlove-footer .gridlove-nav-widget-acordion, 
.gridlove-footer .widget_archive li, 
.gridlove-footer .rss-date{
  color: <?php echo gridlove_hex2rgba( $color_footer_txt, 0.8); ?>;   
}
.gridlove-footer .widget_meta a, 
.gridlove-footer .widget_recent_entries li, 
.gridlove-footer .widget_recent_comments li, 
.gridlove-footer .widget_nav_menu a, 
.gridlove-footer .widget_archive li, 
.gridlove-footer .widget_pages a,
.gridlove-footer table,
.gridlove-footer td,
.gridlove-footer th,
.gridlove-footer .widget_calendar table,
.gridlove-footer .widget.widget_categories select,
.gridlove-footer .widget_calendar table tfoot tr td{
  border-color: <?php echo gridlove_hex2rgba( $color_footer_txt, 0.2); ?>;    
}

/* Form elements */
table,
thead,
td,
th,
.widget_calendar table{
 border-color: <?php echo gridlove_hex2rgba( $color_content_txt, 0.1); ?>;  
}
input[type="text"], input[type="email"], input[type="url"], input[type="tel"], input[type="number"], input[type="date"], input[type="password"], select, textarea{
  border-color: <?php echo gridlove_hex2rgba( $color_content_txt, 0.2); ?>;  
}

div.mejs-container .mejs-controls {
  background-color: <?php echo gridlove_hex2rgba( $color_content_txt, 0.1); ?>;
}
body .mejs-controls .mejs-time-rail .mejs-time-current{
  background: <?php echo $color_content_acc; ?>;
}
body .mejs-video.mejs-container .mejs-controls{
  background-color: <?php echo gridlove_hex2rgba( $color_content_bg, 0.9); ?>;
}

.wp-block-cover .wp-block-cover-image-text, .wp-block-cover .wp-block-cover-text, 
.wp-block-cover h2, .wp-block-cover-image .wp-block-cover-image-text, 
.wp-block-cover-image .wp-block-cover-text, .wp-block-cover-image h2{
  font-family: <?php echo $h_font['font-family']; ?>;
  font-weight: <?php echo $h_font['font-weight']; ?>;
  <?php if ( isset( $h_font['font-style'] ) && !empty( $h_font['font-style'] ) ):?>
  font-style: <?php echo $h_font['font-style']; ?>;
  <?php endif; ?> 
}
.wp-block-cover-image .wp-block-cover-image-text, .wp-block-cover-image h2{
  font-size: <?php echo $font_size_h2; ?>px;  
}

/* WooCommerce classes */
<?php if ( gridlove_is_woocommerce_active() ) { ?>
.woocommerce ul.products li.product .button,
.woocommerce ul.products li.product .added_to_cart,
body.woocommerce .button,
body.woocommerce-page .button,
.woocommerce .widget_shopping_cart_content .buttons .button,
.woocommerce div.product div.summary .single_add_to_cart_button,
.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover,
.woocommerce-page #payment #place_order,
.woocommerce #review_form #respond .form-submit input,
.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
.woocommerce .widget_price_filter .ui-slider .ui-slider-handle{
  color:#FFF;
  background-color: <?php echo $color_content_acc; ?>;    
}
.woocommerce div.product .woocommerce-tabs ul.tabs li.active a{
  border-bottom: 3px solid <?php echo $color_content_acc; ?>;
}
.product-categories li,
.product-categories .children li {
  color:<?php echo $color_content_meta; ?>;
}
.product-categories .children li {
  border-top: 1px solid <?php echo gridlove_hex2rgba( $color_content_txt, 0.1); ?>; 
}
.product-categories li{
   border-bottom: 1px solid <?php echo gridlove_hex2rgba( $color_content_txt, 0.1); ?>; 
}
<?php } ?>

.widget_display_replies li, .widget_display_topics li{
   color:<?php echo $color_content_meta; ?>; 
}


<?php

/* Apply uppercase options */

$uppercase = gridlove_get_option( 'uppercase' );
if ( !empty( $uppercase ) ) {
  foreach ( $uppercase as $text_class => $val ) {
    if ( $val ){
      echo '.'.$text_class.'{text-transform: uppercase;}';
    }
  }
}

/* Generate css for category colors */

echo '.gridlove-cats .gridlove-cat .entry-overlay-wrapper .entry-image:after, 
      .gridlove-cat.gridlove-post-d .entry-image a:after { background-color: '. gridlove_hex2rgba( $color_content_acc, 0.7) .'; }'; 
echo '.gridlove-cat.gridlove-post-d:hover .entry-image a:after { background-color: '. gridlove_hex2rgba( $color_content_acc, 0.9) .'; }'; 

$cat_colors = get_option( 'gridlove_cat_colors' );

if ( !empty( $cat_colors ) ) {
  foreach ( $cat_colors as $cat => $color ) {
    if( $cat != 0) {
      echo '.gridlove-cat-'.$cat.'{ background: '.$color.';}';
      echo '.gridlove-cat-col-'.$cat.':hover{ color: '.$color.';}';    
      echo '.gridlove-cats .gridlove-cat-'.$cat.' .entry-overlay-wrapper .entry-image:after, 
            .gridlove-cat-'.$cat.'.gridlove-post-d .entry-image a:after { background-color: '. gridlove_hex2rgba( $color, 0.7) .'; }'; 
      echo '.gridlove-cat-'.$cat.'.gridlove-post-d:hover .entry-image a:after { background-color: '. gridlove_hex2rgba( $color, 0.9) .'; }'; 
    }
  }
}
?>
