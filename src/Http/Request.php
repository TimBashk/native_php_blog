<?php

namespace App\Http;
class Request
{
    private array $get;

    public function __construct(array $get = null)
    {
        $this->get = $get ?? $_GET;
    }

    public function getInt(string $key, int $default = 0, bool $required = false): int
    {
        $value = $this->get[$key] ?? $default;
        if (!is_numeric($value)) {
            if ($required) {
                $this->notFound();
            }
            return $default;
        }
        $intVal = (int)$value;
        if ($required && $intVal <= 0) {
            $this->notFound();
        }
        return $intVal;
    }

    public function getString(string $key, string $default = '', bool $required = false): string
    {
        $value = $this->get[$key] ?? $default;
        $value = trim((string)$value);
        if ($required && $value === '') {
            $this->notFound();
        }
        return $value;
    }

    private function notFound(): void
    {
        http_response_code(404);
        exit('Not found');
    }
}