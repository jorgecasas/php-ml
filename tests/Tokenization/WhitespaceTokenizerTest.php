<?php

declare(strict_types=1);

namespace Phpml\Tests\Tokenization;

use Phpml\Tokenization\WhitespaceTokenizer;

class WhitespaceTokenizerTest extends TokenizerTest
{
    public function testTokenizationOnAscii(): void
    {
        $tokenizer = new WhitespaceTokenizer();

        $tokens = ['Lorem', 'ipsum-dolor', 'sit', 'amet,', 'consectetur/adipiscing', 'elit.',
            'Cras', 'consectetur,', 'dui', 'et', 'lobortis;auctor.',
            'Nulla', 'vitae', ',.,/', 'congue', 'lorem.', ];

        self::assertEquals($tokens, $tokenizer->tokenize($this->getSimpleText()));
    }

    public function testTokenizationOnUtf8(): void
    {
        $tokenizer = new WhitespaceTokenizer();

        $tokens = ['鋍鞎', '鳼', '鞮鞢騉', '袟袘觕,', '炟砏', '蒮', '謺貙蹖', '偢偣唲', '蒛', '箷箯緷', '鑴鱱爧', '覮轀,',
            '剆坲', '煘煓瑐', '鬐鶤鶐', '飹勫嫢', '銪', '餀', '枲柊氠', '鍎鞚韕', '焲犈,',
            '殍涾烰', '齞齝囃', '蹅輶', '鄜,', '孻憵', '擙樲橚', '藒襓謥', '岯岪弨', '蒮', '廞徲', '孻憵懥', '趡趛踠', '槏', ];

        self::assertEquals($tokens, $tokenizer->tokenize($this->getUtf8Text()));
    }
}
