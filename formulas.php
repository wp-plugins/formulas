<?php
/*
 * Plugin Name: Formulas
 * Plugin URI: http://www.pluginspodcast.com/plugins/formulas/
 * Description: Automotive formulas for car enthusiast web sites
 * Version: 0.1
 * Author: Angelo Mandato
 * Author URI: http://angelo.mandato.com
 * License: GPL2
 
Requires at least: 3.5
Tested up to: 4.1
Text Domain: formulas
Change Log: See readme.txt for complete change log
Contributors: Angelo Mandato, CIO RawVoice and host of the PluginsPodcast.com
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt

Copyright 2009-2015 Angelo Mandato, CIO RawVoice and host of the Plugins Podcast (http://www.pluginspodcast.com)
 */

if( !function_exists('add_action') )
	die("access denied.");
	
// WP_PLUGIN_DIR (REMEMBER TO USE THIS DEFINE IF NEEDED)
define('FORMULAS_VERSION', '0.1' );

// Translation support:
if ( !defined('FORMULAS_ABSPATH') )
	define('FORMULAS_ABSPATH', dirname(__FILE__) );

// Translation support loaded:
load_plugin_textdomain('formulas', // domain / keyword name of plugin
		FORMULAS_ABSPATH .'/languages', // Absolute path
		basename(FORMULAS_ABSPATH).'/languages' ); // relative path in plugins folder
	
class FormulasPlugin {

		var $m_settings = array();
		var $m_instance = 0;
		
    public function __construct()  
    {  
			// Options , for future use when we create admin settings we can tweak these settings
			$this->m_settings['include_css'] = true;
			
			// functions here
			add_action( 'init', 'cms_tpv_load_textdomain' );
			add_shortcode( 'formulas' , array( $this, 'shortcode') );
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
    } 
		
		function cms_tpv_load_textdomain() {
			// echo "load textdomain";
			if (is_admin()) {
				load_plugin_textdomain('cms-tree-page-view', WP_CONTENT_DIR . "/plugins/languages", "/cms-tree-page-view/languages");
			}
		}

		public function shortcode($atts)
		{
			$content = '';
			$divider = '<hr />';
			if( !empty($atts['divider'])  )
				$divider = $atts['divider'];
			
			if( !empty($atts['ed']) && filter_var($atts['ed'], FILTER_VALIDATE_BOOLEAN) )
			{
				if( !empty($content) )
					$content .= $divider;
				$content .= $this->_engine_displacement();
			}
			
			// DO compression calculator in page!
			if( !empty($atts['cr']) && filter_var($atts['cr'], FILTER_VALIDATE_BOOLEAN) )
			{
				if( !empty($content) )
					$content .= $divider;
				$content .= $this->_compression_ratio();
			}
			
			if( !empty($atts['pre'])  )
				$content = $atts['pre'] . $content;
			if( !empty($atts['post'])  )
				$content .= $atts['post'];
			
			return $content;
		}
		
		public function wp_enqueue_scripts()
		{
			if( $this->m_settings['include_css'] )
			{
				wp_register_style( 'formulas', plugins_url( 'formulas/formulas.css' ) );
				wp_enqueue_style( 'formulas' );
			}
			
			if( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG )
			{
				wp_enqueue_script( 'formulas_plugin', plugins_url( 'formulas/formulas.js' ) );
			}
			else
			{
				wp_enqueue_script( 'formulas_plugin', plugins_url( 'formulas/formulas.js' ) ); // Shou;d be formulas.min.js when out of beta
			}
			wp_enqueue_script( 'jquery' );
		}
		
		function _engine_displacement()
		{
			$this->m_instance++;
			$html ='';
			
			$html .= '<div class="formulas formulas-ed" id="formulas_'. $this->m_instance .'">';
			$html .= '<form action="" name="formulas_ed_'. $this->m_instance .'" id="formulas_ed_'. $this->m_instance .'">';
			
			$labels = array();
			$placeholders = array();
			
			$labels['bore'] = __('Bore (inches)', 'formulas');
			$placeholders['bore'] = __('e.g. 4.12', 'formulas');
			
			$labels['stroke'] = __('Stroke (inches)', 'formulas');
			$placeholders['stroke'] = __('e.g. 3.75', 'formulas');
			
			$labels['cylinders'] = __('Cylinders (count)', 'formulas');
			$placeholders['cylinders'] = __('e.g. 8', 'formulas');
						
			while( list($field,$label) = each($labels) )
			{
				$placeholder = '';
				if( !empty($placeholders[$field]) )
					$placeholder = $placeholders[$field];
				
				$html .= '<div class="formulas-ed-row">';
				$html .= '<label for="formulas_ed_'. $this->m_instance .'_'.$field.'" class="formulas-ed-label">';
				$html .= $label;
				$html .= '</label>';
				$html .= '<input type="text" name="formulas_ed_'. $this->m_instance .'_'.$field.'" value="" id="formulas_ed_'. $this->m_instance .'_'.$field.'" class="formulas-input formulas-ed-'.$field.'" placeholder="'. $placeholder .'" />';
				$html .= '</div>';
			}
			
			$html .= '<div class="formulas-btn">'. __('Calculate Engine Displacement', 'formulas') .'</div>';
			
			$html .= '<div class="formulas-result" style="display: none;">'. __('Engine Displacement:', 'formulas') .' <span class="formulas-result-1"></span></div>';
			$html .= '</form>';
			$html .= '</div>';
			
			return $html;
		}
		
		function _compression_ratio()
		{
			$this->m_instance++;
			$html ='';
			
			$html .= '<div class="formulas formulas-cr" id="formulas_'. $this->m_instance .'">';
			$html .= '<form action="" name="formulas_cr_'. $this->m_instance .'" id="formulas_cr_'. $this->m_instance .'">';
			
			$labels = array();
			$placeholders = array();
			
			$labels['bore'] = __('Bore (inches)', 'formulas');
			$placeholders['bore'] = __('e.g. 4.12', 'formulas');
			
			$labels['stroke'] = __('Stroke (inches)', 'formulas');
			$placeholders['stroke'] = __('e.g. 3.75', 'formulas');
			
			$labels['chamber'] = __('Head Chamber (cc\'s)', 'formulas');
			$placeholders['chamber'] = __('e.g. 96', 'formulas');
			
			$labels['gt'] = __('Gasket Thickness (inches)', 'formulas');
			$placeholders['gt'] = __('e.g. 0.041', 'formulas');
			
			// Gasket Bore Diameter
			$labels['gbd'] = __('Gasket Bore Diameter (inches)', 'formulas');
			$placeholders['gbd'] = __('e.g. 4.18', 'formulas');
			
			$labels['piston'] = __('Piston (cc\'s)', 'formulas');
			$placeholders['piston'] = __('e.g. 6.6', 'formulas');
			
			$labels['dh'] = __('Deck Height (inches)', 'formulas');
			$placeholders['dh'] = __('e.g. 0.023', 'formulas');
			
			while( list($field,$label) = each($labels) )
			{
				$placeholder = '';
				if( !empty($placeholders[$field]) )
					$placeholder = $placeholders[$field];
				
				$html .= '<div class="formulas-cr-row">';
				$html .= '<label for="formulas_cr_'. $this->m_instance .'_'.$field.'" class="formulas-cr-label">';
				$html .= $label;
				$html .= '</label>';
				$html .= '<input type="text" name="formulas_cr_'. $this->m_instance .'_'.$field.'" value="" id="formulas_cr_'. $this->m_instance .'_'.$field.'" class="formulas-input formulas-cr-'.$field.'" placeholder="'. $placeholder .'" />';
				$html .= '</div>';
			}
			
			$html .= '<div class="formulas-btn">'. __('Calculate Compression Ratio', 'formulas') .'</div>';
			
			$html .= '<div class="formulas-result" style="display: none;">'. __('Compression Ratio:', 'formulas') .' <span class="formulas-result-1"></span></div>';
			$html .= '</form>';
			$html .= '</div>';
			
			return $html;
		}
};

// Create the plugin object
$wp_formulas_plugin = new FormulasPlugin();

// formula references: 
//   http://www.ajdesigner.com/phpengine/engine_equations_compression_ratio.php
//   http://www.installuniversity.com/install_university/installu_articles/volumetric_efficiency/ve_computation_9.012000.htm
//   http://www.wallaceracing.com/Calculators.htm
//   http://www.projectpontiac.com/ppsite15/compression-ratio-calculator
//   http://www.originalho.com/OttoCycleCalculator.html
//   http://www.texastransams.com/articles/top_speed_calculator.htm
