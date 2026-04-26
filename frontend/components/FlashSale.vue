<template>
  <div v-if="flashSales && flashSales.length">
    <div class="flash-slider">
      <div>
        <div
          class="area home-section"
          v-for="(flashSale, index) in flashSales"
          :key="index"
        >
          <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-6">
              <h2 class="text-xl font-black tracking-tight uppercase text-slate-900">{{ flashSale.title }}</h2>
              <countdown
                :end-time="flashSale.end_time"
                class="bg-blue-50 text-blue-600 px-4 py-1.5 rounded-full text-xs font-black"
              />
            </div>
            <NuxtLink
              class="text-blue-600 text-xs font-bold hover:underline tracking-widest uppercase"
              :to="`/flash-sale/${flashSale.id}`"
            >
              {{ $t('featured.showAll') }}
            </NuxtLink>
          </div>
          <div class="area-content">

            <image-slider
              v-if="flashSale.products && flashSale.products.length"
              :addInitEvt="true"
              :image-count="flashSale.products.length"
            >
              <template v-slot:content>
                <li
                  v-for="(value, index) in flashSale.products"
                  :key="index"
                  class="center-text"
                >
                  <flash-product-tile
                    :flash-product="value"
                  />
                </li>
              </template>
            </image-slider>

          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
  const props = defineProps({
    deactivate: {
      type: Boolean,
      default: true,
    },
    flashSales: {
      type: Array,
      default() {
        return []
      }
    }
  })

</script>
