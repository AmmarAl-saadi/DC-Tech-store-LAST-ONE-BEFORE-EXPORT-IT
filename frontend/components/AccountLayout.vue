<template>
    <div class="container-fluid mtb-20 mtb-sm-15">
      <div class="order-wrapper">
        <ul class="left-sidebar">
          <li
            :class="{active: activeRoute === 'profile'}"
          >
            <nuxt-link
              to="/profile/view"
              @click.native="goingNext('/profile/view')"
            >
              {{ $t('accountLayout.myProfile') }}
            </nuxt-link>
          </li>
          <li
            :class="{active: activeRoute === 'addresses'}"
          >
            <nuxt-link
              to="/profile/addresses"
              @click.native="goingNext('/profile/addresses')"
            >
              {{ $t('accountLayout.myAddressBook') }}
            </nuxt-link>
          </li>
          <li
            :class="{active: activeRoute === 'vouchers'}"
          >
            <nuxt-link
              to="/profile/vouchers"
              @click.native="goingNext('/profile/vouchers')"
            >
              {{ $t('accountLayout.vouchers') }}

            </nuxt-link>
          </li>
          <li
            :class="{active: activeRoute === 'orders'}"
          >
            <nuxt-link
              to="/profile/orders"
              @click.native="goingNext('/profile/orders')"
            >
              {{ $t('accountLayout.myOrders') }}
            </nuxt-link>
          </li>

          <li
            :class="{active: activeRoute === 'following'}"
          >
            <nuxt-link
              to="/profile/following"
              @click.native="goingNext('/profile/following')"
            >
              {{ $t('store.followingStores') }}
            </nuxt-link>
          </li>

          <li
            :class="{active: activeRoute === 'wishlists'}"
          >
            <nuxt-link
              to="/profile/wishlists"
              @click.native="goingNext('/profile/wishlists')"
            >
              {{ $t('accountLayout.myWishlist') }}
            </nuxt-link>
          </li>
          <li
            :class="{active: activeRoute === 'compared'}"
          >
            <nuxt-link
              to="/profile/compared"
              @click.native="goingNext('/profile/compared')"
            >
              {{ $t('accountLayout.comparedList') }}
            </nuxt-link>
          </li>
        </ul>
        <div class="right-area grow pos-rel">
          <slot
            name="rightArea"
          />
        </div>
      </div>
    </div>
</template>

<script>
  export default {
    name: 'AccountLayout',
    data() {
      return {
      }
    },
    mixins: [],
    watch: {},
    props: {
      activeRoute:{
        type: String
      }
    },
    computed: {
    },
    mounted() {
    },
    methods: {
      goingNext(url){
        const clicked = url.split('/')
        this.$emit(`clicked-${clicked[clicked.length-1]}`)
      }
    },
  }
</script>
