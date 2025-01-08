<?php

namespace Utils;

/**
 * Validation class for input validation.
 *
 * This class provides utility methods for validating input data, including
 * required fields, numeric values, length constraints, valid date/time formats,
 * and positive numbers.
 */
class Validation
{
    /**
     * Convert field names to a human-readable format.
     *
     * This method splits camel case or snake case field names into words and capitalizes the first letter of each word.
     *
     * @param string $field The field name.
     * @return string Human-readable field name.
     */
    private static function formatFieldName(string $field): string
    {
        return ucfirst(strtolower(preg_replace('/([a-z])([A-Z])/', '$1 $2', $field)));
    }

    /**
     * Validate required fields in the request data.
     *
     * @param array $data The request data.
     * @param array $fields The required field names.
     * @return array An associative array of errors (field name => error message).
     */
    public static function requiredFields(array $data, array $fields): array
    {
        $errors = [];

        foreach ($fields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                $formattedField = self::formatFieldName($field);
                $errors[$field] = "$formattedField is required.";
            }
        }

        return $errors;
    }

    /**
     * Validate that the specified fields contain numeric values.
     *
     * @param array $data The request data.
     * @param array $fields The fields to validate as numbers.
     * @return array An associative array of errors (field name => error message).
     */
    public static function number(array $data, array $fields): array
    {
        $errors = [];

        foreach ($fields as $field) {
            if (!is_numeric($data[$field])) {
                $formattedField = self::formatFieldName($field);
                $errors[$field] = "$formattedField must be a number.";
            }
        }

        return $errors;
    }

    /**
     * Validate length constraints for specified fields.
     *
     * @param array $data The request data.
     * @param array $fields Associative array of fields with min and max length constraints.
     * @return array An associative array of errors (field name => error message).
     */
    public static function length(array $data, array $fields): array
    {
        $errors = [];

        foreach ($fields as $field => $constraints) {
            if (isset($data[$field])) {
                $length = strlen($data[$field]);
                $formattedField = self::formatFieldName($field);

                if (isset($constraints['min']) && $length < $constraints['min']) {
                    $errors[$field] = "$formattedField must be at least {$constraints['min']} characters.";
                }

                if (isset($constraints['max']) && $length > $constraints['max']) {
                    $errors[$field] = "$formattedField must not exceed {$constraints['max']} characters.";
                }
            }
        }

        return $errors;
    }

    /**
     * Validate that the specified fields contain valid date/time values.
     *
     * @param array $data The request data.
     * @param array $fields The fields to validate as date/time.
     * @return array An associative array of errors (field name => error message).
     */
    public static function validDateTime(array $data, array $fields): array
    {
        $errors = [];

        foreach ($fields as $field) {
            if (!strtotime($data[$field])) {
                $formattedField = self::formatFieldName($field);
                $errors[$field] = "$formattedField must be a valid date/time.";
            }
        }

        return $errors;
    }

    /**
     * Validate that the specified fields contain positive numeric values.
     *
     * @param array $data The request data.
     * @param array $fields The fields to validate as positive numbers.
     * @return array An associative array of errors (field name => error message).
     */
    public static function positiveNumber(array $data, array $fields): array
    {
        $errors = [];

        foreach ($fields as $field) {
            if (isset($data[$field]) && $data[$field] <= 0) {
                $formattedField = self::formatFieldName($field);
                $errors[$field] = "$formattedField must be a positive number.";
            }
        }

        return $errors;
    }
}
