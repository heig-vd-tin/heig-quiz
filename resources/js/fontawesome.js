import { library } from '@fortawesome/fontawesome-svg-core'
import { faUserSecret, faEye, faEyeSlash, faTrashAlt, faPoll, faStop } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faUserSecret)
library.add(faEye)
library.add(faEyeSlash)
library.add(faTrashAlt)
library.add(faPoll)
library.add(faStop)


Vue.component('font-awesome-icon', FontAwesomeIcon)
