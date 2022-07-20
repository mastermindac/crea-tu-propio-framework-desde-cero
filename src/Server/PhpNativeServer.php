<?php

namespace Lune\Server;

use Lune\Http\HttpMethod;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Storage\File;

/**
 * PHP native server that uses `$_SERVER` global.
 */
class PhpNativeServer implements Server {
    /**
     * Get files from `$_FILES` global.
     *
     * @return array<string, \Lune\Storage\File>
     */
    protected function uploadedFiles(): array {
        $files = [];
        foreach ($_FILES as $key => $file) {
            if (!empty($file["tmp_name"])) {
                $files[$key] = new File(
                    file_get_contents($file["tmp_name"]),
                    $file["type"],
                    $file["name"],
                );
            }
        }

        return $files;
    }

    /**
     * @inheritDoc
     */
    public function getRequest(): Request {
        return (new Request())
            ->setUri(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH))
            ->setMethod(HttpMethod::from($_SERVER["REQUEST_METHOD"]))
            ->setHeaders(getallheaders())
            ->setPostData($_POST)
            ->setQueryParameters($_GET)
            ->setFiles($this->uploadedFiles());
    }

    /**
     * @inheritDoc
     */
    public function sendResponse(Response $response) {
        // PHP sends Content-Type header by default, but it has to be removed if
        // the response has not content. Content-Type header can't be removed
        // unless it is set to some value before.
        header("Content-Type: None");
        header_remove("Content-Type");

        $response->prepare();
        http_response_code($response->status());
        foreach ($response->headers() as $header => $value) {
            header("$header: $value");
        }
        print($response->content());
    }
}
