<?php

namespace Svenrtbg\LaminasTestcase;

use Laminas\Cache\ConfigProvider;
use Laminas\Cache\Pattern\ObjectCache;
use Laminas\Cache\Pattern\PatternOptions;
use Laminas\Cache\Service\StorageAdapterFactoryInterface;
use Laminas\Cache\Storage\Adapter\Filesystem;
use Laminas\Cache\Storage\Plugin\Serializer;
use Laminas\Serializer\AdapterPluginManager;
use Laminas\ServiceManager\ServiceManager;

use PHPUnit\Framework\TestCase;

class ObjectWithClosureTest extends TestCase
{
    private static StorageAdapterFactoryInterface $storageAdapterFactory;

    private ObjectCache $objectCache;

    public static function createLaminasAdapterFactory(): StorageAdapterFactoryInterface
    {
        if (isset(self::$storageAdapterFactory)) {
            return self::$storageAdapterFactory;
        }

        $config = array_merge_recursive(
            (new ConfigProvider())(),
            (new Filesystem\ConfigProvider())(),
        );
        $containerConfig = $config['dependencies'] ?? [];
        $container = new ServiceManager($containerConfig);
        return self::$storageAdapterFactory = $container->get(StorageAdapterFactoryInterface::class);
    }

    public function setUp(): void
    {
        $storage = self::createLaminasAdapterFactory()->createFromArrayConfiguration(
            [
                'adapter' => 'filesystem',
                'options' => [
                    'cacheDir' => __DIR__ . "/../cache",
                    'dirPermission' => '777',
                    'filePermission' => '666',
                ],
            ]
        );
        $storage->addPlugin(new Serializer(new AdapterPluginManager(new ServiceManager())));
        $storage->getOptions()
            ->setTtl(3600)
            ->setNamespace(md5(__FILE__));

        $originalObject = new ObjectWithClosure();

        $this->objectCache = new ObjectCache(
            $storage,
            new PatternOptions([
                'object' => $originalObject,
                'cacheOutput' => false,
            ]),
        );
    }

    public function testObjectWithClosureInsideIsCacheable(): void
    {
        $this->assertSame(9999, $this->objectCache->cachedMethod());
        $this->assertSame(9999, $this->objectCache->cachedMethod());
    }

}
