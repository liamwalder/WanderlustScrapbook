<template>
    <div class="location-images">
        <h5 v-if="viewAll">
            Gallery <span class="badge badge-pill badge-info" v-show="viewAll">{{ images.length }}</span>
            <div class="pull-right view-all" v-if="viewAll">
                <div v-if="images.length > maximumImageCount">
                    <div v-show="!allImages">
                        <a v-on:click="toggleFileDisplay(true)" class="pull-right">Show all</a>
                    </div>
                    <a v-show="allImages" v-on:click="toggleFileDisplay(false)" class="pull-right">Show less</a>
                </div>
            </div>
        </h5>
        <div class="images">
            <div v-for="image, imageIndex in images.slice(0, maximumImageCount)" class="image-holder">
                <span class="caption" v-bind:id="imageCaptionId(image)" v-if="image.caption">
                    <i class="fa fa-info"></i>
                </span>
                <b-tooltip v-bind:target="imageCaptionId(image)" placement="top" v-if="image.caption">{{ image.caption }}</b-tooltip>
                <span class="option-dropdown dropdown" v-if="editMode">
                    <span  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                    </span>
                    <div class="dropdown-menu image-dropdown" aria-labelledby="dropdownMenuButton">
                        <span class="dropdown-item">
                            <label>Edit Caption</label>
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
                <span class="caption" v-bind:id="imageCaptionId(image)" v-if="image.caption">
                    <i class="fa fa-info"></i>
                </span>
                <b-tooltip v-bind:target="imageCaptionId(image)" placement="top" v-if="image.caption">{{ image.caption }}</b-tooltip>
                <span class="option-dropdown dropdown" v-if="editMode">
                    <span  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                    </span>
                    <div class="dropdown-menu image-dropdown" aria-labelledby="dropdownMenuButton">
                        <span class="dropdown-item">
                            <label>Edit Caption</label>
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
        </div>
        <p class="notice" v-if="images.length == 0">No files yet added to the gallery.</p>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus';

    export default {

        props: ['images', 'viewAll', 'galleryIdPrefix'],

        data () {
            return {
                maximumImageCount: 3
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
                return this.galleryIdPrefix + '-' + image.id + '-caption';
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