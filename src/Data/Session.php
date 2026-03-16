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
        #[MapInputName('state_id')]
        public int $stateId,
        #[MapInputName('year_start')]
        public int $yearStart,
        #[MapInputName('year_end')]
        public int $yearEnd,
        public bool $prefile,
        #[MapInputName('sine_die')]
        public bool $sineDie,
        public bool $prior,
        public bool $special,
        #[MapInputName('dataset_hash')]
        public string $datasetHash,
    ) {
    }
}