<?php

namespace Tests;

use App\CodeExpires;
use App\Exceptions\CodeExpirationException;
use Illuminate\Support\Carbon;

class CodeExpiresTest extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplication;

    /** @test */
    public function it_does_not_raise_if_the_expiration_date_is_in_the_future()
    {
        Carbon::setTestNow('07/1/2022');
        config()->set('app.env', 'ci');

        CodeExpires::on(expirationDate: new Carbon('08/1/2022'))->andRaises();
        $this->assertTrue(true);
    }

    /**
     * @test
     * @dataProvider envDataProvider
     */
    public function it_only_when_in_the_correct_envs($shouldRaise, string $env)
    {
        Carbon::setTestNow('07/1/2022');
        config()->set('app.env', $env);

        $raised = false;
        try {
            CodeExpires::on(expirationDate: new Carbon('06/1/2022'))->andRaises();
        } catch (CodeExpirationException $exception) {
            $raised = true;
        }

        $this->assertEquals($shouldRaise, $raised);
    }

    public function envDataProvider()
    {
        return [
            'local' => [true, 'local'],
            'ci' => [true, 'ci'],
            'test' => [true, 'test'],
            'production' => [false, 'production'],
            'empty' => [false, ''],
        ];
    }
}
