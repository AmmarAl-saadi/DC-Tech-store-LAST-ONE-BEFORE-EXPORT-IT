<template>
  <div style="background:#F5F5F5;min-height:100vh;padding-bottom:60px;">
    <!-- BREADCRUMB -->
    <div style="background:#FFFFFF;border-bottom:1px solid #EEEEEE;padding:12px 0;">
      <div class="max-w-[980px] mx-auto px-4">
        <div style="display:flex;align-items:center;font-size:13px;color:#888888;flex-wrap:wrap;gap:8px;">
          <nuxt-link to="/" style="color:#154992;text-decoration:none;">Home</nuxt-link>
          <span>/</span>
          <nuxt-link to="/categories" style="color:#154992;text-decoration:none;">Categories</nuxt-link>
          <span>/</span>
          <span style="color:#333333;font-weight:600;">{{ category?.title }}</span>
        </div>
      </div>
    </div>

    <!-- CATEGORY HEADER -->
    <div style="background:#FFFFFF;padding:40px 0;border-bottom:1px solid #EEEEEE;">
      <div class="max-w-[980px] mx-auto px-4">
        <div style="display:flex;align-items:center;gap:30px;flex-direction:row;" class="cat-header-flex">
          <div v-if="category?.image" style="width:120px;height:120px;background:#F9F9F9;border:1px solid #EEEEEE;display:flex;align-items:center;justify-content:center;overflow:hidden;border-radius:8px;flex-shrink:0;">
            <img :src="categoryImage(category)" :alt="category?.title" style="width:80%;height:80%;object-fit:contain;">
          </div>
          <div style="flex:1;">
            <h1 style="font-size:28px;font-weight:700;color:#333333;margin:0 0 8px 0;">{{ category?.title }}</h1>
            <p v-if="category?.meta_description" style="color:#666666;max-width:700px;margin:0;line-height:1.6;font-size:15px;">{{ category?.meta_description }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- CONTENT SECTION -->
    <div class="max-w-[980px] mx-auto px-4 mt-8">
      
      <!-- SUB-CATEGORIES GRID -->
      <template v-if="fetching">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
          <div v-for="i in 10" :key="i" class="h-[160px] bg-white rounded-lg animate-pulse border border-gray-100"></div>
        </div>
      </template>

      <template v-else-if="category?.sub_categories?.length">
        <div style="display:flex;align-items:center;margin-bottom:24px;">
          <div style="background:#154992;padding:6px 14px;flex-shrink:0;">
            <span style="color:#FFFFFF;font-size:13px;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;">Sub-Categories</span>
          </div>
          <div style="height:2px;flex-grow:1;background:#DDDDDD;margin-left:12px;"></div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
          <nuxt-link 
            v-for="sub in category.sub_categories" 
            :key="sub.id"
            :to="`/categories/${sub.slug}`"
            style="background:#FFFFFF;border:1px solid #EEEEEE;padding:20px;display:flex;flex-direction:column;align-items:center;text-decoration:none;transition:all 0.3s;border-radius:8px;height:100%;"
            class="hover:shadow-md hover:border-[#154992] group"
          >
            <div style="width:70px;height:70px;display:flex;align-items:center;justify-content:center;margin-bottom:15px;overflow:hidden;flex-shrink:0;">
              <img :src="thumbImageURL(sub)" :alt="sub.title" style="width:100%;height:100%;object-fit:contain;transition:transform 0.3s;" class="group-hover:scale-110">
            </div>
            <span style="font-size:14px;font-weight:600;color:#333333;text-align:center;display:block;line-height:1.3;">{{ sub.title }}</span>
          </nuxt-link>
        </div>
      </template>

      <!-- NO SUB-CATEGORIES - SHOW PRODUCTS ACTION -->
      <template v-else>
        <div style="background:#FFFFFF;border:1px solid #EEEEEE;padding:80px 20px;text-align:center;border-radius:8px;">
          <div style="font-size:64px;margin-bottom:20px;">📦</div>
          <h3 style="font-size:22px;font-weight:700;color:#333333;margin-bottom:12px;">{{ category?.title }}</h3>
          <p style="color:#888888;margin-bottom:30px;max-width:400px;margin-left:auto;margin-right:auto;font-size:16px;">Browse our full selection of products in this category.</p>
          <nuxt-link :to="`/${category?.slug}/products`" style="background:#154992;color:#FFFFFF;padding:12px 32px;border-radius:6px;text-decoration:none;font-weight:700;display:inline-block;transition:all 0.2s;" class="hover:bg-[#0d3a7a] hover:scale-105">View All Products</nuxt-link>
        </div>
      </template>

    </div>
  </div>
</template>

<script setup>
import { useCommonStore } from '~/store/common';
import { useLanguageStore } from '~/store/language';
import { storeToRefs } from 'pinia';
import { onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';

definePageMeta({
  middleware: ['common-middleware'],
  layout: 'default',
});

const route = useRoute();
const commonStore = useCommonStore();
const languageStore = useLanguageStore();
const { langCode } = storeToRefs(languageStore);
const { getRequest } = commonStore;

const category = ref(null);
const fetching = ref(true);

const fetchCategory = async () => {
  fetching.value = true;
  try {
    const response = await getRequest({
      api: 'category',
      params: `/${route.params.slug}`,
      lang: langCode.value
    });
    if (response?.data) {
      category.value = response.data;
      
      // Update Page Title
      useHead({
        title: `${category.value.title} | DC Tech Store`,
        meta: [
          { name: 'description', content: category.value.meta_description || '' },
          { name: 'keywords', content: category.value.meta_keywords || '' }
        ]
      });
    }
  } catch (e) {
    console.error('Error fetching category:', e);
  } finally {
    fetching.value = false;
  }
};

const { thumbImageURL, categoryImage } = useImageHelper();

onMounted(() => {
  fetchCategory();
});

watch(() => route.params.slug, () => {
  fetchCategory();
});
</script>

<style scoped>
@media (max-width: 640px) {
  .cat-header-flex {
    flex-direction: column !important;
    text-align: center;
    gap: 15px !important;
  }
  .cat-header-flex p {
    margin: 0 auto !important;
  }
}
</style>
