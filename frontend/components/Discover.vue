<template>
  <div class="mt-4">
    <div v-if="loading" class="bg-white p-4 border border-gray-200">
      <div class="flex gap-4">
        <tile-shimmer v-for="i in 5" :key="i" class="flex-1" />
      </div>
    </div>
    <div v-else-if="currentItems.length" class="bg-white border border-gray-200">
      <products-slider :collection="{ title: 'Discover Products', products: currentItems }" />
    </div>
  </div>
</template>

<script>
  import util from '~/mixin/util'
  import TileShimmer from "./TileShimmer";
  import ProductTile from "./ProductTile";
  import {useLanguageStore} from "~/store/language";
  import {useHomeStore} from "~/store/home";
  import {storeToRefs} from "pinia";
  import {prepareGetUrl} from "../utils/fetchClient";
  import {useCommonStore} from "../store/common";

  export default {
    setup(){
      const languageStore = useLanguageStore()
      const {langCode} = storeToRefs(languageStore)

      const homeStore = useHomeStore()
      const {products} = storeToRefs(homeStore)
      const {fetchProducts, setHomeProducts} = homeStore

      const commonStore = useCommonStore()
      const {unAuthGet} = commonStore

      return {langCode, products, fetchProducts, unAuthGet, setHomeProducts}
    },
    name: 'Discover',
    data() {
      return {
        currentItems: [],
        loading: true
      }
    },
    watch: {},
    props: {},
    components: {ProductTile, TileShimmer},
    computed: {

    },
    mixins: [util],
    methods: {

    },
    created() {
    },
    async mounted() {
      if(!this.products){
        try {
          this.loading = true

          const response = await this.unAuthGet({
            api: 'products',
            params: `?${prepareGetUrl({is_home_page: true})}`,
            lang: this.langCode})

          const result = response.data.result

          this.setHomeProducts(result)

          this.currentItems = result.data
          this.loading = false

        } catch (e) {
          showError({
            statusCode: 400,
            message: e.message
          })
        }
      } else {

        this.currentItems = this.products?.data
        this.loading = false
      }
    }
  }
</script>
