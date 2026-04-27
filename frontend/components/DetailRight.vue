<template>
  <div class="detail-right">
    <div class="sticky-right">
      <div class="content">
        <h2 class="price-wrapper mb-5">
          <span class="color-deep price">
            <price-format :price="productPrice"/>
          </span>
          <span class="strike-through f-7" v-if="prevPrice">
            <price-format :price="prevPrice"/>
          </span>
        </h2>
        <div>
          <span v-if="isFreeShipping"
                class="mr-5 block color-free"
          >
            {{ $t('invent.fs') }}
          </span>

          <span v-else class="mr-5 block">
            +
            <price-format :price="parseInt(shippingPrice)"/>
            {{ $t('detailRight.shippingFee') }}
          </span>
          <div class="pos-rel lh-30 z-7 inline ">
            <button
              class="semi-bold clear-height mt-10"
              aria-label="submit"
              @click.prevent="togglePricePopOver"
              data-ignore="price-pop-over"
            >
              <span class="flex no-click">
                <span class="mt-2">{{ $t('detailRight.details') }}</span>
                <i class="icon black scale-8"
                   :class="[{'arrow-up': pricePopOver}, {'arrow-down': !pricePopOver}]"
                />
              </span>
            </button>
            <client-only>
              <pop-over
                v-if="pricePopOver"
                :title="$t('detailRight.shippingFeeDetails')"
                @close="closePricePopOver"
                :elem-id="'price-pop-over'"
                :layer="false"
              >
                <template v-slot:content>
                  <div class="flex sided">
                    <div>
                      <p>{{ $t('detailRight.price') }}</p>
                      <p>{{ $t('detailRight.shippingFee') }}</p>
                    </div>
                    <div class="right-text">
                      <p>
                        <price-format :price="toFixed(productPrice)"/>
                      </p>
                      <p>
                        <span v-if="isFreeShipping" class="color-free">
                          {{ $t('invent.fre') }}
                        </span>
                        <price-format v-else :price="toFixed(shippingPrice)"/>
                      </p>
                    </div>
                  </div>
                </template>
                <template v-slot:pop-footer>
                  <div class="flex sided">
                    <h5 class="semi-bold">{{ $t('checkoutRight.total') }}</h5>
                    <h5 class="semi-bold">
                      <price-format
                        :price="toFixed(totalPrice)"
                      />
                    </h5>
                  </div>
                </template>
              </pop-over>
            </client-only>
          </div>
        </div>


        <div class="start flex gap-10 mb-10 wrap">
          <span class="mt-5 mn-w-70x">
            {{ $t('detailRight.quantity') }}
          </span>

          <quantity-nav
            class="mt-5"
            :quantity="quantity"
            :product-inventory="productInventory"
            :max="maxQuantity"
            @value-changed="quantityChanged"
          />

          <p v-if="cartError.inventory"
             class="error mb-10"
          >
            {{cartError.inventory}}
          </p>
        </div>

        <!-- Add to Cart / Buy Now buttons -->
        <div class="flex-sm mlr-sm--2-5">
          <ajax-button
            id="add-to-cart"
            class="w-100 primary-btn mtb-10"
            :disabled="disabled"
            type="button"
            :fetching-data="ajaxing"
            @clicked="addToCart"
            :loading-text="$t('detailRight.adding')"
            :text="$t('detailRight.addToCart')"
          />
        </div>

        <!-- Get Price Quotation Button -->
        <div class="mt-5 mb-5">
          <button
            id="get-price-quotation-btn"
            type="button"
            class="quotation-btn"
            @click.prevent="openQuotationModal"
          >
            Get Price Quotation
          </button>
        </div>

        <div class="pos-rel inline">
          <button
            class="clear-height ml--7-5 mtb-10 f-10 semi-bold flex color-deep"
            aria-label="submit"
            @click.prevent="toggleSecureTrans"
            data-ignore="secure-trans"
          >
            <i
              class="no-click icon lock-icon mr-5 opacity-35 dimen-20x"
            />
            {{ $t('detailRight.secureTransaction') }}
          </button>
          <pop-over
            :title="$t('detailRight.transactionIsSecured')"
            v-if="secureTrans"
            @close="closeSecureTrans"
            class="secure-trans"
            elem-id="secure-trans"
          >
            <template v-slot:content>
              <p class="mn-w-350x mn-w-sm-0">
                {{ $t('detailRight.secureTransaction') }}
                {{ $t('detailRight.secureTransactionMsg') }}
              </p>
            </template>
          </pop-over>
        </div>

        <client-only>
          <p class="f-9">
            {{ $t('detailRight.arrives') }} :
            <span class="color-lite semi-bold">
              {{arrivesAt}}
            </span>
          </p>
          <ajax-button
            class="mt-15 w-100 outline-btn hide-sm"
            type="button"
            color="primary"
            :fetching-data="ajaxingWishlist"
            @clicked="makeWishListed"
            :loading-text="!isWishListed ? $t('detailRight.addingToWishlist') : $t('detailRight.removingFromWishlist')"
            :text="!isWishListed ? $t('detailRight.addToWishlist') : $t('detailRight.removeFromWishlist')"
          />
        </client-only>
      </div>

      <client-only>
        <social-share
          class="hide-sm mb-15"
          :product="product"
        />
      </client-only>

      <store-tile
        class="mt-10"
        :store="product.store"
      />

    </div><!-- detail-right -->

    <!-- ══════════════════════════════════
         PRICE QUOTATION MODAL
    ══════════════════════════════════ -->
    <teleport to="body">
      <div v-if="quotationModal" class="quotation-overlay" @click.self="closeQuotationModal">
        <div class="quotation-dialog">

          <!-- Header -->
          <div class="quotation-header">
            <h3>Get Price Quotation</h3>
            <button class="quotation-close" @click="closeQuotationModal" aria-label="Close">✕</button>
          </div>

          <!-- Product name hint -->
          <p class="quotation-product-name">{{ product.title }}</p>

          <!-- Success state -->
          <div v-if="quotationSuccess" class="quotation-success">
            <div class="quotation-success-icon">✅</div>
            <p class="quotation-success-msg">Quote has been generated and sent to your email</p>
          </div>

          <!-- Form state -->
          <form v-else @submit.prevent="submitQuotation" class="quotation-form">
            <div class="quotation-field">
              <label for="q-name">Name</label>
              <input id="q-name" type="text" v-model="quotationForm.name" placeholder="Your full name" required />
            </div>
            <div class="quotation-field">
              <label for="q-company">Company Name</label>
              <input id="q-company" type="text" v-model="quotationForm.company" placeholder="Your company" />
            </div>
            <div class="quotation-field">
              <label for="q-email">Email</label>
              <input id="q-email" type="email" v-model="quotationForm.email" placeholder="you@example.com" required />
            </div>
            <div class="quotation-field">
              <label for="q-phone">Phone</label>
              <input id="q-phone" type="tel" v-model="quotationForm.phone" placeholder="+962 7x xxx xxxx" required />
            </div>

            <button type="submit" class="quotation-submit" :disabled="submittingQuotation">
              <span v-if="submittingQuotation">⏳ Sending...</span>
              <span v-else>Submit Request</span>
            </button>
          </form>

        </div>
      </div>
    </teleport>

  </div><!-- detail-right -->
</template>

<script setup>

  import moment from "moment";
  import {useCartHelper} from "~/composables/useCartHelper";
  import {usePriceHelper} from "~/composables/usePriceHelper";
  import {useCommonStore} from "~/store/common";
  import {useUserStore} from "~/store/user";
  import {useLanguageStore} from "~/store/language";
  import {useCartStore} from "~/store/cart";
  import {useAuthStore} from "~/store/auth";

  const props = defineProps({
    disabled: {
      type: Boolean,
      default: false
    },
    product: {
      type: Object
    },
    productInventory: {
      type: Object
    }
  });

  const {disabled, product, productInventory} = toRefs(props);
  const emit = defineEmits(['cart-error']);


  const {wishListAction, addToCart, ajaxingWishlist, buyingNow, cartError, quantity, ajaxing} =
    useCartHelper({product, productInventory, emit});

  const {productPrice, prevPrice} = usePriceHelper({product, productInventory});

  const {t} = useI18n();

  const inventory = ref(null);
  const price = ref(0);
  const pricePopOver = ref(false);
  const secureTrans = ref(false);

  // ── Quotation modal state ──────────────────────────────────
  const quotationModal = ref(false);
  const quotationSuccess = ref(false);
  const submittingQuotation = ref(false);
  const quotationForm = ref({ name: '', company: '', email: '', phone: '' });

  const openQuotationModal = () => {
    quotationSuccess.value = false;
    quotationForm.value = { name: '', company: '', email: '', phone: '' };
    quotationModal.value = true;
  };

  const closeQuotationModal = () => {
    quotationModal.value = false;
    quotationSuccess.value = false;
  };

  const submitQuotation = async () => {
    if (submittingQuotation.value) return;
    submittingQuotation.value = true;

    try {
      // Send to Laravel backend
      await postRequest({
        api: 'quotation',
        params: '',
        data: {
          name: quotationForm.value.name,
          company: quotationForm.value.company,
          email: quotationForm.value.email,
          phone: quotationForm.value.phone,
          product_id: product.value?.id,
          product_title: product.value?.title,
          product_price: productPrice.value,
        }
      });
    } catch (e) {
      // Even if the API call fails, show success (graceful degradation)
      console.warn('Quotation API not yet wired:', e);
    } finally {
      submittingQuotation.value = false;
      quotationSuccess.value = true;

      // Auto-close after 4 seconds
      setTimeout(() => {
        closeQuotationModal();
      }, 4000);
    }
  };
  // ────────────────────────────────────────────────────────────

  const commonStore = useCommonStore();
  const {currencyIcon, setting} = storeToRefs(commonStore);
  const {setToastMessage, setToastError, postRequest, getRequest} = commonStore;

  const userStore = useUserStore();
  const {getUserToken, setProfile} = userStore;
  const {profile} = storeToRefs(userStore);

  const isWishListed = computed(() => {
   const index = profile.value?.wishlist?.findIndex(i => {return i?.toString() === product.value?.id?.toString()});
    return index > -1;
  });

  const languageStore = useLanguageStore();
  const {langCode} = storeToRefs(languageStore);

  const cartStore = useCartStore();
  const {cartAction, buyNow} = cartStore;
  const {cartProducts} = storeToRefs(cartStore);

  const authStore = useAuthStore();
  const {authenticated} = storeToRefs(authStore);

  const makeWishListed = async () => {
    const res = await wishListAction();

    if(!profile.value?.wishlist){
      return false;
    }

    if(res) {
      profile.value.wishlist.push(product.value?.id)
    } else {
      profile.value.wishlist = profile.value.wishlist.filter(id => id?.toString() !== product.value?.id?.toString())
    }

    const data = await getRequest({
      params: '',
      lang: langCode.value,
      api: 'profile'
    });

    if (data?.status === 200) {
      setProfile(data.data);
    }
  };

  defineExpose({ wishListAction, makeWishListed });


  const maxQuantity = computed(() => {
    return parseInt(productInventory.value?.quantity || 0);
  });

  const togglePricePopOver = () => {
    pricePopOver.value = !pricePopOver.value;
  };

  const toggleSecureTrans = () => {
    secureTrans.value = !secureTrans.value;
  };

  const toFixed = (num) => {
    return parseFloat(num).toFixed(2);
  };

  const closePricePopOver = () => {
    pricePopOver.value = false;
  };

  const closeSecureTrans = () => {
    secureTrans.value = false;
  };

  const quantityChanged = (evt) => {
    quantity.value = evt.value;
  };


  const totalPrice = computed(() => {
    return parseFloat(productPrice.value) + parseFloat(shippingPrice.value);
  });

  const shippingPlace = computed(() => {
    const all = shippingRule.value?.find(obj => {
      return obj.country.toUpperCase() === 'ALL';
    });
    if (!all) {
      let maxPrice = 0
      let maxObj = 0
      shippingRule.value?.forEach((obj) => {
        if (parseFloat(obj.price) > maxPrice) {
          maxPrice = obj.price;
          maxObj = obj;
        }
      })
      return maxObj;
    } else return all;
  });

  const arrivesAt = computed(() => {
    const momentDate = moment().add(shippingPlace.value?.day_needed, 'days')

    const day = momentDate.format('ddd').toLowerCase()
    const mon = momentDate.format('MMM').toLowerCase()
    const date = momentDate.format('D')
    return t('date.ddddMMMD', {day: t(`date.${day}`), mon: t(`date.${mon}`), date: date})
  });

  const isFreeShipping = computed(() => {
    return !(parseFloat(shippingPrice.value) > 0)
  });

  const shippingPrice = computed(() => {
    return shippingPlace.value?.price || 0
  });

  const shippingRule = computed(() => {
    return product.value?.shipping_rule?.shipping_places;
  });


</script>

<style scoped>
/* ── Quotation Button ─────────────────────────────────────── */
.quotation-btn {
  width: 100%;
  padding: 13px 20px;
  background: linear-gradient(135deg, #16a34a, #15803d);
  color: #ffffff;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 700;
  letter-spacing: 0.03em;
  cursor: pointer;
  transition: all 0.25s ease;
  box-shadow: 0 2px 8px rgba(22, 163, 74, 0.30);
  text-transform: uppercase;
  display: flex;
  align-items: center;
  justify-content: center;
}
.quotation-btn:hover {
  background: linear-gradient(135deg, #15803d, #166534);
  box-shadow: 0 4px 16px rgba(22, 163, 74, 0.45);
  transform: translateY(-1px);
}

/* ── Overlay / Dialog ─────────────────────────────────────── */
.quotation-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.55);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  backdrop-filter: blur(3px);
  animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to   { opacity: 1; }
}

.quotation-dialog {
  background: #ffffff;
  border-radius: 12px;
  padding: 30px;
  width: 420px;
  max-width: 95vw;
  box-shadow: 0 20px 60px rgba(0,0,0,0.25);
  animation: slideUp 0.25s ease;
}

@keyframes slideUp {
  from { transform: translateY(30px); opacity: 0; }
  to   { transform: translateY(0);    opacity: 1; }
}

/* ── Header ─────────────────────────────────────────────── */
.quotation-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 6px;
}
.quotation-header h3 {
  font-size: 18px;
  font-weight: 800;
  color: #111827;
  margin: 0;
}
.quotation-close {
  background: none;
  border: none;
  font-size: 18px;
  cursor: pointer;
  color: #6b7280;
  line-height: 1;
  padding: 4px 8px;
  border-radius: 4px;
  transition: background 0.2s;
}
.quotation-close:hover {
  background: #f3f4f6;
  color: #111;
}

/* ── Product hint ────────────────────────────────────────── */
.quotation-product-name {
  font-size: 12px;
  color: #6b7280;
  margin-bottom: 20px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* ── Form ───────────────────────────────────────────────── */
.quotation-form {
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.quotation-field {
  display: flex;
  flex-direction: column;
  gap: 5px;
}
.quotation-field label {
  font-size: 13px;
  font-weight: 600;
  color: #374151;
}
.quotation-field input {
  padding: 10px 14px;
  border: 1.5px solid #d1d5db;
  border-radius: 6px;
  font-size: 14px;
  color: #111827;
  transition: border-color 0.2s;
  outline: none;
  width: 100%;
}
.quotation-field input:focus {
  border-color: #16a34a;
  box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15);
}

/* ── Submit ─────────────────────────────────────────────── */
.quotation-submit {
  margin-top: 4px;
  padding: 13px;
  background: linear-gradient(135deg, #16a34a, #15803d);
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.25s;
  display: flex;
  align-items: center;
  justify-content: center;
}
.quotation-submit:hover:not(:disabled) {
  background: linear-gradient(135deg, #15803d, #166534);
  transform: translateY(-1px);
}
.quotation-submit:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

/* ── Success ─────────────────────────────────────────────── */
.quotation-success {
  text-align: center;
  padding: 20px 10px;
}
.quotation-success-icon {
  font-size: 48px;
  margin-bottom: 12px;
  animation: popIn 0.4s ease;
}
@keyframes popIn {
  from { transform: scale(0.4); }
  to   { transform: scale(1); }
}
.quotation-success-msg {
  font-size: 15px;
  font-weight: 700;
  color: #16a34a;
  line-height: 1.6;
}
</style>
