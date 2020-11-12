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
    public function baseFormat($request): array
    {
        return [
            //
        ];
    }
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

#### Exclude

The exclude enhancement allows you to exclude data from the output.

```php
<?php

ExampleResource::make($resource)->exclude('key1', 'key2');
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


### Collections

Enhanced collections work by mapping the format and enhancement calls to each resource contained within.
As a result enhanced collections only work when they collect enhanced resources.
The easiest way to handle this is to set the `$collects` property on the enhanced collection or ensure that you're
following the standard convention for resource and collection naming so that the `collects()` method can detect it.

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
