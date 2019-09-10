<?php

namespace Ronanchilvers\Foundation\Filesystem;

use League\Flysystem\FilesystemInterface;
use Ronanchilvers\Foundation\Traits\Optionable;
use Slim\Psr7\Stream;

/**
 * Disk class representing a single disk
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class Disk
{
    use Optionable;

    /**
     * @var League\Flysystem\FilesystemInterface
     */
    protected $filesystem;

    /**
     * Class constructor
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function __construct(
        FilesystemInterface $filesystem,
        array $options = []
    ) {
        $this->filesystem = $filesystem;
        $this->setOptions(
            $options
        );
    }

    /**
     * Get the full path for a given filename
     *
     * @param string $filename
     * @return string
     */
    public function path($filename)
    {
        return implode(
            '/',
            [
                $this->getOption('base_dir'),
                $filename
            ]
        );
    }

    /**
     * Get a stream to a given file
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function stream($filename)
    {
        return new Stream($this->filesystem->readStream(
            $this->path($filename)
        ));
    }

    /**
     * Get the contents of a file from the filesystem
     *
     * @param string $filename
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function read($filename)
    {
        return $this->filesystem->read(
            $this->path($filename)
        );
    }

    /**
     * Write a file into this disk
     *
     * @param string $filename
     * @param mixed $contents
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function write($filename, $contents)
    {
        $path = $this->path($filename);
        if (is_resource($contents)) {
            return $this->filesystem->putStream(
                $path,
                $contents
            );
        }

        return $this->filesystem->put(
            $path,
            $contents
        );
    }

    /**
     * Delete a file
     *
     * @param string $filename
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function delete($filename)
    {
        return $this->filesystem->delete(
            $this->path($filename)
        );
    }

    /**
     * Check if a file exists
     *
     * @param string $filename
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function has($filename)
    {
        return $this->filesystem->has(
            $this->path($filename)
        );
    }

    /**
     * Get the contents of a directory
     *
     * @param string $directory
     * @param boolean $recursive
     * @return array
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function contents($directory, $recursive = false)
    {
        return $this->filesystem->listContents(
            $this->path($directory),
            $recursive
        );
    }
}
