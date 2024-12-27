# WordPress Admin Bar Manager

A comprehensive PHP library for managing WordPress admin bar nodes with automatic registration, styling, and hierarchical organization capabilities. This library provides a robust solution for programmatically creating and managing admin bar items in WordPress.

## Features

- 🚀 Automatic menu item creation and verification
- 👨‍👧‍👦 Parent-child menu relationships
- 🎨 Custom node styling support
- 🔒 Capability-based access control
- 🎯 Dynamic node ID generation
- 🛠️ Helper function for quick implementation
- ✅ Sanitization and validation

## Requirements

- PHP 7.4 or higher
- WordPress 6.7.1 or higher

## Installation

You can install the package via composer:

```bash
composer require arraypress/wp-register-admin-bar
```

## Basic Usage

Here's a simple example of how to register admin bar nodes:

```php
use ArrayPress\WP\Register\AdminBar;

// Initialize the admin bar manager
$admin_bar = new AdminBar( 'my-plugin', [
	[
		'id'    => 'my-menu',
		'title' => 'My Menu',
		'href'  => admin_url( 'admin.php?page=my-menu' )
	]
] );

// Register the nodes
$admin_bar->register();
```

## Using the Helper Function

For quick implementation, use the provided helper function:

```php
$nodes = [
	[
		'id'         => 'my-store',
		'title'      => 'My Store',
		'href'       => admin_url( 'admin.php?page=my-store' ),
		'capability' => 'manage_options',
		'styles'     => [
			'background-color' => '#32CD32',
			'color'            => '#ffffff'
		]
	]
];

register_admin_bar( 'my-parent-menu', $nodes );
```

## Configuration Options

Each node can be configured with the following options:

| Option | Type | Description |
|--------|------|-------------|
| id | string | Unique identifier for the node |
| title | string | Display text for the menu item |
| href | string | URL the menu item links to |
| capability | string | Required WordPress capability |
| meta | array | Additional meta attributes |
| styles | array | Custom CSS styles for the node |

### Custom Styling

Add custom styles to your nodes:

```php
$admin_bar->add_styles( 'my-node', [
	'background-color' => '#f0f0f0',
	'color'            => '#333333',
	'font-weight'      => 'bold'
] );
```

## Advanced Usage

### Dynamic Node IDs

The library can automatically generate node IDs from titles:

```php
$nodes = [
	[
		'title' => 'My Custom Page',
		'href'  => '/custom-page'
	]
];

$admin_bar = new AdminBar( 'parent-menu', $nodes );
```

### Multiple Node Registration

Register multiple nodes at once:

```php
$admin_bar->add_nodes( [
	'settings' => [
		'title'      => 'Settings',
		'href'       => admin_url( 'admin.php?page=settings' ),
		'capability' => 'manage_options'
	],
	'reports'  => [
		'title'      => 'Reports',
		'href'       => admin_url( 'admin.php?page=reports' ),
		'capability' => 'view_reports'
	]
] );
```

## Error Handling

The library uses type declarations and strict typing for error prevention:

```php
try {
	$admin_bar->add_node( [
		'id'    => 'example',
		'title' => 'Example'
	] );
} catch ( TypeError $e ) {
	// Handle type error
	error_log( $e->getMessage() );
}
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the GPL2+ License. See the LICENSE file for details.

## Credits

Developed and maintained by ArrayPress Limited.

## Support

For support, please use the [issue tracker](https://github.com/arraypress/wp-register-admin-bar/issues).