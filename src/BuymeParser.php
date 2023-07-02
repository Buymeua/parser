<?php

declare(strict_types=1);

namespace Buyme\Parser;

use Buyme\Parser\Adapter\ParserInterface;
use Exception;
use Throwable;

/**
 * @mixin ParserInterface
 */
class BuymeParser
{
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

        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $adaptersParser = config('buyme-parser-config.adapters', []);
        if (empty($adaptersParser)) {
            throw new Exception(__("buyme-parser-lang::buyme_parser.errors.config_mapping_parser_is_empty"));
        }

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
            $context = stream_context_create([
                'http' => [
                    'method' => 'HEAD',
                    'ignore_errors' => true
                ]
            ]);

            $headers = get_headers($filename, true, $context);

            if (isset($headers['Content-Length'])) {
                return (int)$headers['Content-Length'];
            } else {
                $fileContents = file_get_contents($filename);
                if ($fileContents !== false) {
                    return strlen($fileContents);
                }
            }

            return 0;
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
}