export default (el) => {
    if (!(el instanceof HTMLImageElement)) return

    const observer = new IntersectionObserver((entries, obs) => {
        if (entries[0].intersectionRatio === 0) {
            el.setAttribute('loading', 'lazy')
            obs.unobserve(el)
        }
    })
    observer.observe(el)
}
