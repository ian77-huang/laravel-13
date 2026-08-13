import Alpine from 'alpinejs'
import localeSwitcher from './components/locale-switcher'
import lazyImage from './components/lazy-image'
import lib from './components/lib'

const libSupport = lib()

const userLogout = () => {
    $fetch.post('/user/logout', {}).finally(() => {
        window.location.href = '/user/login'
    })
}

document.addEventListener('click', (e) => {
    const link = e.target.closest('a[href="/user/logout"]')
    if (link) {
        e.preventDefault()
        if (link.classList.contains('pointer-events-none')) return
        link.classList.add('opacity-50', 'pointer-events-none')

        userLogout()
    }
})

window.Alpine = Alpine

Alpine.data('localeSwitcher', localeSwitcher)
Alpine.data('usersMenus', (initialTitle) => ({
    async gotoUserUrl(event) {
        window.location.href = event.currentTarget.dataset.url
    },
}))

window.$userLogout = userLogout

window.$fetch = libSupport.fetch
Alpine.magic('fetch', () => libSupport.fetch)

window.$validation = libSupport.validation
Alpine.magic('validation', () => libSupport.validation)

Alpine.directive('lazy', lazyImage)

Alpine.data('App', () => ({
    init() {
        console.log()
    },
}))

Alpine.start()
