<template>
  <div>

    <!-- ══════════════════════════════════════════
         QUICK CATEGORY ICON BAR (CityCenter style)
    ══════════════════════════════════════════ -->
    <div style="background:#FFFFFF;border-top:1px solid #EEEEEE;border-bottom:1px solid #EEEEEE;">
      <div class="max-w-[980px] mx-auto">
        <div style="display:flex;overflow-x:auto;padding:12px 0;" class="no-scrollbar">
          <nuxt-link
            v-for="cat in staticCategories"
            :key="cat.slug"
            :to="`/categories/${cat.slug}`"
            style="display:flex;flex-direction:column;align-items:center;min-width:90px;padding:8px 12px;text-decoration:none;flex-shrink:0;"
            class="category-icon-item"
          >
            <div style="width:64px;height:64px;border-radius:8px;overflow:hidden;border:1px solid #EEEEEE;background:#FAFAFA;display:flex;align-items:center;justify-content:center;margin-bottom:6px;">
              <img :src="cat.image" :alt="cat.name" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <span style="font-size:11px;color:#333333;text-align:center;line-height:1.3;font-weight:600;white-space:nowrap;">{{ cat.name }}</span>
          </nuxt-link>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════
         BRANDS BAR
    ══════════════════════════════════════════ -->
    <div style="background:#FFFFFF;border-bottom:1px solid #EEEEEE;margin-top:4px;">
      <div class="max-w-[980px] mx-auto px-4 py-4">
        <div style="display:flex;align-items:center;margin-bottom:12px;">
          <div style="background:#1D4ED8;padding:6px 14px;flex-shrink:0;">
            <span style="color:#FFFFFF;font-size:13px;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;">Our Brands</span>
          </div>
          <div style="height:2px;flex-grow:1;background:#EEEEEE;margin-left:12px;"></div>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
          <nuxt-link
            v-for="brand in staticBrands"
            :key="brand.slug"
            :to="`/brands/${brand.slug}`"
            style="display:flex;align-items:center;justify-content:center;width:100px;height:52px;border:1px solid #DDDDDD;background:#FFFFFF;padding:6px;text-decoration:none;transition:border-color 0.2s;"
            class="brand-logo-item"
          >
            <img :src="brand.image" :alt="brand.name" style="max-width:100%;max-height:100%;object-fit:contain;">
          </nuxt-link>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
// Static categories with generated images
const staticCategories = [
  { name: 'Computer Systems', slug: 'computer-systems', image: '/categories/cat_computer_systems_1777204862889.png' },
  { name: 'Components',       slug: 'components',       image: '/categories/cat_components_1777204878218.png' },
  { name: 'Security Solutions', slug: 'security-solutions', image: '/categories/cat_security_1777204895204.png' },
  { name: 'Point of Sale',    slug: 'point-of-sale',    image: '/categories/cat_pos_1777204907523.png' },
  { name: 'Gaming',           slug: 'gaming',           image: '/categories/cat_gaming_1777204920446.png' },
  { name: 'Connectors & Converters', slug: 'connectors', image: '/categories/cat_connectors_1777204934440.png' },
  { name: 'Camera & Recorder', slug: 'camera-recorder', image: '/categories/cat_camera_1777204947794.png' },
  { name: 'Network',          slug: 'network',          image: '/categories/cat_network_1777204962214.png' },
  { name: 'Audio',            slug: 'audio',            image: '/categories/cat_audio_1777204976296.png' },
  { name: 'Accessories',      slug: 'accessories',      image: '/categories/cat_accessories_1777204992716.png' },
];

// Static brands — using text logos since image quota is limited
// Images will use generated ones where available, otherwise elegant text fallback
const staticBrands = [
  { name: 'HIKVISION',    slug: 'hikvision',   image: '/brands/brand_hikvision_black.png' },
  { name: 'GAMEMAX',      slug: 'gamemax',     image: '/brands/brand_gamemax_black.png' },
  { name: 'AULA',         slug: 'aula',        image: null },
  { name: 'DRAGON WAR',   slug: 'dragon-war',  image: null },
  { name: 'DATALOGIC',    slug: 'datalogic',   image: null },
  { name: 'EZVIZ',        slug: 'ezviz',       image: null },
  { name: 'GLINK',        slug: 'glink',       image: null },
  { name: 'HIKSEMI',      slug: 'hiksemi',     image: null },
  { name: 'JEQANG',       slug: 'jeqang',      image: null },
  { name: 'MARVO',        slug: 'marvo',       image: null },
  { name: 'TP-LINK',      slug: 'tp-link',     image: '/brands/brand_tplink_black.png' },
  { name: 'TRANSCEND',    slug: 'transcend',   image: null },
  { name: 'UNIQ',         slug: 'uniq',        image: null },
  { name: 'XTRIKE ME',    slug: 'xtrike-me',   image: null },
  { name: 'YORO',         slug: 'yoro',        image: null },
  { name: 'DAHUA',        slug: 'dahua',       image: null },
].map(b => ({
  ...b,
  // For brands without images, generate a styled fallback using a text-as-image service
  image: b.image ?? `https://placehold.co/100x52/1a1a1a/ffffff?text=${encodeURIComponent(b.name)}&font=montserrat`
}));
</script>

<style scoped>
.category-icon-item:hover span {
  color: #1D4ED8;
}
.category-icon-item:hover div {
  border-color: #1D4ED8;
}
.brand-logo-item:hover {
  border-color: #1D4ED8!important;
}
</style>
