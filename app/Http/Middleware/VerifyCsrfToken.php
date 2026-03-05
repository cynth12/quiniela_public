<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
    'webhook/mp',
    '/webhook/mp',
];
    protected function tokensMatch($request)
    {
        \Log::info('VerifyCsrfToken ejecutado, except: ', $this->except);
        return parent::tokensMatch($request);
    }
}
