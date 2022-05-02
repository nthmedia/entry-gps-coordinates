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

use nthmedia\entrygpscoordinates\EntryGpsCoordinates;

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
            new \Twig\TwigFilter('coordinates', [$this, 'coordinates']),
            new \Twig\TwigFilter('latitude', [$this, 'latitude']),
            new \Twig\TwigFilter('longitude', [$this, 'longitude']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('coordinates', [$this, 'coordinates']),
            new \Twig\TwigFunction('latitude', [$this, 'latitude']),
            new \Twig\TwigFunction('longitude', [$this, 'longitude']),
        ];
    }

    /**
     * @param null $value
     *
     * @return string
     */
    public function coordinates($value = null)
    {
        return $value->coordinates;
    }

    /**
     * @param null $value
     *
     * @return string
     */
    public function latitude($value = null)
    {
        return $value->latitude;
    }

    /**
     * @param null $value
     *
     * @return string
     */
    public function longitude($value = null)
    {
        return $value->longitude;
    }
}
