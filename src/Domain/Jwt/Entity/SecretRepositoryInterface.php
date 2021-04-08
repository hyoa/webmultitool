<?php

namespace WebMultiTool\Domain\Jwt\Entity;


interface SecretRepositoryInterface
{
    public function addSecret(Secret $secret): Secret;

    public function findAll(): array;
}
