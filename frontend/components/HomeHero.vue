<template>
  <div>
    <!-- HERO: Big slider left + Two side cards right, all horizontal -->
    <div style="display:flex;flex-direction:row;height:420px;gap:4px;background:#000000;overflow:hidden;">

      <!-- ── LEFT: Main Slider (2/3 width) ── -->
      <div style="flex:2;position:relative;overflow:hidden;min-width:0;border:1px solid #333;">
        <ImageSlider
          ref="homeHeroRef"
          :image-count="staticSlides.length"
          :bullets="true"
          :autoplay="5000"
          :loop="true"
          :addInitEvt="true"
          :lazy="false"
          @on-change="onChange"
          @on-init="onFirstImageLoad()"
        >
          <!-- Custom Navigation Arrows (since default might be small/hidden) -->
          <template v-slot:title>
             <div class="slider-nav-overlay"></div>
          </template>

          <!-- Dot indicators -->
          <template v-slot:bullet-area>
            <div style="position:absolute;bottom:20px;left:40px;display:flex;gap:8px;z-index:30;">
              <button
                v-for="(_, idx) in staticSlides"
                :key="idx"
                @click="selectSliderImage(idx)"
                :style="idx === activeSlide
                  ? 'width:32px;height:4px;background:#1D4ED8;border:none;cursor:pointer;transition:all 0.3s;'
                  : 'width:16px;height:4px;background:rgba(255,255,255,0.3);border:none;cursor:pointer;transition:all 0.3s;'"
              ></button>
            </div>
          </template>

          <!-- Slides -->
          <template v-slot:content>
            <li v-for="(value, idx) in staticSlides" :key="idx" class="hero-slide-item">
              <nuxt-link to="/categories" style="display:block;height:100%;width:100%;position:relative;text-decoration:none;">
                <!-- Slide image -->
                <img
                  :src="value.image"
                  :alt="value.title"
                  style="width:100%;height:100%;object-fit:cover;display:block;"
                >
                <!-- Premium Overlay -->
                <div class="hero-text-overlay">
                  <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                    <span style="width:30px;height:2px;background:#1D4ED8;"></span>
                    <span style="color:#1D4ED8;font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:0.2em;">Exclusive Deal</span>
                  </div>
                  <h2 style="color:#FFFFFF;font-size:42px;font-weight:900;line-height:1.1;margin:0 0 16px 0;text-transform:uppercase;">
                    {{ value.title }}
                  </h2>
                  <p style="color:rgba(255,255,255,0.7);font-size:15px;margin:0 0 24px 0;max-width:400px;line-height:1.6;">
                    {{ value.subtitle }}
                  </p>
                  <div>
                    <span class="hero-cta-btn">Explore Now →</span>
                  </div>
                </div>
              </nuxt-link>
            </li>
          </template>
        </ImageSlider>
      </div>

      <!-- ── RIGHT: Two static cards (1/3 width) ── -->
      <div style="flex:1;display:flex;flex-direction:column;gap:4px;min-width:0;">
        <!-- Top Card -->
        <nuxt-link to="/categories" class="side-promo-card">
          <img :src="staticSlides[1].image" style="width:100%;height:100%;object-fit:cover;opacity:0.6;">
          <div class="side-card-content">
            <span style="color:#CC0000;font-size:10px;font-weight:900;text-transform:uppercase;margin-bottom:4px;display:block;">Precision Gear</span>
            <h3 style="color:#FFFFFF;font-size:18px;font-weight:900;margin:0;">{{ staticSlides[1].title }}</h3>
          </div>
        </nuxt-link>
        <!-- Bottom Card -->
        <nuxt-link to="/categories" class="side-promo-card">
          <img :src="staticSlides[2].image" style="width:100%;height:100%;object-fit:cover;opacity:0.6;">
          <div class="side-card-content">
            <span style="color:#aaaaff;font-size:10px;font-weight:900;text-transform:uppercase;margin-bottom:4px;display:block;">Custom Builds</span>
            <h3 style="color:#FFFFFF;font-size:18px;font-weight:900;margin:0;">{{ staticSlides[2].title }}</h3>
          </div>
        </nuxt-link>
      </div>

    </div>
  </div>
</template>

<style scoped>
.hero-slide-item {
  flex: 0 0 100%;
  min-width: 0;
  height: 100%;
  position: relative;
}

.hero-text-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to right, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 50%, transparent 100%);
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 0 60px;
}

.hero-cta-btn {
  display: inline-block;
  background: #1D4ED8;
  color: #FFFFFF;
  font-size: 13px;
  font-weight: 900;
  text-transform: uppercase;
  padding: 12px 30px;
  border-radius: 2px;
  transition: all 0.3s;
}

.hero-cta-btn:hover {
  background: #2563EB;
  transform: translateY(-2px);
}

.side-promo-card {
  flex: 1;
  position: relative;
  overflow: hidden;
  background: #111;
  border: 1px solid #333;
  display: block;
}

.side-promo-card img {
  transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.side-promo-card:hover img {
  transform: scale(1.1);
  opacity: 0.8;
}

.side-card-content {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 20px;
  background: linear-gradient(transparent, rgba(0,0,0,0.9));
}

/* Fix for Embla hidden buttons if they conflict */
:deep(.prev-btn), :deep(.next-btn) {
  background: rgba(0,0,0,0.5)!important;
  color: white!important;
  width: 40px!important;
  height: 40px!important;
  border-radius: 50%!important;
}
</style>

<script setup>
  import {useSliderHelper} from "~/composables/useSliderHelper";
  import {useUtils} from "~/composables/useUtils";

  const props = defineProps({
    slider: { type: Object, default: null }
  });

  const {slider} = toRefs(props);
  const activeSlide = ref(0);
  const sliderLoaded = ref(false);
  const homeHeroRef = ref(null);

  const {onChange, onFirstImageLoad} = useSliderHelper(activeSlide, sliderLoaded);

  function selectSliderImage(index) {
    if (homeHeroRef.value) {
      homeHeroRef.value.onThumbClick(index);
    }
  }

  const staticSlides = [
    { image: '/images/hero1.png', title: 'N80 Gaming Case', subtitle: 'Gesplittetes Moduldesign & Panorama View' },
    { image: '/images/hero2.png', title: 'Gaming Mouse', subtitle: 'Wireless High Precision RGB Lighting' },
    { image: '/images/hero3.png', title: 'Mechanical Keyboard', subtitle: 'Premium 60% RGB Mechanical Switches' }
  ];
</script>
