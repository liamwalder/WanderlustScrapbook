<template>
    <div class="entries">
        <h5>Entries <span class="badge badge-pill badge-info">{{ entries.length }}</span></h5>
        <div v-for="entry in entries.slice(0, maximumEntriesCount)" class="entry">
            <single-entry-preview :entry="entry"></single-entry-preview>
        </div>
        <div v-show="allEntries" v-for="entry in entries.slice(maximumEntriesCount)" class="entry">
            <single-entry-preview :entry="entry"></single-entry-preview>
        </div>
        <div class="view-all" v-if="entries.length > maximumEntriesCount">
            <a v-show="!allEntries" v-on:click="toggleEntryDisplay(true)">Show more</a>
            <a v-show="allEntries" v-on:click="toggleEntryDisplay(false)">Show less</a>
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus';

    export default {

        props: ['entries', 'viewAll'],

        data () {
            return {
                maximumEntriesCount: 3
            }
        },

        computed: {
            allEntries() {
                return this.$store.getters.contentSidebarState.viewingAllEntries;
            }
        },

        methods: {
            toggleEntryDisplay(state) {
                this.$store.commit('viewAllEntries', { state: state });
            }
        }

    }
</script>