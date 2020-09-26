<template>
  <div>
    <navbar>
    </navbar>
    <a class="d-none d-lg-block logo-heig-vd" href="https://heig-vd.ch" target="_blank"/>
    <div class="mt-2 container">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import NavBar from './navbar'

export default {
  components: {
    navbar: NavBar
  },
  data() {
    return {
      user: {},
      items: [
        {
          text: "Activités",
          href: "/quiz/activities",
        },
        {
          text: "Sandbox",
          href: "#",
        },
      ],
    };
  },
  mounted() {
    this.user = Vue.prototype.$user;
  },
};
</script>
