<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - {{ __('messages.page_not_found') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('tema/css/404.css') }}">
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">{{ __('messages.page_not_found') }}</div>
        <div class="error-description">
            {{ __('messages.page_not_found_description') }}
        </div>
        <a href="{{ url('/') }}" class="btn-home">{{ __('messages.return_home') }}</a>
    </div>
</body>
</html>
