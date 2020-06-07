<?php
namespace App\Helpers;

use Exception;

class Helper {
    public static function insideArea($point, $fenceArea) {
        $x = $point['lat']; $y = $point['lng'];
    
        $inside = false;
        for ($i = 0, $j = count($fenceArea) - 1; $i <  count($fenceArea); $j = $i++) {
            $xi = $fenceArea[$i]['lat']; $yi = $fenceArea[$i]['lng'];
            $xj = $fenceArea[$j]['lat']; $yj = $fenceArea[$j]['lng'];
    
            $intersect = (($yi > $y) != ($yj > $y))
                && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
            if ($intersect) $inside = !$inside;
        }
    
        return $inside;
    }

    /**
     * isNumber
     *
     * @param {*} num Number to validate
     * @returns {boolean} true/false
     * @example
     * turf.isNumber(123)
     * //=true
     * turf.isNumber('foo')
     * //=false
     */
    public static function isNumber($num) {
        return !is_nan($num) && $num !== null && !is_array($num);
    }

    /**
     * Checks if coordinates contains a number
     *
     * @name containsNumber
     * @param {Array<any>} coordinates GeoJSON Coordinates
     * @returns {boolean} true if Array contains a number
     */
    public static function containsNumber($coordinates) {
        
        if (count($coordinates) > 1 && is_array($coordinates) &&  isset($coordinates[0]) ) {
            return true;
        }

        if (is_array($coordinates) &&  isset($coordinates[0]) && count($coordinates[0]) > 0) {
            return Helper::containsNumber($coordinates[0]);
        }
        dd($coordinates);
        throw new Exception('coordinates must only contain numbers');
    }

    /**
     * Unwrap coordinates from a Feature, Geometry Object or an Array of numbers
     *
     * @name getCoords
     * @param {Array<number>|Geometry|Feature} obj Object
     * @returns {Array<number>} coordinates
     * @example
     * $poly = turf.polygon([[[119.32, -8.7], [119.55, -8.69], [119.51, -8.54], [119.32, -8.7]]]);
     *
     * $coord = turf.getCoords(poly);
     * //= [[[119.32, -8.7], [119.55, -8.69], [119.51, -8.54], [119.32, -8.7]]]
     */
    public static function getCoords($obj) {
        if (!$obj) throw new Exception('obj is required');
        $coordinates = [];

       
        if (isset($obj['coordinates'])) {
            $coordinates = $obj['coordinates'];

        // Feature
        } else if ($obj['geometry'] && $obj['geometry']['coordinates']) {
            $coordinates = $obj['geometry']['coordinates'];
        } else {
            $coordinates = $obj;
        }
        // Checks if coordinates contains a number
        if ($coordinates) {
            Helper::containsNumber($coordinates);
            return $coordinates;
        }
        throw new Exception('No valid coordinates');
    }

    /**
     * Unwrap a coordinate from a Point Feature, Geometry or a single coordinate.
     *
     * @name getCoord
     * @param {Array<number>|Geometry<Point>|Feature<Point>} obj Object
     * @returns {Array<number>} coordinates
     * @example
     * $pt = turf.point([10, 10]);
     *
     * $coord = turf.getCoord(pt);
     * //= [10, 10]
     */
    public static function getCoord($obj) {
        if (!$obj) throw new Exception('obj is required');

        $coordinates = Helper::getCoords($obj);

        // getCoord() must contain at least two numbers (Point)
        if (count($coordinates) > 1 && Helper::isNumber($coordinates[0]) && Helper::isNumber($coordinates[1])) {
            return $coordinates;
        } else {
            throw new Exception('Coordinate is not a valid Point');
        }
    }
    /**
     * Takes a {@link Point} and a {@link Polygon} or {@link MultiPolygon} and determines if the point resides inside the polygon. The polygon can
     * be convex or concave. The function accounts for holes.
     *
     * @name booleanPointInPolygon
     * @param {Coord} point input point
     * @param {Feature<Polygon|MultiPolygon>} polygon input polygon or multipolygon
     * @param {Object} [options={}] Optional parameters
     * @param {boolean} [options.ignoreBoundary=false] True if polygon boundary should be ignored when determining if the point is inside the polygon otherwise false.
     * @returns {boolean} `true` if the Point is inside the Polygon; `false` if the Point is not inside the Polygon
     * @example
     * $pt = turf.point([-77, 44]);
     * $poly = turf.polygon([[
     *   [-81, 41],
     *   [-81, 47],
     *   [-72, 47],
     *   [-72, 41],
     *   [-81, 41]
     * ]]);
     *
     * turf.booleanPointInPolygon(pt, poly);
     * //= true
     */
    public static function booleanPointInPolygon($point, $polygon, $options) {
        // Optional parameters
        $options = $options || [];
        //if (!is_array($options)) throw new Exception('options is invalid');
        $ignoreBoundary = $options['ignoreBoundary'];

        // validation
        if (!$point) throw new Exception('point is required');
        if (!$polygon) throw new Exception('polygon is required');

        $pt = Helper::getCoord($point);
        $polys =  Helper::getCoords($polygon);
        $type = ($polygon['geometry']) ? $polygon['geometry']['type'] : $polygon['type'];
        $bbox = $polygon['bbox'] ?? null;

        // Quick elimination if point is not inside bbox
        if ($bbox && Helper::inBBox($pt, $bbox) === false) return false;

        // normalize to multipolygon
        if ($type === 'Polygon') $polys = [$polys];

        for ($i = 0, $insidePoly = false; $i < count($polys) && !$insidePoly; $i++) {
            // check if it is in the outer ring first
            if (Helper::inRing($pt, $polys[$i][0], $ignoreBoundary)) {
                $inHole = false;
                $k = 1;
                // check for the point in any of the holes
                while ($k < count($polys[$i]) && !$inHole) {
                    if (Helper::inRing($pt, $polys[$i][$k], !$ignoreBoundary)) {
                        $inHole = true;
                    }
                    $k++;
                }
                if (!$inHole) $insidePoly = true;
            }
        }
        return $insidePoly;
    }

    /**
     * inRing
     *
     * @private
     * @param {Array<number>} pt [x,y]
     * @param {Array<Array<number>>} ring [[x,y], [x,y],..]
     * @param {boolean} ignoreBoundary ignoreBoundary
     * @returns {boolean} inRing
     */
    public static function inRing($pt, $ring, $ignoreBoundary) {
        $isInside = false;
        
        if ($ring[0][0] === $ring[count($ring) - 1][0] && $ring[0][1] === $ring[count($ring) - 1][1]) $ring = array_slice($ring,0,count($ring)- 1);
        

        for ($i = 0, $j = count($ring) - 1; $i < count($ring); $j = $i++) {
            $xi = $ring[$i][0];
            $yi = $ring[$i][1];
            $xj = $ring[$j][0];
            $yj = $ring[$j][1];
            $onBoundary = ($pt[1] * ($xi - $xj) + $yi * ($xj - $pt[0]) + $yj * ($pt[0] - $xi) === 0) &&
                (($xi - $pt[0]) * ($xj - $pt[0]) <= 0) && (($yi - $pt[1]) * ($yj - $pt[1]) <= 0);
            if ($onBoundary) return !$ignoreBoundary;
            $intersect = (($yi > $pt[1]) !== ($yj > $pt[1])) &&
            ($pt[0] < ($xj - $xi) * ($pt[1] - $yi) / ($yj - $yi) + $xi);
            if ($intersect) $isInside = !$isInside;
        }
        return $isInside;
    }


    /**
     * inBBox
     *
     * @private
     * @param {Position} pt point [x,y]
     * @param {BBox} bbox BBox [west, south, east, north]
     * @returns {boolean} true/false if point is inside BBox
     */
    public static function inBBox($pt, $bbox) {
        return $bbox[0] <= $pt[0] &&
            $bbox[1] <= $pt[1] &&
            $bbox[2] >= $pt[0] &&
            $bbox[3] >= $pt[1];
    }

    /**
     * Wraps a GeoJSON {@link Geometry} in a GeoJSON {@link Feature}.
     *
     * @name feature
     * @param {Geometry} geometry input geometry
     * @param {Object} [properties={}] an Object of key-value pairs to add as properties
     * @param {Object} [options={}] Optional Parameters
     * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north] associated with the Feature
     * @param {string|number} [options.id] Identifier associated with the Feature
     * @returns {Feature} a GeoJSON Feature
     * @example
     * $geometry = {
     *   "type": "Point",
     *   "coordinates": [110, 50]
     * };
     *
     * $feature = turf.feature(geometry);
     *
     * //=feature
     */
    public static function feature($geometry, $properties, $options) {
        // Optional Parameters
        $options = $options || [];
        //if (!is_array($options)) throw new Exception('options is invalid');
        $bbox = $options['bbox'];
        $id = $options['id'];

        // Validation
        if (!$geometry) throw new Exception('geometry is required');
        if ($properties && !$properties['constructor']) throw new Exception('properties must be an Object');
        if ($bbox) Helper::validateBBox($bbox);
        if ($id) Helper::validateId($id);

        // Main
        $feat = ["type"=> 'Feature'];
        if ($id) $feat['id'] = $id;
        if ($bbox) $feat['bbox'] = $bbox;
        $feat['properties'] = $properties || [];
        $feat['geometry'] = $geometry;
        return $feat;
    }

    public static function point($coordinates, $properties, $options) {
        if (!$coordinates) throw new Exception('coordinates is required');
        if (!is_array($coordinates)) throw new Exception('coordinates must be an Array');
        if (count($coordinates) < 2) throw new Exception('coordinates must be at least 2 numbers long');
        if (!Helper::isNumber($coordinates[0]) || !Helper::isNumber($coordinates[1])) throw new Exception('coordinates must contain numbers');
    
        return Helper::feature([
            "type"=>"Point",
            "coordinates"=>$coordinates
        ],$properties, $options);
    }

    /**
     * Validate BBox
     *
     * @private
     * @param {Array<number>} bbox BBox to validate
     * @returns {void}
     * @throws Error if BBox is not valid
     * @example
     * validateBBox([-180, -40, 110, 50])
     * //=OK
     * validateBBox([-180, -40])
     * //=Error
     * validateBBox('Foo')
     * //=Error
     * validateBBox(5)
     * //=Error
     * validateBBox(null)
     * //=Error
     * validateBBox(undefined)
     * //=Error
     */
    public static function validateBBox($bbox) {
        if (!$bbox) throw new Exception('bbox is required');
        if (!is_array($bbox)) throw new Exception('bbox must be an Array');
        if (count($bbox) !== 4 && count($bbox) !== 6) throw new Exception('bbox must be an Array of 4 or 6 numbers');
        foreach ($bbox as $key => $num) {
            if (!Helper::isNumber($num)) throw new Exception('bbox must only contain numbers');
        }
        
    }

    /**
     * Validate Id
     *
     * @private
     * @param {string|number} id Id to validate
     * @returns {void}
     * @throws Error if Id is not valid
     * @example
     * validateId([-180, -40, 110, 50])
     * //=Error
     * validateId([-180, -40])
     * //=Error
     * validateId('Foo')
     * //=OK
     * validateId(5)
     * //=OK
     * validateId(null)
     * //=Error
     * validateId(undefined)
     * //=Error
     */
    public static function validateId($id) {
        if (!$id) throw new Exception('id is required');
        if (is_string($id)) throw new Exception('id must be a number or a string');
    }

    /**
     * Creates a {@link Polygon} {@link Feature} from an Array of LinearRings.
     *
     * @name polygon
     * @param {Array<Array<Array<number>>>} coordinates an array of LinearRings
     * @param {Object} [properties={}] an Object of key-value pairs to add as properties
     * @param {Object} [options={}] Optional Parameters
     * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north] associated with the Feature
     * @param {string|number} [options.id] Identifier associated with the Feature
     * @returns {Feature<Polygon>} Polygon Feature
     * @example
     * $polygon = turf.polygon([[[-5, 52], [-4, 56], [-2, 51], [-7, 54], [-5, 52]]], { name: 'poly1' });
     *
     * //=polygon
     */
    public static function polygon($coordinates, $properties, $options) {
        if (!$coordinates) throw new Exception('coordinates is required');

        for ($i = 0; $i < count($coordinates); $i++) {
            $ring = $coordinates[$i];
            if (count($ring) < 4) {
                throw new Exception('Each LinearRing of a Polygon must have 4 or more Positions.');
            }
            for ($j = 0; $j < count($ring[count($ring) - 1]); $j++) {
                // Check if first point of Polygon contains two numbers
                if ($i === 0 && $j === 0 && !Helper::isNumber($ring[0][0]) || !Helper::isNumber($ring[0][1])) throw new Exception('coordinates must contain numbers');
                if ($ring[count($ring) - 1][$j] !== $ring[0][$j]) {
                    throw new Exception('First and last Position are not equivalent.');
                }
            }
        }

        return Helper::feature([
            "type"=>"Polygon",
            "coordinates"=>$coordinates
        ],$properties, $options);
    }


    /**
     * Creates a {@link Feature<MultiPolygon>} based on a
     * coordinate array. Properties can be added optionally.
     *
     * @name multiPolygon
     * @param {Array<Array<Array<Array<number>>>>} coordinates an array of Polygons
     * @param {Object} [properties={}] an Object of key-value pairs to add as properties
     * @param {Object} [options={}] Optional Parameters
     * @param {Array<number>} [options.bbox] Bounding Box Array [west, south, east, north] associated with the Feature
     * @param {string|number} [options.id] Identifier associated with the Feature
     * @returns {Feature<MultiPolygon>} a multipolygon feature
     * @throws {Error} if no coordinates are passed
     * @example
     * var multiPoly = turf.multiPolygon([[[[0,0],[0,10],[10,10],[10,0],[0,0]]]]);
     *
     * //=multiPoly
     *
     */
    public static function multiPolygon($coordinates, $properties, $options) {
        if (!$coordinates) throw new Exception('coordinates is required');

        return Helper::feature([
            "type"=>"MultiPolygon",
            "coordinates"=>$coordinates
        ],$properties, $options);
    }
}