<?php

namespace App\Core\Routing;

class Route
{
	private array $variables = [];

	private string $method;
	private string $URI;
	//private BaseController $controller;
	private \Closure $action;

	public function __construct(
		string $method,
		string $URI,
		\Closure $action
	)
	{
		$this->action = $action;
		$this->URI = $URI;
		$this->method = $method;
	}

	// TODO: change regexp
	public function match($URI) : bool
	{
		if ($URI !== "/")
		{
			$URI = rtrim($URI, "/");
		}
		$regexpVar = '([A-Za-z0-9-_]+)';
		$regexp = '#^' . preg_replace('(:[A-Za-z]+)', $regexpVar, $this->URI) . '$#';

		$matches = [];
		$result = preg_match($regexp, $URI, $matches);

		if ($result)
		{
			array_shift($matches);
			$this->variables = $matches;
		}

		return $result;
	}

	public function getMethod(): string
	{
		return $this->method;
	}

	public function getURI(): string
	{
		return $this->URI;
	}

	public function getAction(): \Closure
	{
		return $this->action;
	}

	public function getVariables(): array
	{
		return $this->variables;
	}
}