<?php
/**
 * The7 elements scroller widget for Elementor.
 *
 * @package The7
 */

namespace The7\Adapters\Elementor\Widgets;

use Elementor\Plugin;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use The7_Query_Builder;
use The7\Adapters\Elementor\The7_Elementor_Widget_Base;
use The7\Adapters\Elementor\The7_Elementor_Less_Vars_Decorator_Interface;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Border;
use Elementor\Core\Responsive\Responsive;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

use Elementor\Group_Control_Image_Size;

defined( 'ABSPATH' ) || exit;

class The7_Elementor_Testimonials_Carousel_Widget extends The7_Elementor_Widget_Base {

	/**
	 * Get element name.
	 *
	 * Retrieve the element name.
	 *
	 * @return string The name.
	 */
	public function get_name() {
		return 'the7_testimonials_carousel';
	}
	public function get_keywords() {
		return [ 'carousel', 'the7', 'testimonials' ];
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Testimonials Carousel', 'the7mk2' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-posts-carousel the7-widget';
	}

	public function get_script_depends() {
		if ( Plugin::$instance->preview->is_preview_mode() ) {
			return [ 'the7-carousel-widget-preview' ];
		}

		return [];
	}

	public function get_style_depends() {
		return [ 'the7-carousel-widget' ];
	}

	/**
	 * Register widget controls.
	 */
	protected function _register_controls() {
		//parent::_register_controls();
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Slides', 'the7mk2' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		


		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'list_title',
			[
				'label' => __( 'Title', 'the7mk2' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Title', 'the7mk2' ),
			]
		);
		$repeater->add_control(
			'list_subtitle',
			[
				'label' => __( 'Subtitle', 'the7mk2' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '', 'the7mk2' ),
			]
		);

		$repeater->add_control(
			'list_content',
			[
				'label' => __( 'Text', 'the7mk2' ),
				'type' => Controls_Manager::TEXTAREA,
			]
		);

		$repeater->add_control(
			'graphic_type',
			[
				'label' => __( 'Graphic Element', 'the7mk2' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'icon' => [
						'title' => __( 'Icon', 'the7mk2' ),
						'icon' => 'eicon-favorite',
					],
					'image' => [
						'title' => __( 'Image', 'the7mk2' ),
						'icon' => 'eicon-image',
					],
					'none' => [
						'title' => __( 'None', 'the7mk2' ),
						'icon' => 'eicon-ban',
					],
				],

				'toggle' => false,
				'default' => 'icon',
			]
		);

		$repeater->add_control(
			'list_icon',
			[
				'label'     => __( 'Icon', 'the7mk2' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-quote-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'graphic_type' => 'icon',
				],
			]
		);

		$repeater->add_control(
			'list_image',
			[
				'name' => 'image',
				'label' => __( 'Choose Image', 'the7mk2' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'label_block' => true,
				'condition'   => [
					'graphic_type' => 'image',
				],
			]
		);

		$repeater->add_control(
			'button',
			[
				'label' => __( 'Button text', 'the7mk2' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Button text', 'the7mk2' ),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'the7mk2' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => 'https://your-link.com',
			]
		);

		$this->add_control(
			'list',
			[
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => __( 'Item title #1 ', 'the7mk2' ),
						'list_subtitle' => __( 'Item subtitle ', 'the7mk2' ),
						'list_content' => __( 'Item content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'the7mk2' ),
						'list_icon'=> 'fas fa-quote-right',
						'button' => __( 'Click Here', 'the7mk2' ),
						'link' =>__( 'https://your-link.com', 'the7mk2' ),
					],
					[
						'list_title' => __( 'Item title #2', 'the7mk2' ),
						'list_subtitle' => __( 'Item subtitle ', 'the7mk2' ),
						'list_content' => __( 'Item content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'the7mk2' ),
						'list_icon'=> 'fas fa-quote-right',
						'button' => __( 'Click Here', 'the7mk2' ),
						'link' =>__( 'https://your-link.com', 'the7mk2' ),
					],
					[
						'list_title' => __( 'Item title #3', 'the7mk2' ),
						'list_subtitle' => __( 'Item subtitle ', 'the7mk2' ),
						'list_content' => __( 'Item content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'the7mk2' ),
						'list_icon'=> 'fas fa-quote-right',
						'button' => __( 'Click Here', 'the7mk2' ),
						'link' =>__( 'https://your-link.com', 'the7mk2' ),
					],
					[
						'list_title' => __( 'Item title #4', 'the7mk2' ),
						'list_subtitle' => __( 'Item subtitle ', 'the7mk2' ),
						'list_content' => __( 'Item content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'the7mk2' ),
						'list_icon'=> 'fas fa-quote-right',
						'button' => __( 'Click Here', 'the7mk2' ),
						'link' =>__( 'https://your-link.com', 'the7mk2' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'layout_section',
			[
				'label' => __( 'Layout', 'the7mk2' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'responsiveness_settings',
			[
				'label'     => __( 'Columns & Responsiveness', 'the7mk2' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'wide_desk_columns',
			[
				'label'       => __( 'Columns On A Wide Desktop', 'the7mk2' ),
				'description' => sprintf(
					__(
						'Apply when browser width is bigger than %spx ("Content Width" Elementor setting).',
						'the7mk2'
					),
					\The7_Elementor_Compatibility::get_elementor_settings( 'elementor_container_width' )
				),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 3,
			]
		);

		$this->add_responsive_control(
			'widget_columns',
			[
				'label'          => __( 'Columns', 'the7mk2' ),
				'type'           => Controls_Manager::NUMBER,
				'default'        => 3,
				'tablet_default' => 2,
				'mobile_default' => 1,
			]
		);

		$this->add_control(
			'gap_between_posts',
			[
				'label'      => __( 'Gap Between Columns', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 30,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
			]
		);

		$this->add_control(
			'stage_padding',
			[
				'label'      => __( 'Stage Padding (px)', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 0,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
			]
		);
		$this->add_responsive_control(
			'carousel_width',
			[
				'label'      => __( 'Width', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 3000,
						'step' => 1,
					],
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .testimonials-carousel' => 'max-width: {{SIZE}}{{UNIT}}',
				],
				
			]
		);

		$this->add_control(
			'adaptive_height',
			[
				'label'        => __( 'Adaptive Height', 'the7mk2' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'y',
				'default'      => '',
			]
		);

		$this->end_controls_section();
		// Scolling.

		$this->start_controls_section(
			'scrolling_section',
			[
				'label' => __( 'Scrolling', 'the7mk2' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'slide_to_scroll',
			[
				'label'   => __( 'Scroll Mode', 'the7mk2' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'single',
				'options' => [
					'single' => 'One slide at a time',
					'all'    => 'All slides',
				],
			]
		);

		$this->add_control(
			'speed',
			[
				'label'       => __( 'Transition Speed', 'the7mk2' ),
				'description' => __( '(milliseconds)', 'the7mk2' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '600',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'        => __( 'Autoplay Slides', 'the7mk2' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'y',
				'default'      => '',
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'       => __( 'Autoplay Speed', 'the7mk2' ),
				'description' => __( '(milliseconds)', 'the7mk2' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 6000,
				'min'         => 100,
				'max'         => 10000,
				'step'        => 10,
				'condition'   => [
					'autoplay' => 'y',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Arrows section.
		 */
		$this->start_controls_section(
			'arrows_section',
			[
				'label' => __( 'Arrows', 'the7mk2' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'arrows',
			[
				'label'        => __( 'Show Arrows On Desktop', 'the7mk2' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'y',
				'default'      => 'y',

			]
		);
		$this->add_control(
			'arrows_tablet',
			[
				'label'        => __( 'Show Arrows On Tablet', 'the7mk2' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'y',
				'default'      => 'y',

			]
		);
		$this->add_control(
			'arrows_mobile',
			[
				'label'        => __( 'Show Arrows On Mobile', 'the7mk2' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'y',
				'default'      => 'y',

			]
		);
		$this->end_controls_section();


		$this->start_controls_section(
			'bullets_section',
			[
				'label' => __( 'Bullets', 'the7mk2' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_bullets',
			[
				'label'        => __( 'Show Bullets On Desktop', 'the7mk2' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'y',
				'default'      => 'y',
			]
		);
		$this->add_control(
			'show_bullets_tablet',
			[
				'label'        => __( 'Show Bullets On Tablet', 'the7mk2' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'y',
				'default'      => '',
			]
		);
		$this->add_control(
			'show_bullets_mobile',
			[
				'label'        => __( 'Show Bullets On Mobile', 'the7mk2' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'y',
				'default'      => '',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'skin_section',
			[
				'label'     => __( 'Skin', 'the7mk2' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$layouts = [
			'layout_1'  => __( 'Stacked, above content', 'the7mk2' ),
			'layout_5'  => __( 'Stacked, below content', 'the7mk2' ),
			'layout_2'  => __( 'Inline, above content', 'the7mk2' ),
			'layout_9'  => __( 'Stacked, title after content', 'the7mk2' ),
			'layout_6'  => __( 'Inline, below content', 'the7mk2' ),
			'layout_3'  => __( 'Left, title before content', 'the7mk2' ),
			'layout_7'  => __( 'Left, title after content', 'the7mk2' ),
			'layout_4'  => __( 'Right, title before content', 'the7mk2' ),
			'layout_8'  => __( 'Right, title after content', 'the7mk2' ),
		];

		// TODO: Add backward compatibility. It was 'deafult'.
		$responsive_layouts = [ ''  => __( 'No change', 'the7mk2' ) ] + $layouts;
		$this->add_responsive_control(
			'layout',
			[
				'label'   => __( 'Choose Skin', 'the7mk2' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'layout_1',
				'options' => $layouts,
				'device_args' => [
					'tablet' => [
						'options' => $responsive_layouts,
					],
					'mobile' => [
						'options' => $responsive_layouts,
					],
				],
			]
		);

		$this->add_responsive_control(
			'content_alignment',
			[
				'label'       => __( 'Alignment', 'the7mk2' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'center',
				'options'     => [
					'left'   => [
						'title' => __( 'Left', 'the7mk2' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'the7mk2' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'the7mk2' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'toggle'      => false,
				'device_args' => [
					'tablet' => [
						'toggle' => true,
					],
					'mobile' => [
						'toggle' => true,
					],
				],
				'label_block' => false,
			]
		);

		$this->add_responsive_control(
			'icon_below_gap',
			[
				'label'      => __( 'Graphic Element Margin', 'the7mk2' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
					],
				],
			]
		);

		$this->add_responsive_control(
			'icon_bg_size',
			[
				'label'      => __( 'Graphic Element Width', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 40,
				],
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
			]
		);

		$this->add_control(
			'link_click',
			[
				'label'     => __( 'Apply Link & Hover On', 'the7mk2' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'button',
				'options'   => [
					'slide'  => __( 'Whole box', 'the7mk2' ),
					'button' => __( "Separate slide's elements", 'the7mk2' ),
				],
			]
		);
		$this->add_control(
			'link_hover',
			[
				'label'        => __( 'Apply Hover To Slides With No Links', 'the7mk2' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'y',
				'default'      => 'y',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'box_section',
			[
				'label'     => __( 'Box', 'the7mk2' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'box_border_width',
			[
				'label'      => __( 'Border Width', 'the7mk2' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .dt-owl-item-wrap' => 'border-style: solid; box-sizing: border-box; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'box_border_radius',
			[
				'label'      => __( 'Border Radius', 'the7mk2' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .dt-owl-item-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label'      => __( 'Padding', 'the7mk2' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .dt-owl-item-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'box_style_tabs' );

		$this->start_controls_tab(
			'classic_style_normal',
			[
				'label' => __( 'Normal', 'the7mk2' ),
			]
		);

		$this->add_control(
			'box_background_color',
			[
				'label'     => __( 'Background Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dt-owl-item-wrap' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_border_color',
			[
				'label'     => __( 'Border Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dt-owl-item-wrap' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'classic_style_hover',
			[
				'label' => __( 'Hover', 'the7mk2' ),
			]
		);

		$this->add_control(
			'box_background_color_hover',
			[
				'label'     => __( 'Background Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'
					{{WRAPPER}} .dt-owl-item-wrap { transition: all 0.250s linear; }
					{{WRAPPER}} .dt-owl-item-wrap.box-hover:hover, {{WRAPPER}} .dt-owl-item-wrap.elements-hover:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'box_border_color_hover',
			[
				'label'     => __( 'Border Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'
					{{WRAPPER}} .dt-owl-item-wrap { transition: all 0.250s linear; }
					{{WRAPPER}} .dt-owl-item-wrap.box-hover:hover, {{WRAPPER}} .dt-owl-item-wrap.elements-hover:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();



		$this->start_controls_section(
			'section_style_main-menu',
			[
				'label' => __( 'Title', 'the7mk2' ),
				'tab' => Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'     => __( 'HTML Tag', 'the7mk2' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default'   => 'h4',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'post_title',
				'label'          => __( 'Typography', 'the7mk2' ),
				'selector'       => '{{WRAPPER}} .dt-owl-item-heading',
				'fields_options' => [
					'font_family' => [
						'default' => '',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'px',
							'size' => '',
						],
					],
					'font_weight' => [
						'default' => '',
					],
					'line_height' => [
						'default' => [
							'unit' => 'px',
							'size' => '',
						],
					],
				],
			]
		);
		$this->start_controls_tabs( 'post_title_style_tabs' );

		$this->start_controls_tab(
		    'post_title_normal_style',
		    [
		        'label' => __( 'Normal', 'the7mk2' ),
		    ]
		);
		$this->add_control(
			'custom_title_color',
			[
				'label'     => __( 'Font Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => true,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .dt-owl-item-heading' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		    'post_title_hover_style',
		    [
		        'label' => __( 'Hover', 'the7mk2' ),
		    ]
		);

		$this->add_control(
			'post_title_color_hover',
			[
				'label'     => __( 'Font Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => true,
				'default'   => '',
				'selectors' => [
					'
					{{WRAPPER}} .dt-owl-item-heading { transition: color 0.250s linear; }
					{{WRAPPER}} .box-hover:hover .dt-owl-item-heading, {{WRAPPER}} .elements-hover .dt-owl-item-heading:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'post_title_bottom_margin',
			[
				'label'      => __( 'Gap Below Title', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 5,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .dt-owl-item-heading' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_subtitle',
			[
				'label' => __( 'Subtitle', 'the7mk2' ),
				'tab' => Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_control(
			'subtitle_tag',
			[
				'label'     => __( 'HTML Tag', 'the7mk2' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default'   => 'h6',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'post_subtitle',
				'label'          => __( 'Typography', 'the7mk2' ),
				'selector'       => '{{WRAPPER}} .dt-owl-item-subtitle',
				'fields_options' => [
					'font_family' => [
						'default' => '',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'px',
							'size' => '',
						],
					],
					'font_weight' => [
						'default' => '',
					],
					'line_height' => [
						'default' => [
							'unit' => 'px',
							'size' => '',
						],
					],
				],
			]
		);
		$this->start_controls_tabs( 'post_subtitle_style_tabs' );

		$this->start_controls_tab(
		    'post_subtitle_normal_style',
		    [
		        'label' => __( 'Normal', 'the7mk2' ),
		    ]
		);
		$this->add_control(
			'custom_subtitle_color',
			[
				'label'     => __( 'Font Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => true,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .dt-owl-item-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		    'post_subtitle_hover_style',
		    [
		        'label' => __( 'Hover', 'the7mk2' ),
		    ]
		);

		$this->add_control(
			'post_subtitle_color_hover',
			[
				'label'     => __( 'Font Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => true,
				'default'   => '',
				'selectors' => [
					'
					{{WRAPPER}} .dt-owl-item-subtitle { transition: color 0.250s linear; }
					{{WRAPPER}} .box-hover:hover .dt-owl-item-subtitle, {{WRAPPER}} .elements-hover .dt-owl-item-subtitle:hover' => 'color: {{VALUE}};',
					
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'post_subtitle_bottom_margin',
			[
				'label'      => __( 'Gap Below Subtitle', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 5,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .dt-owl-item-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->end_controls_section();

		/**
		 * Text section.
		 */
		$this->start_controls_section(
			'text_section',
			[
				'label' => __( 'Text', 'the7mk2' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'post_content',
				'label'          => __( 'Typography', 'the7mk2' ),
				'fields_options' => [
					'font_family' => [
						'default' => '',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'px',
							'size' => '',
						],
					],
					'font_weight' => [
						'default' => '',
					],
					'line_height' => [
						'default' => [
							'unit' => 'px',
							'size' => '',
						],
					],
				],
				'selector'       => '{{WRAPPER}} .dt-owl-item-description',
			]
		);

		$this->start_controls_tabs( 'post_content_style_tabs' );

		$this->start_controls_tab(
		    'post_content_normal_style',
		    [
		        'label' => __( 'Normal', 'the7mk2' ),
		    ]
		);
		$this->add_control(
			'post_content_color',
			[
				'label'     => __( 'Font Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => true,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .dt-owl-item-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
		    'post_content_hover_style',
		    [
		        'label' => __( 'Hover', 'the7mk2' ),
		    ]
		);

		$this->add_control(
			'post_content_color_hover',
			[
				'label'     => __( 'Font Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => true,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .dt-owl-item-description { transition: color 0.250s linear; }
					{{WRAPPER}} .box-hover:hover .dt-owl-item-description,
					{{WRAPPER}} .elements-hover .dt-owl-item-description:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		

		$this->add_control(
			'post_content_bottom_margin',
			[
				'label'      => __( 'Gap Below Text', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 5,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .dt-owl-item-description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
		/**
		 * Icon section.
		 */
		$this->start_controls_section(
			'icon_section',
			[
				'label' => __( 'Icon', 'the7mk2' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_settings',
			[
				'label'     => __( 'Icon Size & Background', 'the7mk2' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => __( 'Icon Size', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 16,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
			]
		);

		

		$this->add_control(
			'icon_border_width',
			[
				'label'      => __( 'Border Width', 'the7mk2' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'unit' => 'px',
					'size' => 2,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 25,
						//'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .dt-owl-item-icon:before' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} .dt-owl-item-icon:after' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);
		$this->add_control(
			'icon_border_radius',
			[
				'label'      => __( 'Border Radius', 'the7mk2' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'default'    => [
					'unit' => 'px',
					'size' => 100,
				],
				'size_units' => [ 'px', '%'  ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .dt-owl-item-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);


		$this->start_controls_tabs( 'tabs_menu_item_style' );

		$this->start_controls_tab(
			'tab_menu_item_normal',
			[
				'label' => __( 'Normal', 'the7mk2' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'       => __( 'Icon color', 'the7mk2' ),
				'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
				'type'        => Controls_Manager::COLOR,
				'alpha'       => true,
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .dt-owl-item-icon i' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'icon_border_color',
			[
				'label'       => __( 'Icon Border Color', 'the7mk2' ),
				'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
				'type'        => Controls_Manager::COLOR,
				'alpha'       => true,
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .dt-owl-item-icon:before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .dt-owl-item-icon:after' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label'     => __( 'Icon Background Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => true,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .dt-owl-item-icon:before' => 'background: {{VALUE}};',
					'{{WRAPPER}} .dt-owl-item-icon:after' => 'background: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			[
				'label' => __( 'Hover', 'the7mk2' ),
			]
		);


		$this->add_control(
			'icons_hover_colors',
			[
				'label'     => __( 'Hover', 'the7mk2' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label'       => __( 'Icon color', 'the7mk2' ),
				'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
				'type'        => Controls_Manager::COLOR,
				'alpha'       => true,
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .dt-owl-item-icon i { transition: color 0.150s linear; } {{WRAPPER}} .box-hover:hover .dt-owl-item-icon > i,  {{WRAPPER}} .elements-hover .dt-owl-item-icon:hover > i' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'icon_border_color_hover',
			[
				'label'       => __( 'Icon Border Color', 'the7mk2' ),
				'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
				'type'        => Controls_Manager::COLOR,
				'alpha'       => true,
				'default'     => '',
				'selectors'   => [
					'
					{{WRAPPER}} .dt-owl-item-icon:before,
					{{WRAPPER}} .dt-owl-item-icon:after { transition: opacity 0.150s linear; }
					{{WRAPPER}} .dt-owl-item-icon:after' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_bg_color_hover',
			[
				'label'     => __( 'Icon Background Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => true,
				'default'   => '',
				'selectors' => [
					'
					{{WRAPPER}} .dt-owl-item-icon:before,
					{{WRAPPER}} .dt-owl-item-icon:after { transition: opacity 0.150s linear; }
					{{WRAPPER}} .dt-owl-item-icon:after' => 'background: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_image',
			[
				'label'     => __( 'Image', 'the7mk2' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_ratio',
			[
				'label'       => __( 'Image Ratio', 'the7mk2' ),
				'description' => __( 'Lieve empty to use original proportions', 'the7mk2' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => [
					'size' => '',
				],
				'range'       => [
					'px' => [
						'min'  => 0.1,
						'max'  => 2,
						'step' => 0.01,
					],
				],
			]
		);

		$this->add_control(
			'img_border_radius',
			[
				'label'      => __( 'Border Radius', 'the7mk2' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .dt-owl-item-image, {{WRAPPER}} .dt-owl-item-image:before, {{WRAPPER}} .dt-owl-item-image:after'         => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .dt-owl-item-image > a'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .dt-owl-item-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_scale_animation_on_hover',
			[
				'label'   => __( 'Scale Animation On Hover', 'the7mk2' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'quick_scale',
				'options' => [
					'disabled'    => __( 'Disabled', 'the7mk2' ),
					'quick_scale' => __( 'Quick scale', 'the7mk2' ),
					'slow_scale'  => __( 'Slow scale', 'the7mk2' ),
				],
			]
		);

		$this->start_controls_tabs( 'thumbnail_effects_tabs' );

		$this->start_controls_tab(
			'normal',
			[
				'label' => __( 'Normal', 'the7mk2' ),
			]
		);

		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'overlay_background',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Overlay', 'the7mk2' ),
					],
				],
				'selector'       => '
				{{WRAPPER}} .dt-owl-item-image:before,
				{{WRAPPER}} .dt-owl-item-image:after
				',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'img_shadow',
				'selector'  => '
				{{WRAPPER}} .dt-owl-item-image
				',
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'thumbnail_filters',
				'selector' => '
				{{WRAPPER}} .dt-owl-item-image img
				',
			]
		);
		$this->add_control(
			'thumbnail_opacity',
			[
				'label'      => __( 'Opacity', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => '%',
					'size' => '100',
				],
				'size_units' => [ '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .dt-owl-item-image img' => 'opacity: calc({{SIZE}}/100)',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			[
				'label' => __( 'Hover', 'the7mk2' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'overlay_hover_background',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => __( 'Overlay', 'the7mk2' ),
					],
					'color' => [
						'selectors' => [
							'
							{{SELECTOR}},
							{{WRAPPER}} .dt-owl-item-image:before { transition: opacity 0.25s; }
							{{SELECTOR}}' => 'background: {{VALUE}};',
						],
					],

				],
				'selector'       => '
				{{WRAPPER}} .dt-owl-item-image:after

				',

			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'img_hover_shadow',
				
				'selector'  => '{{WRAPPER}} .box-hover:hover .dt-owl-item-image, {{WRAPPER}} .elements-hover .dt-owl-item-image:hover',	
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'thumbnail_hover_filters',

				'selector'  => '{{WRAPPER}} .box-hover:hover .dt-owl-item-image img, {{WRAPPER}} .elements-hover .dt-owl-item-image:hover img',	
			]
		);
		$this->add_control(
			'thumbnail_hover_opacity',
			[
				'label'      => __( 'Opacity', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => '%',
					'size' => '100',
				],
				'size_units' => [ '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'
					{{WRAPPER}} .dt-owl-item-image img { transition: opacity 0.150s linear }
					{{WRAPPER}} .box-hover:hover .dt-owl-item-image img,
					{{WRAPPER}} .elements-hover .dt-owl-item-image:hover img ' => 'opacity: calc({{SIZE}}/100)',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


		$this->start_controls_section(
			'button_style_section',
			[
				'label' => __( 'Button', 'the7mk2' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'button_typography',
				'scheme'    => Schemes\Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .dt-slide-button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'the7mk2' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => __( 'Text Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .dt-slide-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'     => __( 'Background Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .owl-carousel.testimonials-carousel:not(.class-1):not(.class-2) .dt-owl-item-inner .dt-slide-button'       => 'background: {{VALUE}};',
					'{{WRAPPER}} .owl-carousel.testimonials-carousel:not(.class-1):not(.class-2) .dt-owl-item-inner .dt-slide-button:hover' => 'background: {{VALUE}};',
					'{{WRAPPER}} .owl-carousel.testimonials-carousel:not(.class-1):not(.class-2) .dt-owl-item-inner .dt-slide-button:focus' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'the7mk2' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => __( 'Text Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'
					{{WRAPPER}} .dt-slide-button { transition: color 0.25s; }
					{{WRAPPER}} .box-hover:hover .dt-slide-button'          => 'color: {{VALUE}};',
					'{{WRAPPER}} .box-hover:hover .post-details'             => 'color: {{VALUE}};',
					'{{WRAPPER}} .elements-hover .dt-slide-button:hover'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .elements-hover .post-details:focus'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .box-hover:hover .dt-slide-button  svg'     => 'fill: {{VALUE}};',
					'{{WRAPPER}} .elements-hover .dt-slide-button:hover svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .dt-slide-button:focus svg'                 => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label'     => __( 'Background Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'
					{{WRAPPER}} .dt-slide-button { transition: all 0.25s; }
					{{WRAPPER}} .owl-carousel.testimonials-carousel:not(.class-1):not(.class-2) .box-hover:hover .dt-slide-button' => 'background: {{VALUE}};',
					'{{WRAPPER}} .owl-carousel.testimonials-carousel:not(.class-1):not(.class-2) .elements-hover .dt-owl-item-inner .dt-slide-button:hover' => 'background: {{VALUE}};',
					'{{WRAPPER}} .owl-carousel.testimonials-carousel:not(.class-1):not(.class-2) .dt-owl-item-inner .dt-slide-button:focus' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => __( 'Border Color', 'the7mk2' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'
					{{WRAPPER}} .dt-slide-button { transition: all 0.25s; }
					{{WRAPPER}} .elements-hover .dt-slide-button:not(.class-1):not(.class-2):not(.class-3):hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}  .box-hover:hover .dt-slide-button:not(.class-1):not(.class-2):not(.class-3)' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}  .owl-carousel.testimonials-carousel .box-hover:hover .dt-slide-button' => 'border-color: {{VALUE}} !important',
					'{{WRAPPER}}  .owl-carousel.testimonials-carousel .elements-hover .dt-slide-button:hover' => 'border-color: {{VALUE}} !important',
					'{{WRAPPER}} .dt-slide-button:focus' => 'border-color: {{VALUE}}',
				],

				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'button_border',
				//'selector'  => '{{WRAPPER}} .dt-slide-button',
				'selector'  => '{{WRAPPER}} .dt-slide-button:not(.class-1):not(.class-2):not(.class-3), {{WRAPPER}} .dt-slide-button:not(.class-1):not(.class-2):not(.class-3):hover',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => __( 'Border Radius', 'the7mk2' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .dt-slide-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_text_padding',
			[
				'label'      => __( 'Text Padding', 'the7mk2' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .dt-slide-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->end_controls_section();

		/**
		 * Arrows section.
		 */
		$this->start_controls_section(
			'arrows_style',
			[
				'label' => __( 'Arrows', 'the7mk2' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);
		$this->add_control(
			'arrows_heading',
			[
				'label'     => __( 'Arrow Icon', 'the7mk2' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_control(
			'next_icon',
			[
				'label'     => __( 'Choose Next Arrow Icon', 'the7mk2' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'icomoon-the7-font-the7-arrow-09',
					'library' => 'the7-icons',
				],
				'classes'   => [ 'elementor-control-icons-svg-uploader-hidden' ],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_control(
			'prev_icon',
			[
				'label'     => __( 'Choose Previous Arrow Icon', 'the7mk2' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'icomoon-the7-font-the7-arrow-08',
					'library' => 'the7-icons',
				],
				'classes'   => [ 'elementor-control-icons-svg-uploader-hidden' ],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_control(
			'arrow_icon_size',
			[
				'label'      => __( 'Arrow Icon Size', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 16,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_control(
			'arrows_background_heading',
			[
				'label'     => __( 'Arrow Background', 'the7mk2' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_control(
			'arrow_bg_width',
			[
				'label'      => __( 'Width', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 30,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_control(
			'arrow_bg_height',
			[
				'label'      => __( 'Height', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 30,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_control(
			'arrow_border_radius',
			[
				'label'      => __( 'Arrow Border Radius', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 500,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
				],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_control(
			'arrow_border_width',
			[
				'label'      => __( 'Arrow Border Width', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 2,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 25,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .owl-nav a' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid',
				],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);
		$this->start_controls_tabs( 'arrows_style_tabs' );
		$this->start_controls_tab(
			'arrows_colors',
			[
				'label' => __( 'Normal', 'the7mk2' ),
			]
		);


		$this->add_control(
			'arrow_icon_color',
			[
				'label'       => __( 'Arrow Icon Color', 'the7mk2' ),
				'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
				'type'        => Controls_Manager::COLOR,
				'alpha'       => true,
				'default'     => '',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
				'selectors' => [
					'{{WRAPPER}} .owl-nav a i, {{WRAPPER}} .owl-nav a i:before' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'arrow_border_color',
			[
				'label'       => __( 'Arrow Border Color', 'the7mk2' ),
				'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
				'type'        => Controls_Manager::COLOR,
				'alpha'       => true,
				'default'     => '',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
				'selectors' => [
					'{{WRAPPER}} .owl-nav a' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .owl-nav a:hover'  => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'arrow_bg_color',
			[
				'label'       => __( 'Arrow Background Color', 'the7mk2' ),
				'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
				'type'        => Controls_Manager::COLOR,
				'alpha'       => true,
				'default'     => '',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
				'selectors' => [
					'{{WRAPPER}} .owl-nav a' => 'background: {{VALUE}};',
					'{{WRAPPER}} .owl-nav a:hover'  => 'background: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'arrows_hover_colors',
			[
				'label' => __( 'Hover', 'the7mk2' ),
			]
		);

		$this->add_control(
			'arrow_icon_color_hover',
			[
				'label'       => __( 'Arrow Icon Color Hover', 'the7mk2' ),
				'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
				'type'        => Controls_Manager::COLOR,
				'alpha'       => true,
				'default'     => '',
				'selectors' => [
					'{{WRAPPER}} .owl-nav a:hover i' => 'color: {{VALUE}}; ',

					' {{WRAPPER}} .owl-nav a i:before { transition: color 0.150s linear; } {{WRAPPER}} .owl-nav a:hover i:before' => 'color: {{VALUE}};',
				],

				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);


		$this->add_control(
			'arrow_border_color_hover',
			[
				'label'       => __( 'Arrow Border Color Hover', 'the7mk2' ),
				'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
				'type'        => Controls_Manager::COLOR,
				'alpha'       => true,
				'default'     => '',
				
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
				'selectors' => [
					'
					{{WRAPPER}} .owl-nav a { transition: all 0.150s linear; }
					{{WRAPPER}} .owl-nav a:hover'  => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_bg_color_hover',
			[
				'label'       => __( 'Arrow Background Hover Color', 'the7mk2' ),
				'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
				'type'        => Controls_Manager::COLOR,
				'alpha'       => true,
				'default'     => '',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
				'selectors' => [
					'
					{{WRAPPER}} .owl-nav a { transition: all 0.150s linear; }
					{{WRAPPER}} .owl-nav a:hover'  => 'background: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->add_control(
		    'left_arrow_position_heading',
		    [
		        'label' => __( 'Left Arrow Position', 'the7mk2' ),
		        'type' => Controls_Manager::HEADING,
		        'separator' => 'before',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
		    ]
		);


		$this->add_responsive_control(
			'l_arrow_v_position',
			[
				'label' => __( 'Vertical Position', 'the7mk2' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'the7mk2' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Middle', 'the7mk2' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'the7mk2' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'center',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);
		$this->add_responsive_control(
			'l_arrow_h_position',
			[
				'label' => __( 'Horizontal Position', 'the7mk2' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'the7mk2' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'the7mk2' ),
						'icon' => 'eicon-v-align-middle',
					],
					'right' => [
						'title' => __( 'Right', 'the7mk2' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'left',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_responsive_control(
			'l_arrow_v_offset',
			[
				'label' => __( 'Vertical Offset', 'the7mk2' ),
				'type' => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 0,
				],
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					],
				],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_responsive_control(
			'l_arrow_h_offset',
			[
				'label' => __( 'Horizontal Offset', 'the7mk2' ),
				'type' => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => -15,
				],
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					],
				],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		
		$this->add_control(
		    'right_arrow_position_heading',
		    [
		        'label' => __( 'Right Arrow Position', 'the7mk2' ),
		        'type' => Controls_Manager::HEADING,
		        'separator' => 'before',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
		    ]
		);


		$this->add_responsive_control(
			'r_arrow_v_position',
			[
				'label' => __( 'Vertical Position', 'the7mk2' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'the7mk2' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Middle', 'the7mk2' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'the7mk2' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'center',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);
		$this->add_responsive_control(
			'r_arrow_h_position',
			[
				'label' => __( 'Horizontal Position', 'the7mk2' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'the7mk2' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'the7mk2' ),
						'icon' => 'eicon-v-align-middle',
					],
					'right' => [
						'title' => __( 'Right', 'the7mk2' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'right',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_responsive_control(
		    'r_arrow_v_offset',
		    [
		        'label' => __( 'Vertical Offset', 'the7mk2' ),
		        'type' => Controls_Manager::SLIDER,
		        'default'    => [
		            'unit' => 'px',
		            'size' => 0,
		        ],
		        'size_units' => [ 'px', '%' ],
		        'range'      => [
		            'px' => [
		                'min'  => -1000,
		                'max'  => 1000,
		                'step' => 1,
		            ],
		            '%' => [
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					],
		        ],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
		    ]
		);

		$this->add_responsive_control(
		    'r_arrow_h_offset',
		    [
		        'label' => __( 'Horizontal Offset', 'the7mk2' ),
		        'type' => Controls_Manager::SLIDER,
		        'default'    => [
		            'unit' => 'px',
		            'size' => -15,
		        ],
		        'size_units' => [ 'px', '%' ],
		        'range'      => [
		            'px' => [
		                'min'  => -1000,
		                'max'  => 1000,
		                'step' => 1,
		            ],
		            '%' => [
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					],
		        ],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'arrows', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'arrows_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
		    ]
		);

		


		$this->end_controls_section();

		// Bullets.

		$this->start_controls_section(
			'bullets_style_block',
			[
				'label' => __( 'Bullets', 'the7mk2' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'show_bullets', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);


		$this->add_control(
			'bullets_Style_heading',
			[
				'label'     => __( 'Bullets Style, Size & Color', 'the7mk2' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'show_bullets', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_control(
			'bullets_style',
			[
				'label'     => __( 'Choose Bullets Style', 'the7mk2' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'small-dot-stroke',
				'options'   => [
					'small-dot-stroke' => 'Small dor stroke',
					'scale-up'         => 'Scale up',
					'stroke'           => 'Stroke',
					'fill-in'          => 'Fill in',
					'ubax'             => 'Square',
					'etefu'            => 'Rectangular',
				],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'show_bullets', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_control(
			'bullet_size',
			[
				'label'      => __( 'Bullets Size', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 10,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'show_bullets', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_control(
			'bullet_gap',
			[
				'label'      => __( 'Gap Between Bullets', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 16,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'show_bullets', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);
		$this->start_controls_tabs( 'bullet_style_tabs' );
		$this->start_controls_tab(
			'bullet_colors',
			[
				'label' => __( 'Normal', 'the7mk2' ),
			]
		);

		$this->add_control(
			'bullet_color',
			[
				'label'       => __( 'Bullets Color', 'the7mk2' ),
				'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
				'type'        => Controls_Manager::COLOR,
				'alpha'       => true,
				'default'     => '',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'show_bullets', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
				'global' => [
					'active' => false,
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'bullet_hover_colors',
			[
				'label' => __( 'Hover', 'the7mk2' ),
			]
		);

		$this->add_control(
			'bullet_color_hover',
			[
				'label'       => __( 'Bullets Hover Color', 'the7mk2' ),
				'description' => __( 'Live empty to use accent color.', 'the7mk2' ),
				'type'        => Controls_Manager::COLOR,
				'alpha'       => true,
				'default'     => '',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'show_bullets', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
				'global' => [
					'active' => false,
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'bullets_position_heading',
			[
				'label'     => __( 'Bullets Position', 'the7mk2' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'show_bullets', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_control(
			'bullets_v_position',
			[
				'label' => __( 'Vertical Position', 'the7mk2' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'the7mk2' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Middle', 'the7mk2' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'the7mk2' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'bottom',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'show_bullets', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);
		$this->add_control(
			'bullets_h_position',
			[
				'label' => __( 'Horizontal Position', 'the7mk2' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'the7mk2' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'the7mk2' ),
						'icon' => 'eicon-v-align-middle',
					],
					'right' => [
						'title' => __( 'Right', 'the7mk2' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'center',
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'show_bullets', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_control(
			'bullets_v_offset',
			[
				'label'      => __( 'Vertical Offset', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 0,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'show_bullets', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		$this->add_control(
			'bullets_h_offset',
			[
				'label'      => __( 'Horizontal Offset', 'the7mk2' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => 0,
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'conditions' => [
				   'relation' => 'or',
				   'terms' => [
				      [
				         'name' => 'show_bullets', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_tablet', 
				         'value' => 'y',
				      ],
				      [
				         'name' => 'show_bullets_mobile', 
				         'value' => 'y',
				      ],
				   ],
				],
			]
		);

		
		$this->end_controls_section();

	}

	/**
	 * Render widget.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['list'] ) ) {
			return;
		}

		$this->remove_image_hooks();
		$this->print_inline_css();

		echo '<div ' . $this->container_class( [ 'owl-carousel', 'testimonials-carousel', 'elementor-owl-carousel-call' ] ) . $this->get_container_data_atts() . '>';

		$this->add_render_attribute( 'button', 'class', [ 'dt-btn-s dt-btn', 'dt-slide-button' ] );

		$image_proportion     = ( empty( $settings['image_ratio']['size'] ) ? null : $settings['image_ratio']['size'] );
		$image_resize_options = [];
		if ( in_array( 'image', array_column( $settings['list'], 'graphic_type' ), true ) ) {
			$image_resize_options = the7_calculate_bwb_image_resize_options(
				[
					'desktop'  => $settings['widget_columns'],
					'v_tablet' => $settings['widget_columns_tablet'],
					'h_tablet' => $settings['widget_columns_tablet'],
					'phone'    => $settings['widget_columns_mobile'],
				],
				$settings['gap_between_posts']['size'],
				false
			);
		}
		$title_element = $settings['title_tag'];
		$subtitle_element = $settings['subtitle_tag'];
		$slide_count   = 0;

		foreach ( $settings['list'] as $slide ) {
			$btn_attributes   = '';
			$slide_attributes = '';
			$slide_element    = 'div';
			$btn_element      = 'div';
			$icon_element     = 'div';
			$wrap_class       = '';
			$title_link = '';
			$title_link_close = '';

			if ( $slide['graphic_type'] === 'none' ) {
				$wrap_class .= ' hide-icon';
			}
			if ( $slide['button'] === '' ) {
				$wrap_class .= ' hide-btn';
			}
			if ( 'y' === $settings['link_hover'] && 'button' === $settings['link_click'] ) {
				$wrap_class     .= ' elements-hover';
			}else if ( 'y' === $settings['link_hover'] ) {
				$wrap_class       .= ' box-hover';
			}

			if ( ! empty( $slide['link']['url'] ) ) {
				$this->add_link_attributes( 'slide_link' . $slide_count, $slide['link'] );
				
				if ( 'button' === $settings['link_click'] ) {
					$wrap_class     .= ' elements-hover';
					$btn_element    = 'a';
					$icon_element   = 'a';
					$btn_attributes = $this->get_render_attribute_string( 'slide_link' . $slide_count );

					$title_link = '<a '. $btn_attributes. '>';
					$title_link_close = '</a>';
				} else {
					$wrap_class    .= ' box-hover';
					$slide_element  = 'a';
					$slide_attributes = $this->get_render_attribute_string( 'slide_link' . $slide_count );
				}
			}

			echo '<' . $slide_element . '  class="dt-owl-item-wrap' . $wrap_class . '"  ' . $slide_attributes . '>';
			echo '<div class="dt-owl-item-inner ">';

			if ( $slide['list_icon'] ) {
				echo '<' . $icon_element . ' ' . $btn_attributes . '  class="dt-owl-item-icon">';
				Icons_Manager::render_icon(
					$slide['list_icon'],
					[ 'aria-hidden' => 'true', 'class' => 'open-button' ],
					'i'
				);
				echo '</' . $icon_element . '>';
			}

			if ( 'image' === $slide['graphic_type'] && ! empty( $slide['list_image']['id'] ) ){
				echo '<' . $icon_element . ' ' . $btn_attributes . ' class="dt-owl-item-image"> ';

				dt_get_thumb_img(
					[
						'img_id'  => $slide['list_image']['id'],
						'wrap'    => '<img %IMG_CLASS% %SRC% %ALT% %IMG_TITLE% %SIZE% />',
						'options' => $image_resize_options,
						'prop'    => $image_proportion,
						'echo'    => true,
					]
				);

				echo '</' . $icon_element . '>';
			}
			
				
				if ( $slide['list_title'] ) {
					echo '<' . $title_element . '  class="dt-owl-item-heading">' . $title_link . $slide['list_title'] . $title_link_close . '</' . $title_element . '>';
				}
				if ( $slide['list_subtitle'] ) {
					echo '<' . $subtitle_element . '  class="dt-owl-item-subtitle">'  . $slide['list_subtitle'] .  '</' . $subtitle_element . '>';
				}
			//echo '<div class="owl-slide-content">';
				if ( $slide['list_content'] ) {
					echo '<div class="dt-owl-item-description">' . $slide['list_content'] . '</div>';
				}

				if ( $slide['button'] ) {
					echo '<' . $btn_element . ' ' . $btn_attributes . ' ' . $this->get_render_attribute_string( 'button' ) . '>' . $slide['button'] . '</' . $btn_element . '>';
				}
			
			echo '</div>';
			echo '</' . $slide_element . '>';
			

			$slide_count++;
		}

		echo '</div>';

		$this->add_image_hooks();
	}

	/**
	 * Return container class attribute.
	 *
	 * @param array $class
	 *
	 * @return string
	 */
	protected function container_class( $class = [] ) {
		$class[] = 'the7-elementor-widget';

		// Unique class.
		$class[] = $this->get_unique_class();

		$settings = $this->get_settings_for_display();

		$class[] = presscore_array_value(
			$settings['bullets_style'],
			[
				'scale-up'         => 'bullets-scale-up',
				'stroke'           => 'bullets-stroke',
				'fill-in'          => 'bullets-fill-in',
				'small-dot-stroke' => 'bullets-small-dot-stroke',
				'ubax'             => 'bullets-ubax',
				'etefu'            => 'bullets-etefu',
			]
		);
		$class[] = presscore_array_value(
			$settings['layout'],
			[
				'layout_1'  => 'slider-layout_1',
				'layout_2'	=> 'slider-layout_2',
				'layout_3'	=> 'slider-layout_3',
				'layout_4'	=> 'slider-layout_4',
				'layout_5'  => 'slider-layout_5',
				'layout_6'	=> 'slider-layout_6',
				'layout_7'	=> 'slider-layout_7',
				'layout_8'	=> 'slider-layout_8',
				'layout_9'	=> 'slider-layout_9',
			]
		);
		$class[] = presscore_array_value(
			$settings['layout_tablet'],
			[
				'layout_1'  => 'slider-tablet-layout_1',
				'layout_2'	=> 'slider-tablet-layout_2',
				'layout_3'	=> 'slider-tablet-layout_3',
				'layout_4'	=> 'slider-tablet-layout_4',
				'layout_5'  => 'slider-tablet-layout_5',
				'layout_6'	=> 'slider-tablet-layout_6',
				'layout_7'	=> 'slider-tablet-layout_7',
				'layout_8'	=> 'slider-tablet-layout_8',
				'layout_9'	=> 'slider-tablet-layout_9',

			]
		);
		$class[] = presscore_array_value(
			$settings['layout_mobile'],
			[
				'layout_1'  => 'slider-mobile-layout_1',
				'layout_2'	=> 'slider-mobile-layout_2',
				'layout_3'	=> 'slider-mobile-layout_3',
				'layout_4'	=> 'slider-mobile-layout_4',
				'layout_5'  => 'slider-mobile-layout_5',
				'layout_6'	=> 'slider-mobile-layout_6',
				'layout_7'	=> 'slider-mobile-layout_7',
				'layout_8'	=> 'slider-mobile-layout_8',
				'layout_9'	=> 'slider-mobile-layout_9',

			]
		);

		if ( $settings['image_scale_animation_on_hover'] === 'quick_scale' ) {
			$class[] = 'quick-scale-img';
		} elseif ( $settings['image_scale_animation_on_hover'] === 'slow_scale' ) {
			$class[] = 'scale-img';
		}

		if ( $settings['arrow_bg_color'] === $settings['arrow_bg_color_hover'] ) {
			$class[] = 'disable-arrows-hover-bg';
		}

		return sprintf( ' class="%s" ', esc_attr( implode( ' ', $class ) ) );
	}

	protected function get_container_data_atts() {
		$settings = $this->get_settings_for_display();

		$data_atts = [
			'scroll-mode'          => $settings['slide_to_scroll'] === 'all' ? 'page' : '1',
			'col-num'              => $settings['widget_columns'],
			'wide-col-num'         => $settings['wide_desk_columns'],
			'laptop-col'           => $settings['widget_columns_tablet'],
			'h-tablet-columns-num' => $settings['widget_columns_tablet'],
			'v-tablet-columns-num' => $settings['widget_columns_tablet'],
			'phone-columns-num'    => $settings['widget_columns_mobile'],
			'auto-height'          => $settings['adaptive_height'] ? 'true' : 'false',
			'col-gap'              => $settings['gap_between_posts']['size'],
			'stage-padding'        => $settings['stage_padding']['size'],
			'speed'                => $settings['speed'],
			'autoplay'             => $settings['autoplay'] ? 'true' : 'false',
			'autoplay_speed'       => $settings['autoplay_speed'],
			'arrows'               => $settings['arrows'] ? 'true' : 'false',
			'arrows_tablet'        => $settings['arrows_tablet'] ? 'true' : 'false',
			'arrows_mobile'        => $settings['arrows_mobile'] ? 'true' : 'false',
			'bullet'               => $settings['show_bullets'] ? 'true' : 'false',
			'bullet_tablet'        => $settings['show_bullets_tablet'] ? 'true' : 'false',
			'bullet_mobile'        => $settings['show_bullets_mobile'] ? 'true' : 'false',
			'next-icon'            => $settings['next_icon']['value'],
			'prev-icon'            => $settings['prev_icon']['value'],
		];

		return ' ' . presscore_get_inlide_data_attr( $data_atts );
	}

	/**
	 * Return shortcode less file absolute path to output inline.
	 *
	 * @return string
	 */
	protected function get_less_file_name() {
		return PRESSCORE_THEME_DIR . '/css/dynamic-less/elementor/the7-carousel-testimonials-widget.less';
	}

	/**
	 * Specify a vars to be inserted in to a less file.
	 */
	protected function less_vars( The7_Elementor_Less_Vars_Decorator_Interface $less_vars ) {
		// For project icon style, see `selectors` in settings declaration.

		$settings = $this->get_settings_for_display();

		$less_vars->add_keyword(
			'unique-shortcode-class-name',
			$this->get_unique_class() . '.testimonials-carousel',
			'~"%s"'
		);

		//content_alignment
		

		$less_vars->add_pixel_number( 'icon-size', $settings['arrow_icon_size'] );

		$less_vars->add_pixel_number( 'arrow-width', $settings['arrow_bg_width'] );
		$less_vars->add_pixel_number( 'arrow-height', $settings['arrow_bg_height'] );
		$less_vars->add_pixel_number( 'arrow-border-radius', $settings['arrow_border_radius'] );

		if ( ! empty( $settings['arrows']) || ! empty( $settings['arrows_tablet']) || ! empty( $settings['arrows_mobile'])) {
			$less_vars->add_keyword( 'arrow-right-v-position', $settings['r_arrow_v_position'] ? $settings['r_arrow_v_position'] : 'center' );
			$less_vars->add_keyword( 'arrow-right-v-position-tablet',  $settings['r_arrow_v_position_tablet'] ?  $settings['r_arrow_v_position_tablet'] :  $settings['r_arrow_v_position'] );
			if ( ! empty( $settings['r_arrow_v_position_tablet'])) {
				$less_vars->add_keyword( 'arrow-right-v-position-mobile', $settings['r_arrow_v_position_mobile'] ? $settings['r_arrow_v_position_mobile'] : $settings['r_arrow_v_position_tablet'] );
			}else{
				$less_vars->add_keyword( 'arrow-right-v-position-mobile', $settings['r_arrow_v_position_mobile'] ? $settings['r_arrow_v_position_mobile'] : $settings['r_arrow_v_position'] );
			}

			$less_vars->add_keyword( 'arrow-right-h-position', $settings['r_arrow_h_position'] ? $settings['r_arrow_h_position'] : 'right' );
			$less_vars->add_keyword( 'arrow-right-h-position-tablet', $settings['r_arrow_h_position_tablet'] ? $settings['r_arrow_h_position_tablet'] : $settings['r_arrow_h_position'] );
			if ( ! empty( $settings['r_arrow_h_position_tablet'])) {
				$less_vars->add_keyword( 'arrow-right-h-position-mobile', $settings['r_arrow_h_position_mobile'] ? $settings['r_arrow_h_position_mobile'] : $settings['r_arrow_h_position_tablet'] );
			}else {
				$less_vars->add_keyword( 'arrow-right-h-position-mobile', $settings['r_arrow_h_position_mobile'] ? $settings['r_arrow_h_position_mobile'] : $settings['r_arrow_h_position'] );
			}

			$r_arrow_v_offset       = array_merge( [ 'size' => 0 ], array_filter( $settings['r_arrow_v_offset'] ) );
			$r_arrow_v_offset_tablet = array_merge(
				$r_arrow_v_offset,
				$this->unset_empty_value( $settings['r_arrow_v_offset_tablet'] )
			);
			$r_arrow_v_offset_mobile = array_merge(
				$r_arrow_v_offset_tablet,
				$this->unset_empty_value( $settings['r_arrow_v_offset_mobile'] )
			);

			$less_vars->add_pixel_or_percent_number( 'r-arrow-v-position', $r_arrow_v_offset );
			$less_vars->add_pixel_or_percent_number( 'r-arrow-v-position-tablet', $r_arrow_v_offset_tablet );
			$less_vars->add_pixel_or_percent_number( 'r-arrow-v-position-mobile', $r_arrow_v_offset_mobile );

			$r_arrow_h_offset       = array_merge( [ 'size' => 0 ], array_filter( $settings['r_arrow_h_offset'] ) );
			$r_arrow_h_offset_tablet = array_merge(
				$r_arrow_h_offset,
				$this->unset_empty_value( $settings['r_arrow_h_offset_tablet'] )
			);
			$r_arrow_h_offset_mobile = array_merge(
				$r_arrow_h_offset_tablet,
				$this->unset_empty_value( $settings['r_arrow_h_offset_mobile'] )
			);
			$less_vars->add_pixel_or_percent_number( 'r-arrow-h-position', $r_arrow_h_offset );
			$less_vars->add_pixel_or_percent_number( 'r-arrow-h-position-tablet', $r_arrow_h_offset_tablet );
			$less_vars->add_pixel_or_percent_number( 'r-arrow-h-position-mobile', $r_arrow_h_offset_mobile );

			$less_vars->add_keyword( 'arrow-left-v-position', $settings['l_arrow_v_position'] ? $settings['l_arrow_v_position'] : 'center' );
			$less_vars->add_keyword( 'arrow-left-v-position-tablet', $settings['l_arrow_v_position_tablet'] ? $settings['l_arrow_v_position_tablet'] : $settings['l_arrow_v_position'] );
			if ( ! empty( $settings['l_arrow_v_position_tablet'])) {
				$less_vars->add_keyword( 'arrow-left-v-position-mobile', $settings['l_arrow_v_position_mobile'] ? $settings['l_arrow_v_position_mobile'] : $settings['l_arrow_v_position_tablet'] );
			}else{
				$less_vars->add_keyword( 'arrow-left-v-position-mobile', $settings['l_arrow_v_position_mobile'] ? $settings['l_arrow_v_position_mobile'] : $settings['l_arrow_v_position'] );
			}

			$less_vars->add_keyword( 'arrow-left-h-position', $settings['l_arrow_h_position'] ? $settings['l_arrow_h_position'] : 'left' );
			$less_vars->add_keyword( 'arrow-left-h-position-tablet', $settings['l_arrow_h_position_tablet'] ? $settings['l_arrow_h_position_tablet'] : $settings['l_arrow_h_position'] );
			if ( ! empty( $settings['l_arrow_h_position_tablet'])) {
				$less_vars->add_keyword( 'arrow-left-h-position-mobile', $settings['l_arrow_h_position_mobile'] ? $settings['l_arrow_h_position_mobile'] : $settings['l_arrow_h_position_tablet'] );
			}else{
				$less_vars->add_keyword( 'arrow-left-h-position-mobile', $settings['l_arrow_h_position_mobile'] ? $settings['l_arrow_h_position_mobile'] : $settings['l_arrow_h_position'] );
			}

			$l_arrow_v_offset       = array_merge( [ 'size' => 0 ], array_filter( $settings['l_arrow_v_offset'] ) );
			$l_arrow_v_offset_tablet = array_merge(
				$l_arrow_v_offset,
				$this->unset_empty_value( $settings['l_arrow_v_offset_tablet'] )
			);
			$l_arrow_v_offset_mobile = array_merge(
				$l_arrow_v_offset_tablet,
				$this->unset_empty_value( $settings['l_arrow_v_offset_mobile'] )
			);
			$less_vars->add_pixel_or_percent_number( 'l-arrow-v-position', $l_arrow_v_offset );
			$less_vars->add_pixel_or_percent_number( 'l-arrow-v-position-tablet', $l_arrow_v_offset_tablet );
			$less_vars->add_pixel_or_percent_number( 'l-arrow-v-position-mobile', $l_arrow_v_offset_mobile );

			$l_arrow_h_offset       = array_merge( [ 'size' => 0 ], array_filter( $settings['l_arrow_h_offset'] ) );
			$l_arrow_h_offset_tablet = array_merge(
				$l_arrow_h_offset,
				$this->unset_empty_value( $settings['l_arrow_h_offset_tablet'] )
			);
			$l_arrow_h_offset_mobile = array_merge(
				$l_arrow_h_offset_tablet,
				$this->unset_empty_value( $settings['l_arrow_h_offset_mobile'] )
			);
			$less_vars->add_pixel_or_percent_number( 'l-arrow-h-position', $l_arrow_h_offset );
			$less_vars->add_pixel_or_percent_number( 'l-arrow-h-position-tablet', $l_arrow_h_offset_tablet );
			$less_vars->add_pixel_or_percent_number( 'l-arrow-h-position-mobile', $l_arrow_h_offset_mobile );
		}

		$less_vars->add_pixel_number( 'bullet-size', $settings['bullet_size'] );
		$less_vars->add_rgba_color( 'bullet-color', $settings['bullet_color'] );
		$less_vars->add_rgba_color( 'bullet-color-hover', $settings['bullet_color_hover'] );
		$less_vars->add_pixel_number( 'bullet-gap', $settings['bullet_gap'] );
		$less_vars->add_keyword( 'bullets-v-position', $settings['bullets_v_position'] );
		$less_vars->add_keyword( 'bullets-h-position', $settings['bullets_h_position'] );
		$less_vars->add_pixel_number( 'bullet-v-position', $settings['bullets_v_offset'] );
		$less_vars->add_pixel_number( 'bullet-h-position', $settings['bullets_h_offset'] );

		foreach ( Responsive::get_breakpoints() as $size => $value ) {
			$less_vars->add_pixel_number( "elementor-{$size}-breakpoint", $value );
		}

		$less_vars->add_pixel_number( 'grid-posts-gap', $settings['gap_between_posts'] );

		$icon_bg_size       = array_merge( [ 'size' => 0 ], array_filter( $settings['icon_bg_size'] ) );
		$iconbg_size_tablet = array_merge(
			$icon_bg_size,
			$this->unset_empty_value( $settings['icon_bg_size_tablet'] )
		);
		$iconbg_size_mobile = array_merge(
			$iconbg_size_tablet,
			$this->unset_empty_value( $settings['icon_bg_size_mobile'] )
		);
		$less_vars->add_pixel_number( 'icon-bg-size', $icon_bg_size );
		$less_vars->add_pixel_number( 'icon-bg-size-tablet', $iconbg_size_tablet );
		$less_vars->add_pixel_number( 'icon-bg-size-mobile', $iconbg_size_mobile );

		$icon_font_size          = array_merge( [ 'size' => 0 ], array_filter( $settings['icon_size'] ) );
		$icon_font_size_tablet   = array_merge( $icon_font_size, array_filter( $settings['icon_size_tablet'] ) );
		$iconbg_font_size_mobile = array_merge( $icon_font_size_tablet, array_filter( $settings['icon_size_mobile'] ) );
		$less_vars->add_pixel_number( 'icon-font-size', $icon_font_size );
		$less_vars->add_pixel_number( 'icon-font-size-tablet', $icon_font_size_tablet );
		$less_vars->add_pixel_number( 'icon-font-size-mobile', $iconbg_font_size_mobile );

		$defaults = [
			'top'    => 0,
			'right'  => 0,
			'bottom' => 0,
			'left'   => 0,
		];
		$icon_below_gap        = array_merge(
			$defaults,
			the7_array_filter_non_empty_string( $settings['icon_below_gap'] )
		);
		$icon_below_gap_tablet = array_merge(
			$icon_below_gap,
			$this->unset_empty_value( $settings['icon_below_gap_tablet'] )
		);
		$icon_below_gap_mobile = array_merge(
			$icon_below_gap_tablet,
			$this->unset_empty_value( $settings['icon_below_gap_mobile'] )
		);

		$less_vars->add_paddings(
			[
				'icon-padding-top',
				'icon-padding-right',
				'icon-padding-bottom',
				'icon-padding-left',
			],
			$icon_below_gap,
			'px'
		);
		$less_vars->add_paddings(
			[
				'icon-padding-top-tablet',
				'icon-padding-right-tablet',
				'icon-padding-bottom-tablet',
				'icon-padding-left-tablet',
			],
			$icon_below_gap_tablet,
			'px'
		);
		$less_vars->add_paddings(
			[
				'icon-padding-top-mobile',
				'icon-padding-right-mobile',
				'icon-padding-bottom-mobile',
				'icon-padding-left-mobile',
			],
			$icon_below_gap_mobile,
			'px'
		);

		$devices = [
			''        => [
				'layout'    => [],
				'alignment' => [],
			],
			'_tablet' => [
				'layout'    => [ $settings['layout'] ],
				'alignment' => [ $settings['content_alignment'] ],
			],
			'_mobile' => [
				'layout'    => [ $settings['layout_tablet'], $settings['layout'] ],
				'alignment' => [ $settings['content_alignment_tablet'], $settings['content_alignment'] ],
			],
		];
		foreach ( $devices as $device => $extend ) {
			$layout         = $settings[ 'layout' . $device ] ?: current( array_filter( $extend['layout'] ) );
			$alignment      = $settings[ 'content_alignment' . $device ] ?: current( array_filter( $extend['alignment'] ) );

//			$flex_alignment = $this->get_content_alignment_less_keyword( $layout, $alignment, 'flex_general' );
			$var_suffix     = str_replace( '_', '-', $device );

			$less_vars->add_keyword( 'text-alignment' . $var_suffix, $alignment );
			$less_vars->add_keyword(
				'item-alignment' . $var_suffix,
				$this->get_content_alignment_less_keyword( $layout, $alignment, 'item' )
			);
			$less_vars->add_keyword(
				'btn-alignment' . $var_suffix,
				$this->get_content_alignment_less_keyword( $layout, $alignment, 'btn' )
			);
			$less_vars->add_keyword(
				'title-alignment' . $var_suffix,
				$this->get_content_alignment_less_keyword( $layout, $alignment, 'title' )
			);

			$less_vars->add_keyword( 'content-alignment' . $var_suffix, $alignment === 'left' ? 'flex-start' : 'center' );

			$layout_2_6_columns = 'auto auto';
			$slider_2_6_grid = '" desc desc " " icon empty "  " icon header " " icon subtitle "  " icon button " " icon empty1"';
			if ( $alignment === 'left' ) {
				$layout_2_6_columns = sprintf(
					'~"calc(%s + %s + %s) minmax(0,1fr)"',
					$less_vars->get_var( 'icon-bg-size' . $var_suffix ),
					$less_vars->get_var( 'icon-padding-left' . $var_suffix ),
					$less_vars->get_var( 'icon-padding-right' . $var_suffix )
				);
			} 
			elseif( $alignment === 'right' ) {
				//$layout_2_6_columns = '1fr auto';
				$layout_2_6_columns = sprintf(
					'~" minmax(0,1fr) calc(%s + %s + %s)"',
					$less_vars->get_var( 'icon-bg-size' . $var_suffix ),
					$less_vars->get_var( 'icon-padding-right' . $var_suffix ),
					$less_vars->get_var( 'icon-padding-left' . $var_suffix )
				);
				$slider_2_6_grid = '" desc desc " " empty icon "  " header icon " " subtitle  icon "  " button icon " " empty1 icon "';
			}
			$less_vars->add_keyword('slider-layout-2-columns' . $var_suffix, $layout_2_6_columns );

			$less_vars->add_keyword( 'layout' . $var_suffix, $layout );

			// For layouts 1, 5, 9.
			$slider_columns = 'minmax(0, 100%);';
			$slider_gap = 'normal';
			$slider_grid = '" desc desc " " icon empty "  " icon header " " icon subtitle "  " icon button " " icon empty1"';
			$slider_margin = implode( ' ', [
				$less_vars->get_var( 'icon-padding-top' . $var_suffix ),
				$less_vars->get_var( 'icon-padding-right' . $var_suffix ),
				$less_vars->get_var( 'icon-padding-bottom' . $var_suffix ),
				$less_vars->get_var( 'icon-padding-left' . $var_suffix ),
			] );

			if ( in_array( $layout, [ 'layout_4', 'layout_8' ] ) ) {
				$slider_columns = sprintf(
					'~"minmax(0, 1fr) calc(%s + %s + %s)"',
					$less_vars->get_var( 'icon-bg-size' . $var_suffix ),
					$less_vars->get_var( 'icon-padding-right' . $var_suffix ),
					$less_vars->get_var( 'icon-padding-left' . $var_suffix )
				);
				$slider_gap = 0;
				
				$slider_margin = implode( ' ', [
					$less_vars->get_var( 'icon-padding-top' . $var_suffix ),
					$less_vars->get_var( 'icon-padding-right' . $var_suffix ),
					$less_vars->get_var( 'icon-padding-bottom' . $var_suffix ),
					$less_vars->get_var( 'icon-padding-left' . $var_suffix ),
				] );
			} elseif ( in_array( $layout, [ 'layout_3', 'layout_7' ] ) ) {
				$slider_columns = sprintf(
					'~"calc(%s + %s) minmax(30px, 1fr)"',
					$less_vars->get_var( 'icon-bg-size' . $var_suffix ),
-					$less_vars->get_var( 'icon-padding-left' . $var_suffix )
				);
				$slider_gap = 0;
				$slider_margin = implode( ' ', [
					$less_vars->get_var( 'icon-padding-top' . $var_suffix ),
					$less_vars->get_var( 'icon-padding-right' . $var_suffix ),
					$less_vars->get_var( 'icon-padding-bottom' . $var_suffix ),
					$less_vars->get_var( 'icon-padding-left' . $var_suffix ),
				] );
			} elseif ( in_array( $layout, [ 'layout_2', 'layout_6' ] ) ) {
				$slider_columns = $layout_2_6_columns;
				$slider_grid = $slider_2_6_grid;
				$slider_gap = 'normal';
				
			}

			$less_vars->add_keyword( 'slider-columns' . $var_suffix, $slider_columns );
			$less_vars->add_keyword( 'slider-grid' . $var_suffix, $slider_grid );
			$less_vars->add_keyword( 'slider-gap' . $var_suffix, $slider_gap );
			$less_vars->add_keyword( 'slider-margin' . $var_suffix, $slider_margin );
		}
	}

	/**
	 * @param array $setting
	 *
	 * @return array
	 */
	protected function unset_empty_value( $setting ) {
		$maybe_dimesions = array_intersect_key(
			$setting,
			[ 'top' => '', 'bottom' => '', 'left' => '', 'right' => '' ]
		);

		if ( $maybe_dimesions && implode( '', $maybe_dimesions ) === '' ) {
			$setting = the7_array_filter_non_empty_string( $setting );
			unset( $setting['unit'] );
		} elseif ( isset( $setting['size'] ) && $setting['size'] === '' ) {
			unset( $setting['size'], $setting['unit'] );
		} elseif ( isset( $setting['value'] ) && $setting['value'] === '' ) {
			unset( $setting['value'] );
		}

		return $setting;
	}

	protected function get_content_alignment_less_keyword( $layout, $alignment, $context ) {
		$types = array_fill_keys(
			[ 'item', 'btn', 'title' ],
			[
				'left'   => 'flex-start',
				'center' => 'center',
				'right'  => 'flex-end',
			]
		);

		if ( in_array( $layout, [ 'layout_2', 'layout_6' ] ) ) {
			$types = [
				'item'  => [
					'left'   => 'flex-start',
					'center' => 'flex-end',
					'right'  => 'flex-end',
				],
				'btn'   => [
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				],
				'title' => [
					'left'   => 'flex-start',
					'center' => 'flex-start',
					'right'  => 'flex-end',
				],
			];

			if ( $layout === 'layout_6' ) {
				$types['btn'] = $types['title'];
			}
		}
		if ( in_array( $layout, [ 'layout_4', 'layout_8' ] ) ) {
			$types = [
				'item'  => [
					'left'   => 'flex-start',
					'center' => 'flex-start',
					'right'  => 'flex-start',
				],
				'btn'   => [
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				],
			];
		}
		if ( in_array( $layout, [ 'layout_3', 'layout_7' ] ) ) {
			$types = [
				'item'  => [
					'left'   => 'flex-end',
					'center' => 'flex-end',
					'right'  => 'flex-end',
				],
				'btn'   => [
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				],
			];
		}
		return presscore_array_value( $alignment, isset( $types[ $context ] ) ? $types[ $context ] : [] );
	}
}
