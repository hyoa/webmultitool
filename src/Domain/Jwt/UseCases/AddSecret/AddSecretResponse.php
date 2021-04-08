<?php

namespace WebMultiTool\Domain\Jwt\UseCases\AddSecret;


use WebMultiTool\Domain\Jwt\Entity\Secret;

class AddSecretResponse
{
    protected Secret $secret;

    public function __construct(Secret $secret)
    {
        $this->secret = $secret;
    }

    public function getSecret(): Secret
    {
        return $this->secret;
    }
}
