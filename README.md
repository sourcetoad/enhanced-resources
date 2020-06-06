# Enhanced Resources

Laravel's API Resources, Enhanced.

## Installation

```
$ composer require sourcetoad/enhanced-resources
```

## Basic Usage

To create an enhanced resource you simply extend `Sourcetoad\EnhancedResources\EnhancedResource` instead of `Illuminate\Http\Resources\Json\JsonResource`.

```php
<?php

use Sourcetoad\EnhancedResources\EnhancedResource;

class ExampleResource extends EnhancedResource
{
    //
}
```

### Multiple Formats

With EnhancedResources you can have multiple formats for each resource by adding format methods (`{formatName}Format`) to the resource.

When using a resource with multiple formats you can provide the intended format during resource instantiation:

```php
<?php

use Sourcetoad\EnhancedResources\EnhancedResource;

class ExampleResource extends EnhancedResource
{
    public function alternativeFormat($request): array
    {
        return [
            //
        ];    
    }
}

ExampleResource::make($resource, 'alternative');
new ExampleResource($resource, 'alternative');
```

Alternatively, you can provide the desired format after instantiation with the format method:

```php
<?php

ExampleResource::make($resource)->format('alternative');
```
