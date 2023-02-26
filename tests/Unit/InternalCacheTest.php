<?php

namespace tests\Unit;

use tests\TestCase;
use Takuya\Php\GeneratorArrayAccess;

class InternalCacheTest extends TestCase {
  
  public function test_internal_cache(){
    $iter = new GeneratorArrayAccess(static::generator());
    // initialize
    $this->assertEquals([],$this->getPrivateValue($iter,'cache') );
    // access as array.
    $iter[0];
    $this->assertEquals(['a'],$this->getPrivateValue($iter,'cache') );
    $iter[1];
    $this->assertEquals(['a','b'],$this->getPrivateValue($iter,'cache') );
    $iter[2];
    $this->assertEquals(['a','b','c'],$this->getPrivateValue($iter,'cache') );
    $iter[3];
    $this->assertEquals(['a','b','c'],$this->getPrivateValue($iter,'cache') );
    // access index as array just after initialize.
    $iter = new GeneratorArrayAccess(static::generator());
    $iter[3];
    $this->assertEquals(['a','b','c'],$this->getPrivateValue($iter,'cache') );
  }
  protected function getPrivateValue($object,$propName){
    return  (new \ReflectionClass($object))->getProperty($propName)->getValue($object);
  }
}