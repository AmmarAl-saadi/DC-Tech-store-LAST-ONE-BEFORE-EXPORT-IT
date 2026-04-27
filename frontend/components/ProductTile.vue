<template>
  <!-- CityCenter.jo Exact Spec Product Card -->
  <div class="group relative bg-white flex flex-col h-full" style="border:1px solid #DDDDDD;">

    <!-- Sale Badge -->
    <div v-if="badge || reducedPercent" class="absolute top-2 left-2 z-10">
      <span v-if="badge" class="text-white text-[11px] font-bold px-2 py-0.5 inline-block" style="background:#FF6600;">{{ badge }}</span>
      <span v-else-if="reducedPercent" class="text-white text-[11px] font-bold px-2 py-0.5 inline-block" style="background:#154992;">-{{ reducedPercent }}%</span>
    </div>

    <!-- Compare Btn -->
    <div class="absolute top-2 right-2 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
      <slot name="floating-btn">
        <button
          aria-label="Compare"
          class="w-6 h-6 bg-white border text-gray-500 hover:text-white transition-all flex items-center justify-center text-[10px]"
          style="border-color:#DDDDDD;"
          @click.prevent="() => {addToCompare(true)}"
        >
          ⇄
        </button>
      </slot>
    </div>

    <nuxt-link :to="productLink(product)" class="flex flex-col h-full" draggable="false" @click="goToProduct">
      <!-- Image -->
      <div class="flex items-center justify-center overflow-hidden" style="height:180px;background:#FFFFFF;padding:12px;">
        <div class="w-full h-full flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300">
          <ImageLazy v-if="isLazyImage" :lazy-src="getThumbImageURL(product.image)" :title="product.title" :alt="product.title" class="max-h-full max-w-full object-contain"/>
          <img v-else :src="getThumbImageURL(product.image)" :title="product.title" :alt="product.title" class="max-h-full max-w-full object-contain">
        </div>
      </div>

      <!-- Info -->
      <div class="flex flex-col flex-grow p-3" style="border-top:1px solid #EEEEEE;">
        <h5 class="leading-snug line-clamp-2 mb-2 transition-colors min-h-[2.5rem]" style="font-size:13px;color:#333333;font-weight:400;">
          {{ product.title }}
        </h5>

        <div class="mt-auto pt-2" style="border-top:1px solid #EEEEEE;">
          <div class="flex flex-col mb-2">
            <span v-if="prevPrice" class="strike-through" style="font-size:10px;color:#999999;text-decoration:line-through !important; line-height: 1;">
              <price-format :price="prevPrice" />
            </span>
            <span class="font-black text-black tracking-tight whitespace-nowrap" style="font-size:14px; line-height: 1.2;">
              <price-format :price="currentPricing" />
            </span>
          </div>
        </div>
      </div>
    </nuxt-link>
  </div>
</template>

<script setup>
  import {useCompareHelper} from "~/composables/useCompareHelper";
  import {toRefs} from "vue";
  import {usePriceHelper} from "~/composables/usePriceHelper";
  import {useUtils} from "~/composables/useUtils";
  import {storeToRefs} from "pinia";
  import {useDetailStore} from "../store/detail";

  const props = defineProps({
    product: { type: Object, default() { return null; } },
    isLazyImage: { type: Boolean, default: true },
    compared: { type: Boolean, default: false },
    titleEllipsis: { type: Number, default: 2 },
  });
  const {product, isLazyImage, compared, titleEllipsis} = toRefs(props);
  const emit = defineEmits(['removed']);
  const {ajaxingCompare, addToCompare} = useCompareHelper({product, emit});
  defineExpose({ addToCompare });
  const { prevPrice, currentPricing, reducedPercent } = usePriceHelper({product});
  const {getThumbImageURL, productLink} = useUtils();
  const badge = computed(() => product.value?.badge);
  const detailStore = useDetailStore();
  const {setProduct} = detailStore;
  const goToProduct = () => { setProduct(product.value); };
</script>
