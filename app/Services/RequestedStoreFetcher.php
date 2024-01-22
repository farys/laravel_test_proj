<?php

namespace App\Services;

use App\Models\Store;
use Symfony\Component\HttpFoundation\Request;

class RequestedStoreFetcher
{
  protected Store|null $store;

  function __construct(protected Request $request)
  {
    $this->store = Store::where('domain', $request->getHost())->firstOrFail();
  }

  public function get(): Store
  {
    return $this->store;
  }
}