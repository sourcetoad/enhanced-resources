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

#### Collections

Collections pass the format down to each of the collected resources as long as the collection collects EnhancedResources.

### Base Enhancements

EnhancedResources comes with a small set of core enhancements: `append`, `call`, `except`, `only`, and `replace`.

#### Append

The append enhancement allows you to append data from the underlying resource object to the output.

```php
<?php

ExampleResource::make($resource)->append('key1', 'key2');
// Output will include the `key1` and `key2` keys even if they aren't included in the format.
```

#### Call

The call enhancement allows you to use one off enhancements using a callable.

```php
<?php

ExampleResource::make($resource)
    ->call(function (ExampleResource $resource, array $data) {
        // Alter $data

        return $data;
    });
```

#### Except

The except enhancement allows you to exclude data from the output.

```php
<?php

ExampleResource::make($resource)->except('key1', 'key2');
```

#### Only

The only enhancement allows to limit the data to a given set of keys.

```php
<?php

ExampleResource::make($resource)->only('key1', 'key2');
```

#### Replace

The replace enhancement allows you to replace the dataset outright using `array_replace` or `array_replace_recursive`.

```php
<?php

ExampleResource::make($resource)->replace([/* New Data */]); // recursive
ExampleResource::make($resource)->replace([/* New Data */], false); // not recursive
```

## Advanced Usage

EnhancedResources allows you to enhance your resources beyond the included resources.

### Custom Enhancements

With the `EnhancementManager` you can easily add your own custom enhancements by just providing something `callable`, a name, and (optionally) a FQN for an `EnhancedResource`.

```php
<?php

use Sourcetoad\EnhancedResources\EnhancedResource;
use Sourcetoad\EnhancedResources\EnhancementManager;
use Sourcetoad\EnhancedResources\Support\Facades\ResourceEnhancements;

$exampleEnhancement = fn(EnhancedResource $resource, $data) => $data;

// Resolve the Enhancement Manager out of the container.
resolve(EnhancementManager::class)->register('example', $exampleEnhancement);

// Use the ResourceEnhancements facade.
ResourceEnhancements::register('example', fn(EnhancedResource $resource, $data) => $data);
```
