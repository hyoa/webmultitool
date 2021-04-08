<?php

namespace WebMultiTool\Domain\Jwt\UseCases\GetSecrets;

use WebMultiTool\Domain\Jwt\Entity\SecretRepositoryInterface;

class GetSecrets
{
    protected SecretRepositoryInterface $secretRepository;

    public function __construct(SecretRepositoryInterface $secretRepository)
    {
        $this->secretRepository = $secretRepository;
    }

    public function execute(): GetSecretsResponse
    {
        return new GetSecretsResponse($this->secretRepository->findAll());
    }
}
