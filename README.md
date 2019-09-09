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

class Resource extends JsonResource
{
    use EnhancedResource;
}
```

### Creating an Enhanced Collection
```php
<?php

use Illuminate\Http\Resources\Json\ResourceCollection;
use Jasonej\EnhancedResources\EnhancedCollection;

class Collection extends ResourceCollection
{
    use EnhancedCollection;
}
```

### Appending Attributes
The default behavior of API resources is to return the model's attributes:
```php
<?php

$user = User::find(1);

Resource::make($user)->response();
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
Resource::make($user)->append(['name'])->response();
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
Resource::make($user)->exclude(['id', 'secret'])->response();
```
```json
{
  "first_name": "John",
  "last_name": "Doe"
}
```

### Only Attributes
The only method allows you to restrict a resource's output to only the provided set of attributes.
```php
Resource::make($user)->only(['first_name', 'last_name'])->response();
```
```json
{
  "first_name": "John",
  "last_name": "Doe"
}
