<template>
  <ajax-button
    :class="{'following': isFollowing}"
    type="button"
    :color="color"
    loading-text=" "
    :fetching-data="ajaxing"
    :text="followingText"
    @clicked="followStore"
  />
</template>

<script setup>
  import {useCommonStore} from "~/store/common";
  import {useAuthStore} from "~/store/auth";
  import {useUserStore} from "~/store/user";
  import {useLanguageStore} from "~/store/language";

  const props = defineProps({
    color: {
      type: String,
      default: '',
    },
    storeId: {
      required: true
    }
  });

  const emit = defineEmits(['change-following']);

  const {color, storeId} = toRefs(props);

  const commonStore = useCommonStore()
  const {postRequest, getRequest} = commonStore

  const authStore = useAuthStore();
  const {authenticated} = storeToRefs(authStore);

  const {t} = useI18n();

  const userStore = useUserStore();
  const {profile} = storeToRefs(userStore);
  const {setProfile} = userStore;

  const isFollowing = computed(() => {
    const index = profile.value?.following?.findIndex(i => {
      return i?.toString() === storeId.value?.toString()
    });
    return index > -1;
  });

  const followingText = computed(() => {
    return isFollowing.value ? t('store.following') : t('store.follow');
  });

  const ajaxing = ref(false);

  const languageStore = useLanguageStore();
  const {langCode} = storeToRefs(languageStore);

  const route = useRoute();

  const followStore = async () => {
    if (!authenticated.value) {

      const redirectionUrl = route?.fullPath;
      if(redirectionUrl){
        localStorage?.setItem('redirection_url', redirectionUrl);
      }


      return navigateTo('/login');
    }

    ajaxing.value = true
    const data = await postRequest({
      params: {
        store_id: storeId.value
      },
      api: 'followStore'
    });

    ajaxing.value = false;

    if (data.status === 200) {

      if (data?.data) {
        profile.value.following.push(storeId.value)
      } else {
        profile.value.following = profile.value.following.filter(id => id?.toString() !== storeId.value?.toString())
      }

      const res = await getRequest({
        params: '',
        lang: langCode.value,
        api: 'profile'
      });
      if (res?.status === 200) {
        setProfile(data.data);
      }
    }

  }

</script>
