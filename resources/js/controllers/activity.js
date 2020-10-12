import Vue from 'vue';

export default class Activity {
  constructor(app) {
    this.app = app;
    Vue.prototype.$activity = this;
  }

  start(activity_id) {
    axios.post(`/api/activities/${activity_id}/start`).catch((err) => {
      this.app.error('Impossible de démarrer cette activité !');
    });
  }

  open(activity_id) {
    axios.post(`/api/activities/${activity_id}/open`).catch((err) => {
      this.app.error('Impossible d\'ouvrir cette activité !')
    });
  }

  close(activity_id) {
    axios.post(`/api/activities/${activity_id}/close`).catch((err) => {
      this.app.error('Impossible de fermer cette activité !')
    });
  }

  show(activity_id) {
    axios.post(`/api/activities/${activity_id}/show`).catch((err) => {
      this.app.error('Impossible de montrer cette activité !');
    });
  }

  hide(activity_id) {
    axios.post(`/api/activities/${activity_id}/hide`).catch((err) => {
      this.app.error('Impossible de cacher cette activité !');
    });
  }

  delete(activity_id) {
    this.app.$bvModal.msgBoxConfirm('Voulez-vous vraiment supprimer cette activité ?', {
      title: 'Supprimer ?',
      okTitle: 'Supprimer',
      cancelTitle: 'Annuler',
      okVariant: 'danger'
    })
      .then(value => {
        if (value)
          axios.post(`/api/activities/${activity_id}/delete`).then((rep) => {

          });
      })
  }
}
