<?php

namespace App\Service;

class DistanceCalculator
{
    /**
     * Calcule la distance entre deux points (latitude, longitude) en kilomètres
     * en utilisant la formule haversine.
     *
     * @param float $lat1 Latitude du premier point
     * @param float $lon1 Longitude du premier point
     * @param float $lat2 Latitude du deuxième point
     * @param float $lon2 Longitude du deuxième point
     * @return float Distance en kilomètres
     */
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Rayon de la Terre en kilomètres

        // Convertir les degrés en radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Différences des coordonnées
        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        // Formule haversine
        $a = sin($dLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Calcul de la distance
        return $earthRadius * $c;
    }
}