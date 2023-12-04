<?php
/**
 * The7 Elementor plugin compatibility class.
 *
 * @since   7.7.0
 * @package The7
 */

use Elementor\Core\Settings\Manager as Settings_Manager;
use Elementor\Plugin as Elementor;
use ElementorPro\Modules\ThemeBuilder\Documents\Theme_Document;
use ElementorPro\Modules\ThemeBuilder\Module as ThemeBuilderModule;
use The7\Mods\Compatibility\Elementor\Pro\Modules\Dynamic_Tags\The7\Module as DynamicTagsModule;
use The7\Mods\Compatibility\Elementor\The7_Elementor_Modules;
use The7\Mods\Compatibility\Elementor\The7_Elementor_Page_Settings;
use The7\Mods\Compatibility\Elementor\The7_Elementor_Template_Manager;
use The7\Mods\Compatibility\Elementor\The7_Elementor_Widgets;
use The7\Mods\Compatibility\Elementor\The7_Kit_Manager_Control;
use The7\Mods\Compatibility\Elementor\The7_Schemes_Manager_Control;

defined( 'ABSPATH' ) || exit;

/**
 * Class The7_Elementor_Compatibility
 */
class The7_Elementor_Compatibility {

	/**
	 * Instance.
	 * Holds the plugin instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @static
	 * @var The7_Elementor_Compatibility
	 */
	public static $instance = null;

	public $page_settings;
	public $widgets;
	public $edit_mode_backup;

	/**
	 * Modules manager.
	 *
	 * Holds the plugin modules manager.
	 *
	 * @access public
	 *
	 * @var The7_Elementor_Modules
	 */
	public $modules;
	public $template_manager;
	public $theme_builder_adapter;
	public $kit_manager_control;
	public $scheme_manager_control;

	/**
	 * Instance.
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since  1.0.0
	 * @access public
	 * @static
	 * @return The7_Elementor_Compatibility An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->bootstrap();
		}

		return self::$instance;
	}

	/**
	 * Bootstrap module.
	 */
	public function bootstrap() {
		require_once __DIR__ . '/elementor-functions.php';
		require_once __DIR__ . '/class-the7-elementor-widgets.php';
		require_once __DIR__ . '/class-the7-elementor-page-settings.php';
		require_once __DIR__ . '/meta-adapters/class-the7-elementor-color-meta-adapter.php';
		require_once __DIR__ . '/meta-adapters/class-the7-elementor-padding-meta-adapter.php';
		require_once __DIR__ . '/class-the7-elementor-kit-manager-control.php';
		require_once __DIR__ . '/class-the7-elementor-schemes-manager-control.php';
		require_once __DIR__ . '/class-the7-elementor-template-manager.php';

		// Should be on top because of The7_Elementor_Widgets::load_dependencies().
		add_action( 'elementor/init', [ $this, 'on_elementor_init' ] );

		$this->page_settings = new The7_Elementor_Page_Settings();
		$this->page_settings->bootstrap();

		$icons_integration_module = new \The7\Mods\Compatibility\Elementor\The7_Icons_For_Elementor();
		if ( the7_is_icons_manager_enabled() ) {
			$icons_integration_module->use_the7_icons_in_elementor();
		} else {
			// Theme Options Should be disabled by now.
			$icons_integration_module->use_elementor_icons_in_mega_menu();
		}

		if ( the7_is_elementor_theme_mode_active() ) {
			// Hide theme templates.
			add_filter( 'theme_templates', function ( $templates ) {
				$deprecated_templates = [
					'template-albums-jgrid.php',
					'template-albums.php',
					'template-blog-list.php',
					'template-blog-masonry.php',
					'template-media-jgrid.php',
					'template-media.php',
					'template-microsite.php',
					'template-portfolio-jgrid.php',
					'template-portfolio-list.php',
					'template-portfolio-masonry.php',
					'template-team.php',
					'template-testimonials.php',
				];

				return array_diff_key( $templates, array_fill_keys( $deprecated_templates, true ) );
			} );
		}

		$this->widgets = new The7_Elementor_Widgets();
		$this->widgets->bootstrap();

		$this->template_manager = new The7_Elementor_Template_Manager();
		$this->template_manager->bootstrap();

		if ( ! defined( 'THE7_DISABLE_KIT_MANAGER' ) || ( defined( 'THE7_DISABLE_KIT_MANAGER' ) && ! THE7_DISABLE_KIT_MANAGER ) ) {
			$this->kit_manager_control = new The7_Kit_Manager_Control();
			$this->kit_manager_control->bootstrap();
		}

		if ( the7_is_elementor2() ) {
			$this->scheme_manager_control = new The7_Schemes_Manager_Control();
			$this->scheme_manager_control->bootstrap();
		}
		$this->modules = new The7_Elementor_Modules();

		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$this->bootstrap_pro();
		}
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_elementor_global_style_css' ], 30 );
		add_action( 'elementor/theme/before_do_popup', [ $this, 'enqueue_elementor_popup' ] );
		add_filter( 'presscore_localized_script', [ $this, 'extract_elementor_settings_to_js' ] );
		add_action(
			'elementor/editor/before_enqueue_styles',
			function() {
				wp_enqueue_style(
					'the7-broccoli-icons',
					PRESSCORE_ADMIN_URI . '/assets/fonts/the7-broccoli-editor-fonts-v1.0/style.min.css',
					[],
					THE7_VERSION
				);
			}
		);

		/**
		 * Fixes a glitch with svg placeholders in image widget.
		 *
		 * Kses removes unsupported protocols.
		 *
		 * @see Group_Control_Image_Size::print_attachment_image_html
		 */
		add_filter( 'kses_allowed_protocols', 'the7_add_data_to_kses_allowed_protocols' );
		wp_allowed_protocols(); // call function before wp_loaded to initialize allowed protocols

		add_action(
			'elementor/experiments/default-features-registered',
			[
				$this,
				'adjust_default_experiments',
			]
		);

		// Enqueue the7 common editor js when elementor editor is loaded.
		add_action(
			'elementor/preview/enqueue_scripts',
			function () {
				the7_register_script( 'the7-elementor-editor-common', THE7_ELEMENTOR_ADMIN_JS_URI . '/editor-common.js' );
				wp_enqueue_script( 'the7-elementor-editor-common' );
			}
		);
	}

	public function on_elementor_init() {
		require_once __DIR__ . '/pro/modules/dynamic-tags/the7/module.php';
		new DynamicTagsModule();

		$this->modules->bootstrap();
	}

	/**
	 * @param array $dt_local
	 *
	 * @return array
	 */
	public function extract_elementor_settings_to_js( $dt_local ) {
		$dt_local['elementor'] = [
			'settings' => [
				'container_width' => (int) the7_elementor_get_content_width_string(),
			],
		];
		return $dt_local;
	}

	/**
	 * Adjust default Elementor Experiments.
	 *
	 * @sine 10.4.3
	 *
	 * @param \Elementor\Core\Experiments\Manager $experiments
	 */
	public function adjust_default_experiments( $experiments ) {
		// Turn off Additional Custom Breakpoints by default.
		$experiments->set_feature_default_state( 'additional_custom_breakpoints', false );

		// Turn off Improved CSS Loading by default.
		$experiments->set_feature_default_state( 'e_optimized_css_loading', false );
	}

	/**
	 * @return array|mixed|null
	 */
	public static function get_elementor_settings( $key = null ) {
		// TODO: Remove after elementor 3.4.0
		if ( the7_is_elementor2() ) {
			return Settings_Manager::get_settings_managers( 'general' )->get_model()->get_settings( 'elementor_' . $key );
		}

		return Elementor::$instance->kits_manager->get_current_settings( $key );
	}

	protected function bootstrap_pro() {
		require_once __DIR__ . '/pro/class-the7-elementor-theme-builder-adapter.php';

		$this->theme_builder_adapter = new \The7\Mods\Compatibility\Elementor\Pro\The7_Elementor_Theme_Builder_Adapter();
		$this->theme_builder_adapter->bootstrap();
		if ( the7_is_woocommerce_enabled() ) {
			require_once __DIR__ . '/pro/modules/woocommerce/class-the7-woocommerce-support.php';
			new \The7\Mods\Compatibility\Elementor\Pro\Modules\Woocommerce\Woocommerce_Support();
		}

	}

	public static function get_applied_archive_page_id( $page_id = null ) {
		$document = false;
		$location = '';
		if ( is_singular() ) {
			$document = self::get_frontend_document();
		}
		if ( $document && $document instanceof Theme_Document ) {
			// For editor preview iframe.
			$location = $document->get_location();
		} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
			$location = 'archive';
		} elseif ( is_archive() || is_tax() || is_home() || is_search() ) {
			$location = 'archive';
		} elseif ( is_singular() || is_404() ) {
			$location = 'single';
		}
		if ( ! empty( $location ) ) {
			return self::get_document_id_for_location( $location, $page_id );
		}

		return $page_id;
	}

	public static function get_frontend_document() {
		return Elementor::$instance->documents->get_doc_for_frontend( get_the_ID() );
	}


	/**
	 * @param string $location
	 * @param null   $page_id
	 *
	 * @return int|null
	 */
	public static function get_document_id_for_location( $location, $page_id = null ) {
		$document = self::get_document_applied_for_location( $location );
		if ( $document ) {
			$page_id = $document->get_post()->ID;
		}

		return $page_id;
	}

	/**
	 * @return \Elementor\Core\Base\Document|false
	 */
	public static function get_document_applied_for_location( $location ) {
		$document = null;
		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$documents = ThemeBuilderModule::instance()->get_conditions_manager()->get_documents_for_location( $location );
			foreach ( $documents as $document ) {
				if ( is_preview() || Elementor::$instance->preview->is_preview_mode() ) {
					$document = Elementor::$instance->documents->get_doc_or_auto_save( $document->get_id(), get_current_user_id() );
				} else {
					$document = Elementor::$instance->documents->get( $document->get_id() );
				}
				break;
			}
		}

		return $document;
	}

	/**
	 * Retrieve builder content for display.
	 *
	 * Used to render and return the post content with all the Elementor elements.
	 *
	 * @see \Elementor\Frontend::get_builder_content_for_display()
	 *
	 * @param int  $post_id Post ID.
	 * @param bool $with_css Optional. Whether to include CSS files. Default is false.
	 *
	 * @return string The post content.
	 */
    public static function get_builder_content_for_display($post_id, $with_css = false){
        $is_edit_mode = The7_Elementor_Compatibility::instance()->is_edit_mode();

        $with_css = $with_css ? true : $is_edit_mode;

        if ($with_css) {
            add_filter('elementor/frontend/builder_content/before_print_css', '__return_true', 999);
        }

        $content = Elementor::instance()->frontend->get_builder_content_for_display($post_id, $with_css);

        if ($with_css) {
            remove_filter('elementor/frontend/builder_content/before_print_css', '__return_true', 999);
        }
        return $content;
    }

	public static function is_assets_loader_exist() {
		return !! Elementor::$instance->assets_loader;
	}

	public static function enqueue_elementor_popup() {
		wp_enqueue_style( 'the7-custom-scrollbar' );
		wp_enqueue_script( 'the7-custom-scrollbar' );
	}

	public static function enqueue_elementor_global_style_css() {
		the7_register_style(
			'the7-elementor-global',
			PRESSCORE_THEME_URI . '/css/compatibility/elementor/elementor-global'
		);

		wp_enqueue_style( 'the7-elementor-global' );

		if ( self::get_document_applied_for_location( 'popup' ) ) {
			self::enqueue_elementor_popup();
		}
	}

    public function is_edit_mode(){
        if ($this->edit_mode_backup) return true;
        $edit_mode = Elementor::$instance->editor->is_edit_mode();
        return $edit_mode;
    }

    public function backup_edit_mode(){
       $this->edit_mode_backup = Elementor::$instance->editor->is_edit_mode();
    }

    public function restore_edit_mode(){
        $this->edit_mode_backup = false;
    }

    /**
     * Print document content and backup edit mode for nested templates
     *
     * @param \Elementor\Core\Base\Document $document Elementor document class.
     */
    public function print_document($document){
        $this->backup_edit_mode();
        $document->print_content();
        $this->restore_edit_mode();
    }
}
