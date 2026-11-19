<?php

namespace App\Admin;

class ApiResult
{
    /**
     * @param  array<string, mixed>  $errors
     * @param  array<string, mixed>  $meta
     */
    public function __construct(
        public bool $successful,
        public int $status,
        public mixed $data,
        public string $message,
        public array $errors = [],
        public array $meta = [],
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function rows(): array
    {
        if (! is_array($this->data) || ! array_is_list($this->data)) {
            return [];
        }

        return array_values(array_filter($this->data, 'is_array'));
    }

    /**
     * @return array<string, mixed>
     */
    public function record(): array
    {
        return is_array($this->data) && ! array_is_list($this->data) ? $this->data : [];
    }
}
