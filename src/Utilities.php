<?php
/**
 * Admin Bar Registration Helper
 *
 * Provides a simplified interface for registering WordPress admin bar nodes.
 * This helper function wraps the AdminBar class to provide a quick way to register
 * multiple admin bar nodes at once with support for styling and hierarchical organization.
 *
 * Example usage:
 * ```php
 * $nodes = [
 *     [
 *         'id'         => 'my-store',
 *         'title'      => 'My Store',
 *         'href'       => admin_url('admin.php?page=my-store'),
 *         'capability' => 'manage_options',
 *         'styles'     => [
 *             'background-color' => '#32CD32',
 *             'color'           => '#ffffff'
 *         ]
 *     ]
 * ];
 *
 * register_admin_bar( 'my-parent-menu', $nodes );
 * ```
 *
 * @package     ArrayPress/WP/Register/AdminBar
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @version     1.0.0
 * @author      David Sherlock
 */

declare( strict_types=1 );

use ArrayPress\WP\Register\AdminBar;

if ( ! function_exists( 'register_admin_bar' ) ):
	/**
	 * Helper function to create a new AdminBar instance
	 *
	 * @param string $parent_id Parent node ID
	 * @param array  $items     Optional array of menu items
	 *
	 * @return AdminBar
	 */
	function register_admin_bar( string $parent_id, array $items = [] ): AdminBar {
		$admin_bar = new AdminBar( $parent_id, $items );
		$admin_bar->register();

		return $admin_bar;
	}
endif;