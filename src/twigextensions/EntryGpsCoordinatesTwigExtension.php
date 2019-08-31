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

use Craft;

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
        if (array_key_exists('coordinates', $value)) {
            return $value['coordinates'];
        }

        return $value;
    }

    /**
     * @param null $value
     *
     * @return string
     */
    public function latitude($value = null)
    {
        if (array_key_exists('coordinates', $value) && preg_match('/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/', $value['coordinates'], $matches)) {
            return $matches[1];
        }

        return $value;
    }

    /**
     * @param null $value
     *
     * @return string
     */
    public function longitude($value = null)
    {
        if (array_key_exists('coordinates', $value) && preg_match('/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/', $value['coordinates'], $matches)) {
            return $matches[4];
        }

        return $value;
    }
}
