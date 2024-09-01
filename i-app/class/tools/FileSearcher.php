<?php

class FileSearcher
{
    private $directory;
    private $fileName;

    public function __construct($directory, $fileName)
    {
        $this->directory = $directory;
        $this->fileName = $fileName;
    }

    public function search()
    {
        $filesFound = [];

        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->directory)
            );

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getFilename() === $this->fileName) {
                    $filesFound[] = $file->getPathname();
                }
            }
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }

        return $filesFound;
    }
}
