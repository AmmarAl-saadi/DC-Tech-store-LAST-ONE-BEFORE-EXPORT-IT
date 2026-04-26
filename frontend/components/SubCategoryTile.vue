<template>
  <nuxt-link
    class="block page-link"
    :to="categoryLink(subCategory)"
    :title="subCategory.title"
  >
    <div class="img-wrapper">
      <ImageLazy
        :lazy-src="categoryImage(subCategory)"
        :title="subCategory.title"
        :alt="subCategory.title"
      />
    </div>
    <h5 class="item-title ellipsis ellipsis-1">
      {{ subCategory.title }}
    </h5>
  </nuxt-link>
</template>

<script>
  import ImageLazy from '~/components/ImageLazy'
  import util from '~/mixin/util'

  export default {
    name: 'SubCategoryTile',
    props: {
      category: {
        type: Object,
        default() {
          return null
        },
      },
      subCategory: {
        type: Object,
        default() {
          return null
        },
      },
    },
    data() {
      return {
      }
    },
    components: {
      ImageLazy
    },
    mixins: [util],
    computed: {
    },
    mounted() {
    },
    methods: {
      categoryImage(subCategory) {
        const premiumImages = {
          'computer-systems': '/categories/cat_computer_systems.png',
          'components': '/categories/cat_components.png',
          'security-solutions': '/categories/cat_security.png',
          'point-of-sale': '/categories/cat_pos.png',
          'gaming': '/categories/cat_gaming.png',
          'connectors-and-convertors': '/categories/cat_connectors.png',
          'camera-and-recorder': '/categories/cat_camera.png',
          'network': '/categories/cat_network.png',
          'audio': '/categories/cat_audio.png',
          'accessories': '/categories/cat_accessories.png',
          'printers--supplies': '/categories/cat_printers_supplies.png',
          'cables': '/categories/cat_cables.png'
        };

        if (subCategory && premiumImages[subCategory.slug]) {
          return premiumImages[subCategory.slug];
        }
        return this.thumbImageURL(subCategory);
      }
    },
  };
</script>
