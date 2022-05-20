<?php

namespace Lune\Http;

class Response {
    protected int $status = 200;

    protected array $headers = [];

    protected ?string $content = null;

    public function status(): int {
        return $this->status;
    }

    public function setStatus(int $status) {
        $this->status = $status;
    }

    public function headers(): array {
        return $this->headers;
    }

    public function setHeader(string $header, string $value) {
        $this->headers[strtolower($header)] = $value;
    }

    public function removeHeader(string $header) {
        unset($this->headers[strtolower($header)]);
    }

    public function content(): ?string {
        return $this->content;
    }

    public function setContent(string $content) {
        $this->content = $content;
    }

    public function prepare() {
        if (is_null($this->content)) {
            $this->removeHeader("Content-Type");
            $this->removeHeader("Content-Length");
        } else {
            $this->setHeader("Content-Length", strlen($this->content));
        }
    }
}
