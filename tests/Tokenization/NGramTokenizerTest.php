<?php

declare(strict_types=1);

namespace Phpml\Tests\Tokenization;

use Phpml\Exception\InvalidArgumentException;
use Phpml\Tokenization\NGramTokenizer;

/**
 * Inspiration: https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-ngram-tokenizer.html
 */
class NGramTokenizerTest extends TokenizerTest
{
    /**
     * @dataProvider textDataProvider
     */
    public function testNGramTokenization(int $minGram, int $maxGram, string $text, array $tokens): void
    {
        $tokenizer = new NGramTokenizer($minGram, $maxGram);

        self::assertEquals($tokens, $tokenizer->tokenize($text));
    }

    public function testMinGramGreaterThanMaxGramNotAllowed(): void
    {
        self::expectException(InvalidArgumentException::class);

        new NGramTokenizer(5, 2);
    }

    public function testMinGramValueTooSmall(): void
    {
        self::expectException(InvalidArgumentException::class);

        new NGramTokenizer(0, 2);
    }

    public function testMaxGramValueTooSmall(): void
    {
        self::expectException(InvalidArgumentException::class);

        new NGramTokenizer(1, 0);
    }

    public function textDataProvider(): array
    {
        return [
            [
                1, 2,
                'Quick Fox',
                ['Q', 'u', 'i', 'c', 'k', 'Qu', 'ui', 'ic', 'ck', 'F', 'o', 'x', 'Fo', 'ox'],
            ],
            [
                3, 3,
                'Quick Foxes',
                ['Qui', 'uic', 'ick', 'Fox', 'oxe', 'xes'],
            ],
            [
                1, 2,
                '快狐跑过 边缘跑',
                ['快', '狐', '跑', '过', '快狐', '狐跑', '跑过', '边', '缘', '跑', '边缘', '缘跑'],
            ],
            [
                3, 3,
                '快狐跑过狐 边缘跑狐狐',
                ['快狐跑', '狐跑过', '跑过狐', '边缘跑', '缘跑狐', '跑狐狐'],
            ],
            [
                2, 4,
                $this->getSimpleText(),
                [
                    'Lo', 'or', 're', 'em', 'Lor', 'ore', 'rem', 'Lore', 'orem', 'ip', 'ps', 'su', 'um', 'ips', 'psu', 'sum', 'ipsu',
                    'psum', 'do', 'ol', 'lo', 'or', 'dol', 'olo', 'lor', 'dolo', 'olor', 'si', 'it', 'sit', 'am', 'me', 'et', 'ame',
                    'met', 'amet', 'co', 'on', 'ns', 'se', 'ec', 'ct', 'te', 'et', 'tu', 'ur', 'con', 'ons', 'nse', 'sec', 'ect', 'cte',
                    'tet', 'etu', 'tur', 'cons', 'onse', 'nsec', 'sect', 'ecte', 'ctet', 'tetu', 'etur', 'ad', 'di', 'ip', 'pi', 'is',
                    'sc', 'ci', 'in', 'ng', 'adi', 'dip', 'ipi', 'pis', 'isc', 'sci', 'cin', 'ing', 'adip', 'dipi', 'ipis', 'pisc',
                    'isci', 'scin', 'cing', 'el', 'li', 'it', 'eli', 'lit', 'elit', 'Cr', 'ra', 'as', 'Cra', 'ras', 'Cras', 'co', 'on',
                    'ns', 'se', 'ec', 'ct', 'te', 'et', 'tu', 'ur', 'con', 'ons', 'nse', 'sec', 'ect', 'cte', 'tet', 'etu', 'tur',
                    'cons', 'onse', 'nsec', 'sect', 'ecte', 'ctet', 'tetu', 'etur', 'du', 'ui', 'dui', 'et', 'lo', 'ob', 'bo', 'or',
                    'rt', 'ti', 'is', 'lob', 'obo', 'bor', 'ort', 'rti', 'tis', 'lobo', 'obor', 'bort', 'orti', 'rtis', 'au', 'uc',
                    'ct', 'to', 'or', 'auc', 'uct', 'cto', 'tor', 'auct', 'ucto', 'ctor', 'Nu', 'ul', 'll', 'la', 'Nul', 'ull', 'lla',
                    'Null', 'ulla', 'vi', 'it', 'ta', 'ae', 'vit', 'ita', 'tae', 'vita', 'itae', 'co', 'on', 'ng', 'gu', 'ue', 'con',
                    'ong', 'ngu', 'gue', 'cong', 'ongu', 'ngue', 'lo', 'or', 're', 'em', 'lor', 'ore', 'rem', 'lore', 'orem',
                ],
            ],
            [
                2, 4,
                $this->getUtf8Text(),
                [
                    '鋍鞎', '鞮鞢', '鞢騉', '鞮鞢騉', '袟袘', '袘觕', '袟袘觕', '炟砏', '謺貙', '貙蹖', '謺貙蹖', '偢偣', '偣唲',
                    '偢偣唲', '箷箯', '箯緷', '箷箯緷', '鑴鱱', '鱱爧', '鑴鱱爧', '覮轀', '剆坲', '煘煓', '煓瑐', '煘煓瑐', '鬐鶤',
                    '鶤鶐', '鬐鶤鶐', '飹勫', '勫嫢', '飹勫嫢', '枲柊', '柊氠', '枲柊氠', '鍎鞚', '鞚韕', '鍎鞚韕', '焲犈', '殍涾',
                    '涾烰', '殍涾烰', '齞齝', '齝囃', '齞齝囃', '蹅輶', '孻憵', '擙樲', '樲橚', '擙樲橚', '藒襓', '襓謥', '藒襓謥',
                    '岯岪', '岪弨', '岯岪弨', '廞徲', '孻憵', '憵懥', '孻憵懥', '趡趛', '趛踠', '趡趛踠',
                ],
            ],
        ];
    }
}
