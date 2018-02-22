<?php

declare(strict_types=1);

namespace Phpml\Tests\Association;

use Phpml\Association\Apriori;
use Phpml\ModelManager;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class AprioriTest extends TestCase
{
    private $sampleGreek = [
        ['alpha', 'beta', 'epsilon'],
        ['alpha', 'beta', 'theta'],
        ['alpha', 'beta', 'epsilon'],
        ['alpha', 'beta', 'theta'],
    ];

    private $sampleChars = [
        ['E', 'D', 'N', 'E+N', 'EN'],
        ['E', 'R', 'N', 'E+R', 'E+N', 'ER', 'EN'],
        ['D', 'R'],
        ['E', 'D', 'N', 'E+N'],
        ['E', 'R', 'N', 'E+R', 'E+N', 'ER'],
        ['E', 'D', 'R', 'E+R', 'ER'],
        ['E', 'D', 'N', 'E+N', 'EN'],
        ['E', 'R', 'E+R'],
        ['E'],
        ['N'],
    ];

    private $sampleBasket = [
        [1, 2, 3, 4],
        [1, 2, 4],
        [1, 2],
        [2, 3, 4],
        [2, 3],
        [3, 4],
        [2, 4],
    ];

    public function testGreek(): void
    {
        $apriori = new Apriori(0.5, 0.5);
        $apriori->train($this->sampleGreek, []);

        $predicted = $apriori->predict([['alpha', 'epsilon'], ['beta', 'theta']]);

        $this->assertCount(2, $predicted);
        $this->assertEquals([['beta']], $predicted[0]);
        $this->assertEquals([['alpha']], $predicted[1]);
    }

    public function testPowerSet(): void
    {
        $apriori = new Apriori();

        $this->assertCount(8, self::invoke($apriori, 'powerSet', [['a', 'b', 'c']]));
    }

    public function testApriori(): void
    {
        $apriori = new Apriori(3 / 7);
        $apriori->train($this->sampleBasket, []);

        $L = $apriori->apriori();

        $this->assertCount(4, $L[2]);
        $this->assertTrue(self::invoke($apriori, 'contains', [$L[2], [1, 2]]));
        $this->assertFalse(self::invoke($apriori, 'contains', [$L[2], [1, 3]]));
        $this->assertFalse(self::invoke($apriori, 'contains', [$L[2], [1, 4]]));
        $this->assertTrue(self::invoke($apriori, 'contains', [$L[2], [2, 3]]));
        $this->assertTrue(self::invoke($apriori, 'contains', [$L[2], [2, 4]]));
        $this->assertTrue(self::invoke($apriori, 'contains', [$L[2], [3, 4]]));
    }

    public function testAprioriEmpty(): void
    {
        $sample = [];

        $apriori = new Apriori(0, 0);
        $apriori->train($sample, []);

        $L = $apriori->apriori();

        $this->assertEmpty($L);
    }

    public function testAprioriSingleItem(): void
    {
        $sample = [['a']];

        $apriori = new Apriori(0, 0);
        $apriori->train($sample, []);

        $L = $apriori->apriori();

        $this->assertEquals([1], array_keys($L));
        $this->assertEquals([['a']], $L[1]);
    }

    public function testAprioriL3(): void
    {
        $sample = [['a', 'b', 'c']];

        $apriori = new Apriori(0, 0);
        $apriori->train($sample, []);

        $L = $apriori->apriori();

        $this->assertEquals([['a', 'b', 'c']], $L[3]);
    }

    public function testGetRules(): void
    {
        $apriori = new Apriori(0.4, 0.8);
        $apriori->train($this->sampleChars, []);

        $this->assertCount(19, $apriori->getRules());
    }

    public function testGetRulesSupportAndConfidence(): void
    {
        $sample = [['a', 'b'], ['a', 'c']];

        $apriori = new Apriori(0, 0);
        $apriori->train($sample, []);

        $rules = $apriori->getRules();

        $this->assertCount(4, $rules);
        $this->assertContains([
            Apriori::ARRAY_KEY_ANTECEDENT => ['a'],
            Apriori::ARRAY_KEY_CONSEQUENT => ['b'],
            Apriori::ARRAY_KEY_SUPPORT => 0.5,
            Apriori::ARRAY_KEY_CONFIDENCE => 0.5,
        ], $rules);
        $this->assertContains([
            Apriori::ARRAY_KEY_ANTECEDENT => ['b'],
            Apriori::ARRAY_KEY_CONSEQUENT => ['a'],
            Apriori::ARRAY_KEY_SUPPORT => 0.5,
            Apriori::ARRAY_KEY_CONFIDENCE => 1.0,
        ], $rules);
    }

    public function testAntecedents(): void
    {
        $apriori = new Apriori();

        $this->assertCount(6, self::invoke($apriori, 'antecedents', [['a', 'b', 'c']]));
    }

    public function testItems(): void
    {
        $apriori = new Apriori();
        $apriori->train($this->sampleGreek, []);
        $this->assertCount(4, self::invoke($apriori, 'items', []));
    }

    public function testFrequent(): void
    {
        $apriori = new Apriori(0.51);
        $apriori->train($this->sampleGreek, []);

        $this->assertCount(0, self::invoke($apriori, 'frequent', [[['epsilon'], ['theta']]]));
        $this->assertCount(2, self::invoke($apriori, 'frequent', [[['alpha'], ['beta']]]));
    }

    public function testCandidates(): void
    {
        $apriori = new Apriori();
        $apriori->train($this->sampleGreek, []);

        $candidates = self::invoke($apriori, 'candidates', [[['alpha'], ['beta'], ['theta']]]);

        $this->assertCount(3, $candidates);
        $this->assertEquals(['alpha', 'beta'], $candidates[0]);
        $this->assertEquals(['alpha', 'theta'], $candidates[1]);
        $this->assertEquals(['beta', 'theta'], $candidates[2]);
    }

    public function testConfidence(): void
    {
        $apriori = new Apriori();
        $apriori->train($this->sampleGreek, []);

        $this->assertEquals(0.5, self::invoke($apriori, 'confidence', [['alpha', 'beta', 'theta'], ['alpha', 'beta']]));
        $this->assertEquals(1, self::invoke($apriori, 'confidence', [['alpha', 'beta'], ['alpha']]));
    }

    public function testSupport(): void
    {
        $apriori = new Apriori();
        $apriori->train($this->sampleGreek, []);

        $this->assertEquals(1.0, self::invoke($apriori, 'support', [['alpha', 'beta']]));
        $this->assertEquals(0.5, self::invoke($apriori, 'support', [['epsilon']]));
    }

    public function testFrequency(): void
    {
        $apriori = new Apriori();
        $apriori->train($this->sampleGreek, []);

        $this->assertEquals(4, self::invoke($apriori, 'frequency', [['alpha', 'beta']]));
        $this->assertEquals(2, self::invoke($apriori, 'frequency', [['epsilon']]));
    }

    public function testContains(): void
    {
        $apriori = new Apriori();

        $this->assertTrue(self::invoke($apriori, 'contains', [[['a'], ['b']], ['a']]));
        $this->assertTrue(self::invoke($apriori, 'contains', [[[1, 2]], [1, 2]]));
        $this->assertFalse(self::invoke($apriori, 'contains', [[['a'], ['b']], ['c']]));
    }

    public function testSubset(): void
    {
        $apriori = new Apriori();

        $this->assertTrue(self::invoke($apriori, 'subset', [['a', 'b'], ['a']]));
        $this->assertTrue(self::invoke($apriori, 'subset', [['a'], ['a']]));
        $this->assertFalse(self::invoke($apriori, 'subset', [['a'], ['a', 'b']]));
    }

    public function testEquals(): void
    {
        $apriori = new Apriori();

        $this->assertTrue(self::invoke($apriori, 'equals', [['a'], ['a']]));
        $this->assertFalse(self::invoke($apriori, 'equals', [['a'], []]));
        $this->assertFalse(self::invoke($apriori, 'equals', [['a'], ['b', 'a']]));
    }

    public function testSaveAndRestore(): void
    {
        $classifier = new Apriori(0.5, 0.5);
        $classifier->train($this->sampleGreek, []);

        $testSamples = [['alpha', 'epsilon'], ['beta', 'theta']];
        $predicted = $classifier->predict($testSamples);

        $filename = 'apriori-test-'.random_int(100, 999).'-'.uniqid();
        $filepath = tempnam(sys_get_temp_dir(), $filename);
        $modelManager = new ModelManager();
        $modelManager->saveToFile($classifier, $filepath);

        $restoredClassifier = $modelManager->restoreFromFile($filepath);
        $this->assertEquals($classifier, $restoredClassifier);
        $this->assertEquals($predicted, $restoredClassifier->predict($testSamples));
    }

    /**
     * Invokes objects method. Private/protected will be set accessible.
     *
     * @param string $method Method name to be called
     * @param array  $params Array of params to be passed
     *
     * @return mixed
     */
    private static function invoke(&$object, string $method, array $params = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $params);
    }
}
