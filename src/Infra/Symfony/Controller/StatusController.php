<?php

namespace WebMultiTool\Infra\Symfony\Controller;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;

class StatusController
{
    protected AdapterInterface $cache;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter();
    }

    /**
     * @Route("/status/{status}/{recurrence}/{id}")
     * @return Response
     */
    public function getStatusWithRecurrence(int $status, int $recurrence, string $id): Response
    {
        $key = sprintf('recurrence_%d', $id);

        $item = $this->cache->getItem($key);
        $value = 0;
        if ($item->isHit()) {
            $value = $item->get();
        } else {
            $item->set(0);
            $this->cache->save($item);
        }

        if ($value < $recurrence) {
            $value = $value + 1;
            $item->set($value);
            $this->cache->save($item);

            return new JsonResponse(['status' => 'looping'], $status);
        }

        $this->cache->delete($key);
        return new JsonResponse([
            'key' => 'key',
            'key_id' => 'toto',
            'iv' => 'toto',
            'pssh_widevine' => 'pssh_widevine',
            'pssh_play_ready' => 'pssh_play_ready'
        ]);
    }
}
