export default () => ({
    async change(event) {
        const res = await $fetch.post('/api/lang', { locale: event.target.value })
        if (res.ok) {
            window.location.reload()
        }
    },
})
