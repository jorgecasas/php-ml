# Token Count Vectorizer

Transform a collection of text samples to a vector of token counts.

### Constructor Parameters

* $tokenizer (Tokenizer) - tokenizer object (see below)
* $minDF (float) -  ignore tokens that have a samples frequency strictly lower than the given threshold. This value is also called cut-off in the literature. (default 0)

```
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;

$vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
```

### Transformation

To transform a collection of text samples, use the `transform` method. Example:

```
$samples = [
    'Lorem ipsum dolor sit amet dolor',
    'Mauris placerat ipsum dolor',
    'Mauris diam eros fringilla diam',
];

$vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());

// Build the dictionary.
$vectorizer->fit($samples);

// Transform the provided text samples into a vectorized list.
$vectorizer->transform($samples);
// return $samples = [
//    [0 => 1, 1 => 1, 2 => 2, 3 => 1, 4 => 1],
//    [5 => 1, 6 => 1, 1 => 1, 2 => 1],
//    [5 => 1, 7 => 2, 8 => 1, 9 => 1],
//];

```

### Vocabulary

You can extract vocabulary using the `getVocabulary()` method. Example:

```
$vectorizer->getVocabulary();
// return $vocabulary = ['Lorem', 'ipsum', 'dolor', 'sit', 'amet', 'Mauris', 'placerat', 'diam', 'eros', 'fringilla'];
```

### Tokenizers

* WhitespaceTokenizer - select tokens by whitespace.
* WordTokenizer - select tokens of 2 or more alphanumeric characters (punctuation is completely ignored and always treated as a token separator).
* NGramTokenizer - continuous sequence of characters of the specified length. They are useful for querying languages that don’t use spaces or that have long compound words, like German.

**NGramTokenizer**

The NGramTokenizer tokenizer accepts the following parameters:

`$minGram` - minimum length of characters in a gram. Defaults to 1.
`$maxGram` - maximum length of characters in a gram. Defaults to 2.

```php
use Phpml\Tokenization\NGramTokenizer;

$tokenizer = new NGramTokenizer(1, 2);

$tokenizer->tokenize('Quick Fox');

// returns ['Q', 'u', 'i', 'c', 'k', 'Qu', 'ui', 'ic', 'ck', 'F', 'o', 'x', 'Fo', 'ox']
```

**NGramWordTokenizer**

The NGramWordTokenizer tokenizer accepts the following parameters:

`$minGram` - minimum length of characters in a gram. Defaults to 1.
`$maxGram` - maximum length of characters in a gram. Defaults to 2.

```php
use Phpml\Tokenization\NGramWordTokenizer;

$tokenizer = new NGramWordTokenizer(1, 2);

$tokenizer->tokenize('very quick fox');

// returns ['very', 'quick', 'fox', 'very quick', 'quick fox']
```
