<?php

namespace Jascha030\Id3\Map\Binary;

use Jascha030\Id3\Tag\TagInterface;

interface ID3MapInterface
{
    public function getIds(): array;

    public function applicable(string $version): bool;

    public function getOffset(): int;

    public function processTag(string $tag, string $tagId): string;
}