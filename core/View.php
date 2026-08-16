<?php
declare(strict_types=1);

final class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewFile = APP_ROOT . '/views/' . $view . '.php';

        if (!is_file($viewFile)) {
            http_response_code(500);
            throw new RuntimeException("View not found: {$view}");
        }

        require APP_ROOT . '/views/layouts/header.php';
        require $viewFile;
        require APP_ROOT . '/views/layouts/footer.php';
    }
}
