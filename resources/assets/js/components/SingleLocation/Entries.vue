<template>
    <div>
        <div v-for="entry in entries" v-show="selectedEntry !== null && selectedEntry.id == entry.id" class="single-entry">
            <h3>{{ entry.title }}</h3>
            <span class="date">{{ entry.created_at | moment("Do MMMM YYYY, HH:mmA") }}</span>
            <single-location-gallery
                :images="entry.files"
                :view-all="false"
                v-if="entry.files.length !== 0"
            ></single-location-gallery>
            <p>{{ entry.content }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['entries'],

        data () {return {}},

        computed: {
            selectedEntry() {
                return this.$store.getters.contentSidebarState.selectedEntry;
            }
        },

        watch: {},

        methods: {}
    }
</script>