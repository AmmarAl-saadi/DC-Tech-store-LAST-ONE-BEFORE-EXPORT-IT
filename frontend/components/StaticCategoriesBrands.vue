<template>
  <div>

    <!-- ══════════════════════════════════════════
         QUICK CATEGORY ICON BAR (CityCenter style)
    ══════════════════════════════════════════ -->
    <div style="background:#FFFFFF;border-top:1px solid #EEEEEE;border-bottom:1px solid #EEEEEE;">
      <div class="max-w-[980px] mx-auto px-4 py-4">
        <div style="display:flex;align-items:center;margin-bottom:12px;">
          <div style="background:#154992;padding:6px 14px;flex-shrink:0;">
            <span style="color:#FFFFFF;font-size:13px;font-weight:400;text-transform:uppercase;letter-spacing:0.1em;">Categories</span>
          </div>
          <div style="height:2px;flex-grow:1;background:#EEEEEE;margin-left:12px;"></div>
        </div>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
          <nuxt-link
            v-for="cat in staticCategories"
            :key="cat.slug"
            :to="`/categories/${cat.slug}`"
            style="display:flex;flex-direction:column;align-items:center;padding:8px 4px;text-decoration:none;border:1px solid #EEEEEE;background:#FAFAFA;transition:all 0.2s;"
            class="category-icon-item hover:shadow-md rounded-lg"
          >
            <div style="width:64px;height:64px;overflow:hidden;display:flex;align-items:center;justify-content:center;margin-bottom:0px;">
              <img :src="cat.image" :alt="cat.name" style="width:100%;height:100%;object-fit:contain;transform:scale(1.35);">
            </div>
            <span style="font-size:12px;color:#333333;text-align:center;line-height:1.2;font-weight:700;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ cat.name }}</span>
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
          <div style="background:#154992;padding:6px 14px;flex-shrink:0;">
            <span style="color:#FFFFFF;font-size:13px;font-weight:400;text-transform:uppercase;letter-spacing:0.1em;">Our Brands</span>
          </div>
          <div style="height:2px;flex-grow:1;background:#EEEEEE;margin-left:12px;"></div>
        </div>
        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
          <nuxt-link
            v-for="brand in staticBrands"
            :key="brand.slug"
            :to="`/brands/${brand.slug}`"
            style="display:flex;align-items:center;justify-content:center;height:60px;border:1px solid #EEEEEE;background:#FFFFFF;padding:0;text-decoration:none;transition:all 0.2s;overflow:hidden;"
            class="brand-logo-item hover:shadow-sm"
          >
            <img :src="brand.image" :alt="brand.name" style="width:100%;height:100%;object-fit:contain;transform:scale(1.4);">
          </nuxt-link>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
// Static categories with premium generated images (White Background, Professional Design)
const staticCategories = [
  { name: 'Computer Systems', slug: 'computer-systems', image: '/categories/cat_computer_systems.png' },
  { name: 'Components',       slug: 'components',       image: '/categories/cat_components.png' },
  { name: 'Security Solutions', slug: 'security-solutions', image: '/categories/cat_security.png' },
  { name: 'Point of Sale',    slug: 'point-of-sale',    image: '/categories/cat_pos.png' },
  { name: 'Gaming',           slug: 'gaming',           image: '/categories/cat_gaming.png' },
  { name: 'Connectors & Converters', slug: 'connectors-and-convertors', image: '/categories/cat_connectors.png' },
  { name: 'Camera & Recorder', slug: 'camera-and-recorder', image: '/categories/cat_camera.png' },
  { name: 'Network',          slug: 'network',          image: '/categories/cat_network.png' },
  { name: 'Audio',            slug: 'audio',            image: '/categories/cat_audio.png' },
  { name: 'Accessories',      slug: 'accessories',      image: '/categories/cat_accessories.png' },
  { name: 'Printers & Supplies', slug: 'printers--supplies', image: '/categories/cat_printers_supplies.png' },
  { name: 'Cables',           slug: 'cables',           image: '/categories/cat_cables.png' },
];

// Static brands using original logos from the uploads folder
const staticBrands = [
  { name: 'HIKVISION',    slug: 'hikvision',   image: '/uploads/brand-1776677217-2.png' },
  { name: 'GAMEMAX',      slug: 'gamemax',     image: '/uploads/brand-1776677854-7.png' },
  { name: 'AULA',         slug: 'aula',        image: '/uploads/brand-1776856858-9.png' },
  { name: 'DRAGON WAR',   slug: 'dragon-war',  image: '/uploads/brand-1776856909-7.png' },
  { name: 'DATALOGIC',    slug: 'datalogic',   image: '/uploads/brand-1776856976-5.png' },
  { name: 'EZVIZ',        slug: 'ezviz',       image: '/uploads/brand-1776857055-3.png' },
  { name: 'GLINK',        slug: 'glink',       image: '/uploads/brand-1776857144-7.png' },
  { name: 'HIKSEMI',      slug: 'hiksemi',     image: '/uploads/brand-1776857176-8.png' },
  { name: 'JEQANG',       slug: 'jeqang',      image: '/uploads/brand-1776857213-5.png' },
  { name: 'MARVO',        slug: 'marvo',       image: '/uploads/brand-1776857238-5.png' },
  { name: 'TP-LINK',      slug: 'tp-link',     image: '/uploads/brand-1776857270-7.png' },
  { name: 'TRANSCEND',    slug: 'transcend',   image: '/uploads/brand-1776857303-1.png' },
  { name: 'UNIQ',         slug: 'uniq',        image: '/uploads/brand-1776857360-6.png' },
  { name: 'XTRIKE ME',    slug: 'xtrike-me',   image: '/uploads/brand-1776857403-8.png' },
  { name: 'YORO',         slug: 'yoro',        image: '/uploads/brand-1776857464-9.png' },
  { name: 'DAHUA',        slug: 'dahua',       image: '/uploads/brand-1776857528-9.png' },
];
</script>

<style scoped>
.category-icon-item:hover span {
  color: #154992;
}
.category-icon-item:hover div {
  border-color: #154992;
}
.brand-logo-item:hover {
  border-color: #154992!important;
}
</style>
