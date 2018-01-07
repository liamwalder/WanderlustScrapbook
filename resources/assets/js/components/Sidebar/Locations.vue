<template>
    <div>
        <p class="notice instruction col" v-show="editMode"><i class="fa fa-bars" aria-hidden="true"></i> Drag the locations below to re-order your trip.</p>
        <div v-if="locations.length !== 0">
            <draggable
                    v-model="locationList"
                    @start="drag=true"
                    @end="dragEnd(drag)"
                    class="locations"
                    :options="{disabled: draggableDisabled}"
            >
                <div v-for="location in locationList" class="location"  v-bind:class="{ 'location-draggable': !draggableDisabled }">
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
        <p v-else class="notice col">You have not yet added any locations to your trip. Add your first by clicking "Add Location" above.</p>
    </div>
</template>

<script>
    import draggable from 'vuedraggable';
    import { EventBus } from '../../event-bus';

    export default {

        props: ['locations'],

        components: {
            draggable
        },

        data () {
            return {
                locationList: [],
                draggableDisabled: true
            }
        },

        watch: {
            locations: function(newVal) {
                if (newVal !== null) {
                    this.locationList = this.locations;
                }
            }
        },

        computed: {
            editMode() {
                this.draggableDisabled = true;
                if (this.$store.getters.editMode) {
                    this.draggableDisabled = false;
                }
                return this.$store.getters.editMode;
            },
            selectedLocation: {
                get: function () {
                    if (this.$store.getters.selectedLocation == null) {
                        return [];
                    }
                    return this.$store.getters.selectedLocation;
                },
                set: function(newValue) {
                    return newValue;
                }
            }
        },

        methods: {

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
                axios.post('/api/locations', { locations: this.locationList })
                    .then(function (response) {
                        EventBus.$emit('refresh-trip');
                    })
                    .catch(function (error) {});
            },

            selectLocation(location) {
                EventBus.$emit('marker-reset');
                EventBus.$emit('location-selected', location);
                this.$store.commit('selectedLocation', {location: location});
            }

        }

    }
</script>
