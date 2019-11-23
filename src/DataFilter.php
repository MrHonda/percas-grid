<?php

declare(strict_types=1);


namespace Percas\Grid;


class DataFilter
{
    public const PLACEHOLDER_PREFIX = ':';

    public const OPERATOR_LESS = '<';
    public const OPERATOR_GREATER = '>';
    public const OPERATOR_EQUAL = '=';
    public const OPERATOR_LESS_EQUAL = '<=';
    public const OPERATOR_GREATER_EQUAL = '>=';
    public const OPERATOR_LIKE = 'LIKE';

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $placeholder;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var int
     */
    private static $index = 1;

    /**
     * DataFilter constructor.
     * @param string $key
     * @param string $operator
     * @param mixed $value
     */
    public function __construct(string $key, string $operator, $value)
    {
        $this->key = $key;
        $this->operator = $operator;
        $this->value = $value;
        $this->placeholder = 'filterVal' . self::$index++;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $prefix
     * @return string
     */
    public function getPlaceholder(string $prefix = self::PLACEHOLDER_PREFIX): string
    {
        return $prefix . $this->placeholder;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $placeholderPrefix
     * @return string
     */
    public function getSqlCondition(string $placeholderPrefix = self::PLACEHOLDER_PREFIX): string
    {
        return $this->getKey() . ' ' . $this->getOperator() . ' ' . $this->getPlaceholder($placeholderPrefix);
    }
}
