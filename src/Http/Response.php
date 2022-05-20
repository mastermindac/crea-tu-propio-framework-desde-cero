<?php

namespace Lune\Http;

/**
 * HTTP response that will be sent to the client.
 */
class Response {
    /**
     * Response HTTP status code.
     *
     * @var integer
     */
    protected int $status = 200;

    /**
     * Response HTTP headers.
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * Response content.
     *
     * @var string|null
     */
    protected ?string $content = null;

    /**
     * Get response HTTP status code.
     *
     * @return integer
     */
    public function status(): int {
        return $this->status;
    }

    /**
     * Set the HTTP status code for this response.
     *
     * @param integer $status
     * @return self
     */
    public function setStatus(int $status): self {
        $this->status = $status;
        return $this;
    }

    /**
     * Get response HTTP headers.
     *
     * @return array<string, string>
     */
    public function headers(): array {
        return $this->headers;
    }

    /**
     * Set HTTP header `$header` to `$value`.
     *
     * @param string $header
     * @param string $value
     * @return self
     */
    public function setHeader(string $header, string $value): self {
        $this->headers[strtolower($header)] = $value;
        return $this;
    }

    /**
     * Remove the given `$header` from the response.
     *
     * @param string $header
     * @return void
     */
    public function removeHeader(string $header) {
        unset($this->headers[strtolower($header)]);
    }

    /**
     * Set the `"Content-Type"` header for this response.
     *
     * @param string $value
     * @return self
     */
    public function setContentType(string $value): self {
        $this->setHeader("Content-Type", $value);
        return $this;
    }

    /**
     * Get the response content.
     *
     * @return string|null
     */
    public function content(): ?string {
        return $this->content;
    }

    /**
     * Set the response content.
     *
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self {
        $this->content = $content;
        return $this;
    }

    /**
     * Prepare the response to be sent to the client.
     *
     * @return void
     */
    public function prepare() {
        if (is_null($this->content)) {
            $this->removeHeader("Content-Type");
            $this->removeHeader("Content-Length");
        } else {
            $this->setHeader("Content-Length", strlen($this->content));
        }
    }

    /**
     * Create a new JSON response.
     *
     * @param array $data
     * @return self
     */
    public static function json(array $data): self {
        return (new self())
            ->setContentType("application/json")
            ->setContent(json_encode($data));
    }

    /**
     * Create a new plain text response.
     *
     * @param string $text
     * @return self
     */
    public static function text(string $text): self {
        return (new self())
            ->setContentType("text/plain")
            ->setContent($text);
    }

    /**
     * Create a new redirect response.
     *
     * @param string $uri
     * @return self
     */
    public static function redirect(string $uri): self {
        return (new self())
            ->setStatus(302)
            ->setHeader("Location", $uri);
    }
}
