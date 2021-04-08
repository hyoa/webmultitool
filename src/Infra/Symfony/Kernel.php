<?php

namespace WebMultiTool\Infra\Symfony;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    protected $configurationDirectory;

    public function __construct(string $environment, bool $debug)
    {
        parent::__construct($environment, $debug);
        $this->configurationDirectory = __DIR__.'/../../../config';
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import($this->configurationDirectory.'/{packages}/*.yaml');
        $container->import($this->configurationDirectory.'/{packages}/'.$this->environment.'/*.yaml');

        if (is_file($this->configurationDirectory.'/services.yaml')) {
            $container->import($this->configurationDirectory.'/services.yaml');
            $container->import($this->configurationDirectory.'/{services}_'.$this->environment.'.yaml');
        } elseif (is_file($path = $this->configurationDirectory.'/services.php')) {
            (require $path)($container->withPath($path), $this);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import($this->configurationDirectory.'/{routes}/'.$this->environment.'/*.yaml');
        $routes->import($this->configurationDirectory.'/{routes}/*.yaml');

        if (is_file($this->configurationDirectory.'/routes.yaml')) {
            $routes->import($this->configurationDirectory.'/routes.yaml');
        } elseif (is_file($path = $this->configurationDirectory.'/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }
    }
}
