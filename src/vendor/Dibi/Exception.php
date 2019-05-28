<?php

/**
 * This file is part of the Dibi, smart database abstraction layer (https://dibiphp.com)
 * Copyright (c) 2005 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Dibi;

/**
 * Dibi common exception.
 */
class Exception extends \Exception
{
	/** @var string|null */
	private $sql;


	/**
	 * @param  int|string  $code
	 */
	public function __construct(string $message = '', $code = 0, string $sql = null, \Throwable $previous = null)
	{
		parent::__construct($message, 0, $previous);
		$this->code = $code;
		$this->sql = $sql;
	}


	final public function getSql(): ?string
	{
		return $this->sql;
	}


	public function __toString(): string
	{
		return parent::__toString() . ($this->sql ? "\nSQL: " . $this->sql : '');
	}
}
