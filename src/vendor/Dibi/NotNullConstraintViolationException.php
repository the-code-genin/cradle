<?php

/**
 * This file is part of the Dibi, smart database abstraction layer (https://dibiphp.com)
 * Copyright (c) 2005 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Dibi;

/**
 * Exception for a NOT NULL constraint violation.
 */
class NotNullConstraintViolationException extends ConstraintViolationException
{
}
