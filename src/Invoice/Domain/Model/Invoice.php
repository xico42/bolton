<?php

declare(strict_types=1);

namespace Bolton\Invoice\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Invoice
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="string")
     */
    private string $id;

    /**
     * @ORM\Column(name="access_key", type="string", unique=true)
     */
    private string $accessKey;

    /**
     * @ORM\Column(name="value", type="float")
     */
    private float $value;

    public function __construct(string $id, string $accessKey, float $value)
    {
        $this->id = $id;
        $this->accessKey = $accessKey;
        $this->value = $value;
    }

    public function getAccessKey(): string
    {
        return $this->accessKey;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function updateValue(float $newValue): void
    {
        $this->value = $newValue;
    }
}
