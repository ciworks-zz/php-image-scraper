<?php

namespace CiWorks\App;

/**
 * Interface ImageScannerInterface
 */
interface ImageScannerInterface
{
    /**
     * @param string $remoteUrl
     * @return String[]
     */
    public function fetch($remoteUrl) : array;
}
