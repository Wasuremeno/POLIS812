<?php

namespace Cache;

interface CacheInterface{

 public function set(string $key, $value, ?int $ttl = null): bool;
 
}