<template>
    <div class="sub-menu">
        <div v-show="editMode">
            <div v-show="contentSidebarState.selectedLocation !== null && contentSidebarState.selectedEntry == null">
                <a v-on:click="deleteLocation()" class="text-danger">Delete Location</a>
                <span class="circle-separator"></span>
            </div>
            <div v-show="contentSidebarState.selectedEntry !== null">
                <a v-on:click="deleteEntry()" class="text-danger">Delete Entry</a>
                <span class="circle-separator"></span>
                <a v-on:click="editEntry()" class="instruction">Edit Entry</a>
                <span class="circle-separator"></span>
            </div>
        </div>
        <div v-show="contentSidebarState.selectedLocation !== null">
            <a v-on:click="showAllActivity()">All Activity</a>
            <span class="circle-separator"></span>
        </div>
        <div v-show="contentSidebarState.viewingAllImages || contentSidebarState.viewingAllEntries || contentSidebarState.selectedEntry !== null">
            <a v-on:click="back()">Back</a>
        </div>
    </div>

</template>

<script>
    import { EventBus } from '../../event-bus';

    export default {

        data () { return {} },

        computed: {
            contentSidebarState() {
                return this.$store.getters.contentSidebarState;
            },

            editMode() {
                return this.$store.getters.editMode;
            }
        },

        methods: {
            editEntry() {
                EventBus.$emit('entry-edit', this.contentSidebarState.selectedEntry);
            },

            showAllActivity() {
                EventBus.$emit('marker-reset');
                this.$store.commit('resetContentSidebar');
                EventBus.$emit('location-selection-reset');
            },

            /**
             * Working out from the stored state, what we want
             * to show and hide
             */
            back() {
                let self = this;
                EventBus.$emit('marker-reset');
                switch(true) {
                    case self.contentSidebarState.viewingAllEntries && self.contentSidebarState.selectedEntry !== null:
                        self.$store.commit('selectedEntry', {entry: null});
                        break;
                    default:
                        self.$store.commit('viewAllImages', {state: false});
                        self.$store.commit('selectedEntry', {entry: null});
                        self.$store.commit('viewAllEntries', {state: false});
                        break;
                }
            },

            deleteEntry() {
                let self = this;
                let entry = this.contentSidebarState.selectedEntry;
                let result = confirm("Click 'Ok' to confirm your deletion.");
                if (result) {
                    axios.delete('/api/entry/' + entry.id)
                        .then(function (response) {
                            EventBus.$emit('refresh-trip');
                            self.$store.commit('selectedEntry', {entry: null});
                        })
                        .catch(function (error) {});
                }
            },

            deleteLocation() {
                let self = this;
                let location = this.contentSidebarState.selectedLocation;
                let result = confirm("Click 'Ok' to confirm your deletion.");
                if (result) {
                    axios.delete('/api/location/' + location.id)
                        .then(function (response) {
                            EventBus.$emit('refresh-trip');
                            self.$store.commit('selectedLocation', {location: null});
                            self.showAllActivity();
                        })
                        .catch(function (error) {});
                }
            }

        }

    }
</script>