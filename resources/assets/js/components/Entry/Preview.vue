<template>
    <div class="entry">
        <h6 class="entry-title" v-on:click="selectEntry(entry)">
            {{ entry.title }}
            <small v-if="!selectedLocation">{{ entry.location.name }}</small>
        </h6>
        <p class="entry-content">
            {{ entry.content | truncate(200) }}
        </p>
        <div class="entry-footer">
            <p>
                <span>
                    {{ entry.files.length }} <i class="fa fa-file-image-o" aria-hidden="true"></i>
                    {{ entry.entry_locations.length }} <i class="fa fa-map-marker" aria-hidden="true"></i>
                </span>
                <span class="float-right">{{ entry.created_at | moment("DD/MM/YYYY, HH:mmA") }}</span>
            </p>
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus';

    export default {
        props: ['entry'],

        computed: {
            selectedLocation() {
                return this.$store.getters.selectedLocation;
            }
        },

        methods: {
            selectEntry(entry) {
                EventBus.$emit('select-entry', entry);
                this.$store.commit('selectedEntry', { entry: entry });
            }
        }
    }
</script>