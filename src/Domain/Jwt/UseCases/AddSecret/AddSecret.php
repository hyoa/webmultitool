<?php

namespace WebMultiTool\Domain\Jwt\UseCases\AddSecret;


use WebMultiTool\Domain\Jwt\Entity\Secret;
use WebMultiTool\Domain\Jwt\Entity\SecretRepositoryInterface;

class AddSecret
{
    protected SecretRepositoryInterface $secretRepository;

    public function __construct(SecretRepositoryInterface $secretRepository)
    {
        $this->secretRepository = $secretRepository;
    }

    public function execute(AddSecretRequest $request): AddSecretResponse
    {
        $secret = new Secret($request->getSecret(), $request->getLabel());
        $this->secretRepository->addSecret($secret);

        return new AddSecretResponse($secret);
    }
}
