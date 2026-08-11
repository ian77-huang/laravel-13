import Alpine from 'alpinejs'
import localeSwitcher from './components/locale-switcher'
import lazyImage from './components/lazy-image'

window.Alpine = Alpine

Alpine.data('localeSwitcher', localeSwitcher)
Alpine.directive('lazy', lazyImage)

Alpine.data('App', () => ({
    init() {
        console.log()
    },
}))

Alpine.start()
