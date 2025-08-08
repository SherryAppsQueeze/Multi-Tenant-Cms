import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

export default {
    install(app) {
        app.use(Toast, {
            position: 'top-right',
            timeout: 4000,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 0.6,
            showCloseButtonOnHover: false,
            hideProgressBar: false,
            closeButton: 'button',
            icon: true,
            rtl: false,
        })
    }
}
