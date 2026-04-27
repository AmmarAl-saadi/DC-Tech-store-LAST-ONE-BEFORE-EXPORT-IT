<template>
  <div style="background:#F5F5F5;min-height:100vh;padding-top:98px;">
    <div class="max-w-[980px] mx-auto px-4 py-8">
      
      <!-- Page Header -->
      <div class="mb-8 border-b-2 border-[#154992] pb-4">
        <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Our Brands</h1>
        <p class="text-sm text-slate-500 mt-1">Discover the leading technology brands we partner with.</p>
      </div>

      <!-- Brands Grid -->
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        <nuxt-link
          v-for="brand in staticBrands"
          :key="brand.slug"
          :to="`/brands/${brand.slug}`"
          class="brand-card group bg-white border border-gray-200 p-6 flex items-center justify-center h-28 transition-all hover:shadow-xl hover:border-[#154992] relative overflow-hidden"
        >
          <img :src="brand.image" :alt="brand.name" class="max-h-full max-w-full object-contain transform group-hover:scale-110 transition-transform duration-500 relative z-10">
          <div class="absolute inset-0 bg-[#154992]/0 group-hover:bg-[#154992]/5 transition-colors"></div>
        </nuxt-link>
      </div>

    </div>
  </div>
</template>

<script setup>
  import {useCommonStore} from "~/store/common";
  import {useMetaData} from "../composables/useMetaData";
  import {storeToRefs} from "pinia";

  definePageMeta({
    middleware: ['common-middleware'],
    layout: 'default',
  });

  const commonStore = useCommonStore();
  const {site_setting} = storeToRefs(commonStore);
  const {t} = useI18n();
  const {pageMeta} = useMetaData();

  useHead(pageMeta({
    ...site_setting.value,
    ...{meta_title: `Our Brands | ${site_setting.value?.meta_title}`}
  }));

  // Static brands list matching the homepage
  const staticBrands = [
    { name: 'HIKVISION',    slug: 'hikvision',   image: '/brands/brand_hikvision_black.png' },
    { name: 'GAMEMAX',      slug: 'gamemax',     image: '/brands/brand_gamemax_black.png' },
    { name: 'AULA',         slug: 'aula',        image: null },
    { name: 'DRAGON WAR',   slug: 'dragon-war',  image: null },
    { name: 'DATALOGIC',    slug: 'datalogic',   image: null },
    { name: 'EZVIZ',        slug: 'ezviz',       image: null },
    { name: 'GLINK',        slug: 'glink',       image: null },
    { name: 'HIKSEMI',      slug: 'hiksemi',     image: null },
    { name: 'JEQANG',       slug: 'jeqang',      image: null },
    { name: 'MARVO',        slug: 'marvo',       image: null },
    { name: 'TP-LINK',      slug: 'tp-link',     image: '/brands/brand_tplink_black.png' },
    { name: 'TRANSCEND',    slug: 'transcend',   image: null },
    { name: 'UNIQ',         slug: 'uniq',        image: null },
    { name: 'XTRIKE ME',    slug: 'xtrike-me',   image: null },
    { name: 'YORO',         slug: 'yoro',        image: null },
    { name: 'DAHUA',        slug: 'dahua',       image: null },
  ].map(b => ({
    ...b,
    // Use the same black design for placeholders
    image: b.image ?? `https://placehold.co/100x52/1a1a1a/ffffff?text=${encodeURIComponent(b.name)}&font=montserrat`
  }));
</script>

<style scoped>
.brand-card {
  border-radius: 4px;
}
</style>
