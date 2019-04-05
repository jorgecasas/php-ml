<?php

declare(strict_types=1);

namespace Phpml\Tests\Tokenization;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Tokenization\NGramWordTokenizer;

/**
 * Inspiration: https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-ngram-tokenizer.html
 */
class NGramWordTokenizerTest extends TokenizerTest
{
    /**
     * @dataProvider textDataProvider
     */
    public function testNGramTokenization(int $minGram, int $maxGram, string $text, array $tokens): void
    {
        $tokenizer = new NGramWordTokenizer($minGram, $maxGram);

        self::assertEquals($tokens, $tokenizer->tokenize($text));
    }

    public function testMinGramGreaterThanMaxGramNotAllowed(): void
    {
        self::expectException(InvalidArgumentException::class);

        new NGramWordTokenizer(5, 2);
    }

    public function testMinGramValueTooSmall(): void
    {
        self::expectException(InvalidArgumentException::class);

        new NGramWordTokenizer(0, 2);
    }

    public function testMaxGramValueTooSmall(): void
    {
        self::expectException(InvalidArgumentException::class);

        new NGramWordTokenizer(1, 0);
    }

    public function textDataProvider(): array
    {
        return [
            [
                1, 1,
                'one two three four',
                ['one', 'two', 'three', 'four'],
            ],
            [
                1, 2,
                'one two three four',
                ['one', 'two', 'three', 'four', 'one two', 'two three', 'three four'],
            ],
            [
                1, 3,
                'one two three four',
                ['one', 'two', 'three', 'four', 'one two', 'two three', 'three four', 'one two three', 'two three four'],
            ],
            [
                2, 3,
                'one two three four',
                ['one two', 'two three', 'three four', 'one two three', 'two three four'],
            ],
            [
                1, 2,
                '快狐跑过 边缘跑',
                ['快狐跑过', '边缘跑', '快狐跑过 边缘跑'],
            ],
            [
                2, 4,
                $this->getSimpleText(),
                [
                    'Lorem ipsum', 'ipsum dolor', 'dolor sit', 'sit amet', 'amet consectetur', 'consectetur adipiscing',
                    'adipiscing elit', 'elit Cras', 'Cras consectetur', 'consectetur dui', 'dui et', 'et lobortis',
                    'lobortis auctor', 'auctor Nulla', 'Nulla vitae', 'vitae congue', 'congue lorem', 'Lorem ipsum dolor',
                    'ipsum dolor sit', 'dolor sit amet', 'sit amet consectetur', 'amet consectetur adipiscing',
                    'consectetur adipiscing elit', 'adipiscing elit Cras', 'elit Cras consectetur', 'Cras consectetur dui',
                    'consectetur dui et', 'dui et lobortis', 'et lobortis auctor', 'lobortis auctor Nulla', 'auctor Nulla vitae',
                    'Nulla vitae congue', 'vitae congue lorem', 'Lorem ipsum dolor sit', 'ipsum dolor sit amet',
                    'dolor sit amet consectetur', 'sit amet consectetur adipiscing', 'amet consectetur adipiscing elit',
                    'consectetur adipiscing elit Cras', 'adipiscing elit Cras consectetur', 'elit Cras consectetur dui',
                    'Cras consectetur dui et', 'consectetur dui et lobortis', 'dui et lobortis auctor', 'et lobortis auctor Nulla',
                    'lobortis auctor Nulla vitae', 'auctor Nulla vitae congue', 'Nulla vitae congue lorem',
                ],
            ],
            [
                2, 4,
                $this->getUtf8Text(),
                [
                    '鋍鞎 鞮鞢騉', '鞮鞢騉 袟袘觕', '袟袘觕 炟砏', '炟砏 謺貙蹖', '謺貙蹖 偢偣唲', '偢偣唲 箷箯緷', '箷箯緷 鑴鱱爧', '鑴鱱爧 覮轀',
                    '覮轀 剆坲', '剆坲 煘煓瑐', '煘煓瑐 鬐鶤鶐', '鬐鶤鶐 飹勫嫢', '飹勫嫢 枲柊氠', '枲柊氠 鍎鞚韕', '鍎鞚韕 焲犈', '焲犈 殍涾烰',
                    '殍涾烰 齞齝囃', '齞齝囃 蹅輶', '蹅輶 孻憵', '孻憵 擙樲橚', '擙樲橚 藒襓謥', '藒襓謥 岯岪弨', '岯岪弨 廞徲', '廞徲 孻憵懥',
                    '孻憵懥 趡趛踠', '鋍鞎 鞮鞢騉 袟袘觕', '鞮鞢騉 袟袘觕 炟砏', '袟袘觕 炟砏 謺貙蹖', '炟砏 謺貙蹖 偢偣唲', '謺貙蹖 偢偣唲 箷箯緷',
                    '偢偣唲 箷箯緷 鑴鱱爧', '箷箯緷 鑴鱱爧 覮轀', '鑴鱱爧 覮轀 剆坲', '覮轀 剆坲 煘煓瑐', '剆坲 煘煓瑐 鬐鶤鶐', '煘煓瑐 鬐鶤鶐 飹勫嫢',
                    '鬐鶤鶐 飹勫嫢 枲柊氠', '飹勫嫢 枲柊氠 鍎鞚韕', '枲柊氠 鍎鞚韕 焲犈', '鍎鞚韕 焲犈 殍涾烰', '焲犈 殍涾烰 齞齝囃', '殍涾烰 齞齝囃 蹅輶',
                    '齞齝囃 蹅輶 孻憵', '蹅輶 孻憵 擙樲橚', '孻憵 擙樲橚 藒襓謥', '擙樲橚 藒襓謥 岯岪弨', '藒襓謥 岯岪弨 廞徲', '岯岪弨 廞徲 孻憵懥',
                    '廞徲 孻憵懥 趡趛踠', '鋍鞎 鞮鞢騉 袟袘觕 炟砏', '鞮鞢騉 袟袘觕 炟砏 謺貙蹖', '袟袘觕 炟砏 謺貙蹖 偢偣唲', '炟砏 謺貙蹖 偢偣唲 箷箯緷',
                    '謺貙蹖 偢偣唲 箷箯緷 鑴鱱爧', '偢偣唲 箷箯緷 鑴鱱爧 覮轀', '箷箯緷 鑴鱱爧 覮轀 剆坲', '鑴鱱爧 覮轀 剆坲 煘煓瑐',
                    '覮轀 剆坲 煘煓瑐 鬐鶤鶐', '剆坲 煘煓瑐 鬐鶤鶐 飹勫嫢', '煘煓瑐 鬐鶤鶐 飹勫嫢 枲柊氠', '鬐鶤鶐 飹勫嫢 枲柊氠 鍎鞚韕',
                    '飹勫嫢 枲柊氠 鍎鞚韕 焲犈', '枲柊氠 鍎鞚韕 焲犈 殍涾烰', '鍎鞚韕 焲犈 殍涾烰 齞齝囃', '焲犈 殍涾烰 齞齝囃 蹅輶',
                    '殍涾烰 齞齝囃 蹅輶 孻憵', '齞齝囃 蹅輶 孻憵 擙樲橚', '蹅輶 孻憵 擙樲橚 藒襓謥', '孻憵 擙樲橚 藒襓謥 岯岪弨', '擙樲橚 藒襓謥 岯岪弨 廞徲',
                    '藒襓謥 岯岪弨 廞徲 孻憵懥', '岯岪弨 廞徲 孻憵懥 趡趛踠',
                ],
            ],
        ];
    }
}
