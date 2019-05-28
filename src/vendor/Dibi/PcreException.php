<?php

/**
 * This file is part of the Dibi, smart database abstraction layer (https://dibiphp.com)
 * Copyright (c) 2005 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Dibi;

/**
 * PCRE exception.
 */
class PcreException extends Exception
{
	public function __construct(string $message = '%msg.')
	{
		static $messages = [
			PREG_INTERNAL_ERROR => 'Internal error',
			PREG_BACKTRACK_LIMIT_ERROR => 'Backtrack limit was exhausted',
			PREG_RECURSION_LIMIT_ERROR => 'Recursion limit was exhausted',
			PREG_BAD_UTF8_ERROR => 'Malformed UTF-8 data',
			5 => 'Offset didn\'t correspond to the begin of a valid UTF-8 code point', // PREG_BAD_UTF8_OFFSET_ERROR
		];
		$code = preg_last_error();
		parent::__construct(str_replace('%msg', $messages[$code] ?? 'Unknown error', $message), $code);
	}
}
