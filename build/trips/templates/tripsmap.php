<?php
if ($this->allTrips != null && sizeof($this->allTrips) > 0) {
    $map_conf = PVars::getObj('map');
    $env_conf = PVars::getObj('env');
    ?>

    <input type="hidden" id="osm-tiles-provider-base-url" value="<?= $map_conf->osm_tiles_provider_base_url ?>">
    <input type="hidden" id="osm-tiles-provider-api-key" value="<?= $map_conf->osm_tiles_provider_api_key ?>">

    <div id="trips-map"></div>

    <div id="trips-data">
        <table>
            <?php
            $latitudeMin = null;
            $latitudeMax = null;
            $longitudeMin = null;
            $longitudeMax = null;

            // trips data is stored in a hidden table in order to retrieve it from activities_map.js script
            foreach ($this->allTrips as $trip) { ?>
                <tr>
                    <td><?= $trip->trip_name ?></td>
                    <td><?= $trip->username ?></td>
                    <td><?= $trip->tripStartDate ?></td>
                    <td><?= $trip->tripEndDate ?></td>
                    <td><?= $trip->latitude ?></td>
                    <td><?= $trip->longitude ?></td>
                    <td><?= $env_conf->baseuri . 'trip/' . $trip->trip_id ?></td>
                </tr>
                <?php
                // update the bounds of the map with this point
                if ($latitudeMin === null || $latitudeMin > $trip->latitude) {
                    $latitudeMin = $trip->latitude;
                }

                if ($latitudeMax === null || $latitudeMax < $trip->latitude) {
                    $latitudeMax = $trip->latitude;
                }

                if ($longitudeMin === null || $longitudeMin > $trip->longitude) {
                    $longitudeMin = $trip->longitude;
                }

                if ($longitudeMax === null || $longitudeMax < $trip->longitude) {
                    $longitudeMax = $trip->longitude;
                }
            }
            ?>
        </table>
        <?php
        if ($latitudeMin != null) {
            // at least one point with valid trip
            ?>
            <input type="hidden" id="trip-data-min-latitude" value="<?= $latitudeMin ?>" >
            <input type="hidden" id="trip-data-max-latitude" value="<?= $latitudeMax ?>" >
            <input type="hidden" id="trip-data-min-longitude" value="<?= $longitudeMin ?>" >
            <input type="hidden" id="trip-data-max-longitude" value="<?= $longitudeMax ?>" >
        <?php
        } ?>
    </div>
<?php
}