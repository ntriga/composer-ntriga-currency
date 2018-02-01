# README #

## composer.json ##

### Edit composer.json ###

```json
{
	"repositories": [
		{
			"type": "git",
			"url": "https://dspventures@bitbucket.org/dspdevteam/composer-ntriga-currency.git"
		}
	]
}
```

### Require package ###

```
composer require ntriga/currency:dev-master
```

## PHP ##


### Download and update new currency rate ###

```php
use Ntriga\Logger;

require __DIR__ . '/../vendor/autoload.php';

Currency::update();

### Convert currency ###

```php
use Ntriga\Logger;

require __DIR__ . '/../vendor/autoload.php';

$resp = Currency::convert(20.00, 'EUR', 'CNY');

var_dump($resp);
```