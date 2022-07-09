<?php

if (!function_exists("addPathParam")) {
	/**
	 * Search and replay path params
	 *
	 * @param string $path
	 * @param string|int|float|null|null $param
	 *
	 * @return void
	 */
	function addPathParam(string $path, string|int|float|null $param = null)
	{
		if (!empty($param)) {
			if (str_contains($path, ":pathParam")) {
				return str_replace(":pathParam", $param, $path);
			}
		}

		return $path;
	}
}