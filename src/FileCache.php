<?php

namespace Stoyishi\Cache;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class FileCache implements CacheInterface
{
    protected $path;
    protected $defaultTtl = 86400;

    public function __construct($path)
    {
        $this->path = rtrim(__DIR__.'/'.$path, DIRECTORY_SEPARATOR);

        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

    protected function getPath($key)
    {
        $hash = md5($key);
        $dir1 = substr($hash, 0, 2);
        $dir2 = substr($hash, 2, 2);

        $dir = $this->path . DIRECTORY_SEPARATOR . $dir1 . DIRECTORY_SEPARATOR . $dir2;

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return $dir . DIRECTORY_SEPARATOR . $hash . '.cache';
    }

    public function get($key, $default = null)
    {
        $file = $this->getPath($key);

        if (!file_exists($file)) {
            return $default;
        }

        $handle = fopen($file, 'r');
        flock($handle, LOCK_SH);

        $data = unserialize(stream_get_contents($handle));

        flock($handle, LOCK_UN);
        fclose($handle);

        if (time() > $data['expire']) {
            unlink($file);
            return $default;
        }

        return $data['value'];
    }

    public function set($key, $value, $ttl = null)
    {
        $file = $this->getPath($key);

        $ttl = $ttl ?? $this->defaultTtl;

        $data = [
            'expire' => time() + (int)$ttl,
            'value'  => $value
        ];

        $handle = fopen($file, 'c');
        flock($handle, LOCK_EX);

        ftruncate($handle, 0);
        fwrite($handle, serialize($data));

        flock($handle, LOCK_UN);
        fclose($handle);

        return true;
    }

    public function delete($key)
    {
        $file = $this->getPath($key);

        if (file_exists($file)) {
            return unlink($file);
        }

        return true;
    }

    public function clear()
    {
        $this->deleteDirectory($this->path);
        mkdir($this->path, 0777, true);
        return true;
    }

    protected function deleteDirectory($dir)
    {
        if (!is_dir($dir)) return;

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;

            $full = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_dir($full)) {
                $this->deleteDirectory($full);
                rmdir($full);
            } else {
                unlink($full);
            }
        }
    }

    public function getMultiple($keys, $default = null)
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }
        return $result;
    }

    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }
        return true;
    }

    public function deleteMultiple($keys)
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
        return true;
    }

    public function has($key)
    {
        return $this->get($key) !== null;
    }
}
