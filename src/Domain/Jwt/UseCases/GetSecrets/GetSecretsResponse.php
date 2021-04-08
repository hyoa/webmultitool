<?php

namespace WebMultiTool\Domain\Jwt\UseCases\GetSecrets;

class GetSecretsResponse
{
    protected array $secrets;

    public function __construct(array $secrets)
    {
        $this->secrets = $secrets;
    }


    public function getSecrets(): array
    {
        return $this->secrets;
    }
}
