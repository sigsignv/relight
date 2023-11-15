<?php

/**
 * BoardConfig
 *
 * @copyright 2023 Sigsign
 * @license Apache-2.0 or MIT-0
 * @author Sigsign <sig@signote.cc>
 */

declare(strict_types=1);

namespace Relight;

class BoardConfig implements \Countable, \IteratorAggregate
{
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function get(string $key, ?string $default = null): ?string
    {
        return $this->has($key) ? $this->config[$key] : $default;
    }

    public function set(string $key, string $value): void
    {
        $this->config[$key] = $value;
    }

    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->config);
    }

    public function remove(string $key): void
    {
        unset($this->config[$key]);
    }

    public function loadJson(string $filename): void
    {
        $fp = @\fopen($filename, 'r');
        if (!$fp) {
            throw new \InvalidArgumentException("{$filename} is not exists or not a file");
        }

        // Todo: flock() を使わない実装に置き換える
        \flock($fp, LOCK_SH);
        $content = \fread($fp, filesize($filename));
        \flock($fp, LOCK_UN);
        \fclose($fp);
        if ($content === false) {
            throw new \Exception('fread() is failed');
        }

        $config = \json_decode($content, true, 16, JSON_INVALID_UTF8_SUBSTITUTE);
        if (\json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(json_last_error_msg());
        }

        foreach ($config as $key => $value) {
            if (\is_string($key) && \is_string($value)) {
                $this->set($key, $value);
            }
        }
    }

    public function toArray(): array
    {
        return $this->config;
    }

    public function count(): int
    {
        return \count($this->config);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->config);
    }
}
