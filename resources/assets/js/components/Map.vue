<template>
    <div>
        <GmapMap
            :zoom="zoom"
            :center="center"
            :bounds="bounds"
            style="width: 100%; height: 93vh"
            @rightclick="rightClick($event)"
        >
            <GmapCluster :gridSize="20">
                <GmapMarker
                    :key="index"
                    v-for="(m, index) in markers"
                    :position="m.position"
                    :clickable="true"
                    :draggable="false"
                    :icon="m.icon"
                    @click="markerClick(m)"
                ></GmapMarker>
            </GmapCluster>
            <GmapPolyline
                v-for="(path, index) in polylinePath"
                :key="index"
                @click="clickPolyline()"
                :draggable="false"
                :editable="false"
                :path="curvedPath(path, index)"
                :options="polylineOptions"
            ></GmapPolyline>
        </GmapMap>
    </div>
</template>

<script>
    import {range} from 'lodash'
    import VueGallery from 'vue-gallery';
    import draggable from 'vuedraggable';
    import { EventBus } from '../event-bus';

    export default {

        props: ['trip'],

        data () {
            return {
                bounds: null,
                zoom: 2,
                markers: [],
                locations: [],
                polylinePath: [],
                entryLocations: [],
                selectedLocation:[],
                adhocEntryMarkers: [],
                allowRightClick: false,
                adhocEntryMarkerCount: 0,
                calculateCurvedLine: false,
                center: {lat: 13.736717, lng: 100.523186},
                locationMarkerOptions: {
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 5,
                        fillColor: '#f39c12',
                        strokeColor: '#f39c12'
                    }
                },
                polylineOptions: {
                    strokeWeight: 2,
                    icons: [{
                        repeat: '45px',
                        offset: '100%',
                        icon: {
                            path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW
                        }
                    }]
                }
            }
        },

        created() {
            let self = this;

            EventBus.$on('entry-cancelled', function() {
                self.adhocEntryMarkers.forEach(function(location) {
                    self.removeLocationFromMap(location)
                });
                self.adhocEntryMarkers = [];
                self.adhocEntryMarkerCount = 0;
            });

            EventBus.$on('location-selected', function(location) {
                self.center = {
                    lat: parseFloat(location.latitude),
                    lng: parseFloat(location.longitude)
                };
                self.zoom = 9;
            });

            EventBus.$on('select-entry', function(entry) {
                entry.entry_locations.forEach(function (location, key) {
                    self.removeLocationFromMap(location);
                    self.addLocationToMap(
                        location,
                        false,
                        'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_blue' + (key + 1) + '.png',
                        { entry: entry }
                    );
                })
            });

            EventBus.$on('marker-reset', function() {
                self.resetEntryLocationMarkers();
            });

            EventBus.$on('location-selection-reset', function() {
                self.zoom = 6;
                self.center = {lat: 13.736717, lng: 100.523186};
            });

            EventBus.$on('adding-entry', function() {
                self.allowRightClick = true;
            });

            EventBus.$on('remove-location', function (location) {
                self.removeLocationFromMap(location);
            });

            EventBus.$on('entry-edit', function(entry) {
                self.adhocEntryMarkerCount = entry.entry_locations.length;
            });

        },

        watch: {
            trip: function(newVal) {
                let self = this;
                self.markers = [];
                self.polylinePath = [];
                self.locations = newVal.locations;

                self.entryLocations = newVal.markers.entryLocations;

                if (newVal !== null) {
                    newVal.markers.locations.forEach(function(location) {
                        self.addLocationToMap(
                            location,
                            true,
                            self.locationMarkerOptions
                        );
                    });

                    self.calculateCurvedLine = true;
                    self.calculateMapCenter(newVal.markers.locations);

                    newVal.markers.entryLocations.forEach(function(location) {
                        self.addLocationToMap(
                            location,
                            false,
                            'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_black.png',
                            { entry: location.entry }
                        );
                    });
                }
            }
        },

        methods: {

            calculateMapCenter(locations) {
                var bounds = new google.maps.LatLngBounds();
                locations.forEach(function(location) {
                    let marker = new google.maps.Marker({
                        position: new google.maps.LatLng(location.latitude, location.longitude)
                    });
                    bounds.extend(marker.position);
                });

                var boundsCenter = bounds.getCenter();
                this.center = {
                    lat: boundsCenter.lat(),
                    lng: boundsCenter.lng()
                }
            },

            curvedPath(path, key) {
                let firstLocationInPath = this.polylinePath[0];
                if (path.lat == firstLocationInPath.lat && path.lng == firstLocationInPath.lng) {
                    return [];
                }

                let startingLocation = this.polylinePath[(key - 1)];
                let endingLocation = path;

                if (endingLocation && startingLocation) {

                    return range(100).map(function(i) {
                        let tick = i / 99;

                        /* Bezier curve -- set up the control points */
                        let dlat = endingLocation.lat - startingLocation.lat;
                        let dlng = endingLocation.lng - startingLocation.lng;

                        let cp1 = {
                            lat: startingLocation.lat + 0.33 * dlat + 0.33 * dlng,
                            lng: startingLocation.lng - 0.33 * dlat + 0.33 * dlng
                        };

                        let cp2 = {
                            lat: endingLocation.lat - 0.33 * dlat + 0.33 * dlng,
                            lng: endingLocation.lng - 0.33 * dlat - 0.33 * dlng,
                        };

                        /* Bezier curve formula */
                        return {
                            lat:
                                (tick * tick * tick) * startingLocation.lat +
                                3 * ((1 - tick) * tick * tick) * cp1.lat +
                                3 * ((1 - tick) * (1 - tick) * tick) * cp2.lat +
                                ((1 - tick) * (1 - tick) * (1 - tick)) * endingLocation.lat,
                            lng:
                                (tick * tick * tick) * startingLocation.lng +
                                3 * ((1 - tick) * tick * tick) * cp1.lng +
                                3 * ((1 - tick) * (1 - tick) * tick) * cp2.lng +
                                ((1 - tick) * (1 - tick) * (1 - tick)) * endingLocation.lng
                        }
                    })
                }
            },

            rightClick(event) {
                if (this.allowRightClick) {
                    let location = {
                        latitude:  event.latLng.lat(),
                        longitude: event.latLng.lng()
                    };

                    this.adhocEntryMarkers.push(location);
                    this.adhocEntryMarkerCount = (this.adhocEntryMarkerCount + 1);

                    EventBus.$emit('location-added-right-click', location);
                    this.addLocationToMap(location, false, 'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_blue' + this.adhocEntryMarkerCount + '.png')
                }
            },

            addLocationToMap(location, addToPolyline, icon, customInfo = {}) {

                let marker = {
                    position: {
                        lat: parseFloat(location.latitude),
                        lng: parseFloat(location.longitude)
                    },
                    customInfo: customInfo
                };

                if (icon instanceof Object) {
                    marker['icon'] = icon.icon;
                } else {
                    marker['icon'] = {
                        url: icon
                    }
                }

                this.markers.push(marker);

                if (addToPolyline) {
                    this.polylinePath.push({
                        lat: parseFloat(location.latitude),
                        lng: parseFloat(location.longitude)
                    });
                }

            },

            removeLocationFromMap(location) {
                let self = this;
                self.markers.forEach(function(marker, key) {
                    if (location.latitude == marker.position.lat && location.longitude == marker.position.lng) {
                        self.markers.splice(key, 1);
                    }
                });
            },

            markerClick(marker) {
                let self = this;
                self.locations.forEach(function(location) {
                    if (location.latitude == marker.position.lat && location.longitude == marker.position.lng) {
                        self.resetEntryLocationMarkers();
                        EventBus.$emit('location-selected', location);
                    }
                });
                self.entryLocations.forEach(function(location) {
                    if (location.latitude == marker.position.lat && location.longitude == marker.position.lng) {
                        self.resetEntryLocationMarkers();
                        EventBus.$emit('select-entry', marker.customInfo.entry);
                        self.$store.commit('selectedEntry', { entry: marker.customInfo.entry });
                    }
                });
            },

            resetEntryLocationMarkers() {
                let self = this;
                self.entryLocations.forEach(function(location) {
                    self.removeLocationFromMap(location);
                    self.addLocationToMap(
                        location,
                        false,
                        'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_black.png',
                        { entry: location.entry }
                    );
                });

            },

            clickPolyline() {}

        }

    }
</script>
