import Alpine from 'alpinejs'

export default function notificationStore() {
    const items = Alpine.reactive([])

    return {
        getItems() {
            return items
        },
        hasUnread() {
            return items.some((item) => item.is_read === false)
        },
        unreadCount() {
            return items.filter((item) => item.is_read === false).length
        },
        add(item) {
            items.push(item)
        },
        remove(index) {
            items.splice(index, 1)
        },
        clear() {
            items.length = 0
        },
    }
}
