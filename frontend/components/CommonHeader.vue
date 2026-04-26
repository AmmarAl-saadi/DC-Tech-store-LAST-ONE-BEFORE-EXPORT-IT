<template>
  <header ref="headerWrapperRef" class="w-full sticky top-0 z-[100]">

    <!-- ════════════════════════════════════════════
         TOP HEADER BAR  bg: #1A1A1A
    ════════════════════════════════════════════ -->
    <div style="background:#1A1A1A;" class="py-2">
      <div class="max-w-[980px] mx-auto px-4 flex items-center gap-4">

        <!-- LOGO -->
        <nuxt-link to="/" class="flex-shrink-0 flex items-center min-w-[180px] py-1">
          <img v-if="!showTextLogo" src="/images/logo.png" class="h-10 w-auto" alt="DC Tech Logo" @error="handleLogoError">
          <div v-else class="flex flex-col">
            <span class="text-white font-black text-xl tracking-tighter leading-none italic">DIGITAL CREATIVITY</span>
            <span class="text-primary-400 font-bold text-sm tracking-widest uppercase ml-0.5">TECH SHOP</span>
          </div>
        </nuxt-link>

        <!-- SEARCH BAR -->
        <div class="flex-grow" v-outside-click="blurSearchInput">
          <form @submit.prevent="search" class="flex h-9">
            <input
              @focus="openSearchPopup"
              v-model="searchedText"
              type="text"
              placeholder="Search..."
              class="flex-grow px-3 text-sm text-gray-800 bg-white border-0 outline-none"
              style="border-radius:0;"
            >
            <button type="submit" class="px-5 text-white text-xs font-bold uppercase tracking-widest flex-shrink-0" style="background:#1D4ED8;border-radius:0;">
              Search
            </button>
          </form>
          <div class="relative">
            <SearchPopup v-if="searchPopup" :searched-text="searchedText" class="absolute top-1 left-0 right-0 z-[110] bg-white shadow-2xl border border-gray-200 overflow-hidden" @close="blurSearchInput"/>
          </div>
        </div>

        <!-- RIGHT ICONS -->
        <div class="flex items-center gap-1 flex-shrink-0">

          <!-- Account -->
          <div class="relative" v-outside-click="closeDropdown">
            <button @click.prevent="toggleDropdown" class="flex items-center gap-1.5 px-3 py-2 hover:bg-white/10 rounded transition-colors">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              <span class="text-white text-[13px] uppercase font-semibold tracking-wide">MY ACCOUNT</span>
              <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M5.5 8l4.5 4.5L14.5 8"/></svg>
            </button>
            <div v-show="showDropdown" class="absolute right-0 mt-1 w-48 bg-white shadow-xl border border-gray-200 py-1 z-[120]">
              <div class="px-3 py-2 border-b border-gray-100 text-xs font-bold text-gray-800">{{ isLoggedIn ? `Hi, ${username}` : 'My Account' }}</div>
              <nuxt-link to="/profile/orders" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600" @click="closeDropdown">{{ $t('header.orders') }}</nuxt-link>
              <nuxt-link to="/profile/wishlists" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600" @click="closeDropdown">{{ $t('header.wishList') }}</nuxt-link>
              <nuxt-link to="/profile/compared" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600" @click="closeDropdown">{{ $t('header.comparedList') }}</nuxt-link>
              <nuxt-link to="/profile/vouchers" class="block px-3 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600" @click="closeDropdown">{{ $t('header.vouchers') }}</nuxt-link>
              <div class="border-t border-gray-100 mt-1 pt-1">
                <button v-if="isLoggedIn" @click.prevent="userLogOut" class="w-full text-left px-3 py-2 text-sm text-blue-600 hover:bg-blue-50">{{ $t('header.logout') }}</button>
                <nuxt-link v-else to="/login" class="block px-3 py-2 text-sm font-bold text-blue-600 hover:bg-blue-50">Sign In / Register</nuxt-link>
              </div>
            </div>
          </div>


          <!-- Cart -->
          <nuxt-link to="/cart" class="flex items-center gap-2 px-3 py-2 hover:bg-white/10 rounded transition-colors">
            <div class="relative">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              <span v-if="cartCount" class="absolute -top-2 -right-2 text-white text-[10px] font-black w-4 h-4 flex items-center justify-center rounded-full" style="background:#1D4ED8;">{{ cartCount }}</span>
            </div>
            <div class="text-white text-[13px] leading-tight">
              <div>Cart ({{ cartCount || 0 }})</div>
            </div>
          </nuxt-link>

          <!-- Language -->
          <div v-if="Object.keys(languages).length > 1" class="px-1">
            <dropdown :selected-key="currentLanguage.code" :options="languages" :positionFixed="false" key-name="name" class="header-lang-dd" @clicked="selectedLanguage"/>
          </div>
        </div>
      </div>
    </div>

    <!-- ════════════════════════════════════════════
         MAIN NAV BAR  bg: #2D2D2D
    ════════════════════════════════════════════ -->
    <nav style="background:#2D2D2D;">
      <div class="max-w-[980px] mx-auto px-4">
        <ul class="flex items-center overflow-x-auto no-scrollbar">
          <li>
            <nuxt-link to="/" class="block px-4 py-3 text-white text-[13px] font-bold uppercase tracking-wide whitespace-nowrap hover:bg-[#1D4ED8] transition-colors" active-class="!bg-[#1D4ED8]">
              Home
            </nuxt-link>
          </li>
          <li v-for="(item, i) in headerLeft" :key="`l-${i}`">
            <nuxt-link :to="getUrl(item)" class="block px-4 py-3 text-white text-[13px] font-bold uppercase tracking-wide whitespace-nowrap hover:bg-[#1D4ED8] transition-colors">
              {{ getTitle(item) }}
            </nuxt-link>
          </li>
          <li v-for="(item, i) in headerRight" :key="`r-${i}`">
            <nuxt-link :to="getUrl(item)" class="block px-4 py-3 text-white text-[13px] font-bold uppercase tracking-wide whitespace-nowrap hover:bg-[#1D4ED8] transition-colors">
              {{ getTitle(item) }}
            </nuxt-link>
          </li>
        </ul>
      </div>
    </nav>

  </header>
</template>

<script setup>
  import {storeToRefs} from "pinia";
  import {computed, onUnmounted} from "vue";
  import {navigateTo} from "nuxt/app";
  import {useConstants} from "~/composables/useConstants";
  import {onMounted} from "vue";
  import {useLanguageStore} from "~/store/language";
  import {useCartStore} from "~/store/cart";
  import {useUserStore} from "~/store/user";
  import {useCommonStore} from "~/store/common";
  import {useListingStore} from "~/store/listing";
  import {useAuthStore} from "~/store/auth";
  import {useUtils} from "~/composables/useUtils";

  const languageStore = useLanguageStore();
  const {setDefaultLanguage, getLangData} = languageStore;
  const {currentLanguage, languages, langCode} = storeToRefs(languageStore);
  const cartStore = useCartStore();
  const {emptyCartProduct, setCartCount} = cartStore;
  const {cartCount} = storeToRefs(cartStore);
  const userStore = useUserStore();
  const {profile} = storeToRefs(userStore);
  const {setProfile, getUserToken} = userStore;
  const commonStore = useCommonStore();
  const {site_setting, setting, topBanner, headerLinks} = storeToRefs(commonStore);
  const {bgGetRequest} = commonStore;
  const listingStore = useListingStore();
  const {searched} = storeToRefs(listingStore);
  const {updateSearch, setSearchedSuggestion} = listingStore;
  const authStore = useAuthStore();
  const {authenticated} = storeToRefs(authStore);
  const {logUserOut} = authStore;
  const isLoggedIn = computed(() => authenticated.value || false);
  const route = useRoute();
  const showTextLogo = ref(false);
  const handleLogoError = () => { showTextLogo.value = true; };
  const showDropdown = ref(false);
  const closeDropdown = () => { showDropdown.value = false; };
  const toggleDropdown = () => { showDropdown.value = !showDropdown.value; };
  const userLogOut = async () => {
    closeDropdown();
    await bgGetRequest({ params: '', lang: langCode.value, api: 'logout' });
    logUserOut(); emptyCartProduct();
    const r = useRoute();
    const isAuthPage = r.meta.middleware.findIndex(i => i === 'auth');
    if (isAuthPage > -1) return navigateTo('/');
    else window.location.reload();
  };
  const {setLocale} = useI18n();
  const selectedLanguage = (data) => {
    setLocale(data.key);
    document.cookie = 'currentLanguage=' + data.key + '; path=/; max-age=' + 365 * 60 * 60 * 24;
    location.reload();
  };
  const searchPopup = ref(false);
  const searchFocused = ref(false);
  const searchedText = ref('');
  const headerSticky = ref(false);
  const {getImageURL, getUrl, getTitle} = useUtils();
  const filterLinks = (links) => {
    const toRemove = ['track', 'faq', 'help', 'contact'];
    return links.filter(link => {
      const title = getTitle(link).toLowerCase();
      const url = getUrl(link).toLowerCase();
      return !toRemove.some(term => title.includes(term) || url.includes(term));
    });
  };

  const headerLeft = computed(() => filterLinks(headerLinks.value?.left || []));
  const headerRight = computed(() => filterLinks(headerLinks.value?.right || []));
  const cartCountCom = computed(() => profile.value?.cart_count);
  const username = computed(() => profile.value?.name?.split(' ')[0]);
  const email = computed(() => setting.value?.email);
  const phone = computed(() => setting.value?.phone);
  watch(cartCountCom, (value) => { setCartCount(value); });
  watch(searchedText, () => { if (!searchPopup.value) { setSearchedSuggestion(); openSearchPopup(); } });
  const openSearchPopup = () => { if (searchedText.value.length > 0) searchPopup.value = true; searchFocused.value = true; };
  const blurSearchInput = () => { searchFocused.value = false; setTimeout(() => { searchPopup.value = false; }, 100); };
  const setQFromRoute = () => { searchedText.value = route?.query?.q || ''; };
  const search = () => {
    if (searchedText.value && (searchedText.value !== searched.value)) {
      navigateTo(`/search?q=${searchedText.value}`);
      updateSearch(searchedText.value);
      blurSearchInput();
    }
  };
  const headerWrapperRef = ref(null);
  let observer = null;
  const handleIntersection = (entries) => { entries.forEach((e) => { headerSticky.value = !e.isIntersecting; }); };
  onMounted(async () => {
    setQFromRoute(); updateSearch(searchedText.value);
    if (cartCountCom.value) setCartCount(cartCountCom.value);
    await nextTick();
    observer = new IntersectionObserver(handleIntersection, { root: null, rootMargin: '0px', threshold: 0 });
    if (headerWrapperRef.value) observer.observe(headerWrapperRef.value);
  });
  onUnmounted(() => { if (observer) observer.disconnect(); });
</script>

<style scoped>
.header-lang-dd :deep(> span) {
  background: #1D4ED8 !important;
  color: #FFFFFF !important;
  border: none !important;
  border-radius: 6px !important;
  font-weight: 600 !important;
  height: 36px !important;
  line-height: 36px !important;
  padding: 0 16px !important;
  font-family: inherit !important;
  font-size: 13px !important;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.header-lang-dd :deep(.ignore-click) {
  filter: brightness(0) invert(1) !important;
  margin-left: 8px !important;
}

.header-lang-dd :deep(.dropdown-inner) {
  background: #FFFFFF !important;
  color: #333333 !important;
  border: 1px solid #EEEEEE !important;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
  border-radius: 6px !important;
  margin-top: 4px !important;
}

.header-lang-dd :deep(li) {
  font-size: 13px !important;
  padding: 10px 16px !important;
}

.header-lang-dd :deep(li:hover) {
  background: #F0F7FF !important;
  color: #1D4ED8 !important;
}

.header-lang-dd :deep(.active) {
  background: #1D4ED8 !important;
  color: #FFFFFF !important;
}
</style>
