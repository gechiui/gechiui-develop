<?php get_header(); ?>
<div class="book-body">
    <div class="body-inner">
		<div class="book-header" role="navigation">
		    <h1><a href="<?php global $gc; $current_url = home_url(add_query_arg(array(),$gc->request));echo $current_url;?>"><?php single_cat_title(); ?></a></h1>
		</div> 
		<div class="page-wrapper" tabindex="-1" role="main">
			<div class="page-inner">
				<section class="normal markdown-section">
				<h1><?php echo'搜索词：'.get_query_var( 's' ); ?></h1>
				<ul>
				<?php while( have_posts() ): the_post(); ?>
					<li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" target="_blank"><?php the_title(); ?></a></li>
				<?php endwhile; ?>
				</ul>
			</div>
		</div>
        <div class="banquan">
            <?php echo get_option('footbanquan'); ?>
        </div> 
    </div>
</div>
</div>
<?php get_footer(); ?>