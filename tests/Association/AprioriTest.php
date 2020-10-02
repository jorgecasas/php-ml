<?php

declare(strict_types=1);

namespace Phpml\Tests\Association;

use Phpml\Association\Apriori;
use Phpml\ModelManager;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class AprioriTest extends TestCase
{
    /**
     * @var array
     */
    private $sampleGreek = [
        ['alpha', 'beta', 'epsilon'],
        ['alpha', 'beta', 'theta'],
        ['alpha', 'beta', 'epsilon'],
        ['alpha', 'beta', 'theta'],
    ];

    /**
     * @var array
     */
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

    /**
     * @var array
     */
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

        self::assertCount(2, $predicted);
        self::assertEquals([['beta']], $predicted[0]);
        self::assertEquals([['alpha']], $predicted[1]);
    }

    public function testPowerSet(): void
    {
        $apriori = new Apriori();

        self::assertCount(8, self::invoke($apriori, 'powerSet', [['a', 'b', 'c']]));
    }

    public function testApriori(): void
    {
        $apriori = new Apriori(3 / 7);
        $apriori->train($this->sampleBasket, []);

        $L = $apriori->apriori();

        self::assertCount(4, $L[2]);
        self::assertTrue(self::invoke($apriori, 'contains', [$L[2], [1, 2]]));
        self::assertFalse(self::invoke($apriori, 'contains', [$L[2], [1, 3]]));
        self::assertFalse(self::invoke($apriori, 'contains', [$L[2], [1, 4]]));
        self::assertTrue(self::invoke($apriori, 'contains', [$L[2], [2, 3]]));
        self::assertTrue(self::invoke($apriori, 'contains', [$L[2], [2, 4]]));
        self::assertTrue(self::invoke($apriori, 'contains', [$L[2], [3, 4]]));
    }

    public function testAprioriEmpty(): void
    {
        $sample = [];

        $apriori = new Apriori(0, 0);
        $apriori->train($sample, []);

        $L = $apriori->apriori();

        self::assertEmpty($L);
    }

    public function testAprioriSingleItem(): void
    {
        $sample = [['a']];

        $apriori = new Apriori(0, 0);
        $apriori->train($sample, []);

        $L = $apriori->apriori();

        self::assertEquals([1], array_keys($L));
        self::assertEquals([['a']], $L[1]);
    }

    public function testAprioriL3(): void
    {
        $sample = [['a', 'b', 'c']];

        $apriori = new Apriori(0, 0);
        $apriori->train($sample, []);

        $L = $apriori->apriori();

        self::assertEquals([['a', 'b', 'c']], $L[3]);
    }

    public function testGetRules(): void
    {
        $apriori = new Apriori(0.4, 0.8);
        $apriori->train($this->sampleChars, []);

        self::assertCount(19, $apriori->getRules());
    }

    public function testGetRulesSupportAndConfidence(): void
    {
        $sample = [['a', 'b'], ['a', 'c']];

        $apriori = new Apriori(0, 0);
        $apriori->train($sample, []);

        $rules = $apriori->getRules();

        self::assertCount(4, $rules);
        self::assertContains([
            Apriori::ARRAY_KEY_ANTECEDENT => ['a'],
            Apriori::ARRAY_KEY_CONSEQUENT => ['b'],
            Apriori::ARRAY_KEY_SUPPORT => 0.5,
            Apriori::ARRAY_KEY_CONFIDENCE => 0.5,
        ], $rules);
        self::assertContains([
            Apriori::ARRAY_KEY_ANTECEDENT => ['b'],
            Apriori::ARRAY_KEY_CONSEQUENT => ['a'],
            Apriori::ARRAY_KEY_SUPPORT => 0.5,
            Apriori::ARRAY_KEY_CONFIDENCE => 1.0,
        ], $rules);
    }

    public function testAntecedents(): void
    {
        $apriori = new Apriori();

        self::assertCount(6, self::invoke($apriori, 'antecedents', [['a', 'b', 'c']]));
    }

    public function testItems(): void
    {
        $apriori = new Apriori();
        $apriori->train($this->sampleGreek, []);
        self::assertCount(4, self::invoke($apriori, 'items', []));
    }

    public function testFrequent(): void
    {
        $apriori = new Apriori(0.51);
        $apriori->train($this->sampleGreek, []);

        self::assertCount(0, self::invoke($apriori, 'frequent', [[['epsilon'], ['theta']]]));
        self::assertCount(2, self::invoke($apriori, 'frequent', [[['alpha'], ['beta']]]));
    }

    public function testCandidates(): void
    {
        $apriori = new Apriori();
        $apriori->train($this->sampleGreek, []);

        $candidates = self::invoke($apriori, 'candidates', [[['alpha'], ['beta'], ['theta']]]);

        self::assertCount(3, $candidates);
        self::assertEquals(['alpha', 'beta'], $candidates[0]);
        self::assertEquals(['alpha', 'theta'], $candidates[1]);
        self::assertEquals(['beta', 'theta'], $candidates[2]);
    }

    public function testConfidence(): void
    {
        $apriori = new Apriori();
        $apriori->train($this->sampleGreek, []);

        self::assertEquals(0.5, self::invoke($apriori, 'confidence', [['alpha', 'beta', 'theta'], ['alpha', 'beta']]));
        self::assertEquals(1, self::invoke($apriori, 'confidence', [['alpha', 'beta'], ['alpha']]));
    }

    public function testSupport(): void
    {
        $apriori = new Apriori();
        $apriori->train($this->sampleGreek, []);

        self::assertEquals(1.0, self::invoke($apriori, 'support', [['alpha', 'beta']]));
        self::assertEquals(0.5, self::invoke($apriori, 'support', [['epsilon']]));
    }

    public function testFrequency(): void
    {
        $apriori = new Apriori();
        $apriori->train($this->sampleGreek, []);

        self::assertEquals(4, self::invoke($apriori, 'frequency', [['alpha', 'beta']]));
        self::assertEquals(2, self::invoke($apriori, 'frequency', [['epsilon']]));
    }

    public function testContains(): void
    {
        $apriori = new Apriori();

        self::assertTrue(self::invoke($apriori, 'contains', [[['a'], ['b']], ['a']]));
        self::assertTrue(self::invoke($apriori, 'contains', [[[1, 2]], [1, 2]]));
        self::assertFalse(self::invoke($apriori, 'contains', [[['a'], ['b']], ['c']]));
    }

    public function testSubset(): void
    {
        $apriori = new Apriori();

        self::assertTrue(self::invoke($apriori, 'subset', [['a', 'b'], ['a']]));
        self::assertTrue(self::invoke($apriori, 'subset', [['a'], ['a']]));
        self::assertFalse(self::invoke($apriori, 'subset', [['a'], ['a', 'b']]));
    }

    public function testEquals(): void
    {
        $apriori = new Apriori();

        self::assertTrue(self::invoke($apriori, 'equals', [['a'], ['a']]));
        self::assertFalse(self::invoke($apriori, 'equals', [['a'], []]));
        self::assertFalse(self::invoke($apriori, 'equals', [['a'], ['b', 'a']]));
    }

    public function testSaveAndRestore(): void
    {
        $classifier = new Apriori(0.5, 0.5);
        $classifier->train($this->sampleGreek, []);

        $testSamples = [['alpha', 'epsilon'], ['beta', 'theta']];
        $predicted = $classifier->predict($testSamples);

        $filename = 'apriori-test-'.random_int(100, 999).'-'.uniqid('', false);
        $filepath = (string) tempnam(sys_get_temp_dir(), $filename);
        $modelManager = new ModelManager();
        $modelManager->saveToFile($classifier, $filepath);

        $restoredClassifier = $modelManager->restoreFromFile($filepath);
        self::assertEquals($classifier, $restoredClassifier);
        self::assertEquals($predicted, $restoredClassifier->predict($testSamples));
    }

    /**
     * Invokes objects method. Private/protected will be set accessible.
     *
     * @param string $method Method name to be called
     * @param array  $params Array of params to be passed
     *
     * @return mixed
     */
    private static function invoke(Apriori $object, string $method, array $params = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $params);
    }
}
