![](https://github.com/takuya/php-generators-into-array-access/workflows/main/badge.svg)


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

### Generator cannot be a array. 
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

This behaviour is very confusing.

```php
$node = new MyClass();
$elements = new CachingIterator($node->elements())
// CachingIterator cannot access Directory, before cached.
$first = $elements[1]; //=> BadMethodCallException
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

If generator is API call, It can spend a lot of time, caching time inevitable.

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

## When use this.

Reduce WebAPI Call. without re-arrange code.

Current exists code.
```php
function my_list_items(){
  foreach(  $api->call('list_item') as $id){
    $list[]=$api->call('get_item', $id);
  }
  return $list;
}
$items = $my_list_items();
$item = $items[0];
```
Use Generator.
```php
function my_list_items(){
  foreach(  $api->call('list_item') as $id){
    $item  = $api->call('get_item', $id);
    yield $item;
  }
}
$items = $my_list_items();
$item = $items[0];//<= No Code changed. Becomes ERORR!. 
```
Use GeneratorArrayAccess
```php
function my_list_items(){
  return new GeneratorArrayAccess((function(){
  foreach(  $api->call('list_item') as $id){
    $item  = $api->call('get_item', $id);
    yield $item;
  }  
  })());
}
$items = $my_list_items();
$item = $items[0];//<= No Code changed. **No Error**.
```

This class supports to make use of Generator(yield), Less code changed.

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
from composer 
```shell
composer require takuya/php-genetator-array-access
```









