<?php

namespace CiWorks\App;

use Curl\Curl;

/**
 * Class to enable associated images to be returned for a given remote URL
 * Class ImageScanner
 */
class ImageScanner implements ImageScannerInterface
{
    /**
     * @var Curl
     */
    private $curl;

    /**
     * ImageScanner constructor.
     */
    public function __construct(Curl $curl)
    {

        if ($this->debugFlagSet()) {
            $curl->verbose(true);
        }
        $this->setCurl($curl);
    }

    /**
     * @return bool
     */
    private function debugFlagSet(): bool
    {
        return $_ENV['DEBUG'];
    }

    /**
     * @param Curl $curl
     * @return ImageScanner
     */
    private function setCurl(Curl $curl): ImageScanner
    {
        $this->curl = $curl;

        return $this;
    }

    /**
     * @inherit
     */
    public function fetch($remoteUrl): array
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

    /**
     * @param string $remoteUrl
     * @return array
     */
    private function parseRemoteUrl($remoteUrl): array
    {
        $output = '';

        $curl = $this->getCurl();

        if ($_ENV['ALLOW_CURL'] == true) {
            $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
            $curl->get($remoteUrl);

            if ($this->debugFlagSet() === true) {
                echo 'not set';
                var_dump($curl->request_headers);
                var_dump($curl->response_headers);
            }

            if ($curl->error) {
                echo 'An error occurred retrieving from remote URL - status code: ' . $curl->error_code;
                return [];
            } else {
                $output = $curl->getResponse();
            }

        } else {
            $output = file_get_contents($remoteUrl);
        }

        $curl->close;

        // We are only concerned with matching image tags so strip those out using regex
        preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $output, $matchedEntries);

        return count($matchedEntries) ? array_unique($matchedEntries[1]) : [];
    }

    /**
     * @return Curler
     */
    private function getCurl()
    {
        return $this->curl;
    }

    /**
     * @param string $path
     * @return bool
     */
    private function isFullUrl($path): bool
    {
        return filter_var($path, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED);
    }

    /**
     * Regex check for a filepath to see if it contains an image reference
     * @param string $filePath
     * @return boolean|int
     */
    private function isImage($path)
    {
        return preg_match("#\.(jpg|jpeg|gif|png)$# i", $path);
    }

    /**
     * Extract image meta for a given filepath
     * @param string $fileName
     * @return String[]
     */
    private function getImageMeta($fileName)
    {
        list($width, $height, $type, $attr) = getimagesize($fileName);

        return [
            'width' => $width,
            'height' => $height
        ];
    }
}
