<?php

declare(strict_types=1);

namespace Phpml\Tests\Tokenization;

use Phpml\Tokenization\WordTokenizer;

class WordTokenizerTest extends TokenizerTest
{
    public function testTokenizationOnAscii(): void
    {
        $tokenizer = new WordTokenizer();

        $tokens = ['Lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit',
            'Cras', 'consectetur', 'dui', 'et', 'lobortis', 'auctor',
            'Nulla', 'vitae', 'congue', 'lorem', ];

        self::assertEquals($tokens, $tokenizer->tokenize($this->getSimpleText()));
    }

    public function testTokenizationOnUtf8(): void
    {
        $tokenizer = new WordTokenizer();

        $tokens = ['鋍鞎', '鞮鞢騉', '袟袘觕', '炟砏', '謺貙蹖', '偢偣唲', '箷箯緷', '鑴鱱爧', '覮轀',
            '剆坲', '煘煓瑐', '鬐鶤鶐', '飹勫嫢', '枲柊氠', '鍎鞚韕', '焲犈',
            '殍涾烰', '齞齝囃', '蹅輶', '孻憵', '擙樲橚', '藒襓謥', '岯岪弨', '廞徲', '孻憵懥', '趡趛踠', ];

        self::assertEquals($tokens, $tokenizer->tokenize($this->getUtf8Text()));
    }
}
