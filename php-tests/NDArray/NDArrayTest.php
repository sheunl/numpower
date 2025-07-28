<?php

declare(strict_types=1);

namespace Numpower\Tests\NDArray;

use GdImage;
use NDArray;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use Throwable;

class NDArrayTest extends TestCase
{
    #[DataProvider('validTypeDataProvider')]
    public function testValidTypeConstructor(string $type): void
    {
        //when
        $array = new NDArray([1, 2, '4', '9.25'], $type);

        //then
        $this->assertEquals(1, $array[0]);
        $this->assertEquals(2, $array[1]);
        $this->assertEquals('4', $array[2]);
        $this->assertEquals(9.25, $array[3]);
    }

    public static function validTypeDataProvider(): array
    {
        return [
            'float32' => ['float32'],
            'float64' => ['float64'],
        ];
    }

    public function testInvalidTypeConstructor(): void
    {
        //expect
        $this->expectException(Throwable::class);
        $this->expectExceptionMessage('Invalid data type. Supported types are: float32, float64');

        //when
        new NDArray([1,2], 'invalid');
    }

    #[DoesNotPerformAssertions]
    public function testNoTypeConstructor(): void
    {
        //when
        new NDArray([1,2]);
    }

    #[DoesNotPerformAssertions]
    #[DataProvider('scalarArgDataProvider')]
    public function testScalarArgConstructor(NDArray|GdImage|int|float $data): void
    {
        //when
        new NDArray($data);
    }

    public static function scalarArgDataProvider(): array
    {
        return [
            'int' => [1],
            'float' => [1.5],
            'NDArray' => [new NDArray([2,3])],
            'GDImage' => [imagecreatefromjpeg('https://upload.wikimedia.org/wikipedia/commons/thumb/c/cd/Stray_kitten_Rambo002.jpg/1200px-Stray_kitten_Rambo002.jpg')]
        ];
    }

    public function testFloat321DimConstructor(): void
    {
        //when
        $nd = new NDArray([1, 2.45, 3.1234567890987654], 'float32');

        //then
        $this->assertEquals(1, $nd[0]);
        $this->assertEquals(2.450000047683716, $nd[1]);
        $this->assertEquals(3.1234567165374756, $nd[2]);
    }

    public function testFloat322DimsConstructor(): void
    {
        //when
        $nd = new NDArray([[1, 2.45, 3.1234567890987654], [1, 2.45, 3.1234567890987654]], 'float32');

        //then
        $this->assertEquals(1, $nd[0][0]);
        $this->assertEquals(2.450000047683716, $nd[0][1]);
        $this->assertEquals(3.1234567165374756, $nd[0][2]);
        $this->assertEquals(1, $nd[1][0]);
        $this->assertEquals(2.450000047683716, $nd[1][1]);
        $this->assertEquals(3.1234567165374756, $nd[1][2]);
    }

    public function testFloat641DimConstructor(): void
    {
        //when
        $nd = new NDArray([1, 2.45, 9.2234567890987654], 'float64');

        //then
        $this->assertEquals(1, $nd[0]);
        $this->assertEquals(2.45, $nd[1]);
        $this->assertEquals(9.2234567890987655, $nd[2]);
    }

    public function testFloat642DimsConstructor(): void
    {
        //when
        $nd = new NDArray([[1, 2.45, 3.1234567890987654], [1, 2.45, 9.2234567890987654]], 'float64');

        //then
        $this->assertEquals(1, $nd[0][0]);
        $this->assertEquals(2.45, $nd[0][1]);
        $this->assertEquals(3.1234567890987655, $nd[0][2]);
        $this->assertEquals(1, $nd[1][0]);
        $this->assertEquals(2.45, $nd[1][1]);
        $this->assertEquals(9.2234567890987655, $nd[1][2]);
    }
}