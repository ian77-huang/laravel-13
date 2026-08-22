import Echo from 'laravel-echo'

import Pusher from 'pusher-js'
window.Pusher = Pusher

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
})

const userId = document.querySelector('meta[name="user-id"]')?.content ?? ''

window.Echo.channel('broadcast.all').listen('.broadcast.message', (event) => {
    Alpine.store('toast').open(event.title, event.message, event.type)
})
if (userId !== '') {
    window.Echo.channel('broadcast.user.' + userId).listen('.broadcast.message', (event) => {
        console.log(`broadcast.all`, event)
        Alpine.store('toast').open(event.message, event.type)
    })
}
