export default function notificationStore() {
    return {
        items: [],
        unreadCount: 0,
        loaded: false,
        loading: false,

        async fetch() {
            if (this.loading) return

            this.loading = true
            try {
                const res = await $fetch.get('/api/user/notifications')
                if (!res.ok) throw new Error(`HTTP ${res.status}`)
                const data = await res.json()
                this.items = data.items
                this.unreadCount = data.unread_count
                this.loaded = true
            } catch (err) {
                console.error(err)
            } finally {
                this.loading = false
            }
        },

        async markRead(item) {
            if (item.is_read) return

            item.is_read = true
            this.unreadCount = Math.max(0, this.unreadCount - 1)

            try {
                await $fetch.post(`/api/user/notifications/${item.id}/read`)
            } catch (err) {
                console.error(err)
            }
        },

        async markAllRead() {
            this.items.forEach((item) => {
                item.is_read = true
            })
            this.unreadCount = 0

            try {
                await $fetch.post('/api/user/notifications/read-all')
            } catch (err) {
                console.error(err)
            }
        },

        formatTime(iso) {
            if (!iso) return ''

            return new Date(iso).toLocaleString(undefined, {
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            })
        },
    }
}
