<template>
    <div class="content-sidebar">
        <div class="col-12">
            <div class="header">
                <h3 v-if="!editingEntry">Add Entry</h3>
                <h3 v-else>Edit Entry</h3>
                <a class="float-right" v-on:click="cancelEntry()">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </a>
            </div>
            <p class="notice">
                This entry will automatically assigned to the location selected. Right click on the map
                to attach further locations to this entry.
            </p>

            <div class="form-group">
                <label>Where are you?</label>
                <select class="form-control" :class="{ 'is-invalid': errors.location }" v-model="location">
                    <option :value="location.id" v-for="location in locations">{{ location.name }}</option>
                </select>
            </div>

            <div class="form-group">
                <label>Give you entry a title</label>
                <input class="form-control" :class="{ 'is-invalid': errors.title }" v-model="title" />
            </div>

            <div class="form-group">
                <label>What do you have to say</label>
                <textarea class="form-control" rows="8" :class="{ 'is-invalid': errors.content }" v-model="content"></textarea>
            </div>

            <div class="form-group" v-if="entryLocations.length !== 0">
                <label>Markers</label>
                <p class="notice">The numbers on the marker are only there to help manage them. The numbers will not be shown anywhere.</p>
                <div class="markers">
                    <div class="marker" v-for="(entryLocation, key) in entryLocations">
                        <img v-bind:src="markerImageUrl(entryLocation)" />
                        <div class="remove">
                            <i class="fa fa-times" aria-hidden="true" v-on:click="removeMarker(entryLocation)"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" v-show=!editingEntry>
                <label>Add media related to this entry</label>
                <vue-dropzone
                    class="form-control"
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
                <button v-on:click="saveEntry()" class="btn button-primary">Add entry</button>
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
                entryId: null,
                content: null,
                entryLocations: [],
                editingEntry: false,
                entryLocationCount: 0,
                filesAttachedToEntry: [],
                dropzoneOptions: {
                    url: '/api/media',
                    acceptedFiles: 'image/*,video/*',
                    maxFilesize: 5000,
                    thumbnailWidth: 150,
                    addRemoveLinks: true,
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

        created() {
            let self = this;

            EventBus.$on('location-added-right-click', function(location) {
                self.entryLocationCount = (self.entryLocationCount + 1);
                location['markerIdentifier'] = self.entryLocationCount;
                self.entryLocations.push(location);
            });

            EventBus.$on('entry-edit', function(entry) {
                self.reset();
                self.entryId = entry.id;
                self.editingEntry = true;
                self.title = entry.title;
                self.content = entry.content;
                self.location = entry.location_id;
                entry.entry_locations.forEach(function (location) {
                    self.entryLocationCount = (self.entryLocationCount + 1);
                    location['markerIdentifier'] = self.entryLocationCount;
                    self.entryLocations.push(location);
                });
            });
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
                this.title = '';
                this.errors = [];
                this.location = [];
                this.entryId = null;
                this.content = null;
                this.entryLocations = [];
                this.editingEntry = false;
                this.entryLocationCount = 0;
                this.filesAttachedToEntry = [];
            },

            cancelEntry() {
                this.reset();
                EventBus.$emit('input-screen-cancelled');
            },

            sendingFile(file, xhr, formData) {
                formData.append('uuid', file.upload.uuid);
            },

            saveEntry() {
                let captions = [];
                this.$refs.mediaUpload.getAcceptedFiles().forEach(function(file) {
                    captions.push({
                        uuid: file.upload.uuid,
                        caption: $(file.previewElement).find('.caption').find('input').val()
                    });
                });

                let self = this;
                let postData = {
                    title: this.title,
                    captions: captions,
                    content: this.content,
                    location: this.location,
                    locations: this.entryLocations,
                    files: this.filesAttachedToEntry
                };

                if (self.entryId) {
                    axios.put('/api/entry/' + self.entryId, postData)
                        .then(function (response) {
                            self.reset();
                            EventBus.$emit('refresh-trip');
                            $('.file-upload-row').remove();
                            $('.vue-dropzone .dz-default').show();
                        })
                        .catch(function (error) {
                            self.errors = error.response.data;
                        });
                } else {
                    axios.post('/api/entry', postData)
                        .then(function (response) {
                            self.reset();
                            EventBus.$emit('refresh-trip');
                            $('.file-upload-row').remove();
                            $('.vue-dropzone .dz-default').show();
                        })
                        .catch(function (error) {
                            self.errors = error.response.data;
                        });
                }

            },

            removeMarker(location, key) {
                let self = this;
                EventBus.$emit('remove-location', location);
                this.entryLocations.forEach(function(entryLocation, index) {
                    if (location.latitude == entryLocation.latitude && location.longitude == entryLocation.longitude) {
                       self.entryLocations.splice(index, 1);
                    }
                });
            },

            markerImageUrl(location) {
                return 'https://raw.githubusercontent.com/Concept211/Google-Maps-Markers/master/images/marker_blue' + (location.markerIdentifier) + '.png';
            },

            successUpload(file, response) {
                file.previewElement.querySelector("img").src = response[0].thumbnail;
                this.filesAttachedToEntry.push(response[0].filename);
            },

            removeFile(file) {
                axios.delete('/api/files/uuid/' + file.upload.uuid)
                    .then(function (response) {})
                    .catch(function (error) {});
            }

        }

    }
</script>
