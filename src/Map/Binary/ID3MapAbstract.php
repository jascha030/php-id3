<?php

declare(strict_types=1);

namespace Jascha030\Id3\Map\Binary;

abstract class ID3MapAbstract implements ID3MapInterface
{
    public function __construct(private array $ids, private array $versions)
    {
    }

    public function getIds(): array
    {
        return $this->ids;
    }

    final public function applicable(string $version): bool
    {
        return in_array($version, $this->versions, true);
    }

    final public function getOffset(): int
    {
        return strlen(reset($this->ids));
    }

    final protected function generator(string $data): \Generator
    {
        for ($a = 0, $max = strlen($data); $a < $max; ++$a) {
            $char = $data[$a];

            if ($char >= " " && $char <= "~") {
                yield $char;
            }
        }
    }
}