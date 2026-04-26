<template>
  <div class="bg-white p-8 rounded-3xl border border-slate-100 flex items-center gap-6 hover:shadow-xl hover:border-blue-100 transition-all group">
    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:rotate-6 transition-all duration-500">
      <ImageLazy
        :lazy-src="getImageURL(image)"
        :title="$t('date.fi')"
        :alt="$t('date.fi')"
        class="w-7 h-7 object-contain group-hover:invert group-hover:brightness-0"
      />
    </div>

    <div class="space-y-1 min-w-0">
      <div class="text-sm font-black text-slate-900 uppercase tracking-tight truncate" v-html="detail"></div>
    </div>
  </div>
</template>

<script setup>
  import {useUtils} from "~/composables/useUtils";

  const props = defineProps({
    siteFeature: {
      type: Object,
      default() {
        return null
      }
    }
  });

  const {siteFeature} = toRefs(props)
  const {getImageURL} = useUtils();

  const image = computed(() => {
    return siteFeature.value?.image
  });

  const detail = computed(() => {
    return siteFeature.value?.detail
  });

</script>

