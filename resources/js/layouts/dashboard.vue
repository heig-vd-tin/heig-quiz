<template>
  <div class="page-container">
    <navbar>
      <template v-slot:title>
        {{ $route.meta.title }}
      </template>
      <span v-for="(route, index) in routes" :key="index">
        <b-nav-item v-if="$route.name != route.name" :to="{ name: route.name }">
          <b-icon v-if="route.icon" :icon="route.icon"></b-icon>
          {{ route.title }}
        </b-nav-item>
      </span>
      <!-- TODO: Add separator if portal is enabled -->
      <portal-target name="navbar"></portal-target>
    </navbar>
    <!-- TODO: Breadcrumb -->
    <!-- <b-breadcrumb :items="breadcrumb"></b-breadcrumb> -->

    <a class="d-none d-lg-block logo-heig-vd" href="https://heig-vd.ch" target="_blank" />

    <div class="mt-2 container content-wrap">
      <router-view></router-view>
    </div>

    <app-footer class="footer"></app-footer>
  </div>
</template>

<script>
import NavBar from './navbar';
import Footer from './footer';

export default {
  components: {
    navbar: NavBar,
    'app-footer': Footer,
  },
  data() {
    return {
      routes: [],
      breadcrumb: [
        {
          text: 'Activités',
          href: '#',
          icon: 'house-fill'
        },
      ]
    };
  },
  methods: {
    traverseRoutes(el) {
      el.forEach(route => {
        if (route.children) this.traverseRoutes(route.children)
        if (route.meta && route.meta.navbar) {
          this.routes.push({
            name: route.name,
            title: route.meta.title,
            icon: route.meta.icon,
          })
        }
      });
    }
  },
  mounted() {
    this.traverseRoutes(this.$router.options.routes);
  }
};
</script>
<style lang="scss" scoped>
$footer-height: 100px;

.logo-heig-vd {
    background: url(../../img/logo-heig-vd.svg) no-repeat;
    background-size: 100%;
    position: absolute;
    width: 40px;
    height: 100px;
    top: 150px;
}

.page-container {
  position: relative;
  min-height: 100vh;
}

.content-wrap {
  padding-bottom: $footer-height;
}

.footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: $footer-height;
}
</style>
