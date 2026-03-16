<?php

namespace WiserWebSolutions\Lobbyist\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

final class Session extends Data
{
    public function __construct(
        #[MapInputName('session_id')]
        public int $id,
        #[MapInputName('session_tag')]
        public string $tag,
        #[MapInputName('session_title')]
        public string $title,
        #[MapInputName('session_name')]
        public string $name,
        public array $meta,
    ) {
    }

    public static function fromLegiscan(array $payload): self
    {
        return new self(
            id: (int) ($payload['session_id'] ?? 0),
            tag: (string) ($payload['session_tag'] ?? ''),
            title: (string) ($payload['session_title'] ?? ''),
            name: (string) ($payload['session_name'] ?? ''),
            meta: [
                'state_id' => (int) ($payload['state_id'] ?? 0),
                'year_start' => (int) ($payload['year_start'] ?? 0),
                'year_end' => (int) ($payload['year_end'] ?? 0),
                'prefile' => (bool) ($payload['prefile'] ?? false),
                'sine_die' => (bool) ($payload['sine_die'] ?? false),
                'prior' => (bool) ($payload['prior'] ?? false),
                'special' => (bool) ($payload['special'] ?? false),
                'dataset_hash' => (string) ($payload['dataset_hash'] ?? ''),
            ],
        );
    }
}