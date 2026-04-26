<template>
  <div v-if="store" class="store-info">
    <div class="btn-wrap">
      <div class="w-100">
        <p class="store-name">{{ storeName }}</p>
        <h6 class="store-date">{{$t('store.memberSince')}} <b class="block">{{storeDate}}</b></h6>
      </div>

      <div class="action-btn">
        <slot
          name="followBtn"
        />

        <nuxt-link
          class="visit-btn ajax-btn"
          :to="`/shop/${store.slug}`"
        >
          {{$t('store.visitStore')}}
        </nuxt-link>
      </div>
    </div>

  </div>
</template>

<script setup>
  import moment from "moment";

  const props = defineProps({
    store: {
      type: Object,
      default() {
        return null
      },
    },
  });

  const {store} = toRefs(props);

  const {t} = useI18n();

  const storeName = computed(() => {
    return store.value?.name
  });

  const storeDate = computed(() => {
    const newDate = moment(store.value?.created_at);

    const mon = newDate.format('MMM').toLowerCase()
    const date = newDate.format('DD')
    const year = newDate.format('YYYY')

    return t('date.MMMDDYYYY', {mon: t(`date.${mon}`), date: date, year: year})
  });




</script>


