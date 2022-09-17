<?php

namespace Payload\Services;

use JetBrains\PhpStorm\Pure;
use Payload\Domain\Collections\ClaimCollection;

abstract class AbstractClaimService
{
    protected ClaimCollection $claimCollection;

    #[Pure]
    public function __construct()
    {
        $this->claimCollection = new ClaimCollection();
    }

    abstract public function addCustomClaims(): void;

    public function __invoke(): ClaimCollection
    {
        $this->addCustomClaims();
        return $this->claimCollection;
    }
}
