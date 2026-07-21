<?php
declare(strict_types=1);

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->render('auth/login', [
            'pageTitle' => 'Welcome Back | Mindful',
            'pageStyles' => ['login'],
        ]);
    }
}
