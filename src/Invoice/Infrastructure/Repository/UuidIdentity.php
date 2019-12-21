<?php

declare(strict_types=1);

namespace Bolton\Invoice\Infrastructure\Repository;

use Bolton\Invoice\Infrastructure\Common\UuidGenerator;

trait UuidIdentity
{
    final public function nextIdentity(): string
    {
        return UuidGenerator::generate();
    }
}
