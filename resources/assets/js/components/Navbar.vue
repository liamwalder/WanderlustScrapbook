<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <span class="navbar-brand">
            {{ tripName }} <a class="notice instruction" v-show="editMode">Change</a>
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
            <toggle-button
                color="#17a2b8"
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

        props: ['tripName'],

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