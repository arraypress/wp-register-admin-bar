<?php
/**
 * Admin Bar Manager for WordPress
 *
 * A comprehensive solution for managing WordPress admin bar items with features like:
 * - Automatic menu item creation and verification
 * - Parent-child relationships
 * - Node management
 * - Custom styling
 *
 * @package     ArrayPress/WP/Register/AdminBar
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @version     1.0.0
 * @author      ArrayPress
 */

declare( strict_types=1 );

namespace ArrayPress\WP\Register;

class AdminBar {

	/**
	 * Parent node ID
	 *
	 * @var string
	 */
	protected string $parent_id;

	/**
	 * Menu items configuration
	 *
	 * @var array
	 */
	protected array $items = [];

	/**
	 * Custom styles for nodes
	 *
	 * @var array
	 */
	protected array $styles = [];

	/**
	 * Initialize AdminBar
	 *
	 * @param string $parent_id Parent node ID
	 * @param array  $items     Optional array of menu items
	 */
	public function __construct( string $parent_id, array $items = [] ) {
		$this->parent_id = $parent_id;

		if ( ! empty( $items ) ) {
			$this->add_nodes( $items );
		}
	}

	/**
	 * Add multiple nodes from array
	 *
	 * @param array $items Array of node configurations
	 *
	 * @return self
	 */
	public function add_nodes( array $items ): self {
		foreach ( $items as $key => $item ) {
			// Use explicit ID if set
			if ( ! empty( $item['id'] ) ) {
				$this->add_node( $item );
				continue;
			}

			// Use string key if provided
			if ( is_string( $key ) ) {
				$item['id'] = $key;
				$this->add_node( $item );
				continue;
			}

			// Generate ID from title
			if ( ! empty( $item['title'] ) ) {
				$item['id'] = sanitize_title( $item['title'] );
				$this->add_node( $item );
			}
		}

		return $this;
	}

	/**
	 * Add a single node
	 *
	 * @param array $args       {
	 *
	 * @type string $id         Node ID
	 * @type string $title      Node title
	 * @type string $href       URL the node points to
	 * @type string $capability Required capability
	 * @type array  $meta       Meta attributes for the node
	 * @type array  $styles     Custom styles for the node
	 *                          }
	 *
	 * @return self
	 */
	public function add_node( array $args ): self {
		$defaults = [
			'id'         => '',
			'title'      => '',
			'href'       => '',
			'capability' => 'read',
			'meta'       => [],
			'styles'     => []
		];

		$args = wp_parse_args( $args, $defaults );

		$this->items[] = $args;

		if ( ! empty( $args['styles'] ) ) {
			$this->styles[ $args['id'] ] = $args['styles'];
		}

		return $this;
	}

	/**
	 * Add custom styles for a node
	 *
	 * @param string $id     Node ID
	 * @param array  $styles CSS styles
	 *
	 * @return self
	 */
	public function add_styles( string $id, array $styles ): self {
		$this->styles[ $id ] = $styles;

		return $this;
	}

	/**
	 * Register all nodes with WordPress
	 */
	public function register(): void {
		add_action( 'admin_bar_menu', [ $this, 'register_nodes' ], 999 );

		if ( ! empty( $this->styles ) ) {
			add_action( 'wp_print_styles', [ $this, 'print_styles' ] );
			add_action( 'admin_print_styles', [ $this, 'print_styles' ] );
		}
	}

	/**
	 * Register the nodes with WordPress
	 *
	 * @param \WP_Admin_Bar $wp_admin_bar WordPress admin bar object
	 */
	public function register_nodes( \WP_Admin_Bar $wp_admin_bar ): void {
		foreach ( $this->items as $item ) {
			if ( ! current_user_can( $item['capability'] ) ) {
				continue;
			}

			$node = [
				'id'     => $item['id'],
				'title'  => $item['title'],
				'href'   => $item['href'],
				'parent' => $this->parent_id,
				'meta'   => $item['meta']
			];

			$wp_admin_bar->add_node( $node );
		}
	}

	/**
	 * Print custom styles for nodes
	 */
	public function print_styles(): void {
		if ( empty( $this->styles ) ) {
			return;
		}

		$styles = '';
		foreach ( $this->styles as $id => $rules ) {
			$selector = '#wp-admin-bar-' . sanitize_html_class( $id );
			$styles   .= $this->build_css_rules( $selector, $rules );
		}

		if ( ! empty( $styles ) ) {
			wp_register_style( 'arraypress-admin-bar', false );
			wp_add_inline_style( 'arraypress-admin-bar', $styles );
			wp_enqueue_style( 'arraypress-admin-bar' );
		}
	}

	/**
	 * Build CSS rules from array
	 *
	 * @param string $selector CSS selector
	 * @param array  $styles   Array of CSS properties
	 *
	 * @return string
	 */
	protected function build_css_rules( string $selector, array $styles ): string {
		$rules = [];
		foreach ( $styles as $property => $value ) {
			$property = sanitize_key( $property );
			$value    = esc_attr( $value );
			$rules[]  = sprintf( '%s: %s;', $property, $value );
		}

		return sprintf( '%s { %s }', $selector, implode( ' ', $rules ) );
	}

}