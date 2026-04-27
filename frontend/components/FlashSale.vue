<template>
  <div v-if="flashSales && flashSales.length">
    <div class="flash-slider">
      <div>
        <div
          class="area home-section"
          v-for="(flashSale, index) in flashSales"
          :key="index"
        >
          <div class="flex items-center justify-between px-4 py-2" style="border-bottom:2px solid #154992;">
            <div class="flex items-center gap-4">
              <h2 class="font-bold uppercase" style="font-size:14px;color:#154992;">{{ flashSale.title }}</h2>
            </div>
            <NuxtLink
              class="font-bold uppercase ml-4"
              style="font-size:12px;color:#000000;"
              :to="`/flash-sale/${flashSale.id}`"
            >
              <span class="underline">{{ $t('featured.showAll') }}</span>
            </NuxtLink>
          </div>
          <div class="area-content">
            <div
              v-if="flashSale.products && flashSale.products.length"
              class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5"
              style="border-top:1px solid #DDDDDD;border-left:1px solid #DDDDDD;"
            >
              <flash-product-tile
                v-for="(value, index) in flashSale.products"
                :key="index"
                :flash-product="value"
                style="border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;"
              />
            </div>
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
