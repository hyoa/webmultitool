<?php

namespace WebMultiTool\Domain\Jwt\Entity;

class Secret
{
    protected string $secret;
    protected string $label;

    public function __construct(string $secret, string $label)
    {
        $this->secret = $secret;
        $this->label = $label;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function toArray(): array
    {
        return [
            'secret' => $this->secret,
            'label' => $this->label
        ];
    }
}
