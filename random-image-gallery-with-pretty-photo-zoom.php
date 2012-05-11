<?php

/*
Plugin Name: Random image gallery with pretty photo zoom
Plugin URI: http://www.gopiplus.com/work/2011/12/12/wordpress-plugin-random-image-gallery-with-pretty-photo-zoom/
Description: This plug-in which allows you to simply and easily show random image anywhere in your template files or using widgets with onclick pretty photo zoom effect. 
Author: Gopi.R
Version: 3.0
Author URI: http://www.gopiplus.com/work/2011/12/12/wordpress-plugin-random-image-gallery-with-pretty-photo-zoom/
Donate link: http://www.gopiplus.com/work/2011/12/12/wordpress-plugin-random-image-gallery-with-pretty-photo-zoom/
*/

/**
 *     Random image gallery with pretty photo zoom
 *     Copyright (C) 2012  www.gopiplus.com
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function rigwppz_show() 
{
	include_once("select-random-image.php");
	$rigwppz_theme = get_option('rigwppz_theme');
	
	if($rigwppz_theme == "")
	{
		$rigwppz_theme = "dark_rounded";
	}
    ?><script type="text/javascript" charset="utf-8">
  $(document).ready(function(){
    $("a[rel^='prettyPhoto']").prettyPhoto({
overlay_gallery: false, "theme": '<?php echo $rigwppz_theme; ?>', social_tools: false
});
  });
</script><?php
}

function rigwppz_install() 
{
	add_option('rigwppz_title', "Slideshow Pretty photo");
	add_option('rigwppz_width', "180");
	add_option('rigwppz_theme', "light_square");
	
	add_option('rigwppz_dir', "wp-content/plugins/random-image-gallery-with-pretty-photo-zoom/random-gallery/");
	add_option('rigwppz_dir1', "wp-content/plugins/random-image-gallery-with-pretty-photo-zoom/random-gallery/");
	add_option('rigwppz_dir2', "wp-content/plugins/random-image-gallery-with-pretty-photo-zoom/random-gallery/");
	add_option('rigwppz_dir3', "wp-content/plugins/random-image-gallery-with-pretty-photo-zoom/random-gallery/");
	add_option('rigwppz_dir4', "wp-content/plugins/random-image-gallery-with-pretty-photo-zoom/random-gallery/");
	add_option('rigwppz_dir5', "wp-content/plugins/random-image-gallery-with-pretty-photo-zoom/random-gallery/");
	add_option('rigwppz_title_yes', "YES");
}

add_filter('the_content','rigwppz_show_filter');

function rigwppz_show_filter($content)
{
	return 	preg_replace_callback('/\[RIGWPPZ=(.*?)\]/sim','rigwppz_show_filter_callback',$content);
}

function rigwppz_show_filter_callback($matches) 
{
	//[RIGWPPZ=DIR1=200]
	global $wpdb;
	$rigwfz = "";
	
	$shortcode = explode("=",  $matches[1]);
	$userdir = @$shortcode[0];
	$rigwppz_width = @$shortcode[1];
	
	$rigwppz_theme = get_option('rigwppz_theme');
	
	if($rigwppz_theme == "")
	{
		$rigwppz_theme = "dark_rounded";
	}
	
	if($userdir == "DIR1")
	{
		$rigwppz_dir = get_option('rigwppz_dir1');
	}
	elseif($userdir == "DIR2")
	{
		$rigwppz_dir = get_option('rigwppz_dir2');
	}
	elseif($userdir == "DIR3")
	{
		$rigwppz_dir = get_option('rigwppz_dir3');
	}
	elseif($userdir == "DIR4")
	{
		$rigwppz_dir = get_option('rigwppz_dir4');
	}
	elseif($userdir == "DIR5")
	{
		$rigwppz_dir = get_option('rigwppz_dir5');
	}
	else
	{
		$rigwppz_dir = get_option('rigwppz_dir');
	}
	
	
	$rigwppz_siteurl = get_option('siteurl') . "/" . $rigwppz_dir ;
	
	$imglist='';
	//$img_folder is the variable that holds the path to the banner images. Mine is images/tutorials/
	// see that you don't forget about the "/" at the end 
	$img_folder = $rigwppz_dir;
	
	mt_srand((double)microtime()*1000);
	
	//use the directory class
	if($img_folder =="")
	{
		$img_folder = "wp-content/plugins/random-image-gallery-with-pretty-photo-zoom/random-gallery/";
	}
	$imgs = dir($img_folder);
	
	//read all files from the  directory, checks if are images and ads them to a list (see below how to display flash banners)
	while ($file = $imgs->read()) {
	if (eregi("gif", $file) || eregi("jpg", $file) || eregi("png", $file))
	$imglist .= "$file ";
	
	} closedir($imgs->handle);
	
	//put all images into an array
	$imglist = explode(" ", $imglist);
	$no = sizeof($imglist)-2;
	
	//generate a random number between 0 and the number of images
	$random = mt_rand(0, $no);
	$image = $imglist[$random];
	
	$mainsiteurl =	get_option('siteurl') . "/wp-content/plugins/random-image-gallery-with-pretty-photo-zoom/";
	
	
	if(!is_numeric($rigwppz_width))
	{
		$rigwppz_width = 180;
	} 
	
	$rigwfz = $rigwfz . '<div>';
	$rigwfz = $rigwfz . '<a href="'.$rigwppz_siteurl . $image .'" rel="prettyPhoto" title="">';
	$rigwfz = $rigwfz . '<img src="'.$mainsiteurl.'crop-random-image.php?AC=YES&DIR='.$rigwppz_dir.'&IMGNAME='.$image.'&MAXWIDTH='.$rigwppz_width.'"> ';
	$rigwfz = $rigwfz . '</a>';
	$rigwfz = $rigwfz . '</div>';
	
	$prettyPhoto = "'prettyPhoto'";
	$rigwppz_theme = "'".$rigwppz_theme."'";

	$rigwfz = $rigwfz . '<script type="text/javascript" charset="utf-8">$(document).ready(function(){$("a[rel^='.$prettyPhoto.']").prettyPhoto({overlay_gallery: false, "theme": '.$rigwppz_theme.', social_tools: false});});</script>';

	return $rigwfz;	
}

function rigwppz_widget($args) 
{
	extract($args);
	
	if(get_option('rigwppz_title_yes') == "YES") 
	{
		echo $before_widget . $before_title;
		echo get_option('rigwppz_title');
		echo $after_title;
	}
	
	rigwppz_show();
	
	if(get_option('rigwppz_title_yes') == "YES") 
	{
		echo $after_widget;
	}
}

function rigwppz_admin_option() 
{
	
	echo "<div class='wrap'>";
	echo "<h2>"; 
	echo "Random image gallery with pretty photo zoom (R I G W PP Z)";
	echo "</h2>";
    
	$rigwppz_title = get_option('rigwppz_title');
	$rigwppz_width = get_option('rigwppz_width');
	$rigwppz_theme = get_option('rigwppz_theme');
	$rigwppz_dir = get_option('rigwppz_dir');
	$rigwppz_title_yes = get_option('rigwppz_title_yes');
	
	$rigwppz_dir1 = get_option('rigwppz_dir1');
	$rigwppz_dir2 = get_option('rigwppz_dir2');
	$rigwppz_dir3 = get_option('rigwppz_dir3');
	$rigwppz_dir4 = get_option('rigwppz_dir4');
	$rigwppz_dir5 = get_option('rigwppz_dir5');
	
	if (@$_POST['rigwppz_submit']) 
	{
		$rigwppz_title = stripslashes($_POST['rigwppz_title']);
		$rigwppz_width = stripslashes($_POST['rigwppz_width']);
		$rigwppz_theme = stripslashes($_POST['rigwppz_theme']);
		$rigwppz_dir = stripslashes($_POST['rigwppz_dir']);
		$rigwppz_title_yes = stripslashes($_POST['rigwppz_title_yes']);
		
		$rigwppz_dir1 = stripslashes($_POST['rigwppz_dir1']);
		$rigwppz_dir2 = stripslashes($_POST['rigwppz_dir2']);
		$rigwppz_dir3 = stripslashes($_POST['rigwppz_dir3']);
		$rigwppz_dir4 = stripslashes($_POST['rigwppz_dir4']);
		$rigwppz_dir5 = stripslashes($_POST['rigwppz_dir5']);
		
		update_option('rigwppz_title', $rigwppz_title );
		update_option('rigwppz_width', $rigwppz_width );
		update_option('rigwppz_theme', $rigwppz_theme );
		update_option('rigwppz_dir', $rigwppz_dir );
		update_option('rigwppz_title_yes', $rigwppz_title_yes );
		
		update_option('rigwppz_dir1', $rigwppz_dir1 );
		update_option('rigwppz_dir2', $rigwppz_dir2 );
		update_option('rigwppz_dir3', $rigwppz_dir3 );
		update_option('rigwppz_dir4', $rigwppz_dir4 );
		update_option('rigwppz_dir5', $rigwppz_dir5 );
	}
	?>
	<form name="form_hsa" method="post" action="">
	<table width="100%" border="0" cellspacing="0" cellpadding="3"><tr><td align="left">
	<?php
	echo '<p>Title:<br><input  style="width: 450px;" maxlength="200" type="text" value="';
	echo $rigwppz_title . '" name="rigwppz_title" id="rigwppz_title" /></p>';
	
	echo '<p>Width:<br><input  style="width: 250px;" maxlength="3" type="text" value="';
	echo $rigwppz_width . '" name="rigwppz_width" id="rigwppz_width" />(Only Number)</p>';
	
	echo '<p>Theme:<br><input  style="width: 250px;" maxlength="15" type="text" value="';
	echo $rigwppz_theme . '" name="rigwppz_theme" id="rigwppz_theme" />(dark_rounded/dark_square/default/light_rounded/facebook/light_square)</p>';
	
	echo '<p>Display Sidebar Title:<br><input maxlength="3" style="width: 250px;" type="text" value="';
	
	echo $rigwppz_title_yes . '" name="rigwppz_title_yes" id="rigwppz_title_yes" />(YES/NO)</p>';
	echo '<p>Image directory(Widget):<br><input  style="width: 550px;" type="text" value="';
	echo $rigwppz_dir . '" name="rigwppz_dir" id="rigwppz_dir" /></p>';
	
	echo '<p>Image directory(DIR1):<br><input  style="width: 550px;" type="text" value="';
	echo $rigwppz_dir1 . '" name="rigwppz_dir1" id="rigwppz_dir1" /></p>';
	
	echo '<p>Image directory(DIR2):<br><input  style="width: 550px;" type="text" value="';
	echo $rigwppz_dir2 . '" name="rigwppz_dir2" id="rigwppz_dir2" /></p>';
	
	echo '<p>Image directory(DIR3):<br><input  style="width: 550px;" type="text" value="';
	echo $rigwppz_dir3 . '" name="rigwppz_dir3" id="rigwppz_dir3" /></p>';
	
	echo '<p>Image directory(DIR4):<br><input  style="width: 550px;" type="text" value="';
	echo $rigwppz_dir4 . '" name="rigwppz_dir4" id="rigwppz_dir4" /></p>';
	
	echo '<p>Image directory(DIR5):<br><input  style="width: 550px;" type="text" value="';
	echo $rigwppz_dir5 . '" name="rigwppz_dir5" id="rigwppz_dir5" /></p>';
	
	echo '<p>Default Image directory:<br>wp-content/plugins/random-image-gallery-with-pretty-photo-zoom/random-gallery/<br>';
	echo 'Dont upload your original images into this defult folder, instead you change this default path to original path from the above text box.</p>';
	echo '<input name="rigwppz_submit" id="rigwppz_submit" class="button-primary" value="Submit" type="submit" />';
	?>
	</td><td align="center" valign="middle"> </td></tr></table>
	</form>
    <h2>Drag and drop the widget</h2>
	Go to widget menu and drag and drop the "R I G W PP Z" widget to your sidebar location. <br />
    <h2>Paste the below php code to your php file</h2>
    <div style="padding-top:7px;padding-bottom:7px;">
    <code style="padding:7px;">
    &lt;?php if (function_exists (rigwppz_show)) rigwppz_show(); ?&gt;
    </code></div>
    <h2>Short code to add images into posts and pages</h2>
    <a target="_blank" href='http://www.gopiplus.com/work/2011/12/12/wordpress-plugin-random-image-gallery-with-pretty-photo-zoom/'>Click here</a> to find the short code. <br> 
	<h2>Plugin live demo and Help</h2>
	Check official website for more information <a target="_blank" href='http://www.gopiplus.com/work/2011/12/12/wordpress-plugin-random-image-gallery-with-pretty-photo-zoom/'>Click here</a><br> 
    <br>
	<?php
	echo "</div>";
}

function rigwppz_control()
{
	echo '<p>Random image gallery with pretty photo zoom. to change the setting goto R I G W PP Z link on Setting menu.';
	echo ' <a href="options-general.php?page=random-image-gallery-with-pretty-photo-zoom/random-image-gallery-with-pretty-photo-zoom.php">';
	echo 'click here</a></p>';
}

function rigwppz_widget_init() 
{
	if(function_exists('wp_register_sidebar_widget')) 	
	{
		wp_register_sidebar_widget('rigwfz', 'R I G W PP Z', 'rigwppz_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 	
	{
		wp_register_widget_control('rigwfz', array('R I G W PP Z', 'widgets'), 'rigwppz_control');
	} 
}

function rigwppz_deactivation() 
{

}

function rigwppz_add_to_menu() 
{
	add_options_page('Random image gallery with pretty photo zoom - R I G W PP Z', 'R I G W PP Z', 'manage_options', __FILE__, 'rigwppz_admin_option' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'rigwppz_add_to_menu');
}

function rigwppz_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'jquery-1.6.1.min', get_option('siteurl').'/wp-content/plugins/random-image-gallery-with-pretty-photo-zoom/js/jquery-1.6.1.min.js');
		wp_enqueue_style( 'prettyPhoto', get_option('siteurl').'/wp-content/plugins/random-image-gallery-with-pretty-photo-zoom/css/prettyPhoto.css','','','screen');
		wp_enqueue_script( 'jquery.prettyPhoto', get_option('siteurl').'/wp-content/plugins/random-image-gallery-with-pretty-photo-zoom/js/jquery.prettyPhoto.js');
	}	
}
add_action('init', 'rigwppz_add_javascript_files');

add_action("plugins_loaded", "rigwppz_widget_init");
register_activation_hook(__FILE__, 'rigwppz_install');
register_deactivation_hook(__FILE__, 'rigwppz_deactivation');
add_action('init', 'rigwppz_widget_init');
?>
