<template>
    <div class="location-images">
        <h5>Gallery <span class="badge badge-pill badge-info" v-show="viewAll">{{ images.length }}</span></h5>
        <div class="location-images">
            <div
                    class="image"
                    v-for="image, imageIndex in images.slice(0, maximumImageCount)"
                    @click="openGallery(images, imageIndex)"
                    :style="{ backgroundImage: 'url(' + image.filename + ')' }"
            >
            </div>
            <div
                    class="image"
                    v-for="image, imageIndex in images.slice(maximumImageCount)"
                    @click="openGallery(images, imageIndex)"
                    :style="{ backgroundImage: 'url(' + image.filename + ')' }"
                    v-show="allImages || !viewAll"
            >
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
            allImages() {
                return this.$store.getters.contentSidebarState.viewingAllImages;
            }
        },

        methods: {

            toggleFileDisplay(state) {
                this.$store.commit('viewAllImages', { state: state });
            },

            openGallery(images, index) {
                EventBus.$emit('open-gallery', {
                    images: images,
                    index: index
                })
            }
        }

    }
</script>