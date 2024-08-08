<?php

namespace App\Enum;

enum StatusEnum: string
{

    const AVAILABLE = 'available';

    const RESERVED = 'reserved';

    const BORROWED = 'borrowed';

    const OVERDUE = 'overdue';

    const RETURNED = 'returned';
}
