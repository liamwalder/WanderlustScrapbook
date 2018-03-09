<template>
    <div class="container-fluid">
        <navbar :trip="trip.trip"></navbar>
        <image-gallery></image-gallery>

        <div class="row" id="trip">
            <div class="col-sm-12 col-md-12 col-lg-8 col-xl-7 left-side">
                <div class="sidebar">
                    <div class="row" v-show="!addingEntry && !addingLocation & !addingMedia">
                        <div class="col content divider">
                            <locations-list
                                :locations="locations"
                            ></locations-list>
                        </div>

                        <div class="col content">
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
                            <add-entry :locations="locations"></add-entry>
                        </div>
                    </div>
                    <div class="row" v-show="addingLocation">
                        <div class="col-12 content add-entry">
                            <add-location :trip-id="tripId" :locations="locations"></add-location>
                        </div>
                    </div>
                    <div class="row" v-show="addingMedia">
                        <div class="col-12 content add-entry">
                            <add-media :locations="locations"></add-media>
                        </div>
                    </div>
                </div>
            </div>
            <travel-map class="d-none d-lg-flex d-xl-flex col-lg-4 col-xl-5 map" v-if="trip.length !== 0" :trip="trip"></travel-map>
        </div>
    </div>
</template>

<script>
    import VueGallery from 'vue-gallery';
    import draggable from 'vuedraggable';
    import { EventBus } from '../event-bus';

    export default {

        props: ['requestedTripId', 'authenticated'],

        components: {
            draggable,
            'gallery': VueGallery
        },

        data () {
            return {
                trip: [],
                tripId: null,
                locations: [],
                activityImages: [],
                activityEntries: [],
                selectedLocation:[],
                addingEntry: false,
                addingMedia: false,
                addingLocation: false
            }
        },

        created() {
            let self = this;
            self.getTrip();

            self.$store.commit('setAuthenticated', {authenticated: self.authenticated});

            EventBus.$on('location-selected', function(location) {
                self.selectedLocation = location;
            });

            EventBus.$on('location-selection-reset', function(location) {
                self.selectedLocation = [];
            });

            EventBus.$on('refresh-trip', function() {
                self.getTrip();
                self.addingEntry = false;
                self.addingMedia = false;
                self.addingLocation = false;
            });

            EventBus.$on('entry-edit', function(entry) {
                self.addingEntry = true;
                EventBus.$emit('add-entry');
            });

            EventBus.$on('entry-cancelled', function() {
                self.getTrip();
                self.addingEntry = false;
            });

            EventBus.$on('input-screen-cancelled', function() {
                self.getTrip();
                self.addingEntry = false;
                self.addingMedia = false;
                self.addingLocation = false;
            });

            EventBus.$on('adding-location', function() {
                self.addingEntry = false;
                self.addingMedia = false;
                self.addingLocation = true;
            });

            EventBus.$on('adding-entry', function() {
                self.addingMedia = false;
                self.addingEntry = true;
                self.addingLocation = false;
            });

            EventBus.$on('adding-media', function() {
                self.addingMedia = true;
                self.addingEntry = false;
                self.addingLocation = false;
            });

        },

        computed: {
            editMode() {
                return this.$store.getters.editMode;
            }
        },

        methods: {
            getTrip() {
                let self = this;
                axios.get('/api/trip/' + self.requestedTripId)
                    .then(function (response) {
                        self.trip = response.data;
                        self.tripId = response.data.trip.id;
                        self.tripName = response.data.trip.name;
                        self.locations = response.data.locations;
                        self.activityImages = response.data.activity.files;
                        self.activityEntries = response.data.activity.entries;
                        EventBus.$emit('refresh-map', response.data);
                    })
                    .catch(function (error) {});
            }
        }

    }
</script>
