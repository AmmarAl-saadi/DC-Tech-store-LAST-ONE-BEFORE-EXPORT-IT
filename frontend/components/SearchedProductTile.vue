<template>
  <nuxt-link
    :to="productLink(product)"
    class="group block bg-white rounded-2xl border border-slate-100 p-4 hover:shadow-xl transition-all duration-300"
    @click="goToProduct"
  >
    <div class="flex items-center gap-5">
      <!-- Thumbnail -->
      <div class="w-16 h-16 flex-shrink-0 bg-slate-50 rounded-xl overflow-hidden flex items-center justify-center p-2 transform group-hover:scale-105 transition-transform">
        <ImageLazy
          :lazy-src="getThumbImageURL(product.image)"
          :title="product.title"
          :alt="product.title"
          class="max-h-full max-w-full object-contain"
        />
      </div>

      <!-- Info -->
      <div class="flex-grow min-w-0 space-y-1">
        <h5 class="text-xs font-bold text-slate-800 truncate group-hover:text-blue-600 transition-colors">
          {{product.title}}
        </h5>
        
        <div class="flex items-center gap-3">
          <div class="flex flex-col">
            <span v-if="prevPrice" class="text-[10px] text-slate-300 line-through">
              <price-format :price="prevPrice" />
            </span>
            <span class="text-sm font-black text-slate-900">
              <price-format :price="currentPricing" />
            </span>
          </div>
          <span v-if="reducedPercent" class="text-[9px] font-black bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded uppercase">
            -{{reducedPercent}}%
          </span>
        </div>
      </div>

      <!-- Quick Action -->
      <button
        aria-label="Compare"
        class="flex-shrink-0 p-2 bg-slate-50 text-slate-400 rounded-lg hover:bg-blue-600 hover:text-white transition-all transform active:scale-90"
        :title="$t('product.compare')"
        @click.prevent="addToCompare"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
      </button>
    </div>
  </nuxt-link>
</template>

<script setup>
  import {defineEmits} from "vue";
  import {useUtils} from "../composables/useUtils";
  import {usePriceHelper} from "../composables/usePriceHelper";
  import {toRefs} from "vue";
  import {useCompareHelper} from "../composables/useCompareHelper";
  import {useDetailStore} from "../store/detail";

  const props = defineProps({
    product: {
      type: Object,
      default() {
        return null;
      }
    },
  });

  const {product} = toRefs(props);
  const emit = defineEmits(['removed', 'on-click']);

  const {prevPrice, currentPricing, reducedPercent} = usePriceHelper({product});
  const {getThumbImageURL, productLink} = useUtils();
  const {ajaxingCompare, addToCompare} = useCompareHelper({product, emit});

  const detailStore = useDetailStore();
  const {setProduct} = detailStore;

  const goToProduct = () => {
    setProduct(product.value);
    emit('on-click');
  };

</script>
