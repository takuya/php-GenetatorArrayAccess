<?php

namespace tests\Unit;

use tests\TestCase;
use Takuya\Php\GeneratorArrayAccess;
use PHPUnit\Framework\Constraint\IsTrue;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertNotFalse;

class AccessAfterAccessTest extends TestCase {
  
  public function test_access_index_after_foreach(){
    $iter = new GeneratorArrayAccess(static::generator());
    foreach ($iter as  $idx => $item) {
      $this->assertIsInt($idx);
      $this->assertNotNull($item);
    }
    $this->assertEquals('a',$iter[0]);
    $this->assertEquals('b',$iter[1]);
    $this->assertEquals('c',$iter[2]);
  }
  public function test_access_foreach_after_index(){
    $iter = new GeneratorArrayAccess(static::generator());
    $this->assertEquals('a',$iter[0]);
    $this->assertEquals('b',$iter[1]);
    $this->assertEquals('c',$iter[2]);
    foreach ($iter as $idx=>$item) {
      $this->assertIsInt($idx);
      $this->assertNotNull($item);
    }
  }
}