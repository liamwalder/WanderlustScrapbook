<template>
    <div class="container-fluid">
        <navbar></navbar>
        <image-gallery></image-gallery>

        <fab position="bottom-left"
             bg-color="#17a2b8"
             :actions="[{name: 'addEntry', icon: 'insert_comment'}, {name: 'addMedia', icon: 'insert_photo'}]"
             @addEntry="addEntry()"
             @addMedia="addMedia()"
        ></fab>

        <div class="row">
            <div class="col-6">
                <div class="sidebar">
                    <div class="row" v-show="!addingEntry">
                        <div class="col-6 content divider">
                            <draggable v-model="locations" @start="drag=true" @end="dragEnd(drag)" class="locations">
                                <div v-for="location in locations" class="location">
                                    <div class="col">
                                        <div>
                                            <h5 v-on:click="selectLocation(location)">{{ location.name }}</h5>
                                            <span v-if="location.from && location.to">{{ location.from | moment("Do MMMM YYYY") }}  - {{ location.to | moment("Do MMMM YYYY") }}</span>
                                        </div>
                                        <div class="arrow" v-show="selectedLocation.id == location.id">
                                            <div class="arrow-left float-right"></div>
                                        </div>
                                    </div>
                                </div>
                            </draggable>
                        </div>

                        <div class="col-6 content">
                            <sidebar
                                :locations="locations"
                                :selected-location="selectedLocation"
                                :activity-images="activityImages"
                                :activity-entries="activityEntries"
                            ></sidebar>
                        </div>
                    </div>
                    <div class="row" v-show="addingEntry">
                        <div class="col-12 content add-entry">
                            <add-entry
                                :locations="locations"
                            >
                            </add-entry>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 map">
                <travel-map :trip="trip"></travel-map>
            </div>
        </div>
    </div>
</template>

<script>
    import fab from 'vue-fab'
    import VueGallery from 'vue-gallery';
    import draggable from 'vuedraggable';
    import { EventBus } from '../event-bus';

    export default {

        components: {
            fab,
            draggable,
            'gallery': VueGallery
        },

        data () {
            return {
                trip: null,
                locations: [],
                activityImages: [],
                activityEntries: [],
                selectedLocation:[],
                addingEntry: false
            }
        },

        created() {
            let self = this;
            self.getTrip();

            EventBus.$on('location-selected', function(location) {
                self.selectedLocation = location;
            });

            EventBus.$on('location-selection-reset', function(location) {
                self.selectedLocation = [];
            });

            EventBus.$on('refresh-trip', function() {
                self.getTrip();
            });

            EventBus.$on('entry-edit', function(entry) {
                self.addingEntry = true;
                EventBus.$emit('add-entry');
            });

            EventBus.$on('entry-cancelled', function() {
                self.addingEntry = false;
            });

        },

        methods: {

            markerClick(marker) {},

            getTrip() {
                let self = this;
                axios.get('/api/trip')
                    .then(function (response) {
                        self.trip = response.data;
                        self.activityImages = response.data.activity.files;
                        self.activityEntries = response.data.activity.entries;
                        self.locations = response.data.locations;
                        self.addingEntry = false;
                    })
                    .catch(function (error) {});
            },

            open: function () {
                console.log('slideoutOpen')
            },

            dragEnd(drag) {
                drag = false;
                this.storeLocations();
            },

            /**
             * On re-ordering the draggable list of locations,
             * we send all the locations as the order will have
             * have changed
             */
            storeLocations() {
                let self = this;
                axios.post('/api/locations', { locations: this.locations })
                    .then(function (response) {
                        self.getTrip();
                    })
                    .catch(function (error) {});
            },

            /**
             * Store a single location when adding from the
             * Google Autocomplete.
             */
            storeLocation() {
                let self = this;

                if (this.place) {

                    let location = {
                        name: this.place.address_components[0].long_name,
                        lat: this.place.geometry.location.lat(),
                        lng: this.place.geometry.location.lng()
                    };

                    axios.post('/api/location', location)
                        .then(function (response) {
                            self.getTrip();
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                }

            },

            selectLocation(location) {
                this.selectedLocation = location;
                EventBus.$emit('location-selected', location);
                this.$store.commit('selectedLocation', {location: location});
            },

            clickPolyline() {
                console.log('czcx');
            },

            setPlace(place) {
                this.place = place
            },

            addEntry(){
                this.addingEntry = true;
                EventBus.$emit('add-entry');
            },

            addMedia(){
                EventBus.$emit('add-media', location);
            }

        }

    }
</script>
