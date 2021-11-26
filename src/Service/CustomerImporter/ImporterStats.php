<?php

namespace App\Service\CustomerImporter;

class ImporterStats
{
    public int $new;
    public int $invalid;

    public function __construct(int $new, int $invalid)
    {
        $this->new = $new;
        $this->invalid = $invalid;
    }
}
