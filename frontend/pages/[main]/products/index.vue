<template>
  <listing-layout
    :result-title="slugText"
    :product-params="productParams"
  />
</template>

<script setup>
  import {useCommonStore} from "~/store/common";
  import {useMetaData} from '~/composables/useMetaData'

  definePageMeta({
    middleware: ['common-middleware'],
    layout: 'default',
  });

  const commonStore = useCommonStore();
  const {customScripts} = storeToRefs(commonStore);

  const {pageMeta, slugToText} = useMetaData();
  const {site_setting} = storeToRefs(commonStore);

  const route = useRoute();

  const {t} = useI18n();

  const isDailyDiscover = computed(() => {
    return route?.params?.main === 'daily-discover';
  });

  const slugText = computed(() => {
    return isDailyDiscover.value ? t('discover.dailyDiscover') : slugToText(route?.params?.main);
  });

  useHead(pageMeta({
    ...site_setting.value,
    ...{meta_title: `${slugText.value} | ${site_setting.value?.meta_title}`}
  }));

  const productParams = computed(() => {
    return route.query;
  });

</script>
