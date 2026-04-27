<template>
    <div style="background:#F5F5F5;min-height:100vh;padding-top:0;">
        <div class="max-w-[980px] mx-auto px-0">

            <!-- Trust Bar -->
            <static-section v-if="hasSiteFeatures" :site-features="siteFeatures" />

            <!-- Hero Slider -->
            <home-hero :slider="slider" />

            <!-- Static Categories Icon Bar + Brands -->
            <StaticCategoriesBrands />

            <div v-if="exclusiveBanners && exclusiveBanners.length" class="mt-4">
              <div class="flex items-center justify-between px-4 py-2 bg-white" style="border:1px solid #DDDDDD;border-bottom:2px solid #154992;">
                <h2 class="font-bold uppercase" style="font-size:14px;color:#154992;">Exclusive</h2>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div v-for="(banner, i) in exclusiveBanners" :key="i" style="background:#FFFFFF;border:1px solid #DDDDDD;padding:32px;" class="flex items-center justify-between group">
                  <div style="flex:1;">
                    <h2 class="font-black leading-tight mb-2 uppercase" style="font-size:28px;color:#154992;">{{ banner.title }}</h2>
                    <p class="mb-4 pr-4" style="font-size:14px;color:#666666;">{{ banner.tags }}</p>
                    <nuxt-link :to="banner.url" class="font-bold uppercase inline-block border-b-2 border-transparent hover:border-[#154992] transition-all" style="font-size:13px;color:#154992;">FIND OUT MORE →</nuxt-link>
                  </div>
                  <div style="width:160px;height:160px;flex-shrink:0;">
                    <img :src="banner.image.startsWith('/categories') ? banner.image : getImageURL(banner.image)" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500" :alt="banner.title">
                  </div>
                </div>
              </div>
            </div>

            <div v-if="flashSales && flashSales.length" class="mt-4 mb-4" style="background:#FFFFFF;border:1px solid #DDDDDD;">
              <flashSale :flash-sales="flashSales" />
            </div>

            <!-- Product Collections -->
            <div class="space-y-4 mt-4">
              <div v-for="(value, index) in productCollection" :key="index" style="background:#FFFFFF;border:1px solid #DDDDDD;">
                <products-slider :collection="value" />
              </div>
            </div>

            <!-- Product Grid -->
            <div v-if="productGrid" class="mt-4" style="background:#FFFFFF;border:1px solid #DDDDDD;">
              <div class="flex items-center justify-between px-4 py-2" style="border-bottom:2px solid #154992;">
                <h2 class="font-bold uppercase" style="font-size:14px;color:#154992;">{{ productGrid.title }}</h2>
                <nuxt-link class="font-bold uppercase ml-4" style="font-size:12px;color:#000000;" :to="collectionLink(productGrid)"><span class="underline">View All</span></nuxt-link>
              </div>
              <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5" style="border-top:1px solid #DDDDDD;border-left:1px solid #DDDDDD;">
                <ProductTile
                  v-for="(value, index) in productGrid.products"
                  :key="`prod-${index}`"
                  :product="value"
                  style="border-right:1px solid #DDDDDD;border-bottom:1px solid #DDDDDD;"
                />
              </div>
            </div>






        </div>
    </div>
</template>



<script setup>

    import {useHomeStore} from "~/store/home";
    import {useCommonStore} from "~/store/common";
    import {storeToRefs} from "pinia";
    import {useLanguageStore} from "~/store/language";
    import {useAsyncData} from "nuxt/app";
    import {useUtils} from "~/composables/useUtils";
    import {useMetaData} from "~/composables/useMetaData";
    import {useConstants} from "~/composables/useConstants";

    definePageMeta({
        middleware: ['common-middleware'],
        layout: 'default',
    });

    const languageStore = useLanguageStore();
    const {langCode} = storeToRefs(languageStore);


    const commonStore = useCommonStore();
    const {unAuthGet} = commonStore;
    const {topBanner} = storeToRefs(commonStore);

    const homeStore = useHomeStore();
    const {hasHomeData} = storeToRefs(homeStore);
    const {setHomeData} = homeStore;


    const {data, pending, error} = await useAsyncData('home', async () => {
        if (hasHomeData.value) {
            return null;
        }
        const response = await unAuthGet({api: 'home', params: '', lang: langCode.value});
        setHomeData(response);
        return response.data;
    });

    const {customScripts, site_setting} = storeToRefs(commonStore);
    const {pageMeta, preloadScript} = useMetaData();
    const {getImageURL, collectionLink} = useUtils();

    const {
        featuredCategories, flashSales, collections,
        featuredBrands, slider, banners, siteFeatures
    } = storeToRefs(homeStore);

    const heroMain = computed(() => {
        return slider.value?.main[0];
    });

    const heroRightTop = computed(() => {
        return slider.value?.right_top;
    });

    const heroRightBottom = computed(() => {
        return slider.value?.right_bottom;
    });

    const getPreloadImages = computed(() => {
        return {
            link: [
                preloadScript(getImageURL(heroMain.value?.image), 'image'),
                preloadScript(getImageURL(heroRightTop.value?.image), 'image'),
                preloadScript(getImageURL(heroRightBottom.value?.image), 'image'),
                preloadScript(getImageURL(topBanner.value?.image), 'image'),
            ]
        }
    });

    useHead({
        ...pageMeta({
            ...site_setting.value,
            ...{image_url: getImageURL(site_setting.value.header_logo)}
        }),
        ...getPreloadImages.value
    });

    const hasSiteFeatures = computed(() => {
        return siteFeatures.value?.length
    });

    const banner5 = computed(() => {
        return bannerData.value?.banner5;
    });

    const banner6 = computed(() => {
        return bannerData.value?.banner6;
    });

    const featuredBanner = computed(() => {
        return bannerData.value?.banner1;
    });

    const productCollection = computed(() => {
        if (collections.value) {
            // Filter out 'Exclusive' collection from sliders
            const col = collections.value.filter(c => c.slug !== 'exclusive');
            col?.pop();
            return col;
        }
        return [];
    });

    const productGrid = computed(() => {
        const filtered = collections.value?.filter(c => c.slug !== 'exclusive') || [];
        return filtered.slice(-1)?.pop();
    });

    const {bannerType} = useConstants();

    const exclusiveBanners = computed(() => {
        return banners.value?.filter(i => i.type === 10) || [];
    });

    const bannerData = computed(() => {
        let banner = {
            banner1: null,
            banner2: null,
            banner3: null,
            banner4: null,
            banner5: null,
            banner6: null
        };

        banners.value?.forEach(i => {
            banner['banner' + bannerType['BANNER_' + i.type]] = i;
        });
        return banner;
    });

</script>

