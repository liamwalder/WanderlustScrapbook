<template>
    <div>
        <GmapMap
            :center="center"
            :zoom="zoom"
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
                @click="clickPolyline()"
                :draggable="false"
                :editable="false"
                :path="polylinePath"
            >
            </GmapPolyline>
        </GmapMap>
    </div>
</template>

<script>
    import VueGallery from 'vue-gallery';
    import draggable from 'vuedraggable';
    import { EventBus } from '../event-bus';

    export default {

        props: ['trip'],

        data () {
            return {
                zoom: 6,
                markers: [],
                locations: [],
                adhocEntryMarkers: [],
                adhocEntryMarkerCount: 0,
                polylinePath: [],
                selectedLocation:[],
                allowRightClick: false,
                center: {lat: 13.736717, lng: 100.523186}
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
                self.zoom = 10;
            });

            EventBus.$on('select-entry', function(entry) {
                entry.entry_locations.forEach(function (location, key) {
                    self.removeLocationFromMap(location);
                    self.addLocationToMap(location, false, 'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_blue' + (key + 1) + '.png');
                })
            });

            EventBus.$on('location-selection-reset', function() {
                self.zoom = 6;
                self.center = {lat: 13.736717, lng: 100.523186};
            });

            EventBus.$on('add-entry', function() {
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
                if (newVal !== null) {
                    newVal.markers.locations.forEach(function(location) {
                        self.addLocationToMap(location, true, 'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_red.png');
                    });
                    newVal.markers.entryLocations.forEach(function(location) {
                        self.addLocationToMap(location, false, 'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_black.png');
                    });
                }
            }
        },

        methods: {

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

            addLocationToMap(location, addToPolyline, icon) {
                this.markers.push({
                    position: {
                        lat: parseFloat(location.latitude),
                        lng: parseFloat(location.longitude)
                    },
                    icon: {
                        url: icon
                    }
                });

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
                        EventBus.$emit('location-selected', location);
                    }
                });
            },

            clickPolyline() {}

        }

    }
</script>
