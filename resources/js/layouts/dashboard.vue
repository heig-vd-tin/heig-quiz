<template>
  <div class="page-container">
    <navbar>
      <template v-slot:title>
        {{ $route.meta.title }}
      </template>
      
      
      <span v-for="(route, index) in routesMono" :key="index">
        <b-nav-item :to="{ name: route.name }">
          <b-icon v-if="route.icon" :icon="route.icon"></b-icon>
          {{ route.title }}
        </b-nav-item>
      </span>
      
      <span v-if="$store.state.user.role == 'teacher'" v-for="(routes, index) in routesComp">
        <b-nav-item-dropdown id="my-nav-dropdown" :text="nameList[index]">
          <span v-for="(route, index) in routes" :key="index">
            <b-dropdown-item :to="{ name: route.name }">
              <b-icon v-if="route.icon" :icon="route.icon"></b-icon>
              {{ route.title }}
            </b-dropdown-item>
          </span>
        </b-nav-item-dropdown>
      </span>
     
      <!-- TODO: Add separator if portal is enabled -->
      <portal-target name="navbar"></portal-target>
    </navbar>
    <!-- TODO: Breadcrumb -->
    <!-- <b-breadcrumb :items="breadcrumb"></b-breadcrumb> -->

    <div class="mt-2 container content-wrap">
      <router-view></router-view>
    </div>

    <app-footer class="footer"></app-footer>
  </div>
</template>

<script>
import NavBar from './navbar';
import Footer from './footer';
import { tsThisType } from '@babel/types';

export default {
  components: {
    navbar: NavBar,
    'app-footer': Footer,
  },
  data() {
    return {
      routes: [],
      routesComp: [[],[],[]],
      routesMono: [],
      nameList: ["Quizz", "Question", "Roster"],
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
    routesOrganisation() {

      this.routesComp[0].push(this.routes[1]);
      this.routesComp[0].push(this.routes[5]);
      this.routesComp[0].push(this.routes[6]);

      this.routesComp[1].push(this.routes[3]);
      this.routesComp[1].push(this.routes[4]);

      this.routesComp[2].push(this.routes[7]);
      this.routesComp[2].push(this.routes[8]);

      this.routesMono.push(this.routes[0]);
    },

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
    this.routesOrganisation();
  }
};
</script>
<style lang="scss" scoped>
$footer-height: 100px;

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
