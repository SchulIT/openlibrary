<?php

namespace App\Book;

class TitleCleanup {

    public const array TO_REMOVE = [
        '¬'
    ];

    public const array ENSURE_SPACE_AFTER = [
        '.',
        ':',
        ';'
    ];

    public const array ENSURE_NO_SPACE_BEFORE = [
        '.',
        ':',
        ';'
    ];

    public function cleanup(string|null $input): ?string {
        if(empty($input)) {
            return null;
        }

        foreach(self::TO_REMOVE as $char) {
            $input = str_replace($char, '', $input);
        }

        foreach(self::ENSURE_SPACE_AFTER as $char) {
            $input = str_replace($char, $char . ' ', $input);
        }

        foreach(self::ENSURE_NO_SPACE_BEFORE as $char) {
            if($char === '.') {
                $input = preg_replace("/(\s+)\./", $char, $input);
            } else {
                $input = preg_replace("/(\s+)$char/", $char, $input);
            }
        }

        $input = preg_replace('/(\s)+/', ' ', $input);

        return mb_trim($input);
    }
}
