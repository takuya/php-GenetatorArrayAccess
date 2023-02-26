<?php

namespace tests\Unit;

use tests\TestCase;
use Takuya\Php\GeneratorArrayAccess;

class AccessTest extends TestCase {
  
  protected function setUp():void {
    parent::setUp();
  }
  protected static  function generator(){
    return $generator = (function (){foreach (['a', 'b', 'c'] as $item) {yield $item;}})();
  }
  
  public function test_index(){
    $iter = new GeneratorArrayAccess(static::generator());
    $this->assertEquals('a',$iter[0]);
    $iter = new GeneratorArrayAccess(static::generator());
    $this->assertEquals('b',$iter[1]);
    $iter = new GeneratorArrayAccess(static::generator());
    $this->assertEquals('c',$iter[2]);
    $iter = new GeneratorArrayAccess(static::generator());
    $this->assertEquals(null,$iter[3]);
  }
  public function test_index_exists_called_offsetExists(){
    $iter = new GeneratorArrayAccess(static::generator());
    $this->assertFalse(empty($iter[0]));
    $this->assertFalse(empty($iter[1]));
    $this->assertFalse(empty($iter[2]));
    $this->assertTrue(empty($iter[3]));
    $this->assertTrue(empty($iter[4]));
  }
  public function test_foreach(){
    $iter = new GeneratorArrayAccess(static::generator());
    $list = [];
    foreach ($iter as $item) {
      $list[]= $item;
    }
    $this->assertEquals(["a","b","c"],$list);
  }
  public function test_count(){
    $iter = new GeneratorArrayAccess(static::generator());
    $this->assertEquals(3,sizeof($iter));
  }
  public function test_rewind(){
    $iter = new GeneratorArrayAccess(static::generator());
    foreach ($iter as $item) {
      $this->assertNotEmpty($item);
    }
    foreach ($iter as $item) {
      $this->assertNotEmpty($item);
    }
  }
  public function test_foreach_with_index(){
    $iter = new GeneratorArrayAccess(static::generator());
    $keys = [];
    foreach ($iter as $idx=> $item) {
      $keys[]=$idx;
    }
    $this->assertEquals([0,1,2],$keys);
    $keys = [];
    // with rewind
    foreach ($iter as $idx=> $item) {
      $keys[]=$idx;
    }
    $this->assertEquals([0,1,2],$keys);
  }
  
  
}