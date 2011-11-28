# Server Environments

mysite.com is running in 4 environments. Local copies are on each independent developer's computer. There is a private Development and private Staging environment and a public Production environment.

## Environments

### Local

- Specific to each developer.
- Developers are encouraged to run the latest stable versions of PHP and MySQL

### Development

- Located at dev.mysite.com
- PHP version 5.2.14
- MySQL version 5.0.84
- Apache 2.2.17

### Staging

- Located at staging.mysite.com
- PHP version 5.3.2
- MySQL version 5.0.91
- Apache 2.2.17

### Production

- Located at mysite.com
- PHP version 5.3.2
- MySQL version 5.0.91
- Apache 2.2.17


## Cross-environment config.php

This site uses the 3rd Party NSM Config Bootstrap by Leevi Graham of Newism. You can find more information on that multi-environment approach [here](http://expressionengine-addons.com/nsm-config-bootstrap)

Included in the config\_bootstrap.php file we also set the path for the 3rd Party plugin [ED ImageResizer](http://github.com/erskinedesign/ED-Imageresizer)

This may also be helpful for creating a multi-server environment config.php base file: <http://eeinsider.com/articles/multi-server-setup-for-ee-2/>