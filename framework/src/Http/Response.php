<?php

namespace Everl\Framework\Http;

class Response
{
    public function __construct(
        private string $content = '',
        private int $statusCode = 200,
        private array $headers = [],
    )
    {
        http_response_code($this->statusCode);
    }

    public function setContent(string $content): Response
    {
        $this->content = $content;

        return $this;
    }

    public function send(): void
    {
        echo $this->content;
    }

}