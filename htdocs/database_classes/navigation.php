<?php

class Navigation
{
    /* 
    The following equation where φ is latitude, λ is longitude, R is earth’s radius (mean radius = 6,371km)
    is how we translate the above formula to include latitude and longitude coordinates. Note that angles need
    to be in radians to pass to trig functions:
        a = sin²(φB - φA/2) + cos φA * cos φB * sin²(λB - λA/2)
        c = 2 * atan2( √a, √(1−a) )
        d = R ⋅ c  
    */   
    public function haversineDistance($latitudePointADeg, $longitudePointADeg, $latitudePointBDeg, $longitudePointBDeg)
    {
        $distance = 3;

        $meanRadius = 6371;

        $latitudePointARad = deg2rad ( $latitudePointADeg );
        $longitudePointARad = deg2rad ($longitudePointADeg);

        $latitudePointBRad = deg2rad($latitudePointBDeg);
        $longitudePointBRad = deg2rad($longitudePointBDeg);

        # Δφ = φB - φA
        $latDifferenceRad = abs($latitudePointBRad - $latitudePointARad);

        # Δλ = λB - λA
        $longDifferenceRad = abs($longitudePointBRad - $longitudePointARad);

        

        #a = sin²(φA/2) + cos φA * cos φB * sin²(Δλ/2)
        $stepOne = (
            #sin²(Δφ/2)
            ((sin($latDifferenceRad/2))*(sin($latDifferenceRad/2)))
            +
            #cos φA * cos φB
            cos($latitudePointARad) * cos($latitudePointBRad)
            *
            #sin²(Δλ/2)
            (sin($longDifferenceRad/2))*(sin($longDifferenceRad/2))
        );

        #c = 2 * atan2( √a, √(1−a) )
        $stepTwo = 2 * asin(sqrt($stepOne));

        #d = R ⋅ c
        $distance = $stepTwo * $meanRadius;




        return $distance;
    }


}
?>