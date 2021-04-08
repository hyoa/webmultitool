<?php

namespace WebMultiTool\Infra\Repository\FileRepository;


use WebMultiTool\Domain\Jwt\Entity\Secret;
use WebMultiTool\Domain\Jwt\Entity\SecretRepositoryInterface;

class SecretRepository implements SecretRepositoryInterface
{
    const SAVE_FILE = __DIR__.'/../../../../var/storage/secrets.json';
    const SAVE_DIR = __DIR__.'/../../../../var/storage';

    public function __construct()
    {
        if (!$this->repositoryExist()) {
            $this->createRepository();
        }
    }

    public function addSecret(Secret $secret): Secret
    {
        $secrets =  json_decode(file_get_contents(self::SAVE_FILE), true);
        $secrets[] = $secret->toArray();

        file_put_contents(self::SAVE_FILE, json_encode($secrets));

        return new Secret('toto', 'tata');
    }

    public function findAll(): array
    {
        return json_decode(file_get_contents(self::SAVE_FILE), true) ?? [];
    }

    protected function repositoryExist(): bool
    {
        return file_exists(self::SAVE_FILE);
    }

    protected function createRepository(): void
    {
        if (!is_dir(self::SAVE_DIR)){
            mkdir(self::SAVE_DIR);
        }
        touch(self::SAVE_FILE);
    }
}
