# Enhanced Resources
Laravel's API Resources enhanced.
## Installation
```
$ composer require sourcetoad/enhanced-resources
```
## Basic Usage
To create an enhanced resource you simply extend `Sourcetoad\Tests\EnhancedResources` instead of `Illuminate\Http\Resources\Json\JsonResource`.

```php
<?php

use Sourcetoad\EnhancedResources\EnhancedResource;

class ExampleResource extends EnhancedResource
{
    public function format($request): array
    {
        return parent::format($request);
    }
}
```

### Multiple Formats
With EnhancedResources you can have multiple formats for a single resource by adding a `{formatName}Format` method to the resource and providing the desired format during instantiation.

```php
<?php

use Sourcetoad\EnhancedResources\EnhancedResource;

class ExampleResource extends EnhancedResource
{
    public function alternativeFormat($request): array
    {
        return [];
    }
}

ExampleResource::make($resource, 'alternative');
```

### Calling Enhancements
EnhancedResources comes with a base set of enhancements that include:

#### Exclude
Exclude allows you to dynamically remove a set of fields from a return for an endpoint without needing an entirely new resource or format.

```php
<?php

use App\Http\Resources\UserResource;

UserResource::make($user)->resolve(); // ['email_address', 'first_name', 'id', 'last_name']
UserResource::make($user)->exclude(['email_address'])->resolve(); // ['first_name', 'id', 'last_name']
```

## Advanced Usage
For when you need even more enhancements.

### Creating a Custom Enhancement
EnhancedResources supports both class based and callable enhancements.

#### Callable
To create a callable enhancement all you need is something `callable` that accepts a resource and data array as the first two arguments:

```php
<?php

use Sourcetoad\EnhancedResources\EnhancedResource;

function (EnhancedResource $resource, array $data, ...$additionalParams): array {
    // ...
}
```

#### Class Based
To create a class based enhancement you need a class that is a subclass of `Sourcetoad\EnhancedResources\Enhancements\Enhancement`.

```php
<?php

use Sourcetoad\EnhancedResources\EnhancedResource;
use Sourcetoad\EnhancedResources\Enhancements\Enhancement;

class ExampleEnhancement extends Enhancement
{
    // Constructor accepts anything passed in when calling an enhancement.
    public function __construct(...$params) {}

    // Invoke accepts the resource and its data.
    public function __invoke(EnhancedResource $resource, array $data): array {}
}
```