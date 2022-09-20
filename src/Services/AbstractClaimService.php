<?php

namespace Payload\Services;

use JetBrains\PhpStorm\Pure;
use Payload\Domain\Collections\ClaimCollection;

abstract class AbstractClaimService
{
    protected ClaimCollection $claimCollection;

    protected int | string $id;

    #[Pure]
    public function __construct()
    {
        $this->claimCollection = new ClaimCollection();
    }

    abstract public function addCustomClaims(): void;

    public function __invoke(int | string $id): ClaimCollection
    {
        $this->id = $id;
        $this->addCustomClaims();
        return $this->claimCollection;
    }
}
