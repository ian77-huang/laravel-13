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
    const notificationChannel = window.Echo.private(`notifications.${userId}`)

    notificationChannel.listen('.notification.created', (event) => {
        const store = Alpine.store('notification')
        store.unreadCount = event.unread_count
        if (store.loaded && Array.isArray(store.items)) {
            const exists = store.items.some((item) => item.id === event.id)
            if (!exists) {
                store.items.unshift({
                    id: event.id,
                    type: event.type,
                    message: event.message,
                    is_read: false,
                    sender_name: event.sender_name,
                    created_at: event.created_at,
                })
                if (store.items.length > 20) store.items.pop()
            }
        }
        Alpine.store('toast').notify(event.message, 'info')
    })

    notificationChannel.listen('.broadcast.message', (event) => {
        Alpine.store('toast').open(event.title, event.message, event.type)
    })
}
