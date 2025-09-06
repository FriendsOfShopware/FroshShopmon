# FroshShopmon

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Shopware 6](https://img.shields.io/badge/Shopware-6.5%20|%206.6%20|%206.7-blue.svg)](https://shopware.com)

A Shopware 6 plugin that serves as a connector for Shopmon Shop Monitoring with automatically generated, easy to configure access credentials.

## Permissions

The plugin automatically creates an integration with the following permissions:

- `app:read` - Read access to apps
- `plugin:read` - Read access to plugins  
- `system_config:read` - Read access to system configuration
- `scheduled_task:read` - Read access to scheduled tasks
- `frosh_tools:read` - Read access to FroshTools (if installed)
- `system:clear:cache` - Permission to clear cache
- `system:cache:info` - Permission to get cache information

## Installation

### Via Plugin Manager

1. Download the plugin
2. Upload it via the Plugin Manager in your Shopware Administration
3. Install and activate the plugin

### Via Composer

```bash
composer require frosh/shopmon
bin/console plugin:refresh
bin/console plugin:install --activate FroshShopmon
```

### Manual Installation

1. Download the plugin
2. Extract it to `custom/plugins/FroshShopmon`
3. Install via command line:

```bash
bin/console plugin:refresh
bin/console plugin:install --activate FroshShopmon
```

## Configuration

After installation, the integration data will be automatically generated and stored. You can view the Base64-encoded integration data in:

**Administration → Settings → Extensions → FroshShopmon**

The integration data contains:
- Shop URL (from APP_URL environment variable)
- Client ID (automatically generated)
- Client Secret (automatically generated)

## Usage with Shopmon

1. Install and activate the FroshShopmon plugin
2. Navigate to the plugin configuration in the admin panel
3. Copy the Base64-encoded integration data
4. Use this data to configure your Shopmon monitoring setup

## Development

### Requirements

- Shopware 6.5.0 or higher
- PHP 8.1 or higher


## Compatibility

- Shopware 6.5.x ✅
- Shopware 6.6.x ✅  
- Shopware 6.7.x ✅

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support and issues, please visit:
- [FriendsOfShopware](https://friendsofshopware.com)
- [GitHub Issues](https://github.com/FriendsOfShopware/FroshShopmon/issues)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

- Support for Shopware 6.5, 6.6, and 6.7
