<?php
declare(strict_types=1);
namespace Narrowspark\Discovery\Common\Traits;

trait PhpFileMarkerTrait
{
    /**
     * Check if file is marked.
     *
     * @param string $packageName
     * @param string $file
     *
     * @return bool
     */
    protected function isFileMarked(string $packageName, string $file): bool
    {
        return \is_file($file) && \mb_strpos(\file_get_contents($file), \sprintf('/** > %s **/', $packageName)) !== false;
    }

    /**
     * Mark file with given data.
     *
     * @param string $packageName
     * @param string $data
     * @param int    $spaceMultiplier
     *
     * @return string
     */
    protected function markData(string $packageName, string $data, int $spaceMultiplier = 4): string
    {
        $spaces = \str_repeat(' ', $spaceMultiplier);

        return \sprintf("%s/** > %s **/\n%s%s/** %s < **/\n", $spaces, $packageName, $data, $spaces, $packageName);
    }
}