# Enhanced Resources
Laravel's API Resources enhanced.
## Installation
```
$ composer require sourcetoad/enhanced-resources
```
## Usage
### Creating an Enhanced Resource
```php
<?php

use Illuminate\Http\Resources\Json\JsonResource;
use Jasonej\EnhancedResources\EnhancedResource;

class UserResource extends EnhancedResource {}
```

### Creating an Enhanced Collection
```php
<?php

use Illuminate\Http\Resources\Json\ResourceCollection;
use Jasonej\EnhancedResources\EnhancedCollection;

class UserCollection extends EnhancedCollection {}
```

### Appending Attributes
The default behavior of API resources is to return the model's attributes:
```php
<?php

$user = User::find(1);

UserResource::make($user)->response();
```
```json
{
  "id": 1,
  "first_name": "John",
  "last_name": "Doe",
  "secret": "SUPERSECRET"
}
```


Appending an attribute allows you to dynamically append attributes via the model's underlying accessors.
```php
UserResource::make($user)->append(['name'])->response();
```
```json
{
  "id": 1,
  "first_name": "John",
  "last_name": "Doe",
  "name": "John Doe", 
  "secret": "SUPERSECRET"
}
```

### Excluding Attributes
Excluding an attribute allows you to dynamically remove attributes from the resource's output.
```php
UserResource::make($user)->exclude(['id', 'secret'])->response();
```
```json
{
  "first_name": "John",
  "last_name": "Doe"
}
```

### Masking
Masking allows you to hide the true values of fields. Masking applies to all attributes on a resource that has a mask function.
```php
class UserResource extends EnhancedResource
{
    public function maskFirstName(string $value): string
    {
        return substr($value, 0, 1).'*****';
    }

    public function maskLastName(string $value): string
    {
        return substr($value, 0, 1).'*****';
    }
}

UserResource::make($user)->mask()->response();
```
```json
{
  "first_name": "J*****",
  "last_name": "D*****"
}
```

### Only Attributes
The only method allows you to restrict a resource's output to only the provided set of attributes.
```php
UserResource::make($user)->only(['first_name', 'last_name'])->response();
```
```json
{
  "first_name": "John",
  "last_name": "Doe"
}
```

## Adding New Enhancements
Enhanced resources are quite extensible. In order to add an additional piece of behavior you just create a trait and have your resource/collection class use it.

```php
<?php

trait SomeNewEnhancement
{
    protected static function bootSomeNewEnhancement()
    {
        static::registerHook(function ($target, array $data) {
            // Apply your changes to the resolved data here
        });

        static::registerMap(function ($resourceCollection) {
            // Map your enhancement behavior to the underlying resource here
        });
    }
}

class ExtraEnhancedResource extends EnhancedResource
{
    use SomeNewEnhancement;

    protected static $anonymousResourceCollectionClass = ExtraEnhancedAnonymousResourceCollection::class;
}

class ExtraEnhancedCollection extends EnhancedCollection
{
    use SomeNewEnhancement;
}

class ExtraEnhancedAnonymousResourceCollection extends ExtraEnhancedCollection
{
    use SomeNewEnhancement;
}
```