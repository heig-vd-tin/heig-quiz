<template>
  <div>
    <navbar>
        <b-nav-item href="/quiz/activities">Activités</b-nav-item>
        <b-nav-item href="/quiz/sandbox">Bac à sable</b-nav-item>
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
};
</script>
