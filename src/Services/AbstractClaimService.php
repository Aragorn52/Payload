<?php

namespace Payload\Services;

use Payload\Domain\Collections\ClaimCollection;

abstract class AbstractClaimService
{
    protected ClaimCollection $claimCollection;

    public function __construct()
    {
        $this->claimCollection = app()->make(ClaimCollection::class);
    }

    abstract public function addCustomClaims(): void;
}
