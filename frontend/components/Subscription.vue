<template>
  <section class="bg-gray-50 py-16 px-4">
    <div class="max-w-7xl mx-auto bg-white rounded-[3rem] p-12 lg:p-20 shadow-xl shadow-blue-900/5 flex flex-col lg:flex-row items-center justify-between gap-12 border border-primary-50">
      
      <!-- Text Content -->
      <div class="text-center lg:text-left space-y-4 max-w-xl">
        <h3 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight">
          {{ $t('home.subscribeNewsletter') }}
        </h3>
        <p class="text-slate-500 font-medium text-lg">
          {{ $t('home.getLatestEmail') }}
        </p>
      </div>

      <!-- Subscription Form -->
      <form @submit.prevent="formSubmit" class="w-full lg:w-auto flex flex-col gap-4">
        <div v-if="!messageSent" class="flex flex-col sm:flex-row gap-4">
          <div class="relative group flex-grow">
            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-600 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <input
              type="text"
              v-model="email"
              :placeholder="$t('contact.your', { type: $t('contact.email') })"
              class="w-full sm:w-[320px] pl-14 pr-8 py-5 rounded-full border-2 border-slate-100 focus:outline-none focus:border-primary-600 focus:ring-4 focus:ring-primary-100 transition-all font-medium shadow-sm text-sm"
            >
          </div>

          <ajax-button
            class="bg-primary-600 text-white px-12 py-5 rounded-full font-black uppercase tracking-widest hover:bg-primary-700 shadow-lg shadow-primary-200 transition-all active:scale-95 disabled:opacity-50 disabled:pointer-events-none"
            :fetching-data="formSubmitting"
            :text="$t('home.subscribe')"
          />
        </div>

        <!-- Success Message -->
        <div v-else class="flex items-center gap-4 bg-green-50 text-green-700 px-8 py-5 rounded-full border border-green-100 animate-bounce">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <h4 class="font-bold uppercase tracking-widest text-sm">
            {{ $t('home.subscribeSuccessMsg') }}
          </h4>
        </div>

        <!-- Error Messages -->
        <div v-if="hasFormError" class="px-6 space-y-1">
          <span v-for="(value, index) in errors" :key="index" class="text-xs text-red-500 font-bold block italic">
            * {{ value }}
          </span>
          <span v-if="!email && hasFormError" class="text-xs text-red-500 font-bold block italic">
            * {{ $t('addressPopup.isRequired', {type: $t('addressPopup.email') }) }}
          </span>
          <span v-else-if="invalidEmail && hasFormError" class="text-xs text-red-500 font-bold block italic">
            * {{ $t('contact.invalidEmail') }}
          </span>
        </div>
      </form>
    </div>
  </section>
</template>

<script>
  import validation from '~/mixin/validation'
  import {useCommonStore} from "~/store/common";

  export default {
    setup(){
      const commonStore = useCommonStore()
      const {unAuthPost} = commonStore

      return {unAuthPost}
    },

    name: 'Subscription',
    data() {
      return {
        errors: [],
        formSubmitting: false,
        email: '',
        hasFormError: false,
        messageSent: false
      }
    },
    components: {
    },
    props: {
    },
    mixins: [validation],
    computed: {
      invalidEmail() {
        return !this.isValidEmail(this.email)
      },
    },
    methods:{
      async formSubmit() {
        if (this.email && !this.invalidEmail) {
          this.formSubmitting = true

            const data = await this.unAuthPost({
              params: {email: this.email},
              api: 'emailSubscription'
            })

            if (data?.status === 200) {
              this.messageSent = true
              this.hasFormError = false
            } else {
              this.hasFormError = true
              this.errors = data?.data?.form
            }

          this.formSubmitting = false

        } else {
          this.hasFormError = true
        }
      },
    },
  }
</script>

