<?php

/** API Response error formaterss */
defined("API_ERR") ||
	define("API_ERR", [
		400 => fn($msg = null) => !empty($msg) ? "CIV_ERR: {$msg}" : "CIV_ERR: They was an errory while processing your request.",
		404 => fn($msg = null) => !empty($msg) ? "RES_ERR: {$msg}" : "RES_ERR: The requested resources was not found or has been renamed.",
		500 => fn($msg = null) => !empty($msg) ? "SER_ERR: {$msg}" : "SER_ERR: They was an error with the servers.",
		501 => fn($msg = null) => !empty($msg) ? "SER_ERR: {$msg}" : "SER_ERR: They was an error with the servers.",
		502 => fn($msg = null) => !empty($msg) ? "SER_ERR: {$msg}" : "SER_ERR: They was an error with the servers.",
		503 => fn($msg = null) => !empty($msg) ? "SER_ERR: {$msg}" : "SER_ERR: They was an error with the servers.",
		504 => fn($msg = null) => !empty($msg) ? "SER_ERR: {$msg}" : "SER_ERR: They was an error with the servers.",
	]);