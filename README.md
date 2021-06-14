
# terminal42/contao-shortlink

This extension allows to create shortlinks in the Contao back end,
similar to bit.ly and other shortener services.

## Installation

Choose the installation method that matches your workflow!

### Installation via Contao Manager

Search for `terminal42/contao-shortlink` in the Contao Manager and add it to your installation. Finally, update the
packages.

### Manual installation

Add a composer dependency for this bundle. Therefore, change in the project root and run the following:

```bash
composer require terminal42/contao-shortlink
```

Depending on your environment, the command can differ, i.e. starting with `php composer.phar …` if you do not have
composer installed globally.


## Bundle configuration

**Default configuration:**
```yaml
terminal42_shortlink:
    host: ~
    salt: terminal42_shortlink
    log_ip: false
```

- **host:** The host to use for shortlinks. Can be different than the website host,
    but make sure it is set up on your hosting/server.

- **salt:** If a shortlink does not have a custom alias (path), a unique hash ID is
    generated from the database ID. By changing the salt you can get unique IDs for your
    installation.

- **log_ip:** This extension records how often a shortlink is accessed. Enable this
    flag to also capture IP addresses (not allowed under DSGVO in europe!)


## License

This bundle is released under the [MIT](LICENSE)
