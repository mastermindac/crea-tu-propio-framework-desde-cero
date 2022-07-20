<?php

namespace Lune\Storage;

/**
 * File helper.
 */
class File {
    /**
     * Instantiate new file.
     *
     * @param string $path
     * @param mixed $content
     * @param string $type
     */
    public function __construct(
        private mixed $content,
        private string $type,
        private string $originalName,
    ) {
        $this->content = $content;
        $this->type = $type;
        $this->originalName = $originalName;
    }

    /**
     * Check if the current file is an image.
     *
     * @return boolean
     */
    public function isImage(): bool {
        return str_starts_with($this->type, "image");
    }

    /**
     * Type of the image.
     *
     * @return string|null
     */
    public function extension(): ?string {
        return match ($this->type) {
            "image/jpeg" => "jpeg",
            "image/png" => "png",
            "application/pdf" => "pdf",
            default => null,
        };
    }

    /**
     * Store the file.
     *
     * @return string URL.
     */
    public function store(?string $directory = null): string {
        $file = uniqid() . $this->extension();
        $path = is_null($directory) ? $file : "$directory/$file";
        return Storage::put($path, $this->content);
    }
}
