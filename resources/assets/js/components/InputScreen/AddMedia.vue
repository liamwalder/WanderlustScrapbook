<template>
    <div class="content-sidebar">
        <div class="col-12">
            <div class="header">
                <h3>Add Media</h3>
                <a class="float-right" v-on:click="cancelMedia()">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
            </div>
            <div class="form-group">
                <label>Where are you?</label>
                <select class="form-control" :class="{ 'is-invalid': errors.location }" v-model="location">
                    <option :value="location.id" v-for="location in locations">{{ location.name }}</option>
                </select>
            </div>

            <div class="form-group">
                <label>Add your images and videos.</label>
                <vue-dropzone
                    class="form-control"
                    :class="{ 'is-invalid': errors.files }"
                    ref="mediaUpload"
                    id="dropzone"
                    :options="dropzoneOptions"
                    v-on:vdropzone-removed-file="removeFile"
                    v-on:vdropzone-success="successUpload"
                >
                </vue-dropzone>
            </div>

            <div class="form-group save-entry">
                <button v-on:click="saveMedia()" class="btn button-primary">Add media</button>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus';
    import vue2Dropzone from 'vue2-dropzone';
    import 'vue2-dropzone/dist/vue2Dropzone.css';

    export default {

        components: {
            'vue-dropzone' : vue2Dropzone
        },

        props: ['locations'],

        data () {
            return {
                errors: [],
                title: null,
                location: [],
                filesAttachedToLocation: [],
                dropzoneOptions: {
                    url: '/api/media',
                    maxFilesize: 40,
                    thumbnailWidth: 150,
                    addRemoveLinks: true,
                    headers: {
                        'Authorization' : 'Bearer ' + $('meta[name="api-token"]').attr('content')
                    }
                }
            }
        },

        created() {},

        watch: {
            location: function(selectedLocation) {
                let self = this;
                self.locations.forEach(function(location) {
                    if (selectedLocation == location.id) {
                        EventBus.$emit('location-selected', location);
                    }
                });
            }
        },

        methods: {
            
            reset() {
                this.errors = [];
                this.location = [];
                this.filesAttachedToEntry = [];
            },

            cancelMedia() {
                this.reset();
                EventBus.$emit('input-screen-cancelled');
            },

            saveMedia() {
                let self = this;
                let postData = {
                    location: this.location,
                    files: this.filesAttachedToLocation
                };

                axios.post('/api/media/location/attach', postData)
                    .then(function (response) {
                        self.reset();
                        EventBus.$emit('refresh-trip');
                        self.$refs.mediaUpload.removeAllFiles();
                    })
                    .catch(function (error) {
                        self.errors = error.response.data;
                    });
            },

            successUpload(file, response) {
                this.filesAttachedToLocation.push(response[0].filename);
            },

            removeFile(file) {
                console.log(file);
            }

        }

    }
</script>
