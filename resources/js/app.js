import Alpine from 'alpinejs'
import localeSwitcher from './components/locale-switcher'
import lazyImage from './components/lazy-image'
import lib from './components/lib'
import toast from './components/toast'
import notificationStore from './components/notification'

const libSupport = lib()

window.$action = libSupport.action

window.$fetch = libSupport.fetch
Alpine.magic('fetch', () => libSupport.fetch)

window.$validation = libSupport.validation
Alpine.magic('validation', () => libSupport.validation)

Alpine.directive('lazy', lazyImage)

document.addEventListener('click', (e) => {
    const link = e.target.closest('a[href="/user/logout"]')
    if (link) {
        e.preventDefault()
        if (link.classList.contains('pointer-events-none')) return
        link.classList.add('opacity-50', 'pointer-events-none')

        $action.userLogout()
    }
})

window.Alpine = Alpine

Alpine.data('localeSwitcher', localeSwitcher)
Alpine.data('usersMenus', (initialTitle) => ({
    async gotoUserUrl(event) {
        window.location.href = event.currentTarget.dataset.url
    },
}))

Alpine.data('App', () => ({
    init() {
        console.log()
    },
}))

Alpine.store('toast', toast())
Alpine.store('notification', notificationStore())

Alpine.start()

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */
import './echo'
