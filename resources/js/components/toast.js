import Alpine from 'alpinejs'

const toast = () => {
    const data = Alpine.reactive({
        items: [],
    })
    const add = (title, message, type = 'success') => {
        const item = {
            id: Date.now() + Math.random(),
            title,
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
        open(title, message, type = 'success') {
            add(title, message, type)
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
        /**
         * @param {string} message 內容
         * @param {string} type success|error|info|warning
         * @param {number} delay 自動關閉毫秒；0 表示不自動關
         * @param {string} [title=''] 選填標題
         */
        notify(message, type = 'success', delay = 5000, title = '') {
            const id = add(title, message, type)

            setTimeout(() => {
                this.close(id)
            }, delay)
        },
    }
}

export default toast
