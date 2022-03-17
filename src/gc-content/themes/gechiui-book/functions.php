<?php

add_action('admin_init', 'remove_dashboard_meta');
register_nav_menus(array('main' => __('列表导航')));
add_filter('rest_enabled', '_return_false');
add_filter('rest_jsonp_enabled', '_return_false');
add_filter('admin_title', 'gcdx_custom_admin_title', 10, 2);
add_filter('rest_enabled', '_return_false');
add_filter('rest_jsonp_enabled', '_return_false');
add_action('init', 'disable_emojis');
add_filter('gc_resource_hints', 'remove_dns_prefetch', 10, 2);
add_filter('style_loader_src', 'gcdaxue_remove_cssjs_ver', 999);
add_filter('script_loader_src', 'gcdaxue_remove_cssjs_ver', 999);
add_action('init', 'disable_embeds_init', 9999);
register_activation_hook(__FILE__, 'disable_embeds_remove_rewrite_rules');
register_deactivation_hook(__FILE__, 'disable_embeds_flush_rewrite_rules');
add_filter('the_content', 'v7v3_seo_wl');
add_filter('the_content', 'imagesalt');
add_filter('image_size_names_choose', 'ztmao_remove_image_size');
add_action('init', 'remove_open_sans');
add_filter('mce_buttons_3', 'enable_more_buttons');
add_filter('tiny_mce_before_init', 'custum_fontfamily');
add_filter('category_description', 'deletehtml');
add_filter('pre_get_posts', 'search_filter_page');
add_filter('the_content', 'wd_content');
add_action('load-themes.php', 'no_category_base_refresh_rules');
add_action('created_category', 'no_category_base_refresh_rules');
add_action('edited_category', 'no_category_base_refresh_rules');
add_action('delete_category', 'no_category_base_refresh_rules');
add_action('init', 'no_category_base_permastruct');
add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
add_filter('query_vars', 'no_category_base_query_vars');
add_filter('request', 'no_category_base_request');
global $texonomy_slug_keywords;
$texonomy_slug_keywords = 'category';
add_action($texonomy_slug_keywords . '_add_form_fields', 'categorykeywords');
add_action($texonomy_slug_keywords . '_edit_form_fields', 'categorykeywordsedit');
add_action('edit_term', 'categorykeywordssave');
add_action('create_term', 'categorykeywordssave');
add_action('customize_register', 'puma_customize_register');
add_action('customize_register', 'lgurl_customize_register');
add_action('customize_register', 'bt_customize_register');
add_action('customize_register', 'sygjc_customize_register');
add_action('customize_register', 'ms_customize_register');

function remove_dashboard_meta()
{
	remove_meta_box('dashboard_activity', 'dashboard', 'normal');
}
function gcse50723_remove_help($old_help, $screen_id, $screen)
{
	$screen->remove_help_tabs();
	return $old_help;
}
function gcdx_custom_admin_title($admin_title, $title)
{
	return $title . ' &lsaquo; ' . get_bloginfo('name');
}
function disable_emojis()
{
	add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
}
function disable_emojis_tinymce($plugins)
{
	if (is_array($plugins)) {
		return array_diff($plugins, array('gcemoji'));
	}
	return array();
}
function remove_dns_prefetch($hints, $relation_type)
{
	if ('dns-prefetch' === $relation_type) {
		return array_diff(gc_dependencies_unique_hosts(), $hints);
	}
	return $hints;
}
function gcdaxue_remove_cssjs_ver($src)
{
	if (strpos($src, 'ver=')) {
		$src = remove_query_arg('ver', $src);
	}
	return $src;
}
function disable_embeds_init()
{
	global $gc;
	$gc->public_query_vars = array_diff($gc->public_query_vars, array('embed'));
	remove_action('rest_api_init', 'gc_oembed_register_route');
	add_filter('embed_oembed_discover', '__return_false');
	add_filter('tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin');
	add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
}
function disable_embeds_tiny_mce_plugin($plugins)
{
	return array_diff($plugins, array('gcembed'));
}
function disable_embeds_rewrites($rules)
{
	foreach ($rules as $rule => $rewrite) {
		if (false !== strpos($rewrite, 'embed=true')) {
			unset($rules[$rule]);
		}
	}
	return $rules;
}
function disable_embeds_remove_rewrite_rules()
{
	add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
	flush_rewrite_rules();
}
function disable_embeds_flush_rewrite_rules()
{
	remove_filter('rewrite_rules_array', 'disable_embeds_rewrites');
	flush_rewrite_rules();
}
function v7v3_seo_wl($content)
{
	$regexp = '<a\\s[^>]*href=("??)([^" >]*?)\\1[^>]*>';
	if (preg_match_all('/' . $regexp . '/siU', $content, $matches, PREG_SET_ORDER)) {
		if (!empty($matches)) {
			$srcUrl = get_option('siteurl');
			for ($i = 0; $i < count($matches); $i++) {
				$tag = $matches[$i][0];
				$tag2 = $matches[$i][0];
				$url = $matches[$i][0];
				$noFollow = '';
				$pattern = '/target\\s*=\\s*"\\s*_blank\\s*"/';
				preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
				if (count($match) < 1) {
					$noFollow .= ' target="_blank" ';
				}
				$pattern = '/rel\\s*=\\s*"\\s*[n|d]ofollow\\s*"/';
				preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
				if (count($match) < 1) {
					$noFollow .= ' rel="nofollow" ';
				}
				$pos = strpos($url, $srcUrl);
				if ($pos === false) {
					$tag = rtrim($tag, '>');
					$tag .= $noFollow . '>';
					$content = str_replace($tag2, $tag, $content);
				}
			}
		}
	}
	$content = str_replace(']]>', ']]>', $content);
	return $content;
}
function imagesalt($content)
{
	global $post;
	$pattern = '/<a(.*?)href=(\'|")(.*?).(bmp|gif|jpeg|jpg|png)(\'|")(.*?)>/i';
	$replacement = '<a$1href=$2$3.$4$5 alt="' . $post->post_title . '" title="' . $post->post_title . '"$6>';
	$content = preg_replace($pattern, $replacement, $content);
	return $content;
}

function ztmao_remove_image_size($sizes)
{
	unset($sizes['small']);
	unset($sizes['medium']);
	unset($sizes['large']);
	return $sizes;
}

// function remove_open_sans()
// {
// 	gc_deregister_style('open-sans');
// 	gc_register_style('open-sans', false);
// 	gc_enqueue_style('open-sans', '');
// }

function enable_more_buttons($buttons)
{
	$buttons[] = 'del';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'cleanup';
	$buttons[] = 'styleselect';
	$buttons[] = 'gc_page';
	$buttons[] = 'anchor';
	$buttons[] = 'backcolor';
	return $buttons;
}
function custum_fontfamily($initArray)
{
	$initArray['font_formats'] = '微软雅黑=\'微软雅黑\';宋体=\'宋体\';黑体=\'黑体\';仿宋=\'仿宋\';楷体=\'楷体\';隶书=\'隶书\';幼圆=\'幼圆\';';
	return $initArray;
}
function deletehtml($description)
{
	$description = trim($description);
	$description = strip_tags($description, '');
	return $description;
}

function search_filter_page($query)
{
	if ($query->is_search) {
		$query->set('post_type', 'post');
	}
	return $query;
}
function replace_heading($content)
{
	global $toc_count;
	global $toc;
	$toc_count++;
	$toc[] = array('text' => trim(strip_tags($content[3])), 'depth' => $content[1], 'count' => $toc_count);
	return '<h' . $content[1] . ' id="' . $content[3] . '">' . $content[3] . '<a data-anchorjs-icon="" class="anchorjs-link" href="#' . $content[3] . '"></a></h' . $content[1] . '>';
}
function wd_content($content)
{
	$depe = 6;
	global $toc_count;
	global $toc;
	$toc = array();
	$toc_count = 0;
	$regex = '/<h([1-' . $depe . '])(.*?)>(.*?)<\\/h\\1>/';
	$content = preg_replace_callback($regex, 'replace_heading', $content);
	return $content;
}
function no_category_base_refresh_rules()
{
	global $gc_rewrite;
	$gc_rewrite->flush_rules();
}
function no_category_base_permastruct()
{
	global $gc_rewrite;
	global $gc_version;
	if (version_compare($gc_version, '3.4', '<')) {
		$gc_rewrite->extra_permastructs['category'][0] = '%category%';
	} else {
		$gc_rewrite->extra_permastructs['category']['struct'] = '%category%';
	}
}
function no_category_base_rewrite_rules($category_rewrite)
{
	$category_rewrite = array();
	$categories = get_categories(array('hide_empty' => false));
	foreach ($categories as $category) {
		$category_nicename = $category->slug;
		if ($category->parent == $category->cat_ID) {
			$category->parent = 0;
		} else {
			if ($category->parent != 0) {
				$category_nicename = get_category_parents($category->parent, false, '/', true) . $category_nicename;
			}
		}
		$category_rewrite['(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
		$category_rewrite['(' . $category_nicename . ')/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
		$category_rewrite['(' . $category_nicename . ')/?$'] = 'index.php?category_name=$matches[1]';
	}
	global $gc_rewrite;
	$old_category_base = get_option('category_base') ? get_option('category_base') : 'category';
	$old_category_base = trim($old_category_base, '/');
	$category_rewrite[$old_category_base . '/(.*)$'] = 'index.php?category_redirect=$matches[1]';
	return $category_rewrite;
}
function no_category_base_query_vars($public_query_vars)
{
	$public_query_vars[] = 'category_redirect';
	return $public_query_vars;
}
function no_category_base_request($query_vars)
{
	if (isset($query_vars['category_redirect'])) {
		$catlink = trailingslashit(get_option('home')) . user_trailingslashit($query_vars['category_redirect'], 'category');
		status_header(301);
		header('Location: ' . $catlink);
		exit(0);
	}
	return $query_vars;
}

//给分类增加一个关键词功能
function categorykeywords($taxonomy)
{
    echo '
    <li class="list-group-item">
        <div  class="row">
            <p class="col-sm-3 text-dark">
                SEO关键词
            </p>
            <div class="col-sm-9">
                <input class="form-control" type="text" name="tag-keywords" id="tag-keywords" value="" />
                <p>
                    请在此输入分类关键词,多个之间用英文逗号隔开
                </p>
            </div>
        </div>
    </li>
    ';
}
//给分类编辑页面增加一个关键词功能
function categorykeywordsedit($taxonomy)
{
    echo '
    <li class="list-group-item">
        <div  class="row">
            <p class="col-sm-3 text-dark">
                SEO关键词
            </p>
            <div class="col-sm-9">
                <input class="form-control" type="text" name="tag-keywords" id="tag-keywords" value="'. get_option('_category_keywords' . $taxonomy->term_id) .'" />
                <p>
                    请在此输入分类关键词,多个之间用英文逗号隔开
                </p>
            </div>
        </div>
    </li>
    ';
}
function categorykeywordssave($term_id)
{
	if (isset($_POST['tag-keywords'])) {
		if (isset($_POST['tag-keywords'])) {
			update_option('_category_keywords' . $term_id, $_POST['tag-keywords']);
		}
	}
}
function puma_customize_register($gc_customize)
{
	$gc_customize->add_section('header_logo', array('title' => '网站LOGO', 'priority' => 50));
	$gc_customize->add_setting('header_logo_image', array('default' => '', 'transport' => 'postMessage', 'type' => 'option'));
	$gc_customize->add_control(new GC_Customize_Image_Control($gc_customize, 'header_logo_image', array('label' => '网站LOGO', 'section' => 'header_logo')));
}
function lgurl_customize_register($gc_customize)
{
	$gc_customize->add_section('logourl', array('title' => 'LOGO超链接', 'priority' => 50));
	$gc_customize->add_setting('logourl', array('default' => '', 'transport' => 'postMessage', 'type' => 'option'));
	$gc_customize->add_control(new GC_Customize_Control($gc_customize, 'logourl', array('label' => 'LOGO超链接', 'section' => 'logourl')));
}
function bt_customize_register($gc_customize)
{
	$gc_customize->add_section('sybiaoti', array('title' => '网站首页标题', 'priority' => 50));
	$gc_customize->add_setting('sybiaoti', array('default' => '', 'transport' => 'postMessage', 'type' => 'option'));
	$gc_customize->add_control(new GC_Customize_Control($gc_customize, 'sybiaoti', array('label' => '网站首页标题', 'section' => 'sybiaoti')));
}
function sygjc_customize_register($gc_customize)
{
	$gc_customize->add_section('sygjc', array('title' => '网站首页关键词', 'priority' => 50));
	$gc_customize->add_setting('sygjc', array('default' => '', 'transport' => 'postMessage', 'type' => 'option'));
	$gc_customize->add_control(new GC_Customize_Control($gc_customize, 'sygjc', array('label' => '网站首页关键词', 'section' => 'sygjc')));
}
function ms_customize_register($gc_customize)
{
	$gc_customize->add_section('footbanquan', array('title' => '网站底部版权', 'priority' => 50));
	$gc_customize->add_setting('footbanquan', array('default' => '', 'transport' => 'postMessage', 'type' => 'option'));
	$gc_customize->add_control(new GC_Customize_Control($gc_customize, 'footbanquan', array('label' => '网站底部版权信息', 'section' => 'footbanquan')));
}