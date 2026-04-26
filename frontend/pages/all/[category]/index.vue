<template>
  <div class="category-page-wrapper">
    <!-- Category Hero Banner -->
    <div v-if="category" class="category-hero-section" :style="heroStyle">
      <div class="container-fluid hero-container">
        <div class="hero-text-content">
          <h1 class="hero-category-title">{{ categoryTitle }}</h1>
          <div class="hero-breadcrumb-nav">
             <nuxt-link to="/" class="breadcrumb-link">Home</nuxt-link>
             <span class="breadcrumb-separator">/</span>
             <span class="breadcrumb-current">{{ categoryTitle }}</span>
          </div>
        </div>
      </div>
    </div>

    <product-list
      v-if="category"
      :result-title="categoryTitle"
      :has-breadcrumb="false"
      :categories="[category]"
      :fetching-product-data="fetchingProductData"
      @fetch-data="fetchingData"
    />
  </div>
</template>

<script setup>
  import {useCommonStore} from "~/store/common";
  import {useListingStore} from "~/store/listing";
  import {useLanguageStore} from "~/store/language";
  import {storeToRefs} from "pinia";
  import {prepareGetUrl} from "~/utils/fetchClient";
  import {useAsyncData} from "nuxt/app";
  import {computed} from "vue";
  import {useUtils} from "~/composables/useUtils";
  import {useMetaData} from "~/composables/useMetaData";

  definePageMeta({
    middleware: ['common-middleware'],
    layout: 'default',
  });

  const commonStore = useCommonStore();
  const {customScripts} = storeToRefs(commonStore);
  const {unAuthGet} = commonStore;

  const listingStore = useListingStore();
  const {brands, shippingRules, collections, categoryData} = storeToRefs(listingStore);
  const {emptyProducts, setProducts} = listingStore;

  const languageStore = useLanguageStore();
  const {langCode} = storeToRefs(languageStore);
  const route = useRoute();

  const categoryKey = `category-${route.params.category}}`;

  const fetchCategoryData = async () => {
    return await unAuthGet({
      api: 'all',
      params: `?${prepareGetUrl({
        category: route?.params?.category,
        sortby: route.query.sortby || '',
        shipping: route.query.shipping || '',
        brand: route.query.brand || '',
        collection: route.query.collection || '',
        rating: route.query.rating || 0,
        max: route?.query?.max || 0,
        min: route?.query?.min || 0,
        page: route.query.page || '',
        sidebar_data: !brands.value || !shippingRules.value || !collections.value
      })}`,
      lang: langCode.value
    });
  };

  const {data: category, pending, error} = await useAsyncData(categoryKey, async () => {
    const result = await fetchCategoryData();
    setProducts(result)
    return result.data.category;
  });

  const {pageMeta} = useMetaData();
  const {getImageURL} = useUtils();

  watch(() => route.query, () => {
    fetchingData();
  });

  useHead(pageMeta({
    meta_title: category.value?.meta_title,
    meta_description: category.value?.meta_description,
    meta_keywords: category.value?.meta_keywords,
    image_url: getImageURL(category.value?.image)
  }));

  const categoryTitle = computed(() => {
    return category.value.title;
  });

  const heroStyle = computed(() => {
    const slug = category.value?.slug;
    let bgImage = '/categories/cat_accessories.png'; // Fallback

    if (slug === 'computer-systems') bgImage = '/categories/cat_computer_systems.png';
    else if (slug === 'components') bgImage = '/categories/cat_components.png';
    else if (slug === 'security-solutions') bgImage = '/categories/cat_security.png';
    else if (slug === 'point-of-sale') bgImage = '/categories/cat_pos.png';
    else if (slug === 'gaming') bgImage = '/categories/cat_gaming.png';
    else if (slug === 'connectors') bgImage = '/categories/cat_connectors.png';
    else if (slug === 'camera-recorder') bgImage = '/categories/cat_camera.png';
    else if (slug === 'network') bgImage = '/categories/cat_network.png';
    else if (slug === 'audio') bgImage = '/categories/cat_audio.png';
    else if (slug === 'accessories') bgImage = '/categories/cat_accessories.png';

    return {
      backgroundImage: `linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url(${bgImage})`,
      backgroundSize: 'cover',
      backgroundPosition: 'center',
    };
  });

  const fetchingProductData = ref(false);

  const fetchingData = async () => {
    fetchingProductData.value = true;

    if (route.params.category !== category.value.slug) {
      return false;
    }

    emptyProducts();
    const result = await fetchCategoryData();
    setProducts(result);
    fetchingProductData.value = false;
  };

</script>

<style scoped>
.category-hero-section {
  height: 220px;
  display: flex;
  align-items: center;
  position: relative;
  margin-bottom: 20px;
  background-color: #f4f4f4;
}
.hero-container {
  width: 100%;
}
.hero-text-content {
  color: #FFFFFF;
}
.hero-category-title {
  font-size: 36px;
  font-weight: 800;
  text-transform: uppercase;
  margin-bottom: 8px;
  letter-spacing: -0.02em;
}
.hero-breadcrumb-nav {
  font-size: 14px;
  font-weight: 500;
}
.breadcrumb-link {
  color: #FFFFFF;
  text-decoration: none;
  opacity: 0.8;
}
.breadcrumb-link:hover {
  opacity: 1;
  text-decoration: underline;
}
.breadcrumb-separator {
  margin: 0 10px;
  opacity: 0.6;
}
.breadcrumb-current {
  opacity: 0.9;
}

@media (max-width: 576px) {
  .category-hero-section {
    height: 160px;
  }
  .hero-category-title {
    font-size: 24px;
  }
}
</style>
