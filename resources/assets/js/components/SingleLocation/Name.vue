<template>
    <div class="location-name">
        <h3 v-show="!editMode">{{ location.name }}</h3>
        <div v-show="editMode">
            <label>Name</label>
            <input type="text" class="form-control" v-model="name" v-on:blur="saveName()" />
        </div>
    </div>
</template>

<script>

    import { EventBus } from '../../event-bus';

    export default {

        props: ['location'],

        data () {
            return {
                name: this.location.name
            }
        },

        computed: {
            editMode() {
                return this.$store.getters.editMode;
            }
        },

        methods: {
            saveName() {
                let self = this;
                axios.put('/api/location/' + this.location.id, { name: this.name })
                    .then(function (response) {
                        EventBus.$emit('refresh-trip');
                    })
                    .catch(function (error) {});
            }
        }

    }
</script>