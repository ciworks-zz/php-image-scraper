<?php

namespace CiWorks\App;

use CiWorks\App\Interfaces\ImageScannerInterface;
use Katzgrau\KLogger\Logger;
use RuntimeException;

class ImageScanner implements ImageScannerInterface
{
    /** @var CurlRequest */
    private $request;

    /** @var Logger */
    private $logger;

    /** @var string */
    private $folderName;

    /** @var string */
    private $baseOutputFolder;

    public function __construct(
        CurlRequest $request,
        Logger $logger,
        string $baseOutputFolder = 'images'
    ) {
        $this->request = $request;
        $this->logger = $logger;
        $this->baseOutputFolder = $baseOutputFolder;
    }

    /**
     * @throws RuntimeException
     */
    public function fetch(string $remoteUrl): array
    {
        $output = [];

        $deDupedList = $this->parseRemoteUrl($remoteUrl);

        foreach ($deDupedList as $entry) {
            if ($this->isFullUrl($entry) && $this->isImage($entry)) {
                $output[] = ['path' => $entry, 'meta' => $this->getImageMeta($entry)];
            }
        }
        return $output;
    }

    public function scrape(array $imageList): void
    {
        $directory = $this->createOutputDirectory($this->folderName);

        $this->logger->info("Output directory set to " . $this->folderName);

        foreach ($imageList as $imageData) {
            $filename = basename($imageData['path']);
            $finalFile = sprintf('%s/%s', $directory, $filename);
            file_put_contents($finalFile, file_get_contents($imageData['path']));
        }
    }

    public function setFolderName(string $targetDir = ''): void
    {
        $this->folderName = 'output-' . (new \DateTime())->format('Ymd-His');
        if ($targetDir) {
            $this->folderName = $targetDir;
        }
    }

    private function createOutputDirectory(string $folderName): string
    {

        if (!file_exists($this->baseOutputFolder) && !is_dir($this->baseOutputFolder)) {
            mkdir($this->baseOutputFolder);
        }

        $directory = $this->baseOutputFolder . '/' . $folderName;
        if (!file_exists($directory) && !is_dir($directory)) {
            mkdir($directory);
        }
        return $directory;
    }

    /**
     * @throws RuntimeException
     */
    private function parseRemoteUrl(string $remoteUrl): array
    {

        $this->request->initalise();
        $response = $this->request->get($remoteUrl . 'b');

        $this->logger->debug("request headers: ", $this->request->getRequestHeaders());
        $this->logger->debug("response header: ", $this->request->getResponseHeaders());

        // We are only concerned with matching image tags so strip those out using regex
        preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $response, $matchedEntries);

        return count($matchedEntries) ? array_unique($matchedEntries[1]) : [];
    }

    private function isFullUrl(string $path): bool
    {
        return filter_var($path, FILTER_VALIDATE_URL);
    }

    private function isImage(string $path): bool
    {
        return preg_match("#\.(jpg|jpeg|gif|png)$# i", $path);
    }

    private function getImageMeta(string $fileName): array
    {
        list($width, $height, $type, $attr) = getimagesize($fileName);

        return ['width' => $width, 'height' => $height];
    }
}
