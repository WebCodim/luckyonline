<?php


namespace app\models\search;

use app\models\Surfer;

/**
 * Class SurferSearch
 * @package app\models\search
 */
class SurferSearch extends Surfer
{
    /** @var string */
    public $startDate;

    /** @var string */
    public $endDate;

    /**
     * SurferSearch constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        if ($this->startDate === null && $this->endDate === null) {
            $this->startDate = $this->getDefaultStartDateTime();
            $this->endDate = $this->getDefaultEndDateTime();
        }
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['startDate', 'endDate'], 'required'],
            [['startDate', 'endDate'], 'date', 'format' => 'yyyy-mm-dd H:i:s'],
            [['startDate', 'endDate'], 'validatePeriod'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
        ];
    }

    public function validatePeriod($attribute)
    {
        if ($this->startDate !== null && $this->endDate !== null) {
            $startDateTimestamp = strtotime($this->startDate);
            $endDateTimestamp = strtotime($this->endDate);

            $diffTime = $endDateTimestamp - $startDateTimestamp;

            if ($diffTime < 1 || $diffTime > 24 * 60 * 60) {
                $this->addError($attribute, 'Period is invalid (1s - 1d)');
            }
        }
    }

    /**
     * @return string|null
     */
    public function getMinDateTime(): ?string
    {
        return self::find()->min('datetime');
    }

    /**
     * @return string|null
     */
    public function getMaxDateTime(): ?string
    {
        return self::find()->max('datetime');
    }

    /**
     * @return string|null
     */
    public function getDefaultEndDateTime(): ?string
    {
        return $this->getMaxDateTime() ?? null;
    }

    /**
     * @return string|null
     */
    public function getDefaultStartDateTime(): ?string
    {
        return  is_null($this->getMaxDateTime())
            ? null
            : date('Y-m-d H:i:s', strtotime($this->getMaxDateTime()) - 12 * 60 * 60);
    }
}