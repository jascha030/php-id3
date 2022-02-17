<?php

declare(strict_types=1);

namespace Jascha030\Id3\Map\Binary;

use JetBrains\PhpStorm\Pure;

final class ID3V23 extends ID3MapAbstract
{
    public const MAP      = [
        'Title'  => 'TIT2',
        'Album'  => 'TALB',
        'Author' => 'TPE1',
        'Track'  => 'TRCK',
        'Year'   => 'TDRC',
        'Length' => 'TLEN',
        'Lyric'  => 'USLT'
    ];

    public const VERSIONS = ['3.0', '4.0'];

    #[Pure]
    public function __construct()
    {
        parent::__construct(self::MAP, self::VERSIONS);
    }

    public function processTag(string $tag, string $tagId): string
    {
        $tmp = '';

        $position = strpos($tag, $tagId . chr(0));
        $length   = hexdec(bin2hex(substr($tag, ($position + 5), 3)));
        $data     = substr($tag, $position, 9 + $length);

        for ($a = 0, $max = strlen($data); $a < $max; ++$a) {
            $char = $data[$a];

            if ($char >= " " && $char <= "~") {
                $tmp .= $char;
            }
        }

        return $tmp;
    }
}