<?php

    namespace App\Enums;

    enum CategoryEnum: int
    {
        case Electronics = 1;
        case Fashion = 2;
        case Sports = 3;

        public function category_title(): string
        {
            return match ($this) {
                self::Electronics => 'ელექტროობა',
                self::Fashion => 'მოდა',
                self::Sports => 'სპორტი',
            };
        }
    }
