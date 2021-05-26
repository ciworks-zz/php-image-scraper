<?php

namespace CiWorks\App\Interfaces;

interface ImageScannerInterface {

    public function fetch(string $remoteUrl) : array;
}
