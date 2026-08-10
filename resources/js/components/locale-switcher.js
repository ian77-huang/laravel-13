export default () => ({
    async change(event) {
        const res = await fetch('/api/lang', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            },
            body: JSON.stringify({ locale: event.target.value }),
        })

        if (res.ok) {
            window.location.reload()
        }
    },
})
