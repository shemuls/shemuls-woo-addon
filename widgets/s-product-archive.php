<?php
namespace Elementor;
use WP_Query;

if ( class_exists( 'WooCommerce' ) ) {
	// if exist woocommerce start

	function s_woo_product_cat_list_for_archive( ) {
        $elements = get_terms( 'product_cat', array('hide_empty' => false) );
        $product_cat_array = array();

        if ( !empty($elements) ) {
            foreach ( $elements as $element ) {
                $info = get_term($element, 'product_cat');
                $product_cat_array[ $info->term_id ] = $info->name;
            }
        }
    
        return $product_cat_array;
    }


// Widget Start here
class S_Product_Archive extends Widget_Base {

	/**
	 * Get widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 's-product-archive';
	}

	/**
	 * Get widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return 'SS Product Archive';
	}

	/**
	 * Get widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-code';
	}

	/**
	 * Get widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'woocommerce-elements' ];
	}

	/**
	 * Register widget controls.
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		/**
		 *  Here you can add your controls. The controls below are only examples.
		 *  Check this: https://developers.elementor.com/elementor-controls/
		 *
		 **/

		// Create a section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Configuration', 'shemuls-woo-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

// Product col widht management control
		$this->add_responsive_control(
			'product_col_width',
			[
				'label' => __( 'Collumn Width', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 28,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 32,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .s-woo-product-col-3' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
            's_product_cart_btn',
            [
                'label' => __( 'Button', 'shemuls-woo-addon' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
					'view_details_button'  => __( 'View Details', 'shemuls-woo-addon' ),
                    'cart_button'  => __( 'Add to cart', 'shemuls-woo-addon' ),
                ],
                'default' => 'view_details_button'
            ]
        );

		$this->end_controls_section();

		// product_img_section Style control start here
		$this->start_controls_section(
			'product_img_section',
			[
				'label' => __( 'Image', 'shemuls-woo-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'product_img_width',
			[
				'label' => __( 'Width', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} img.s-woo-product-image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'product_img_height',
			[
				'label' => __( 'Height', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],

				'selectors' => [
					'{{WRAPPER}} img.s-woo-product-image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
            'product_img_object_fit',
            [
                'label' => __( 'Object fit', 'shemuls-woo-addon' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'contain',
                'options' => [
                    'cover'  => __( 'Cover', 'shemuls-woo-addon' ),
                    'contain'  => __( 'Contain', 'shemuls-woo-addon' )
                ],
                'selectors' => [
					'{{WRAPPER}} img.s-woo-product-image' => 'object-fit: {{product_img_object_fit}};',
				],
            ]
        );
		$this->add_responsive_control(
			'product_img_spacing',
			[
				'label' => __( 'Spacing', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} img.s-woo-product-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();
		//product_img_section Style control end here

		// product_save_PARCENTAGE_style_section control start here
		$this->start_controls_section(
			'product_save_percentage_style_section',
			[
				'label' => __( 'Save %', 'shemuls-woo-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'product_save_percentage_typography',
				'label' => __( 'Typography', 'shemuls-woo-addon' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .s-woo-product-area .s-woo-on-sale',
			]
		);
		$this->add_control(
			'product_save_percentage_color',
			[
				'label' => __( 'Color', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .s-woo-product-area .s-woo-on-sale' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'product_save_percentage_background_color',
			[
				'label' => __( 'Background', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .s-woo-product-area .s-woo-on-sale' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'product_save_percentage_hover_color',
			[
				'label' => __( 'Hover', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .s-woo-product-area .s-woo-on-sale:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .s-woo-product-area .s-woo-on-sale',
			]
		);
		$this->add_responsive_control(
			'product_save_percentage_padding',
			[
				'label' => __( 'Padding', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .s-woo-product-area .s-woo-on-sale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'product_save_percentage_margin',
			[
				'label' => __( 'Margin', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .s-woo-product-area .s-woo-on-sale' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		//product_save_PARCENTAGE_style_section  control end here
	

		// product_title_style_section control start here
		$this->start_controls_section(
			'product_title_style_section',
			[
				'label' => __( 'Title', 'shemuls-woo-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'product_title_typography',
				'label' => __( 'Typography', 'shemuls-woo-addon' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} h3.s-woo-product-tile',
			]
		);
		$this->add_control(
			'product_title_color',
			[
				'label' => __( 'Color', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} h3.s-woo-product-tile' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
		//product_content_style_section  control end here

		// product_Desc_style_section control start here
		$this->start_controls_section(
			'product_desc_style_section',
			[
				'label' => __( 'Description', 'shemuls-woo-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'product_desc_typography',
				'label' => __( 'Typography', 'shemuls-woo-addon' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} p.s-woo-product-short-desc',
			]
		);
		$this->add_control(
			'product_desc_color',
			[
				'label' => __( 'Color', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} p.s-woo-product-short-desc' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'product_read_more_typography',
				'label' => __( 'Typography', 'shemuls-woo-addon' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} a.s-woo-desc-read-more',
			]
		);
		$this->add_control(
			'product_desc_read_more_color',
			[
				'label' => __( 'Read More', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} a.s-woo-desc-read-more' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'product_desc_spacing',
			[
				'label' => __( 'Spacing', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} p.s-woo-product-short-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		//product_desc_style_section  control end here

		// product_PRICE_style_section control start here
		$this->start_controls_section(
			'product_price_style_section',
			[
				'label' => __( 'Price', 'shemuls-woo-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'product_price_typography',
				'label' => __( 'Typography', 'shemuls-woo-addon' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} span.woocommerce-Price-amount.amount',
			]
		);
		$this->add_control(
			'product_price_color',
			[
				'label' => __( 'Color', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} span.woocommerce-Price-amount.amount' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'product_sale_price_color',
			[
				'label' => __( 'Sale Price', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} del, {{WRAPPER}} del span.woocommerce-Price-amount.amount' => 'color: {{VALUE}}',
				],
			]
		);


		$this->end_controls_section();
		//product_PRICE_style_section  control end here

		// product_addToCart_button_style_section control start here
		$this->start_controls_section(
			'product_add_to_cart_btn_style_section',
			[
				'label' => __( 'Cart Button', 'shemuls-woo-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'product_cart_btn_typography',
				'label' => __( 'Typography', 'shemuls-woo-addon' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .s-woo-product-area .s-woo-cart-btn a.button',
			]
		);
		$this->add_control(
			'product_cart_btn_text_color',
			[
				'label' => __( 'Color', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .s-woo-product-area .s-woo-cart-btn a.button' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'product_cart_btn_background_color',
			[
				'label' => __( 'Background', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .s-woo-product-area .s-woo-cart-btn a.button' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'product_cart_backgorund_hover_color',
			[
				'label' => __( 'Hover', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .s-woo-product-area .s-woo-cart-btn a.button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'product_cart_btn_padding',
			[
				'label' => __( 'Padding', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .s-woo-product-area .s-woo-cart-btn a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'product_cart_btn_margin',
			[
				'label' => __( 'Margin', 'shemuls-woo-addon' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .s-woo-product-area .s-woo-cart-btn a.button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		//product_addToCart_button_style_section  control end here
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		if( is_product_category() ) {
			// yay, we are on a product category page!
		
		echo '<div class="s-woo-product-area">';
		if(have_posts()){
		while(have_posts()) : the_post(); 
		global $product;
		?>

		<div class="s-woo-product s-woo-product-col-3">
		<div class="s-woo-product-img">
			
				
				<?php 

					
					   if ( $product->is_on_sale() ) {
						   echo '<div class="s-woo-on-sale">';
					   if ( $product->is_type( 'simple' ) ) {
						  $max_percentage = ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100;
					   } elseif ( $product->is_type( 'variable' ) ) {
						  $max_percentage = 0;
						  foreach ( $product->get_children() as $child_id ) {
							 $variation = wc_get_product( $child_id );
							 $price = $variation->get_regular_price();
							 $sale = $variation->get_sale_price();
							 if ( $price != 0 && ! empty( $sale ) ) 

							 $percentage = ( $price - $sale ) / $price * 100;

							 if(!empty( $percentage )){
								if ( $percentage > $max_percentage ) {
								 	$max_percentage = $percentage;
								 }
							 }
							 
						  }
					   }
					   if ( $max_percentage > 0 ) echo "<span class='s-woo-onsale'>save " . round($max_percentage) . "%</span></div>"; // If you would like to show -40% off then add text after % sign
					}


				   
						
														

				 ?>

			
			<a href="<?php echo the_permalink(); ?>"><img class="s-woo-product-image" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" alt=""></a>
		</div>

		<div class="s-woo-product-cont">
			<a href="<?php echo the_permalink(); ?>">
				<h3 class="s-woo-product-tile"><?php echo get_the_title(); ?></h3>
			</a>
			<p class="s-woo-product-short-desc"><?php echo get_the_excerpt(); ?> <br/><a class="s-woo-desc-read-more" href="<?php echo the_permalink(); ?>">Read More</a></p>
			<h4><?php echo $product->get_price_html(); ?></h4>
			<div class="s-woo-cart-btn">

				<?php
				if( $product->is_type( 'variable' ) ) {
					?>
					<p class="product woocommerce add_to_cart_inline">
						<a href="<?php echo the_permalink(); ?>"  class="button">View Details</a>
					</p>
					<?php
				}else{

					if($settings['s_product_cart_btn'] == 'view_details_button'){
						?>
							<p class="product woocommerce add_to_cart_inline">
								<a href="<?php echo the_permalink(); ?>"  class="button">View Details</a>
							</p>
					<?php
					}else{
						echo do_shortcode('[add_to_cart style="" show_price="FALSE" id="'.get_the_ID().'"]');
					}
					
				}
				?>
			</div>
		</div>
	</div>
	<?php


	endwhile; wp_reset_query();
	}else{
		echo 'Product Not Available';
	}
	echo '</div>';

		}else{
			echo 'Please use this widget in category single page';	
		}
	}

	/**
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 * With JS templates we don’t really need to retrieve the data using a special function, its done by Elementor for us.
	 * The data from the controls stored in the settings variable.
	 */
	
}


// If exits woocommerce end
}