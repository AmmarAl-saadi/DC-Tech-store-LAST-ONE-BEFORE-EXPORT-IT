<template>
  <div class="group bg-white rounded-3xl border border-slate-100 overflow-hidden hover:shadow-2xl transition-all duration-500 cursor-pointer flex flex-col h-full relative">
    
    <!-- Compare Button Overlay -->
    <div class="absolute top-4 right-4 z-20 opacity-0 group-hover:opacity-100 transition-opacity">
       <button
          aria-label="Compare"
          class="p-2.5 bg-white text-slate-400 rounded-xl shadow-lg hover:bg-blue-600 hover:text-white transition-all transform hover:rotate-12"
          :title="$t('product.compare')"
          @click.prevent="addToCompare"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
        </button>
    </div>

    <nuxt-link
      :to="productLink(product)"
      class="flex flex-col h-full"
      :title="product.title"
      @click="goToProduct"
    >
      <!-- Product Image Container -->
      <div class="relative h-56 bg-slate-50 flex items-center justify-center overflow-hidden">
        <!-- Sale Badge -->
        <span
          class="absolute top-4 left-4 bg-blue-600 text-white text-[10px] font-black px-3 py-1 rounded-full z-10 shadow-lg shadow-blue-200 uppercase tracking-widest"
        >
          Flash Sale
        </span>

        <div class="p-8 w-full h-full flex items-center justify-center transform group-hover:scale-110 transition-transform duration-700">
          <ImageLazy
            :lazy-src="getThumbImageURL(product.image)"
            :title="product.title"
            :alt="product.title"
            class="max-h-full max-w-full object-contain drop-shadow-2xl"
          />
        </div>
      </div>

      <!-- Product Content -->
      <div class="p-6 flex flex-col flex-grow space-y-4">
        <h5 class="text-sm font-bold text-slate-800 leading-snug line-clamp-2 group-hover:text-blue-600 transition-colors">
          {{product.title}}
        </h5>

        <!-- Pricing -->
        <div class="mt-auto pt-2 flex items-center justify-between">
          <div class="flex flex-col">
            <span class="text-[11px] text-slate-300 line-through font-medium">
              <price-format :price="prevPrice" />
            </span>
            <div class="flex items-center gap-2">
              <span class="text-lg font-black text-blue-600 tracking-tight">
                <price-format :price="reducedPrice" />
              </span>
              <span class="text-[9px] font-black bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded">
                -{{reducedPercent}}%
              </span>
            </div>
          </div>
          
          <button class="p-2.5 bg-slate-50 text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all transform active:scale-90">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </button>
        </div>
      </div>
    </nuxt-link>
  </div>
</template>

<script setup>
  import {useCommonStore} from "~/store/common";
  import {storeToRefs} from "pinia";
  import {useAuthStore} from "../store/auth";
  import {usePriceHelper} from "../composables/usePriceHelper";
  import {useCompareHelper} from "../composables/useCompareHelper";
  import {useUtils} from "../composables/useUtils";
  import {defineEmits} from "vue";
  import {useDetailStore} from "../store/detail";

  const props = defineProps({
    flashProduct: {
      type: Object,
      default() {
        return null
      },
    }
  });

  const {flashProduct} = toRefs(props);

  const commonStore = useCommonStore()
  const {currencyIcon, setting} = storeToRefs(commonStore)
  const {unAuthPost, setToastMessage, setToastError} = commonStore

  const authStore = useAuthStore();
  const {authenticated} = storeToRefs(authStore);

  const {getThumbImageURL, productLink } = useUtils();

  const product = computed(() => {
    return flashProduct.value?.product_data || flashProduct.value
  });

  const emit = defineEmits(['removed']);

  const { prevPrice, currentPricing } = usePriceHelper({product});

  const reducedPercent = computed(() => {
    return 100 - parseInt(((reducedPrice.value / prevPrice.value) * 100).toString())
  });

  const {ajaxingCompare, addToCompare} = useCompareHelper({product, emit});

  const reducedPrice = computed(() => {
    return flashProduct.value?.price
  });

  const quantity = computed(() => {
    return flashProduct.value?.quantity || 0
  });

  const sold = computed(() => {
    return flashProduct.value?.sold || 0
  });

  const detailStore = useDetailStore();
  const {setProduct} = detailStore;

  const goToProduct = () => {
    setProduct(product.value);
  };

</script>
