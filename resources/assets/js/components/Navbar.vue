<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" id="single-trip-navbar">
        <span class="navbar-brand">
            <trip-name :trip="trip" v-if="trip"></trip-name>
        </span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" v-on:click="addLocation()">Add Location</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" v-on:click="addEntry()">Add Entry</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" v-on:click="addMedia()">Add Media</a>
                </li>
            </ul>
        </div>
        <div class="form-inline my-2 my-lg-0">

            <div class="details" v-if="trip">
                <span>{{ trip.countries }} countries</span>
                <span class="circle-separator grey"></span>
                <span>{{ trip.miles }} miles</span>
                <span class="circle-separator grey"></span>
                <span>{{ trip.locationCount }} locations</span>
            </div>

            <toggle-button
                :cssColors="true"
                :value="editMode"
                :width="toggleWidth"
                @change="toggleEditMode"
                :labels="{ checked: 'Edit mode enabled', unchecked: 'Edit mode disabled' }"
            />
        </div>
    </nav>
</template>

<script>
    import { EventBus } from '../event-bus';

    export default {

        props: ['trip'],

        data () {
            return {
                toggleWidth: 135
            }
        },

        computed: {
            editMode() {
                return this.$store.getters.editMode;
            }
        },

        methods: {

            toggleEditMode(toggle) {
                this.$store.commit('setEditMode', { state: toggle.value });
            },

            addLocation() {
                EventBus.$emit('marker-reset');
                EventBus.$emit('adding-location');
            },

            addEntry() {
                EventBus.$emit('marker-reset');
                EventBus.$emit('adding-entry');
            },

            addMedia() {
                EventBus.$emit('marker-reset');
                EventBus.$emit('adding-media');
            }

        }

    }
</script>