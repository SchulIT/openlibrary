<?php

namespace App\Tests\Book;

use App\Book\TitleCleanup;
use PHPUnit\Framework\TestCase;

class TitleCleanupTest extends TestCase {

    public const array Tests = [
        '¬DAS TAL - Season 1: Die Prophezeiung. Band 4 : Thriller / Krystyna Kuhn . - 3. Aufl.' => 'DAS TAL - Season 1: Die Prophezeiung. Band 4: Thriller / Krystyna Kuhn. - 3. Aufl.',
        '¬DAS TAL - Season 1: ¬Die Prophezeiung. Band 4 : Thriller / Krystyna Kuhn. - 3. Aufl.' => 'DAS TAL - Season 1: Die Prophezeiung. Band 4: Thriller / Krystyna Kuhn. - 3. Aufl.',
        '... und wer liebt mich? / Hortense Ullrich. - 1. [Aufl. ]' => '... und wer liebt mich? / Hortense Ullrich. - 1. [Aufl.]'
    ];

    public function testTitleCleanup() {
        $cleanup = new TitleCleanup();

        foreach(self::Tests as $input => $expected) {
            $this->assertEquals($expected, $cleanup->cleanup($input));
        }
    }
}
