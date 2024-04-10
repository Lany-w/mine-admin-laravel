<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/8 15:41
 */

namespace Lany\MineAdmin\Command;

use Illuminate\Console\Command;

class MineCommand extends Command
{
    protected const CONSOLE_GREEN_BEGIN = "\033[32;5;1m";

    protected const CONSOLE_RED_BEGIN = "\033[31;5;1m";
    protected const CONSOLE_END = "\033[0m";
    protected function getGreenText($text): string
    {
        return self::CONSOLE_GREEN_BEGIN . $text . self::CONSOLE_END;
    }

    protected function getRedText($text): string
    {
        return self::CONSOLE_RED_BEGIN . $text . self::CONSOLE_END;
    }

    protected function getInfo(): string
    {
        return sprintf('
/---------------------- welcome to use -----------------------\
|               _                ___       __          _      |
|    ____ ___  (_)___  _____    /   | ____/ /___ ___  (_)___  |
|   / __ `__ \/ / __ \/ ___/   / /| |/ __  / __ `__ \/ / __ \ |
|  / / / / / / / / / / /__/   / ___ / /_/ / / / / / / / / / / |
| /_/ /_/ /_/_/_/ /_/\___/   /_/  |_\__,_/_/ /_/ /_/_/_/ /_/  |
|                                                             |
\_____________  Copyright MineAdmin 2021 ~ %s  _____________|
', date('Y'));
    }
}