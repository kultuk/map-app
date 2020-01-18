import userLogin from '../components/userLogin.vue'
import locations from '../components/locations.vue'
import notFound404 from '../components/404.vue'

export default [
    {path: '/'         , component: userLogin},
    {path: '/404'         , component: notFound404},
    {path: '/app'      , component: locations}
]