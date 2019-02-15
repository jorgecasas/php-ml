<?php

declare(strict_types=1);

namespace Phpml\Tests\Performance\Tokenization;

use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;
use Phpml\Tokenization\NGramTokenizer;

final class NGramTokenizerBench
{
    /**
     * @Revs(1000)
     * @Iterations(5)
     */
    public function benchSimpleTokenizer(): void
    {
        $tokenizer = new NGramTokenizer(2, 3);
        $tokenizer->tokenize(
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent placerat blandit cursus. Suspendisse sed 
            turpis sit amet enim viverra sodales a euismod est. Ut vitae tincidunt est. Proin venenatis placerat nunc 
            sed ornare. Etiam feugiat, nisl nec sollicitudin sodales, nulla massa sollicitudin ipsum, vitae cursus ante 
            velit vitae arcu. Vestibulum feugiat ultricies hendrerit. Morbi sed varius metus. Nam feugiat maximus 
            turpis, a sollicitudin ligula porttitor eu.Fusce hendrerit tellus et dignissim sagittis. Nulla consectetur
            condimentum tortor, non bibendum erat lacinia eget. Integer vitae maximus tortor. Vestibulum ante ipsum 
            primis in faucibus orci luctus et ultrices posuere cubilia Curae; Pellentesque suscipit sem ipsum, in 
            tincidunt risus pellentesque vel. Nullam hendrerit consequat leo, in suscipit lectus euismod non. Cras arcu
            lacus, lacinia semper mauris vel, pharetra dignissim velit. Nam lacinia turpis a nibh bibendum, et
            placerat tellus accumsan. Sed tincidunt cursus nisi in laoreet. Suspendisse amet.'
        );
    }
}
