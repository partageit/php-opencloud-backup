# Backup with OpenCloud

... especially with [OVH](http://www.ovh.com)

## Inspiration

This script is very inspired from https://gist.github.com/BaptisteDixneuf/85dc4419a0398446d2d3

Thank you [BaptisteDixneuf](http://baptistedixneuf.fr/) :)

## Installation

git and [composer](https://getcomposer.org/) must be present.

    git clone https://github.com/partageit/php-opencloud-backup.git
    composer update

## Usage

To start backup of `/home/me/photos/*.jpeg` in the container `photos-2016` reading credentials from `config.json`:

    php oc-backup.php --files=/home/me/photos/*.jpeg --container=photos-2016 --config=config.json

For further details, type:

    php oc-backup.php --help

### Configuration file

```json
{
	"authUrl": "https://auth.cloud.ovh.net/v2.0",
	"username": "",
	"password": "",
	"tenant": "",
	"swiftUrl": "",
	"serviceName": "swift",
	"region": ""
}
```

Where: 

- authUrl: Access point to the Identity service, defined in "Accès et sécurité/Accès API"
- username: Horizon user
- password: Horizon password
- tenant: OS_TENANT_NAME value from the RC OpenStack file
- swiftUrl: Access point to the Object store service, defined in "Accès et sécurité/Accès API"
- serviceName: Always swift?
- region: the region name, in GRA1, SBG1, ...

## [License](LICENSE)
