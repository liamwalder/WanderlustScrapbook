<template>
    <div class="location-images">
        <h5 v-if="viewAll">Gallery <span class="badge badge-pill badge-info" v-show="viewAll">{{ images.length }}</span></h5>
        <div class="images">
            <div v-for="image, imageIndex in images.slice(0, maximumImageCount)" class="image-holder">
                <b-tooltip v-bind:target="imageCaptionId(image)" placement="bottom">{{ image.caption }}</b-tooltip>
                <span class="caption" v-bind:id="imageCaptionId(image)" v-if="image.caption">
                    <i class="fa fa-info"></i>
                </span>
                <span class="option-dropdown dropdown" v-if="editMode">
                    <span  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                    </span>
                    <div class="dropdown-menu image-dropdown" aria-labelledby="dropdownMenuButton">
                        <span class="dropdown-item">
                            <label>Caption</label>
                            <input
                                class="form-control"
                                placeholder="Caption"
                                v-model="image.caption"
                                v-on:blur="saveCaption(image)"
                            />
                        </span>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="#" @click="deleteImage(image)">Delete</a>
                    </div>
                </span>
                <div
                    class="image"
                    @click="openGallery(vueGalleryFiles, imageIndex)"
                    :style="{ backgroundImage: 'url(' + image.thumbnail + ')' }"
                ></div>
            </div>

            <div v-for="image, imageIndex in images.slice(maximumImageCount)" v-show="allImages || !viewAll" class="image-holder">
                <b-tooltip v-bind:target="imageCaptionId(image)" placement="bottom">{{ image.caption }}</b-tooltip>
                <span class="caption" v-bind:id="imageCaptionId(image)" v-if="image.caption">
                    <i class="fa fa-info"></i>
                </span>
                <span class="option-dropdown dropdown" v-if="editMode">
                    <span  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                    </span>
                    <div class="dropdown-menu image-dropdown" aria-labelledby="dropdownMenuButton">
                        <span class="dropdown-item">
                            <label>Caption</label>
                            <input
                                class="form-control"
                                placeholder="Caption"
                                v-model="image.caption"
                                v-on:blur="saveCaption(image)"
                            />
                        </span>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="#" @click="deleteImage(image)">Delete</a>
                    </div>
                </span>
                <div
                    class="image"
                    @click="openGallery(vueGalleryFiles, imageIndex)"
                    :style="{ backgroundImage: 'url(' + image.thumbnail + ')' }"
                ></div>
            </div>
            <div class="view-all" v-if="viewAll">
                <div v-if="images.length > maximumImageCount">
                    <div v-show="!allImages">
                        <a v-on:click="toggleFileDisplay(true)" class="show-indicator">Show more</a>
                    </div>
                    <a v-show="allImages" v-on:click="toggleFileDisplay(false)" class="show-indicator">Show less</a>
                </div>
            </div>
        </div>
        <p class="notice" v-if="images.length == 0">No files yet added to the gallery.</p>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus';
    import { mixin as clickaway } from 'vue-clickaway';

    export default {

        mixins: [ clickaway ],

        props: ['images', 'viewAll'],

        data () {
            return {
                maximumImageCount: 4
            }
        },

        computed: {
            editMode() {
                return this.$store.getters.editMode;
            },

            allImages() {
                return this.$store.getters.contentSidebarState.viewingAllImages;
            },

            vueGalleryFiles() {
                let mediaFiles = [];
                this.images.forEach(function (image) {
                    mediaFiles.push({
                        title: image.caption,
                        type: image.mime,
                        href: image.filename,
                        poster: image.thumbnail
                    })
                });
                return mediaFiles;
            }

        },

        methods: {

            imageCaptionId(image) {
                return image.id + '-caption';
            },

            saveCaption(file) {
                axios.put('/api/media/' + file.id, {caption: file.caption})
                    .then(function (response) {
                        EventBus.$emit('refresh-trip');
                    })
                    .catch(function (error) {});
            },

            toggleFileDisplay(state) {
                this.$store.commit('viewAllImages', { state: state });
            },

            openGallery(images, index) {
                EventBus.$emit('open-gallery', {
                    files: images,
                    index: index
                })
            },

            deleteImage(file) {
                let result = confirm("Click 'Ok' to confirm your deletion.");
                if (result) {
                    axios.delete('/api/files/' + file.id)
                        .then(function (response) {
                            EventBus.$emit('refresh-trip');
                        })
                        .catch(function (error) {});
                }
            }
        }

    }
</script>