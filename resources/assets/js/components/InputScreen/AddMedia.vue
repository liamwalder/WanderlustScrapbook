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
                    v-on:vdropzone-sending="sendingFile"
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
                    acceptedFiles: 'image/*,video/*',
                    maxFilesize: 5000,
                    headers: {
                        'Authorization' : 'Bearer ' + $('meta[name="api-token"]').attr('content')
                    },
                    previewTemplate: "<div class='dz-preview dz-file-preview file-upload-row' data-id=''>\
                                        <div class='images col-md-2'>\
                                            <img data-dz-thumbnail class='thumbnail' />\
                                        </div>\
                                        <div class='caption col-md-10'>\
                                            <span data-dz-errormessage></span>\
                                            <label data-dz-name></label>\
                                            <input type='text' class='form-control' name='caption_id[]' placeholder='Caption'>\
                                        </div>\
                                        <div class='actions col-md-1'>\
                                            <i aria-hidden='true' class='fa fa-times remove' data-dz-remove></i>\
                                        </div>\
                                    </div>"
                }
            }
        },

        watch: {
            location: function(selectedLocation) {
                let self = this;
                self.locations.forEach(function(location) {
                    if (selectedLocation == location.id) {
                        EventBus.$emit('location-selected', location);
                        self.$store.commit('selectedLocation', {location: location});
                    }
                });
            }
        },

        methods: {
            
            reset() {
                this.errors = [];
                this.location = [];
                this.filesAttachedToLocation = [];
            },

            cancelMedia() {
                this.reset();
                EventBus.$emit('input-screen-cancelled');
            },

            sendingFile(file, xhr, formData) {
                formData.append('uuid', file.upload.uuid);
            },

            /**
             * Save media
             */
            saveMedia() {
                let loader = this.$loading.show();

                let captions = [];
                this.$refs.mediaUpload.getAcceptedFiles().forEach(function(file) {
                    captions.push({
                        uuid: file.upload.uuid,
                        caption: $(file.previewElement).find('.caption').find('input').val()
                    });
                });

                let self = this;
                let postData = {
                    captions: captions,
                    location: this.location,
                    files: this.filesAttachedToLocation
                };

                axios.post('/api/media/location/attach', postData)
                    .then(function (response) {
                        self.reset();
                        EventBus.$emit('refresh-trip');
                        $('.file-upload-row').remove();
                        $('.vue-dropzone .dz-default').show();
                        loader.hide();
                    })
                    .catch(function (error) {
                        self.errors = error.response.data;
                        loader.hide();
                    });
            },

            successUpload(file, response) {
                file.previewElement.querySelector("img").src = response[0].thumbnail;
                this.filesAttachedToLocation.push(response[0].filename);
            },

            removeFile(file) {
                axios.delete('/api/files/uuid/' + file.upload.uuid)
                    .then(function (response) {})
                    .catch(function (error) {});
            }

        }

    }
</script>
