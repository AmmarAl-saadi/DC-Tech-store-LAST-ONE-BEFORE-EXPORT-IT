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
          :to="`/${brand.slug}/brand?brand=${brand.id}`"
          class="brand-card group bg-white border border-gray-200 p-4 flex items-center justify-center h-36 transition-all hover:shadow-xl hover:border-[#154992] relative overflow-hidden"
        >
          <img :src="brand.image" :alt="brand.name" class="max-h-full max-w-full object-contain transform scale-125 group-hover:scale-150 transition-transform duration-500 relative z-10">
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
    { id: 9442200, name: 'HIKVISION', slug: 'HIKVISION', image: '/uploads/brand-1776677217-2.png' },
    { id: 9442203, name: 'GAMEMAX', slug: 'gamemax', image: '/uploads/brand-1776677854-7.png' },
    { id: 9442213, name: 'AULA', slug: 'aula', image: '/uploads/brand-1776856858-9.png' },
    { id: 9442214, name: 'DRAGON WAR', slug: 'dragon-war', image: '/uploads/brand-1776856909-7.png' },
    { id: 9442215, name: 'DATALOGIC', slug: 'datalogic', image: '/uploads/brand-1776856976-5.png' },
    { id: 9442216, name: 'ezviz', slug: 'ezviz', image: '/uploads/brand-1776857055-3.png' },
    { id: 9442217, name: 'GLINK', slug: 'glink', image: '/uploads/brand-1776857144-7.png' },
    { id: 9442218, name: 'HIKSEMI', slug: 'hiksemi', image: '/uploads/brand-1776857176-8.png' },
    { id: 9442219, name: 'JEQANG', slug: 'jeqang', image: '/uploads/brand-1776857213-5.png' },
    { id: 9442220, name: 'MARVO', slug: 'marvo', image: '/uploads/brand-1776857238-5.png' },
    { id: 9442221, name: 'tp-link', slug: 'tp-link', image: '/uploads/brand-1776857270-7.png' },
    { id: 9442222, name: 'Transcend', slug: 'transcend', image: '/uploads/brand-1776857303-1.png' },
    { id: 9442223, name: 'UNIQ', slug: 'uniq', image: '/uploads/brand-1776857360-6.png' },
    { id: 9442224, name: 'XTRIKE ME', slug: 'xtrike-me', image: '/uploads/brand-1776857403-8.png' },
    { id: 9442225, name: 'YORO', slug: 'yoro', image: '/uploads/brand-1776857464-9.png' },
    { id: 9442226, name: 'dahua', slug: 'dahua', image: '/uploads/brand-1776857528-9.png' }
  ];
</script>

<style scoped>
.brand-card {
  border-radius: 4px;
}
</style>
