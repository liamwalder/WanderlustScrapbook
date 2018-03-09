require('./bootstrap');


import Vue from 'vue';
import Vuex from 'vuex';
import { EventBus } from './event-bus';
import BootstrapVue from 'bootstrap-vue';
import ToggleButton from 'vue-js-toggle-button';
import * as VueGoogleMaps from 'vue2-google-maps';

Vue.use(Vuex);
Vue.use(ToggleButton);
Vue.use(BootstrapVue);
Vue.use(require('vue-moment'));
Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyC77tkgnSwKwwL7hEq8o7P9cRO2ibIC8ds',
        libraries: 'places', // This is required if you use the Autocomplete plugin
        // OR: libraries: 'places,drawing'
        // OR: libraries: 'places,drawing,visualization'
        // (as you require)
    }
});


/**
 * Filters
 */
Vue.filter('truncate', function (text, stop, clamp) {
    return text.slice(0, stop) + (stop < text.length ? clamp || '' : '')
});

/**
 * Components
 */
Vue.component('trip', require('./components/Trip.vue'));
Vue.component('navbar', require('./components/Navbar.vue'));
Vue.component('travel-map', require('./components/Map.vue'));
Vue.component('image-gallery', require('./components/Gallery.vue'));

// Trip
Vue.component('trip-name', require('./components/Trip/Name.vue'));

// Input Screens
Vue.component('add-media', require('./components/InputScreen/AddMedia.vue'));
Vue.component('add-entry', require('./components/InputScreen/AddEntry.vue'));
Vue.component('add-location', require('./components/InputScreen/AddLocation.vue'));

// Sidebar
Vue.component('sidebar', require('./components/Sidebar/Sidebar.vue'));
Vue.component('navigation', require('./components/Sidebar/Navigation.vue'));
Vue.component('locations-list', require('./components/Sidebar/Locations.vue'));

// Location Specific
Vue.component('single-location-name', require('./components/SingleLocation/Name.vue'));
Vue.component('single-location-dates', require('./components/SingleLocation/Dates.vue'));
Vue.component('single-location-gallery', require('./components/SingleLocation/Gallery.vue'));
Vue.component('single-location-entries', require('./components/SingleLocation/Entries.vue'));
Vue.component('single-location-entry-previews', require('./components/SingleLocation/EntryPreviews.vue'));

// Entries
Vue.component('single-entry-preview', require('./components/Entry/Preview.vue'));

/**
 * Store
 * @type {*|Store}
 */
const store = new Vuex.Store({
    state: {
        authenticated: null,
        editMode: false,
        contentSidebar: {
            selectedEntry: null,
            selectedLocation: null,
            viewingAllImages: false,
            viewingAllEntries: false
        }
    },
    getters: {
        contentSidebarState: function(state)  {
            return state.contentSidebar;
        },
        editMode: function(state) {
            return state.editMode;
        },
        selectedLocation: function(state) {
            return state.contentSidebar.selectedLocation;
        },
        authenticated: function(state) {
            return state.authenticated;
        }
    },
    mutations: {
        setAuthenticated: function(state, payload) {
            state.authenticated = payload.authenticated;
        },
        setEditMode: function(state, payload) {
            state.editMode = payload.state;
        },
        viewAllEntries: function(state, payload) {
            state.contentSidebar.viewingAllEntries = payload.state;
        },
        viewAllImages: function(state, payload) {
            state.contentSidebar.viewingAllImages = payload.state;
        },
        selectedEntry: function(state, payload) {
            state.contentSidebar.selectedEntry = payload.entry;
        },
        selectedLocation: function(state, payload) {
            state.contentSidebar.selectedLocation = payload.location;
        },
        resetContentSidebar: function(state) {
            state.contentSidebar.selectedEntry = null;
            state.contentSidebar.selectedLocation = null;
            state.contentSidebar.viewingAllImages = false;
            state.contentSidebar.viewingAllEntries = false;
        }
    }
});

/**
 *  Vue Definition
 */
const app = new Vue({
    store: store,
    el: '#app'
});