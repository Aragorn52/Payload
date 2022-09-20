#  Payload-token

Library to add payload to Access token for lumen passport

## Dependencies

* PHP >= 8.1
* Lumen >= 9.0
* Lumen-passport: >= 0.1.1

## Installation via Composer

```bash
$ composer require payloadtoken/aragorn52
```
Or if you prefer, edit `composer.json` manually:

```json
{
    "require": {
        "payloadtoken/aragorn52": "^1.0"
    }
}
```

### Modify the bootstrap flow (```bootstrap/app.php``` file)
```php
$app->register(\Payload\Providers\PassportServiceProvider::class);
```

## Create YourCustomClaimService
### in method addCustomClaim add for claimCollection your claims
```php
class YourCustomClaimService extends AbstractClaimService
{
    public function addCustomClaims(): void
    {
        $this->claimCollection->add('test', 'testClaim');
        $this->claimCollection->add('test2', 'testClaim2');
    }
}
```
### Modify the AppServiceProvider flow (```app/Providers/PassportServiceProvider```) add this code in method boot()
```php
    public function boot()
    {
        $this->app->bind(AbstractClaimService::class, fn () => new YourCustomClaimService());
    }
```
