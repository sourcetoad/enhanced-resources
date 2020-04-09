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