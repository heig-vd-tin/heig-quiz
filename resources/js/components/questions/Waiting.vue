<!-- Waiting Room -->
<template>
  <b-jumbotron header="Salle d'attente">
    <template v-slot:lead>
      <b-spinner label="Spinning"></b-spinner>
      Attente de {{activity.joined}} / {{activity.roster.students}} étudiants...
    </template>
  </b-jumbotron>
</template>

<script>
export default {
  props: ['activity']
}
</script>
