<?php

namespace CiWorks\App\Interfaces;

interface RemoteRequestInterface
{
    public function initalise();

    public function get(string $url);

    public function setVerbosity(bool $flag);
}
