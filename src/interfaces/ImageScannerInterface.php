<?php

namespace CIWORKS\Interfaces;

interface ImageScannerInterface {

    /**
     * @param string $remoteUrl
     * @return String array
     */
    public static function fetch($remoteUrl) : array;
}
?>
