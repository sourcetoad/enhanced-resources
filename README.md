# Enhanced Resources

Enhancements for Laravel's API resources.

## Installation

```
$ composer require sourcetoad/enhanced-resources
```

## Basic Usage

To create an enhanced resource you simply extend `Sourcetoad\EnhancedResources\Resource` instead of `Illuminate\Http\Resources\Json\JsonResource` and provide a format method.

```php
<?php

use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Resource;

class ExampleResource extends Resource
{
    #[Format]
    public function foo(): array
    {
        return [];
    }
}
```

## Formatting

With EnhancedResources you can have multiple formats for a single resource by adding format methods. Format methods are defined using the `#[Format]` attribute.

If only a single format method is defined, as is the case in the example above in the [basic usage](#basic-usage) section, that format will be the default format that is used when resolving the resource. However, you can define as many formats as you like.

```php
<?php

use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Resource;

class ExampleResource extends Resource
{
    #[Format]
    public function bar(): array
    {
        return [];
    }

    #[Format]
    public function foo(): array
    {
        return [];
    }

    #[Format]
    public function foobar(): array
    {
        return [];
    }
}
```

In cases like the one above you'll need to specify the format to be used by providing its name to the `format()` method. By default the format uses the same name as the method, so in this example we have format names of `bar`, `foo`, and `foobar`.

```php
ExampleResource::make($object)->format('foo');
```

Failing to specify the format in a situation where there is no default format will result in a `NoFormatSelectedException` being thrown.

### Specifying a Default

If you don't want to always explicitly specify the format to be used when you have a resource with multiple formats you can specify one format as default using the `#[IsDefault]` attribute.

```php
<?php

use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Formatting\Attributes\IsDefault;
use Sourcetoad\EnhancedResources\Resource;

class ExampleResource extends Resource
{
    #[Format]
    public function bar(): array
    {
        return [];
    }

    #[IsDefault, Format]
    public function foo(): array
    {
        return [];
    }

    #[Format]
    public function foobar(): array
    {
        return [];
    }
}
```

After adding the `#[IsDefault]` attribute to one of your format methods it will be used unless the format is explicitly specified via the `format()` method.

Specifying more than one default method via the `#[IsDefault]` attribute will result in a `MultipleDefaultFormatsException` being thrown.

The `#[IsDefault]` attribute is detected on a per-class basis up the inheritance chain, so you can define a format as `#[IsDefault]` on a parent resource and override it with another `#[IsDefault]` format on the child resource without triggering a `MultipleDefaultFormatsException`. However, if no `#[IsDefault]` format is defined on the child resource the one on the parent will still be used.

### Naming Formats

You can also override the name of formats and even provide multiple names for a single format. Let's look at the following example:

```php
<?php

use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Formatting\Attributes\IsDefault;
use Sourcetoad\EnhancedResources\Resource;

class ExampleResource extends Resource
{
    #[Format, Format('a')]
    public function bar(): array
    {
        return [];
    }

    #[Format, Format('b'), Format('something-else')]
    public function foo(): array
    {
        return [];
    }

    #[Format('c')]
    public function foobar(): array
    {
        return [];
    }
}
```

In this example we have three formats, but six names:
 - The `bar` method can be used with the names `bar`, and `a`.
 - The `foo` method can be used with the names `foo`, `b`, and `something-else`.
 - The `foobar` method can be used with the name `c`.

The primary name of each format is the first instance of the `#[Format]` attribute, and the rest are aliases. This means that the primary names would be: `bar`, `foo`, and `c` in the example above. In most cases this distinction should not come into play.

### Collections

Both anonymous collections and defined resource collections utilize the formats of the underlying resource objects, and follow all the same rules.

## Modifications

Modifications allow you to tweak the output of resources on the fly. They are applied similarly to how `state` is applied for Eloquent factories. The most basic form of modification is a simple array merge modification done by providing an array to the `modify` method of a resource:

```php
ExampleResource::make($object)->modify(['some_key' => 'some_value']);
```

To accomplish more complex modifications you can also pass any callable that accepts `(array $data, Resource $resource)`. It is important when using these types of modifications to return the data as failing to do so will result in resource's data being replaced with `null`.

```php
ExampleResource::make($object)->modify(function (array $data) {
    $data['some_key'] = 'some_value';
    
    return $data;
})
```

You can also define methods on the resource class itself that can make modifications via calling the `modify` method.

```php
<?php

use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Formatting\Attributes\IsDefault;
use Sourcetoad\EnhancedResources\Resource;

class ExampleResource extends Resource
{
    #[Format]
    public function foo(): array
    {
        return [
            'value' => $this->resource['value'],
        ];
    }
    
    public function double(): static
    {
        return $this->modify(function (array $data) {
            $data['value'] *= 2;
            
            return $data;        
        });
    }
}

ExampleResource::make(['value' => 1])->double()->toArray(); // ['value' => 2]
```

### Except
The except enhancement is a modification class and trait combination that allows for the easy exclusion of certain fields from a resource.

```php
<?php

use Sourcetoad\EnhancedResources\Enhancements\Except;
use Sourcetoad\EnhancedResources\Enhancements\Traits\HasExceptEnhancement;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Formatting\Attributes\IsDefault;
use Sourcetoad\EnhancedResources\Resource;

class ExampleResource extends Resource
{
    use HasExceptEnhancement;

    #[Format]
    public function foo(): array
    {
        return [
            'first_name' => $this->resource->firstName,
            'id' => $this->resource->id,
            'last_name' => $this->resource->lastName,
        ];
    }
}

ExampleResource::make(new class {
    public string $firstName = 'John';
    public int $id = 1;
    public string $lastName = 'Doe';
})->except('id'); // ['first_name' => 'John', 'last_name' => 'Doe']

// Without the trait you can still use the Except enhancement.
ExampleResource::make(new class {
    public string $firstName = 'John';
    public int $id = 1;
    public string $lastName = 'Doe';
})->modify(new Except(['id'])); // ['first_name' => 'John', 'last_name' => 'Doe']
```

### Only
The only enhancement is a modification class and trait combination that allows for the easy exclusion of certain fields from a resource.

```php
<?php

use Sourcetoad\EnhancedResources\Enhancements\Only;
use Sourcetoad\EnhancedResources\Enhancements\Traits\HasOnlyEnhancement;
use Sourcetoad\EnhancedResources\Formatting\Attributes\Format;
use Sourcetoad\EnhancedResources\Formatting\Attributes\IsDefault;
use Sourcetoad\EnhancedResources\Resource;

class ExampleResource extends Resource
{
    use HasOnlyEnhancement;

    #[Format]
    public function foo(): array
    {
        return [
            'first_name' => $this->resource->firstName,
            'id' => $this->resource->id,
            'last_name' => $this->resource->lastName,
        ];
    }
}

ExampleResource::make(new class {
    public string $firstName = 'John';
    public int $id = 1;
    public string $lastName = 'Doe';
})->only('id'); // ['id' => 1]

// Without the trait you can still use the Only enhancement.
ExampleResource::make(new class {
    public string $firstName = 'John';
    public int $id = 1;
    public string $lastName = 'Doe';
})->modify(new Only(['id'])); // ['id' => 1]
```

## Additional Enhancements
EnhancedResources also includes a couple of other helpful enhancements.

### Status Codes
You can now tweak the status code of the resource response with a simple call to the `setResponseStatus()` method.

```php
use Symfony\Component\HttpFoundation\Response;

ExampleResource::make($object)->setResponseStatus(Response::HTTP_I_AM_A_TEAPOT);
```

## Testing
Testing endpoints that respond with enhanced resources is recommended to be done using Laravel's existing response assertions. One approach to creating resource asserter objects to help simplify the process that leverages functionality provided by enhanced resources can be found [here](https://gist.github.com/Jasonej/4d76d916a888b4ffe1894782ddd7af32).
