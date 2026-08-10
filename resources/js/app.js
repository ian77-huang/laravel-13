import Alpine from 'alpinejs'
import localeSwitcher from './components/locale-switcher'

window.Alpine = Alpine

Alpine.data('localeSwitcher', localeSwitcher)

Alpine.data('App', () => ({
    init() {
        console.log()
    },
}))

Alpine.start()
