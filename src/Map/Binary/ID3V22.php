<?php

declare(strict_types=1);

namespace Jascha030\Id3\Map\Binary;

use JetBrains\PhpStorm\Pure;

final class ID3V22 extends ID3MapAbstract
{
    public const MAP      = [
        'Title'  => 'TT2',
        'Album'  => 'TAL',
        'Author' => 'TP1',
        'Track'  => 'TRK',
        'Year'   => 'TYE',
        'Length' => 'TLE',
        'Lyric'  => 'ULT'
    ];

    public const VERSIONS = ['2.0'];

    #[Pure]
    public function __construct()
    {
        parent::__construct(self::MAP, self::VERSIONS);
    }

    public function processTag(string $tag, string $tagId): string
    {
        // TODO: Implement processTag() method.
        return '';
    }
}