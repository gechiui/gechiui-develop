<?php get_header(); ?>
<div class="book-body">
    <div class="body-inner">
		<div class="book-header" role="navigation">
			<div class="pull-right">
                <div class="bdsharebuttonbox">
                	<!--<a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a>-->
                	<a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                	<a href="#" class="bds_fbook" data-cmd="fbook" title="分享到Facebook"></a>
                	<a href="#" class="bds_twi" data-cmd="twi" title="分享到Twitter"></a>
                	<a href="#" class="bds_linkedin" data-cmd="linkedin" title="分享到linkedin"></a>
                </div>
            </div>
		    <h1><a href="<?php global $gc; $current_url = home_url(add_query_arg(array(),$gc->request));echo $current_url;?>"><?php single_cat_title(); ?></a></h1>
		</div> 
		<div class="page-wrapper" tabindex="-1" role="main">
			<div class="page-inner">
				<section class="normal markdown-section">
				<h1 id="<?php single_cat_title(); ?>"><?php single_cat_title(); ?><a class="anchorjs-link " href="#<?php single_cat_title(); ?>" data-anchorjs-icon=""></a></h1>
				<p><span class="sa-last-update-time">最后更新于：<?php the_modified_time('Y-m-d H:i:s'); ?></span></p>
				<p><?php echo category_description(); ?> </p>
				<ul>
				<?php while( have_posts() ): the_post(); ?>
					<li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
				<?php endwhile; ?>
				</ul>
				<div class="posts-nav clearfix">
					<?php echo paginate_links(array(  
					    'prev_next' => 1,  
					    'before_page_number' => '',  
					    'mid_size' => 4,  
					    'prev_text' => __('<'),  
					    'next_text' => __('>'),  
					));  
					?>
				</div>
			</div>
		</div>
        <div class="banquan">
            <?php echo get_option('footbanquan'); ?>
        </div>
    </div>
    <a href="" rel="nofollow" class="navigation navigation-prev" id="shangyige">
        <i class="fa fa-angle-left"></i>
    </a>
    <a href="" rel="nofollow" class="navigation navigation-next" id="xiayige">
        <i class="fa fa-angle-right"></i>
    </a>
</div>
</div>
<?php get_footer(); ?>