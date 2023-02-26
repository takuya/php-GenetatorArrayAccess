# php / GeneratorArrayAccess
Make generator(yield) into ArrayAccess.

## Example

```php
<?php
// generators ( yield ).
$generator = (function(){foreach (['x'.'y','z'] as $e){ yield $e;}})();
// Using with new.
use Takuya\Php\GeneratorArrayAccess;
$iter = new GeneratorArrayAccess($generator);

// Access as Array. 
$iter[0]; #=> 'x'
$iter[1]; #=> 'y'
$iter[2]; #=> 'z'
```

## Why use.

Generator can foreach itself. but, cannot access as Array. This characteristic  against developers's intention. Like this.

### Generator cannot as a array. 
```php
class MyClass{
  public function elements(){
    foreach ($this->paths[] as $path){
      yield get_something($path);
    }
  }
}

$node = new MyClass();
// Generator can Access foreach
foreach ($node->elements() as $item) {
  // something.    
}
// but Cannot access as Array
$first = $node->elements()[0]; //=> Error
```

### CachingIterator is not enough

Using `\CachingIterator` is a common manner, but make a problem
#### \CachingIterator cannot be a array.

This null behaviour is very confusing.

```php
$node = new MyClass();
$elements = new CachingIterator($node->elements())
// CachingIterator cannot access Directory, before cached.
$first = $elements[1]; //=> null or BadMethodCallException
// after caching, CachingIterator can access as Array.
foreach ($elements as $e){;;}
$first = $elements[1]; //=> not null.
```

With FullCache option, cached at initializing.

```php
$node = new MyClass();
$elements = new \CachingIterator(
  $node->elements(),
  \CachingIterator::FULL_CACHE
);// <= All Cached in NEW.
```

If generator is API call , It can spend a lot of time. so login caching time inevitable.

`iterator_to_array()` function has same problem.

### Dynamically get, make Unnecessary api call avoidable.

To solve that problem ,GeneratorArrayAccess cache dynamically.
```php

$node = new MyClass();
$iter = new GeneratorArrayAccess($node->elements());
$iter[1]; //=> make cache $iter[0],$iter[1];
$iter[9]; //=> make cache $iter[0]...$iter[9]
```

Cache is able to reuse.
```php
$node = new MyClass();
$iter = new GeneratorArrayAccess($node->elements());
// first access 
foreach($iter as $e){;;}
// cache access with rewind.
foreach($iter as $e){;;}
```

### Limitations.

Infinite generator will be no response.
```php
$generator = (function(){
  while(true){ yield 'a';}
})();
$iter = new GeneratorArrayAccess($generator);
sizeof($iter);//=> infinite loop
```




## Installation
from github

```shell
repository='php-generators-into-array-access'
composer config repositories.$repository \
vcs https://github.com/takuya/$repository  
composer require takuya/$repository:master
composer install
```










