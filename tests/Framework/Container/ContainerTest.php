<?php

namespace Tests\Framework\Container;

use Framework\Container\Container;
use Framework\Container\ServiceNotFoundException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testPrimitives(): void
    {
        $container = new Container();
        $container->set($id = 'name', $value = 5);
        self::assertEquals($value, $container->get($id));

        $container->set($id = 'name', $value = 'string');
        self::assertEquals($value, $container->get($id));

        $container->set($id = 'name', $value = ['array']);
        self::assertEquals($value, $container->get($id));

        $container->set($id = 'name', $value = new \stdClass());
        self::assertEquals($value, $container->get($id));
    }

    public function testCallback(): void
    {
        $container = new Container();
        $container->set($id = 'name', function () {
            return new \stdClass();
        });

        self::assertNotNull($value = $container->get($id));
        self::assertInstanceOf(\stdClass::class, $value);
    }

    public function testSingleton(): void
    {
        $container = new Container();

        $container->set($id = 'name', function () {
            return new \stdClass();
        });

        self::assertNotNull($value1 = $container->get($id));
        self::assertNotNull($value2 = $container->get($id));
        self::assertSame($value1, $value2);
    }

    public function testNotFound(): void
    {
        $container = new Container();

        $this->expectException(ServiceNotFoundException::class);

        $container->get('email');
    }

    public function testContainerPass(): void
    {
        $container = new Container();
        $container->set('param', $value = 15);
        $container->set($id = 'name', function (Container $c) {
            $object = new \stdClass();
            $object->param = $c->get('param');

            return $object;
        });

        self::assertObjectHasAttribute('param', $object = $container->get($id));
        self::assertEquals($value, $object->param);
    }
}
