<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" id="single-trip-navbar">
        <div class="navbar-brand">
            <trip-name :trip="trip" v-if="trip"></trip-name>
            <span class="badge badge-pill badge-info d-xs-inline-block d-lg-none" id="journey-details">
                <i class="fa fa-info"></i>
            </span>
            <b-tooltip target="journey-details" placement="bottom" v-if="trip">
                <strong>{{ trip.countries }}</strong> countries<br>
                <strong>{{ trip.miles }}</strong> miles<br>
                <strong>{{ trip.locationCount }}</strong> locations
            </b-tooltip>
            <span id="edit-trip"
                  v-on:click="toggleEditMode()"
                  class="badge badge-pill d-xs-inline-block d-lg-none"
                  v-bind:class="{ 'badge-secondary': !editMode, 'badge-warning': editMode }"
            >
                <i class="fa fa-pencil"></i>
            </span>
        </div>

        <button  v-if="isAuthenticated" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent" v-if="isAuthenticated">
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

        <div class="form-inline my-2 my-lg-0 ml-auto d-none d-lg-flex">
            <div class="details" v-if="trip">
                <span>{{ trip.countries }} countries</span>
                <span class="circle-separator grey"></span>
                <span>{{ trip.miles }} miles</span>
                <span class="circle-separator grey"></span>
                <span>{{ trip.locationCount }} locations</span>
            </div>
            <toggle-button
                v-if="isAuthenticated"
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
            isAuthenticated() {
                return this.$store.getters.authenticated;
            },
            editMode() {
                return this.$store.getters.editMode;
            }
        },

        methods: {

            /**
             * The reason toggle may be null is on the mobile button.
             * If it is null, we check the current state of edit mode
             * and set it as the opposite.
             *
             * @param toggle
             */
            toggleEditMode(toggle = null) {
                let toggledEditMode = null;
                if (toggle == null) {
                    toggledEditMode = true;
                    if (this.editMode == true) {
                        toggledEditMode = false;
                    }
                } else {
                    toggledEditMode = toggle.value;
                }
                this.$store.commit('setEditMode', { state: toggledEditMode });
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