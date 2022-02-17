<?php

namespace Jascha030\Id3\Reader;

use Jascha030\Id3\Map\Binary\ID3MapInterface;
use Jascha030\Id3\Map\Binary\ID3V22;
use Jascha030\Id3\Map\Binary\ID3V23;
use JetBrains\PhpStorm\Pure;

class Reader
{
    /**
     * @var ID3MapInterface[]
     */
    private array $maps;

    private function __construct(array $defaultMaps = [new ID3V22(), new ID3V23()])
    {
        $this->maps = [];

        foreach ($defaultMaps as $map) {
            $this->addVersionMap($map);
        }
    }

    public function addVersionMap(ID3MapInterface $map): void
    {
        $this->maps[] = $map;
    }

    public function __invoke(string $filePath): ?array
    {
        $stream = fopen($filePath, 'rb');
        $tag    = fread($stream, filesize($filePath));
        $tmp    = "";

        fclose($stream);
        $result = [];

        if (! str_starts_with($tag, "ID3")) {
            return null;
        }

        $result['FileName'] = $filePath;
        $result['TAG']      = substr($tag, 0, 3);
        $version            = sprintf("%s.%s", hexdec(bin2hex($tag[3])), hexdec(bin2hex($tag[4])));

        foreach ($this->maps as $map) {
            if (! $map->applicable($version)) {
                continue;
            }

            foreach ($map->getIds() as $tagId) {
                if (! str_contains($tag, $tagId . chr(0))) {
                    continue;
                }

                $position = strpos($tag, $tagId . chr(0));
                $length   = hexdec(bin2hex(substr($tag, ($position + 5), 3)));
                $data     = substr($tag, $position, 9 + $length);

                for ($a = 0, $max = strlen($data); $a < $max; ++$a) {
                    $char = $data[$a];

                    if ($char >= " " && $char <= "~") {
                        $tmp .= $char;
                    }
                }

                $tagTypeId = substr($tmp, $map->getOffset());
                $key       = array_flip($map->getIds())[$tagTypeId] ?? null;

                if (null !== $key) {
                    $offset       = $key === 'Lyric' ? $map->getOffset() + 3 : $map->getOffset();
                    $result[$key] = str_replace(substr($tmp, $offset), '', $tmp);
                }

                $tmp = "";
            }
        }

//        if ($version === "2.0") {
//            foreach (self::ID3_V22 as $tagId) {
//                if (! str_contains($tag, $tagId . chr(0))) {
//                    continue;
//                }
//
//                $position = strpos($tag, $tagId . chr(0));
//                $length   = hexdec(bin2hex(substr($tag, ($position + 3), 3)));
//                $data     = substr($tag, $position, 6 + $length);
//
//                for ($a = 0, $aMax = strlen($data); $a < $aMax; $a++) {
//                    $char = $data[$a];
//
//                    if ($char >= " " && $char <= "~") {
//                        $tmp .= $char;
//                    }
//                }
//
//                $tagTypeId = substr($tmp, 3);
//                $key       = array_flip(self::ID3_V22)[$tagTypeId] ?? null;
//
//                if (null !== $key) {
//                    $offset       = $key === 'Lyric'
//                        ? 6
//                        : 3;
//                    $result[$key] = str_replace(substr($tmp, $offset), '', $tmp);
//                }
//
//                $tmp = "";
//            }
//        }

        return $result;
    }
}