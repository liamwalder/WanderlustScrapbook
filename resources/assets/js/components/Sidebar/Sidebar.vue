<template>
    <div class="content-sidebar">
        <div v-show="selectedLocation.length == 0">
            <navigation></navigation>
            <h4>All Activity</h4>
            <hr>
            <single-location-gallery
                :view-all="true"
                :images="activityImages"
                gallery-id-prefix="activity"
                v-show="!contentSidebarState.viewingAllEntries && contentSidebarState.selectedEntry == null"
            ></single-location-gallery>
            <single-location-entry-previews
                :entries="activityEntries"
                :view-all="true"
                v-show="!contentSidebarState.viewingAllImages && contentSidebarState.selectedEntry == null"
            ></single-location-entry-previews>
            <single-location-entries
                :entries="activityEntries"
            ></single-location-entries>
        </div>

        <div v-for="location in locations" class="content-location">
            <div v-show="location.id === selectedLocation.id">
                <navigation></navigation>
                <div v-show="contentSidebarState.selectedEntry == null">
                    <single-location-name
                            :location="location"
                    ></single-location-name>
                    <single-location-dates
                            :location="location"
                    ></single-location-dates>
                    <hr>
                </div>
                <single-location-gallery
                    :view-all="true"
                    :images="location.files"
                    gallery-id-prefix="location"
                    v-show="!contentSidebarState.viewingAllEntries && contentSidebarState.selectedEntry == null"
                ></single-location-gallery>

                <single-location-entry-previews
                    :entries="location.entries"
                    :view-all="true"
                    v-show="!contentSidebarState.viewingAllImages && contentSidebarState.selectedEntry == null"
                ></single-location-entry-previews>

                <single-location-entries
                    :entries="location.entries"
                ></single-location-entries>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus';

    export default {

        props: ['locations', 'selectedLocation', 'activityImages', 'activityEntries'],

        data () { return {} },

        created() {
            let self = this;
            EventBus.$on('location-selected', function() {
                self.$store.commit('resetContentSidebar');
            });
        },

        computed: {
            editMode() {
                return this.$store.getters.editMode;
            },
            contentSidebarState() {
                return this.$store.getters.contentSidebarState;
            }
        }

    }
</script>