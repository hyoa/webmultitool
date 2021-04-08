<?php

namespace WebMultiTool\Domain\Jwt\UseCases\AddSecret;

class AddSecretRequest
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

    public function getLabel(): string
    {
        return $this->label;
    }
}
