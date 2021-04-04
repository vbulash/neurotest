<?php

    use App\Models\Blocks\Auth_Full;
    use App\Models\Blocks\Auth_Guest;
    use App\Models\Blocks\Auth_PKey;
    use App\Models\Blocks\Image;
    use App\Models\Blocks\Images2;
    use App\Models\Blocks\Images4;
    use App\Models\Blocks\Results_Show;

    return [
        // Готовые модули тестов
        // Вступительные блоки
        'auth_guest' => Auth_Guest::class,
        'auth_full' => Auth_Full::class,
        'auth_pkey' => Auth_PKey::class,
        // Основные блоки теста
        'images' => Image::class,
        'images2' => Images2::class,
        'images4' => Images4::class,
        // TODO Завершить цепочкку блоков теста
        // ...
        // Завершение теста
        'results_show' => Results_Show::class,
        'results_mail' => \App\Models\Blocks\Results_Mail::class,
    ];
