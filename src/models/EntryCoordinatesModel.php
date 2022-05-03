<?php

namespace nthmedia\entrygpscoordinates\models;

use yii\base\Model as BaseModel;

class EntryCoordinatesModel extends BaseModel
{
    protected const validPattern = '/^([-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)),\s*([-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?))$/';

    public ?string $coordinates = null;
    public ?string $searchQuery = null;
    public ?string $address = null;
    public ?string $latitude = null;
    public ?string $longitude = null;

    public function __construct($attributes = [], array $config = [])
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this[$key] = $value;
            }
        }
        parent::__construct($config);
    }

    /**
     * @return ?float
     */
    public function getLatitude(): ?float
    {
        if ($this->coordinates !== null && preg_match(self::validPattern, $this->coordinates, $matches)) {
            return (float)$matches[1];
        }

        return null;
    }

    /**
     * @return ?float
     */
    public function getLongitude(): ?float
    {
        if ($this->coordinates !== null && preg_match(self::validPattern, $this->coordinates, $matches)) {
            return (float)$matches[5];
        }

        return null;
    }
}
