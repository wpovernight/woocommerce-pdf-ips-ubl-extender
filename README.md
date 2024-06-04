# PDF Invoices & Packing Slips for WooCommerce - UBL Extender

This addon extends the functionality of the PDF Invoices & Packing Slips for WooCommerce plugin by allowing you to add custom handlers to the UBL document format. Handlers can be positioned before or after existing handlers, providing flexibility in customizing the UBL document generation process.

## Installation

1. **Clone this repository:**
   
```shell
git clone git@github.com:wpovernight/woocommerce-pdf-ips-ubl-extender.git
``` 

## Usage

### Directory Structure

Ensure your custom handler classes are placed inside the `includes/handlers` directory with the following namespace:

```php
namespace WPO\WC\UBL\Extender\Handlers;
```

### Extending UBL Handler

Your custom handlers should extend the main plugin abstract class `\WPO\WC\UBL\Handlers\UblHandler`.

Example of a custom handler:

```php
<?php
namespace WPO\WC\UBL\Extender\Handlers;

use WPO\WC\UBL\Handlers\UblHandler;

class CustomHandler extends UblHandler {
    public function handle() {
        // Your custom handling logic here
    }
}
?>
```

### Including Custom Handlers

When you add a new handler, you must include it inside the `add_handlers()` function in the main addon plugin file `woocommerce-pdf-ips-ubl-extender.php`. Here is how you can do it:

```php
public function add_handlers( array $format ): array {
    $custom_handler = array(
        'enabled' => true,
        'handler' => \WPO\WC\UBL\Extender\Handlers\CustomHandler::class,
    );
    
    $format = $this->insert_handler_at_key( $format, 'customhandler', $custom_handler, 'issuedate', 'after' ); // Insert after 'issuedate'
    
    return $format;
}
```

### Autoloading

After adding new handler classes, make sure to dump the Composer autoload to include the new classes:

```bash
composer dump-autoload
```

## License

This project is licensed under the GPLv3 license. For more details, see the `LICENSE` file.

## Acknowledgments

- [PDF Invoices & Packing Slips for WooCommerce](https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)

Feel free to contribute to this project by submitting issues or pull requests on the [GitHub repository](https://github.com/wpovernight/woocommerce-pdf-ips-ubl-extender).

---

This readme provides comprehensive instructions on how to set up, extend, and include custom handlers in the PDF Invoices & Packing Slips for WooCommerce - UBL Extender addon.