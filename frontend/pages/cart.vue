<template>
  <client-only>
    <div class="max-w-7xl mx-auto px-4 lg:px-8 py-12">
      <div class="flex flex-col lg:flex-row gap-12">
        <!-- Cart Items List -->
        <div class="flex-grow space-y-8">
          <div class="bg-white rounded-[2.5rem] p-8 lg:p-12 shadow-xl shadow-blue-900/5 border border-slate-100">
            <div class="flex items-center justify-between mb-10 pb-8 border-b border-slate-100">
              <h1 class="text-3xl font-black text-slate-900 tracking-tight">
                {{ $t('cart.shoppingCart') }}
              </h1>
              
              <div v-if="!checked.length">
                <span class="text-sm text-slate-400 font-medium mr-4">{{ $t('cart.noSelected') }}</span>
                <button
                  @click.prevent="selectAllItems"
                  class="text-blue-600 text-sm font-black uppercase tracking-widest hover:text-blue-700 transition-colors"
                >
                  {{ $t('cart.selectItems') }}
                </button>
              </div>
              <div v-else>
                 <button
                  @click.prevent="deselectAllItems"
                  class="text-slate-400 text-sm font-black uppercase tracking-widest hover:text-red-500 transition-colors"
                >
                  {{ $t('cart.deselectItems') }}
                </button>
              </div>
            </div>

            <!-- Cart Products -->
            <cart-list
              :cart-products="cartProducts"
              :ajaxing="ajaxing"
              :checked="checked"
            />

            <!-- Subtotal -->
            <div class="mt-12 pt-8 border-t border-slate-100 flex items-center justify-between">
              <h5 class="text-lg font-bold text-slate-500">
                {{ $t('cart.subtotal', { items: cartPrice.totalItems }) }}
              </h5>
              <h4 class="text-3xl font-black text-slate-900 tracking-tighter">
                <price-format :price="cartPrice.totalPriceWithOffer" />
              </h4>
            </div>
          </div>
        </div>

        <!-- Checkout Summary -->
        <div class="w-full lg:w-[400px] flex-shrink-0">
          <div class="sticky top-32">
            <checkout-right
              :disabled="preventGoing"
              :checked-product="checkedProduct"
              @calculated-price="setCartPrice"
              @go-next="goToAddress"
              class="!bg-slate-900 !text-white !rounded-[2.5rem] !p-10 !shadow-2xl !shadow-blue-900/20"
            />
          </div>
        </div>
      </div>
    </div>
  </client-only>
</template>


<script setup>
  import {useUserStore} from "~/store/user";
  import {useCommonStore} from "~/store/common";
  import {useLanguageStore} from "~/store/language";
  import {useCartStore} from "~/store/cart";
  import {storeToRefs} from "pinia";
  import {onBeforeMount, onMounted} from "vue";
  import {useMetaData} from "~/composables/useMetaData";
  import {useAuthStore} from "~/store/auth";

  definePageMeta({
    middleware: ['common-middleware'],
    layout: 'default',
  });

  const languageStore = useLanguageStore()
  const {langCode} = storeToRefs(languageStore)

  const cartStoreStore = useCartStore()
  const {cartProducts} = storeToRefs(cartStoreStore)
  const {getCartByUser, cartChanged} = cartStoreStore

  const userStore = useUserStore()
  const {getUserToken} = userStore
  const {profile} = storeToRefs(userStore);

  const commonStore = useCommonStore();
  const {currencyIcon, setting, customScripts, site_setting} = storeToRefs(commonStore);
  const {fetchLocation} = commonStore;

  const authStore = useAuthStore();
  const {authenticated} = storeToRefs(authStore);

  onBeforeMount(() => {
    if (!setting.value?.guest_checkout) {
      if (!authenticated.value) {
        return navigateTo('/login');
      }
    }
  });

  const {t} = useI18n();
  const {pageMeta} = useMetaData();

  useHead(pageMeta({
    ...site_setting.value,
    ...{meta_title: `${t('header.cart')} | ${site_setting.value?.meta_title}`}
  }));

  const setCartPrice = (event) => {
    cartPrice.value = event
  };

  const preventGoing = ref(true);
  const checked = ref([]);
  const ajaxing = ref(false);

  const cartPrice = ref({
    totalItems: 0,
    totalPriceWithOffer: 0,
    totalPrice: 0,
  });

  const checkedProduct = computed(() => {
    checked.value = []
    let checkedP = []
    cartProducts.value.forEach(obj => {
      if (parseInt(obj.selected) === 1) {
        checked.value.push(obj.id)
        checkedP.push(obj)
      }
    })
    preventGoing.value = checkedP.length === 0
    return checkedP
  });

  const cartCount = computed(() => {
    return profile.value?.cart_count
  });

  const router = useRouter();

  const goToAddress = () => {
    router.push('/shipping')
  };

  const selectAllItems = async () => {
    checked.value = cartProducts.value.map(i => {
      return i.id
    });
    await cartChanged({
      payload: {
        checked: checked.value
      },
      lang: langCode.value
    })
    await cartChangedApi()
  };

  const deselectAllItems = async () => {
    checked.value = [];
    await cartChanged({
      payload: {
        checked: []
      },
      lang: langCode.value
    })
  };


  const cartChangedApi = async (bundleDeal = false) => {
    await cartChanged({
      payload: {
        checked: checked.value,
        isBundle: bundleDeal
      },
      lang: langCode.value
    })
  };

  const settingChecked = () => {
    checkedProduct.value = []
    checked.value = []

    cartProducts.value.forEach(obj => {
      if (parseInt(obj.selected) === 1) {
        checked.value.push(obj.id)
        checkedProduct.value.push(obj)
      }
    })
  };

  const fetchingData = async () => {
    ajaxing.value = true

    await getCartByUser({
      lang: langCode.value,
      params: {
        user_token: await getUserToken()
      }
    })

    ajaxing.value = false
  };

  onMounted(async () => {
    await fetchingData()
  });


</script>
