<?php
/*
Plugin Name: Inspectlet Websites HeadMap
Plugin URI: http://www.seocom.es
Description: Allows to insert the inspectlet code (www.inspectlet.com - Free Plan) inside the <head> section. Visit the <a href="http://www.seocom.es/">Seocom website</a> for more information about SEO or WPO optimization.
Author: David Garcia
Version: 1.0.3
*/

class inspectlet
{
	function inspectlet()
	{
		$this->__construct();
	}	
	
	function __construct()
	{
		if ( is_admin() )
		{
			add_action('admin_menu', array(&$this, 'admin_menu') );
			add_filter('plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );
		
			return;
		}
		
		add_action('wp_head', array(&$this,'wp_header') );
	}

	function admin_menu()
	{
		add_submenu_page( 'options-general.php', 'Inspectlet Websites HeadMap', 'Inspectlet Websites HeadMap', 10, 'inspectlet.php', array(&$this, 'options_page') );
	}

	function plugin_action_links( $links, $file )
	{
		if ( $file == plugin_basename( dirname(__FILE__). '/inspectlet.php' ) ) {
			$links[] = '<a href="' . admin_url( 'admin.php?page=inspectlet.php' ) . '">'.__( 'Settings' ).'</a>';
		}

		return $links;
	}

	function wp_header()
	{
		$option = get_option('inspectlet_config');
		if ( empty( $option['code']) )
		{
			return;
		}
		$code = $option['code'];

		echo <<<VALUE
<!-- Begin Inspectlet Embed Code -->
<script type="text/javascript" id="inspectletjs">
	window.__insp = window.__insp || [];
	__insp.push(['wid', {$code}]);
	(function() {
		function __ldinsp(){var insp = document.createElement('script'); insp.type = 'text/javascript'; insp.async = true; insp.id = "inspsync"; insp.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cdn.inspectlet.com/inspectlet.js'; var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(insp, x); }
		if (window.attachEvent){
			window.attachEvent('onload', __ldinsp);
		}else{
			window.addEventListener('load', __ldinsp, false);
		}
	})();
</script>
<!-- End Inspectlet Embed Code -->		
VALUE;

	}
	
	function options_page()
	{	
		if ( isset($_POST['inspectlet']) )
		{
			update_option( 'inspectlet_config', $_POST['inspectlet'] );
			print '<div id="message" class="updated fade"><p><strong>Options updated.</strong> <a href="'.get_bloginfo('url').'">View site &raquo;</a></p></div>';
		}
		$option = get_option('inspectlet_config');
	
		print '
		<div class="wrap">
		<h2>Inspectlet Websites HeadMap</h2>

		<form method="post" action="http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'">
		<table class="form-table">
		<tr valign="top">
			<th scope="row">Enter your wid code</th>
			<td><input id="inspectlet_code" name="inspectlet[code]" class="regular-text" value = "'. $option['code'].'" /></td>
		</tr>
		</table>
		<p class="submit"><input type="submit" value="Submit &raquo;" class="button button-primary"/></p>
		</form>

		</div>
		';
	}
	
	
}

$inspectlet = new inspectlet();
