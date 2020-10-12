import Vue from 'vue'
import TimeAgo from 'javascript-time-ago';
import fr from 'javascript-time-ago/locale/fr';
TimeAgo.addLocale(fr);
const timeAgo = new TimeAgo('fr-CH');

Vue.mixin({
  methods: {
    error: function (msg) {
      this.$bvModal.msgBoxOk(msg, {
        centered: true,
        title: 'Oops !',
        okVariant: 'danger',
        noCloseOnEsc: true,
        noCloseOnBackdrop: true,
        okTitle: 'Fermer'
      })
      .then(value => {
        this.boxOne = value
      })
    },
    /**
     * Show a date in a form of "2 minutes ago"
     */
    timeAgo(date) {
      return timeAgo.format(Date.parse(date));
    },
    /**
     * Convert a duration in seconds into a human value
     */
    humanDuration(duration) {
      let minutes = parseInt(duration / 60, 10);
      let seconds = parseInt(duration % 60, 10);

      let text = '';
      if (minutes > 0) text += minutes + ' minute';
      if (minutes > 1) text += 's';
      if (seconds > 0) text += ' ' + seconds + ' seconde';
      if (seconds > 1) text += 's';

      return text;
    },
  },
})
