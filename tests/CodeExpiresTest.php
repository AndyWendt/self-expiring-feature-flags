<?php

namespace Tests;

use App\CodeExpires;
use App\Exceptions\CodeExpirationException;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class CodeExpiresTest extends TestCase
{
    /** @test */
    public function it_expires_a_piece_of_code()
    {
        $this->expectException(CodeExpirationException::class);
        Carbon::setTestNow('07/1/2022');
        CodeExpires::on(expirationDate: new Carbon('06/1/2022'))->andRaises();
    }

    /** @test */
    public function it_does_not_raise_if_the_expiration_date_is_in_the_future()
    {
        Carbon::setTestNow('07/1/2022');
        CodeExpires::on(expirationDate: new Carbon('08/1/2022'))->andRaises();
        $this->assertTrue(true);
    }
}
