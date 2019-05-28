<?php

/**
 * This file is part of the Dibi, smart database abstraction layer (https://dibiphp.com)
 * Copyright (c) 2005 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Dibi;

/**
 * Database procedure exception.
 */
class ProcedureException extends Exception
{
	/** @var string */
	protected $severity;


	/**
	 * Construct the exception.
	 */
	public function __construct(string $message = '', int $code = 0, string $severity = '', string $sql = null)
	{
		parent::__construct($message, $code, $sql);
		$this->severity = $severity;
	}


	/**
	 * Gets the exception severity.
	 */
	public function getSeverity(): string
	{
		return $this->severity;
	}
}
