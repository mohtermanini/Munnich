<?php

namespace Mohte\DriverLogbook\Resources;

use Utils\Sanitizer;

class TripResource
{
    /**
     * Format and sanitize trip data.
     * 
     * @param array $trips
     * @return array
     */
    public static function format(array $trips): array
    {
        // Sanitize each trip before returning the data
        foreach ($trips as &$trip) {
            if (isset($trip['purpose'])) {
                $trip['purpose'] = Sanitizer::sanitizeString($trip['purpose']);
            }

            if (isset($trip['departure_location'])) {
                $trip['departure_location'] = Sanitizer::sanitizeString($trip['departure_location']);
            }
            if (isset($trip['arrival_location'])) {
                $trip['arrival_location'] = Sanitizer::sanitizeString($trip['arrival_location']);
            }
        }

        return $trips;
    }

    
}
