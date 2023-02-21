<?php

namespace App;

use App\Exceptions\CodeExpirationException;
use Illuminate\Support\Carbon;

class CodeExpires
{
    public static function on(Carbon $expirationDate)
    {
        return new self(expirationDate: $expirationDate, env: config('app.env'));
    }

    public function __construct(private readonly Carbon $expirationDate, private readonly string $env) {}

    public function andRaises()
    {
        if ($this->expirationDate->greaterThan('today')) return;
        if (in_array($this->env, ['local', 'ci', 'test'])) throw new CodeExpirationException();
    }
}
