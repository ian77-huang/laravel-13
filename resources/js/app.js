import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.data('App', () => ({
    init() {
        console.log()
    },
    async LangHandler(event) {
        const res = await fetch('/api/lang', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                locale: event.target.value,
            }),
        })
        window.location.reload()
    },
}))

Alpine.start()
