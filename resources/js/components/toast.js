import Alpine from 'alpinejs'

const toast = () => {
    const data = Alpine.reactive({
        items: [],
    })
    const add = (message, type = 'success') => {
        const item = {
            id: Date.now() + Math.random(),
            message,
            type,
        }
        data.items.push(item)
        return item.id
    }
    return {
        get items() {
            return data.items
        },
        open(message, type = 'success') {
            add(message, type)
        },
        close(id) {
            const index = data.items.findIndex((item) => item.id === id)

            if (index !== -1) {
                data.items.splice(index, 1)
            }
        },
        clear() {
            data.items.splice(0)
        },
        errorMsg(message) {
            this.notify(message, 'error')
        },
        notify(message, type = 'success', delay = 5000) {
            const id = add(message, type)

            setTimeout(() => {
                this.close(id)
            }, delay)
        },
    }
}

export default toast
