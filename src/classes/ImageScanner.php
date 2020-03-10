<?php

declare(strict_types=1);

namespace CIWORKS\Classes;

use CIWORKS\Interfaces\ImageScannerInterface;

/**
 * Class to enable associated images to be returned for a given remote URL
 * Class ImageScanner
 * @author Chima Ijeoma (ciworks.co.uk)
 */
class ImageScanner implements ImageScannerInterface
{
    /**
     * @inherit
     */
    public static function fetch($remoteUrl) : array
    {

        try {

            $curl = new \Curl\Curl();
            $curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
            $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
            $curl->get($remoteUrl);

            if ($curl->error) {
                echo 'An error occurred retrieving from remote URL - status code: ' . $curl->error_code;
                exit();
            }
            else {
                $out = $curl->response;
            }

            libxml_use_internal_errors(true);
            $dom = new \DOMDocument();
            $dom->loadHTML($out);
            $xpath = new \DOMXPath($dom);

            $returnedImageList = [];
            $index = 0;

            // iterate our curl output and parse any image urls using our Xpath query
            foreach ($xpath->query('//img') as $item) {
                $sourceImagePath = $item->getAttribute('src');

                if (self::isImage($sourceImagePath)) {
                    $imageMeta = self::getImageMeta($sourceImagePath);

                    $returnedImageList[] = [
                        'path'  => $item->getAttribute('src'),
                        'width' => $imageMeta['width'],
                        'height' => $imageMeta['height']
                    ];
                }
            }

            // close cURL resource
            $curl->close;

            return $returnedImageList;

        } catch (Exception $e) {
            echo "Unable to retrieve images from supplied URL: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Extract image meta for a given filepath
     * @param string $fileName
     * @return String[]
     */
    private function getImageMeta($fileName) {

        list($width, $height, $type, $attr) = getimagesize($fileName);

        return [
            'width' => $width,
            'height' => $height,
            'type' => $type,
            'attr' => $attr
        ];
    }

    /**
     * Regex check for a filepath to see if it contains an image reference
     * @param string $filePath
     * @return boolean|int
     */
    private function isImage($filePath) {
        return preg_match("#\.(jpg|jpeg|gif|png)$# i", $filePath);
    }
}
