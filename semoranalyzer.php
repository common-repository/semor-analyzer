<?php
/**
 *
 * @package SEMOR Analyzer Plugin
 * @author Vít Michalek
 * @license GPL-2.0+
 * @link https://www.semor.cz
 * @copyright 2019 SEMOR
 *
 *            @wordpress-plugin
 *            Plugin Name: SEMOR Analyzer
 *            Plugin URI: https://www.semor.cz
 *            Description:Sledujeme přístupy robotů vyhledávačů a pomáháme tak s technickým SEO
 *            Version: 1.3
 *            Author: SEMOR, Vít Michalek
 *            Author URI: http://vitmichalek.cz
 *            Text Domain: semoranalyzer
 *            Contributors: SEMORAnalyzer
 *            License: GPL-2.0+
 *            License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
 
/**
 * Adding Submenu under Settings Tab
 *
 * @since 1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class SEMOR {
	static $dataShoda = array(
	//google
		"Mozilla/5.0 (Linux; Android 5.0; SM-G920A) AppleWebKit (KHTML, like Gecko) Chrome Mobile Safari (compatible; AdsBot-Google-Mobile; +http://www.google.com/mobile/adsbot.html)",
		"Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1 (compatible; AdsBot-Google-Mobile; +http://www.google.com/mobile/adsbot.html)",
		"AdsBot-Google (+http://www.google.com/adsbot.html)",
		"Googlebot-Image/1.0",
		"Googlebot-News",
		"Googlebot-Video/1.0",
		"Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)",
		"Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; Googlebot/2.1; +http://www.google.com/bot.html) Safari/537.36",
		"Googlebot/2.1 (+http://www.google.com/bot.html)",
		"Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.96 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)",
		"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36 (compatible; Google-Read-Aloud;",
		"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko)  Chrome/49.0.2623.75 Safari/537.36 Google Favicon",
	//yahoo
		"Mozilla/5.0 (compatible; Yahoo!-AdCrawler; http://help.yahoo.com/yahoo_adcrawler)",
		"Mozilla/5.0 (compatible; Yahoo! Slurp/3.0; Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)",
	//duckduck
		"DuckDuckBot/1.0; (+http://duckduckgo.com/duckduckbot.html)",
		//seznam
		"Mozilla/5.0 (compatible; SeznamBot/3.2; +http://napoveda.seznam.cz/en/seznambot-intro/)",
		"Mozilla/5.0 (compatible; SeznamBot/3.2-test4; +http://napoveda.seznam.cz/en/seznambot-intro/)",
		//bing
		"Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)",
		//yandexx
		"Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)",
		//baidu
		"Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)"
	);
	static function submitHit(){
		$data = $_SERVER;
		unset($data["HTTP_COOKIE"]);//delete info cookies
		$body = array();
		$body["analyzer"] = $data;
		$body["id"] = stripslashes_deep ( esc_attr ( get_option ( 'semoranalyzer-text' ) ) );
		$body["analyzer"]["SSTATUS"] = http_response_code();
		 
		$args = array(
			'body' => $body,
			'timeout' => '1',
			'redirection' => '1',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'cookies' => array()
		);
		//if(isset(SEMOR::$dataShoda[$data["HTTP_USER_AGENT"]])){//posilame jen to cozname
			$response = wp_remote_post( 'https://www.semor.cz/anl/', $args );
		//}
	}
	
	static function wp_semor_menu(){    
		$page_title = 'SEMOR Analyzer';   
		$menu_title = 'SEMOR Analyzer';   
		$capability = 'manage_options';   
		$menu_slug  = 'semoranalyzer';   
		$function   = 'seoanalyzer_options';   
		$icon_url   = 'dashicons-media-code';   
		$position   = 4;    
		//add_menu_page( $page_title,$menu_title, $capability,  $menu_slug, $function,$icon_url,$position ); 
		add_menu_page('SEMOR', 'SEMOR Analyzer', 'manage_options', __FILE__, array('SEMOR','seoanalyzer_options'), "");
		//add_submenu_page(__FILE__, 'Analyzer', 'Analyzer', 'manage_options', __FILE__, 'seoanalyzer_options');
		//add_submenu_page(__FILE__, 'Analyzer', 'Analyzer', 'manage_options', __FILE__, 'seoanalyzer_options');
		//add_submenu_page(__FILE__, 'Nastavení', 'Nastavení', 'manage_options', __FILE__, 'seoanalyzer_options');
		//add_submenu_page(__FILE__, 'Aboutx', 'Aboutx', 'manage_options', __FILE__.'/about', 'clivern_render_about_page');
	} 
	
	static function seoanalyzer_options(){
		include "semoranalyzer_option.php";
	}
	static function semoranalyzr_opt(){
		include "semoranalyzer_form.php";
	}
	
	static function semoranalazyer_setting() {
		add_settings_section ( "semoranalyzer_config", "", null, "semoranalyzer" );
		add_settings_field ( "semoranalyzer-text", "Token pro přenos dat", array("SEMOR","semoranalyzr_opt"), "semoranalyzer", "semoranalyzer_config" );
		register_setting ( "semoranalyzer_config", "semoranalyzer-text" );
	}
	
	static function install() {
		
	}
	static function uninstall() {
		
	}
}

if(is_admin()){
	add_action('admin_menu', array('SEMOR', 'wp_semor_menu') ); 
	add_action ("admin_init", array('SEMOR',"semoranalazyer_setting") );
	register_activation_hook( __FILE__, array( 'SEMOR', 'install' ) );
	register_deactivation_hook( __FILE__, array( 'SEMOR', 'uninstall' ));
}else{
	if(stripslashes_deep ( esc_attr ( get_option ( 'semoranalyzer-text' ) ) ) != ""){//only, when user token isset
		add_action('shutdown', array( "SEMOR", 'submitHit'));
	}
}


?>