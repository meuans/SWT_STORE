<?php

declare(strict_types=1);

namespace App\Http;

final class Response
{
    private int $status;
    private array $headers;
    private string $body;

    private function __construct(int $status, array $headers, string $body)
    {
        $this->status = $status;
        $this->headers = $headers;
        $this->body = $body;
    }

    public static function json(array $data, int $status = 200, array $headers = []): self
    {
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
        return new self($status, array_merge(['Content-Type' => 'application/json; charset=utf-8'], $headers), $payload ?: '{}');
    }

    public static function text(string $text, int $status = 200, array $headers = []): self
    {
        return new self($status, array_merge(['Content-Type' => 'text/plain; charset=utf-8'], $headers), $text);
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }
        echo $this->body;
    }
}


