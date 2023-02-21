<?php

namespace App;

use App\Exceptions\CodeExpirationException;
use Illuminate\Support\Carbon;

class CodeExpires
{
    public static function on(Carbon $expirationDate)
    {
        return new self($expirationDate);
    }

    public function __construct(private readonly Carbon $expirationDate) {}

    public function forEnvironments(array $environments): self
    {
        return $this;
    }

    public function andRaises()
    {
        if ($this->expirationDate->greaterThan('today')) return;
        throw new CodeExpirationException();
    }
}
