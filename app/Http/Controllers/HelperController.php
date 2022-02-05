<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Str;

    class HelperController extends Controller
    {
        public function generatePassword(int $length = 8): array
        {
            return [
                'password' => Str::random($length)
            ];
        }

        public function getPhpInfo()
        {
            phpinfo();
        }

        public static function digitsLetter(int $count): ?string
        {
            return null;
        }
    }
