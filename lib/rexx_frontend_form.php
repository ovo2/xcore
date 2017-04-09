<?php

class rexx_frontend_form {
	// sanitize types for rexx_frontend_form::sanitizeFormValue()
	const SANITIZE_TYPE_STRING = 1;
	const SANITIZE_TYPE_EMAIL = 2;
	const SANITIZE_TYPE_INT = 3;
	const SANITIZE_TYPE_URL = 4;

	// validate types for rexx_frontend_form::validateFormValue()
	const VALIDATE_TYPE_NOT_EMPTY = 1;
	const VALIDATE_TYPE_EMPTY = 2;
	const VALIDATE_TYPE_EMAIL = 3;
	const VALIDATE_TYPE_INT = 4;
	const VALIDATE_TYPE_URL = 5;

	/**
	 * Sanitizes a form value by given sanitize type constant. 
	 *
	 * @param string $value
	 * @param int $sanitizeType
	 *
	 * @return string
	 *
	 */
	public static function sanitizeFormValue($value, $sanitizeType) {
		$filterType = 0;

		switch ($sanitizeType) {
			case rexx::SANITIZE_TYPE_STRING:
				$filterType = FILTER_SANITIZE_STRING;
				break;	
			case rexx::SANITIZE_TYPE_EMAIL:
				$filterType = FILTER_SANITIZE_EMAIL;
				break;
			case rexx::SANITIZE_TYPE_INT:
				$filterType = FILTER_SANITIZE_INT;
				break;
			case rexx::SANITIZE_TYPE_URL:
				$filterType = FILTER_SANITIZE_URL;
				break;
			default:
				throw new InvalidArgumentException('Value of $sanitizeType in sanitizeFormValue() call not recongized!');
		}

		if (isset($_POST[$value])) {
			$data = $_POST[$value];
			$data = filter_var($data, $filterType);

			if ($data !== false) {
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);

				return $data;
			}
		} 

		return '';
	}

	/**
	 * Validates a form value by given validate type constant.
	 *
	 * @param string $data
	 * @param int $validateType
	 *
	 * @return bool
	 *
	 */
	public static function validateFormValue($data, $validateType) {
		$isValid = false;

		switch ($validateType) {
			case rexx::VALIDATE_TYPE_EMAIL:
				if (filter_var($data, FILTER_VALIDATE_EMAIL) !== false) {
					$isValid = true;
				}
				break;	
			case rexx::VALIDATE_TYPE_INT:
				if (filter_var($data, FILTER_VALIDATE_INT) !== false) {
					$isValid = true;
				}
				break;
			case rexx::VALIDATE_TYPE_URL:
				if (filter_var($data, FILTER_VALIDATE_URL) !== false) {
					$isValid = true;
				}
				break;
			case rexx::VALIDATE_TYPE_NOT_EMPTY:
				if (!empty($data)) {
					$isValid = true;
				}
				break;
			case rexx::VALIDATE_TYPE_EMPTY:
				if (empty($data)) {
					$isValid = true;
				}
				break;
			default:
				throw new InvalidArgumentException('Value of $validateType in validateFormValue() call not recongized!');			
		}

		return $isValid;
	}

	/**
	 * Helper for getting validate class if $value in $valueArray.
	 *
	 * @param string $value
	 * @param string[] $valueArray
	 *
	 * @return string
	 *
	 */
	public static function getValidateAlertClass($value, $valueArray, $validateClass = 'validate-alert') {
		if (isset($valueArray[$value])) { 
			return $validateClass;
		} else {
			return '';
		}
	}

	/**
	 * Helper for getting required string with extra required span tag.
	 *
	 * @param string $key
	 * @param bool $required
	 * @param string $requiredClass
	 * @param string $requiredContent
	 *
	 * @return string
	 *
	 */
	public static function getRequiredString($key, $required = false, $requiredClass = 'required', $requiredContent = '*') {
		if ($required) {
			return rexx::getString($key) . ' <span class="' . $requiredClass . '">' . $requiredContent . '</span>';
		} else {
			return rexx::getString($key);
		}
	}

	/**
	 * Helper for getting required placeholder string with extra required content string.
	 *
	 * @param string $key
	 * @param bool $required
	 * @param string $requiredContent
	 *
	 * @return string
	 *
	 */
	public static function getRequiredPlaceholderString($key, $required = false, $requiredContent = '*') {
		if ($required) {
			return rexx::getString($key) . $requiredContent;
		} else {
			return rexx::getString($key);
		}
	}
}

