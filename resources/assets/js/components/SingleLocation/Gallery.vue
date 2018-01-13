<template>
    <div class="location-images">
        <h5 v-if="viewAll">Gallery <span class="badge badge-pill badge-info" v-show="viewAll">{{ images.length }}</span></h5>
        <div class="images">
            <div v-for="image, imageIndex in images.slice(0, maximumImageCount)" class="image-holder">
                <span class="delete" @click="deleteImage(image)" v-if="editMode">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </span>
                <div
                    class="image"
                    @click="openGallery(vueGalleryFiles, imageIndex)"
                    :style="{ backgroundImage: 'url(' + image.filename + ')' }"
                ></div>
            </div>
            <div v-for="image, imageIndex in images.slice(maximumImageCount)" v-show="allImages || !viewAll" class="image-holder">
                <span class="delete" @click="deleteImage(image)" v-if="editMode">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </span>
                <div
                    class="image"
                    @click="openGallery(vueGalleryFiles, imageIndex)"
                    :style="{ backgroundImage: 'url(' + image.filename + ')' }"
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

    export default {

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
                        type: image.mime,
                        href: image.filename
                    })
                });
                return mediaFiles;
            }

        },

        methods: {

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
                let result = confirm("Are you sure you want to delete  this image?");
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