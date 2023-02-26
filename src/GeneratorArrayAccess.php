<?php

namespace Takuya\Php;

use \BadMethodCallException;

class GeneratorArrayAccess implements \Iterator, \ArrayAccess, \Countable {
  
  private $cache;
  private $cnt;
  private $root;
  
  public function __construct( \Generator $iter ) {
    $this->cache = [];
    $this->root = $iter;
    $this->cnt = 0;
  }
  
  public function count():int {
    foreach ($this as $e) {
      // Do nothing. Run generator to end.
      ;;;
    }
    
    return sizeof($this->cache);
  }
  
  public function offsetExists( $offset ):bool {
    if(!is_int($offset) || $offset<0){
      throw new BadMethodCallException('Only int(>=0) key can be used.');
    }
    while($offset > sizeof($this->cache) && $this->root->valid()) {
      $this->current();
      $this->next();
    }
    
    return empty($this->cache[$offset]);
  }
  
  public function next():void {
    $this->root->valid() && $this->root->next();
    $this->cnt++;
  }
  
  public function valid():bool {
    //after rewind and next.
    return $this->root->valid() || $this->cnt < sizeof($this->cache);
  }
  
  #[\ReturnTypeWillChange]
  public function offsetGet( $offset ) {
    if(!is_int($offset) || $offset<0){
      throw new BadMethodCallException('Only int(>=0) key can be used.');
    }
    while($offset >= sizeof($this->cache) && $this->root->valid()) {
      $this->current();
      $this->next();
    }
    
    return $this->cache[$offset] ?? null;
  }
  
  #[\ReturnTypeWillChange]
  public function current() {
    if( $this->root->valid() ) {
      $this->cache[] = $this->root->current();
    }
    
    return $this->cache[$this->cnt];
  }
  
  #[\ReturnTypeWillChange]
  public function key() {
    return $this->cnt;
  }
  
  public function rewind():void {
    $this->cnt = 0;
  }
  
  public function offsetSet( $offset, $value ):void { throw new BadMethodCallException('Read only'); }
  
  public function offsetUnset( $offset ):void { throw new BadMethodCallException('Read only'); }
}