<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\blitz\models;

use craft\base\Model;
use craft\helpers\ConfigHelper;
use craft\helpers\StringHelper;
use craft\validators\DateTimeValidator;
use DateTime;

/**
 * @property int|null $cacheDuration
 */
class CacheOptionsModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var bool
     */
    public $cachingEnabled = true;

    /**
     * @var bool
     */
    public $cacheElements = true;

    /**
     * @var bool
     */
    public $cacheElementQueries = true;

    /**
     * @var int|bool
     */
    public $outputComments = true;

    /**
     * @var string[]|null
     */
    public $tags;

    /**
     * @var int|null
     */
    public $paginate;

    /**
     * @var DateTime|null
     */
    public $expiryDate;

    /**
     * @var int|null
     */
    private $_cacheDuration;


    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case 'cacheDuration':
                $this->cacheDuration($value);
                break;
            case 'tags':
                $this->tags($value);
                break;
            default:
                parent::__set($name, $value);
        }
    }

    /**
     * @inheritdoc
     */
    public function attributes(): array
    {
        $names = parent::attributes();

        return array_merge($names, ['cacheDuration']);
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['cachingEnabled', 'cacheElements', 'cacheElementQueries'], 'boolean'],
            [['paginate'], 'integer'],
            [['expiryDate'], DateTimeValidator::class],
        ];
    }

    /**
     * @return int|null
     */
    public function getCacheDuration()
    {
        return $this->_cacheDuration;
    }

    /**
     * @param bool $value
     *
     * @return static self reference
     */
    public function cachingEnabled(bool $value): self
    {
        $this->cachingEnabled = $value;

        return $this;
    }

    /**
     * @param bool $value
     *
     * @return static self reference
     */
    public function cacheElements(bool $value): self
    {
        $this->cacheElements = $value;

        return $this;
    }

    /**
     * @param bool $value
     *
     * @return static self reference
     */
    public function cacheElementQueries(bool $value): self
    {
        $this->cacheElementQueries = $value;

        return $this;
    }

    /**
     * @param int|bool $value
     *
     * @return static self reference
     */
    public function outputComments($value): self
    {
        $this->outputComments = $value;

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return static self reference
     */
    public function cacheDuration($value): self
    {
        // Set cache duration if greater than 0 seconds
        $cacheDuration = ConfigHelper::durationInSeconds($value);

        if ($cacheDuration > 0) {
            $this->_cacheDuration = $cacheDuration;

            $timestamp = $cacheDuration + time();

            // Prepend with @ symbol to specify a timestamp
            $this->expiryDate = new DateTime('@'.$timestamp);
        }

        return $this;
    }

    /**
     * @param string|string[]|null $value
     *
     * @return static self reference
     */
    public function tags($value): self
    {
        $this->tags = is_string($value) ? StringHelper::split($value) : $value;

        return $this;
    }

    /**
     * @param int|null $value
     *
     * @return static self reference
     */
    public function paginate(int $value = null): self
    {
        $this->paginate = $value;

        return $this;
    }

    /**
     * @param DateTime|null $value
     *
     * @return static self reference
     */
    public function expiryDate(DateTime $value = null): self
    {
        $this->expiryDate = $value;

        return $this;
    }
}
