require('./bootstrap');

window.Vue = require('vue');
window.axios = require('axios');

import Vue from 'vue';
import Vuex from 'vuex';
import { EventBus } from './event-bus';
import * as VueGoogleMaps from 'vue2-google-maps';

Vue.use(Vuex);
Vue.use(require('vue-moment'));
Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyBvWE_sIwKbWkiuJQOf8gSk9qzpO96fhfY',
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
Vue.component('add-entry', require('./components/AddEntry.vue'));
Vue.component('image-gallery', require('./components/Gallery.vue'));

// Sidebar
Vue.component('sidebar', require('./components/Sidebar/Sidebar.vue'));
Vue.component('navigation', require('./components/Sidebar/Navigation.vue'));

// Location Specific
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
        contentSidebar: {
            selectedEntry: null,
            selectedLocation: null,
            viewingAllImages: false,
            viewingAllEntries: false
        }
    },
    getters: {
        contentSidebarState: state => {
            return state.contentSidebar;
        }
    },
    mutations: {
        viewAllEntries (state, payload) {
            state.contentSidebar.viewingAllEntries = payload.state;
        },
        viewAllImages (state, payload) {
            state.contentSidebar.viewingAllImages = payload.state;
        },
        selectedEntry(state, payload) {
            state.contentSidebar.selectedEntry = payload.entry;
        },
        selectedLocation(state, payload) {
            state.contentSidebar.selectedLocation = payload.location;
        },
        resetContentSidebar(state) {
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