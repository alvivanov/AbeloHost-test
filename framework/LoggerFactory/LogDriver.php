<?php

declare(strict_types=1);

namespace Framework\LoggerFactory;

enum LogDriver: string
{
    case ROTATING_FILE = 'rotating_file';
    case STDOUT = 'stdout';
}
