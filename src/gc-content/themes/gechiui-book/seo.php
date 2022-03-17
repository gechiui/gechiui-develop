<title><?php if ( is_tag() ) {
		echo trim(gc_title('',0));if($paged > 1) printf(' - 第%s页',$paged);echo ' | ';bloginfo( 'name' );
	} elseif ( is_archive() ) {
		echo trim(gc_title('',0));if($paged > 1) printf(' - 第%s页',$paged);echo ' | ';bloginfo( 'name' );
	} elseif ( is_search() ) {
		echo '&quot;'.gc_specialchars($s).'&quot;的搜索结果 | '; bloginfo( 'name' );
	} elseif ( is_home() ) {
		bloginfo( 'name' );echo' | ';bloginfo('description');$paged = get_query_var('paged'); if($paged > 1) printf(' - 第%s页',$paged);
	} elseif ( is_404() ) {
		echo '页面不存在！ |'; bloginfo( 'name' );
	} elseif ( is_single()) {
	    echo trim(gc_title('',0));if($paged > 1) printf(' - 第%s页',$paged);echo ' | ';bloginfo( 'name' );		
	} elseif (is_home() || is_front_page()){
		echo get_option('sybiaoti');
	}else {
		echo trim(gc_title('',0)).' | ';bloginfo( 'name' );
	} ?>
</title>
<?php if (is_home() || is_front_page()) { ?>
<meta name="keywords" content="<?php echo get_option('sygjc'); ?>"/>
<?php } ?>
<?php if (is_single()) { ?>
<meta name="keywords" content="<?php $keywords = "";$tags = gc_get_post_tags($post->ID);foreach ($tags as $tag ) {$keywords = $keywords . $tag->name . ",";}echo $keywords; ?>"/>
<?php } ?>
<?php
if(is_category()){
$category_info = get_the_category();
$category_id = $category_info[0]->cat_ID;
$cat_keywords = get_option('_category_keywords'.$category_id);
?>
<meta name="keywords" content="<?php echo $cat_keywords;?>"/>
<?php } ?>
<meta name="description" content="<?php if (is_home() || is_front_page()) {echo bloginfo('description');
} elseif (is_page()) { echo mb_strimwidth(strip_tags(apply_filters('the_content',$post->post_content)),0,220);
} elseif (is_single()) { echo mb_strimwidth(strip_tags(apply_filters('the_content',$post->post_content)),0,220);
} elseif (is_category()){echo strip_tags(category_description());
} elseif (is_tag()){echo strip_tags(category_description());
} ?>" />
