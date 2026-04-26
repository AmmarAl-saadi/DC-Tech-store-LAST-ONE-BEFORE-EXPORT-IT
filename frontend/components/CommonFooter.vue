<template>
  <div>
    <!-- NEWSLETTER -->
    <subscription />

    <!-- MAIN FOOTER  bg:#2D2D2D -->
    <footer style="background:#2D2D2D;color:#CCCCCC;">
      <div class="max-w-[980px] mx-auto px-4 py-8">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-8">

          <!-- Col 1: Information -->
          <div>
            <h4 class="font-bold uppercase text-white mb-4" style="font-size:13px;">Information</h4>
            <ul class="space-y-2">
              <li><nuxt-link to="/about" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">About Us</nuxt-link></li>
              <li><nuxt-link to="/delivery" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">Delivery Information</nuxt-link></li>
              <li><nuxt-link to="/privacy" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">Privacy Policy</nuxt-link></li>
              <li><nuxt-link to="/terms" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">Terms &amp; Conditions</nuxt-link></li>
              <li v-for="(item, i) in services" :key="i">
                <nuxt-link :to="pageLink(item)" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">{{ item.title }}</nuxt-link>
              </li>
            </ul>
          </div>

          <!-- Col 2: Customer Service -->
          <div>
            <h4 class="font-bold uppercase text-white mb-4" style="font-size:13px;">Customer Service</h4>
            <ul class="space-y-2">
              <li><nuxt-link to="/contact" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">Contact Us</nuxt-link></li>
              <li><nuxt-link to="/track-order" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">Track Order</nuxt-link></li>
              <li><nuxt-link to="/faq" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">FAQ</nuxt-link></li>
              <li><nuxt-link to="/help" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">Help</nuxt-link></li>
              <li><nuxt-link to="/sitemap" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">Sitemap</nuxt-link></li>
              <li><nuxt-link to="/service-centers" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">Service Centers</nuxt-link></li>
            </ul>
          </div>

          <!-- Col 3: Extra -->
          <div>
            <h4 class="font-bold uppercase text-white mb-4" style="font-size:13px;">Extra</h4>
            <ul class="space-y-2">
              <li><nuxt-link to="/brands" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">Brands</nuxt-link></li>
              <li><nuxt-link to="/flash-sale" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">Specials</nuxt-link></li>
              <li v-for="(item, i) in about" :key="i">
                <nuxt-link :to="pageLink(item)" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">{{ item.title }}</nuxt-link>
              </li>
            </ul>
          </div>

          <!-- Col 4: My Account -->
          <div>
            <h4 class="font-bold uppercase text-white mb-4" style="font-size:13px;">My Account</h4>
            <ul class="space-y-2">
              <li><nuxt-link to="/profile/view" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">My Account</nuxt-link></li>
              <li><nuxt-link to="/profile/orders" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">Order History</nuxt-link></li>
              <li><nuxt-link to="/profile/wishlists" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">Wishlist</nuxt-link></li>
              <li><nuxt-link to="/profile/vouchers" class="text-[12px] hover:text-white transition-colors block" style="color:#CCCCCC;">Vouchers</nuxt-link></li>
            </ul>
          </div>

          <!-- Col 5: Company Info -->
          <div>
            <!-- Logo -->
            <div class="mb-4">
              <img v-if="!showTextLogo" src="/images/logo.png" class="h-10 w-auto mb-2" alt="DC Tech Logo" @error="handleLogoError">
              <div v-else class="flex flex-col">
                <div class="text-white font-black text-lg leading-tight italic">DIGITAL CREATIVITY</div>
                <div class="text-primary-400 font-bold text-[10px] tracking-widest uppercase">TECH SHOP</div>
              </div>
            </div>
            <!-- Address -->
            <div class="space-y-1 text-[12px]" style="color:#AAAAAA;">
              <p>{{ setting?.address || 'Amman, Jordan' }}</p>
              <div v-if="setting?.phone" class="mt-2">
                <span class="text-white font-bold">Phone: </span>
                <a :href="`tel:${setting.phone}`" class="hover:text-white">{{ setting.phone }}</a>
              </div>
              <div v-if="setting?.email" class="mt-1">
                <span class="text-white font-bold">Email: </span>
                <a :href="`mailto:${setting.email}`" class="hover:text-white">{{ setting.email }}</a>
              </div>
            </div>
            <!-- Payment Icons -->
            <div v-if="payment?.length" class="mt-4 flex flex-wrap gap-2">
              <a v-for="(item, i) in payment" :key="i" :href="item.link" target="_blank" class="flex items-center justify-center p-1" style="background:#FFFFFF;border:1px solid #444;width:40px;height:26px;">
                <ImageLazy :lazy-src="getImageURL(item.image)" class="max-h-full max-w-full object-contain"/>
              </a>
            </div>
          </div>

        </div>
      </div>
    </footer>

    <!-- BOTTOM BLUE BAR -->
    <div style="background:#1D4ED8;" class="py-2 relative">
      <div class="max-w-[980px] mx-auto px-4 flex items-center justify-between">
        <p class="text-white text-center text-[12px] flex-grow">
          Copyright &copy; {{ current_year }} {{ site_setting?.copyright_text || 'DC Tech For Computers' }}
        </p>
        <!-- Scroll to top -->
        <button
          @click="scrollToTop"
          class="w-7 h-7 bg-white flex items-center justify-center rounded-full flex-shrink-0 hover:opacity-90 transition-opacity"
          style="color:#1D4ED8;"
          title="Back to top"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
  import {useCommonStore} from "~/store/common";
  import {storeToRefs} from "pinia";
  import {useUtils} from "../composables/useUtils";

  const commonStore = useCommonStore();
  const {site_setting, setting, services, about, payment, social, current_year} = storeToRefs(commonStore);
  const {getImageURL, pageLink} = useUtils();
  const showTextLogo = ref(false);
  const handleLogoError = () => { showTextLogo.value = true; };

  const scrollToTop = () => window.scrollTo({ top: 0, behavior: 'smooth' });
</script>
