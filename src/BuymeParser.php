<?php

declare(strict_types=1);

namespace Buyme\Parser;

use Buyme\Parser\Adapter\ParserInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;

/**
 * @mixin ParserInterface
 */
class BuymeParser
{
    private string $contentType = "";
    private ParserInterface $driver;

    /**
     * @throws Exception Throws exception if the file doesn't exist or its size is 0
     */
    public function open(string $filename): void
    {
        if (file_exists($filename) || str_contains($filename, '://')) {
            $this->openFile($filename);
        } else {
            throw new Exception(__("buyme-parser-lang::buyme_parser.errors.file_not_exists", ['filename' => $filename]));
        }
    }

    /**
     * @throws Exception
     */
    private function openFile(string $filename): void
    {
        $fileSize = $this->getFileSize($filename);
        if ($fileSize === 0) {
            throw new Exception(__("buyme-parser-lang::buyme_parser.errors.file_is_empty", ['filename' => $filename]));
        }

        $adaptersParser = config('buyme-parser-config.adapters', []);
        if (empty($adaptersParser)) {
            throw new Exception(__("buyme-parser-lang::buyme_parser.errors.config_mapping_parser_is_empty"));
        }

        $extension = $this->getFileExtension($filename);
        $adapterClass = $adaptersParser[$extension] ?? null;
        if ($adapterClass === null) {
            throw new Exception(__("buyme-parser-lang::buyme_parser.errors.config_mapping_parser_adapter_not_found", ['extension' => $extension]));
        }

        $this->driver = new $adapterClass();

        $this->driver->open($filename);
    }

    public function getFileSize(string $filename): int
    {
        if (str_contains($filename, '://')) {
            try {
                $client = new Client();

                $response = $client->get($filename);

                $this->contentType = $response->getHeaderLine('Content-Type');

                return (int)$response->getBody()->getSize();
            } catch (GuzzleException | Throwable $e) {
                return 0;
            }
        } else {
            // Локальный файл
            return filesize($filename);
        }
    }

    public function __call($method, $args = null)
    {
        return call_user_func_array([$this->driver, $method], $args);
    }

    /**
     * Get the current driver instance.
     *
     * @return ParserInterface
     * @throws Exception If the driver instance is not set
     */
    public function getDriver(): ParserInterface
    {
        if (!isset($this->driver)) {
            throw new Exception('Driver instance is not set.');
        }

        return $this->driver;
    }
    private function getFileExtension(string $filename): string
    {
        $extension = "";
        $parts = explode('?', $filename);

        if (isset($parts[0])) {
            $filename = basename($parts[0]);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
        }

        if (empty($extension)) {
            $mimeType = explode(';', $this->contentType);
            $mimeType = $mimeType[0] ?? '';

            if (!empty($mimeType)) {
                return match ($mimeType) {
                    'text/xml', 'application/xml' => 'xml',
                    'text/csv' => 'csv',
                    'application/vnd.ms-excel' => 'xls',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
                    'application/x-yaml' => 'yml',
                    default => '',
                };
            }
        }

        return $extension;
    }
}