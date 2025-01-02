<?php

use Illuminate\Auth\Middleware\EnsureEmailIsVerified;

return [
    'verified' => EnsureEmailIsVerified::class,
];
// Compare this snippet from config/auth.php: