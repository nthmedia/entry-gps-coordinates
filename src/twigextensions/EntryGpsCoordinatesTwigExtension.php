<?php

/**
 * Entry GPS Coordinates plugin for Craft CMS 3.x
 *
 * Pick a GPS location for an entry
 *
 * @link      https://nthmedia.nl
 * @copyright Copyright (c) 2019 NTH media
 */

namespace nthmedia\entrygpscoordinates\twigextensions;

use nthmedia\entrygpscoordinates\models\EntryCoordinatesModel;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @author    nthmedia
 * @package   EntryGpsCoordinates
 * @since     1
 */
class EntryGpsCoordinatesTwigExtension extends \Twig\Extension\AbstractExtension
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Entry GPS Coordinates';
    }

    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        return [
            new TwigFilter('coordinates', [$this, 'coordinates']),
            new TwigFilter('address', [$this, 'address']),
            new TwigFilter('searchQuery', [$this, 'searchQuery']),
            new TwigFilter('latitude', [$this, 'latitude']),
            new TwigFilter('longitude', [$this, 'longitude']),
            new TwigFilter('zoomLevel', [$this, 'zoomLevel']),
            new TwigFilter('streetNumber', [$this, 'streetNumber']),
            new TwigFilter('route', [$this, 'route']),
            new TwigFilter('locality', [$this, 'locality']),
            new TwigFilter('postalCode', [$this, 'postalCode']),
            new TwigFilter('country', [$this, 'country']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('coordinates', [$this, 'coordinates']),
            new TwigFunction('address', [$this, 'address']),
            new TwigFunction('searchQuery', [$this, 'searchQuery']),
            new TwigFunction('latitude', [$this, 'latitude']),
            new TwigFunction('longitude', [$this, 'longitude']),
            new TwigFunction('zoomLevel', [$this, 'zoomLevel']),
            new TwigFunction('streetNumber', [$this, 'streetNumber']),
            new TwigFunction('route', [$this, 'route']),
            new TwigFunction('locality', [$this, 'locality']),
            new TwigFunction('postalCode', [$this, 'postalCode']),
            new TwigFunction('country', [$this, 'country']),
        ];
    }

    /**
     * @param ?EntryCoordinatesModel $value
     *
     * @return string
     */
    public function coordinates(?EntryCoordinatesModel $value = null)
    {
        return $value->coordinates;
    }

    /**
     * @param ?EntryCoordinatesModel $value
     *
     * @return string
     */
    public function latitude(?EntryCoordinatesModel $value = null)
    {
        return $value->getLatitude();
    }

    /**
     * @param ?EntryCoordinatesModel $value
     *
     * @return string
     */
    public function longitude(?EntryCoordinatesModel $value = null)
    {
        return $value->getLongitude();
    }

    /**
     * @param ?EntryCoordinatesModel $value
     *
     * @return string
     */
    public function searchQuery(?EntryCoordinatesModel $value = null)
    {
        return $value->searchQuery;
    }

    /**
     * @param ?EntryCoordinatesModel $value
     *
     * @return string
     */
    public function address(?EntryCoordinatesModel $value = null)
    {
        return $value->address;
    }

    public function zoomLevel(?EntryCoordinatesModel $value = null)
    {
        return $value->zoomLevel;
    }

    public function streetNumber(?EntryCoordinatesModel $value = null)
    {
        return $value->streetNumber;
    }

    public function route(?EntryCoordinatesModel $value = null)
    {
        return $value->route;
    }

    public function locality(?EntryCoordinatesModel $value = null)
    {
        return $value->locality;
    }

    public function postalCode(?EntryCoordinatesModel $value = null)
    {
        return $value->postalCode;
    }

    public function country(?EntryCoordinatesModel $value = null)
    {
        return $value->country;
    }
}
