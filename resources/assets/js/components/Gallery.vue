<template>
    <div>
        <gallery :images="galleryImages"
                 :index="galleryImageIndex"
                 @close="galleryImageIndex = null"
        ></gallery>
    </div>
</template>

<script>
    import VueGallery from 'vue-gallery';
    import { EventBus } from '../event-bus';

    export default {

        components: {
            'gallery': VueGallery
        },

        props: ['images', 'index'],

        created() {
            let self = this;
            EventBus.$on('open-gallery', function(data) {
                self.galleryImages = data.files;
                self.galleryImageIndex = data.index;
            });
        },

        data () {
            return {
                galleryImages: [],
                galleryImageIndex: null
            }
        }

    }
</script>