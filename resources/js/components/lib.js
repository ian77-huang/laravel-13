const request = async (input, method = 'GET', body = null, headers = null) => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? ''

    const init = {
        method,
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            ...(headers && typeof headers === 'object' ? headers : {}),
            'X-CSRF-TOKEN': csrfToken,
        },
    }
    if (body instanceof FormData) {
        delete init.headers['Content-Type']
        init.body = body
    } else if (body !== null && body !== undefined) {
        init.body = typeof body === 'object' ? JSON.stringify(body) : body
    }

    return fetch(input, init)
}
const lib = () => ({
    fetch: {
        async get(input, headers = null) {
            return request(input, 'GET', null, headers)
        },

        async post(input, body = null, headers = null) {
            return request(input, 'POST', body, headers)
        },

        async put(input, body = null, headers = null) {
            return request(input, 'PUT', body, headers)
        },

        async patch(input, body = null, headers = null) {
            return request(input, 'PATCH', body, headers)
        },

        async delete(input, body = null, headers = null) {
            return request(input, 'DELETE', body, headers)
        },

        async head(input, headers = null) {
            return request(input, 'HEAD', null, headers)
        },

        async options(input, headers = null) {
            return request(input, 'OPTIONS', null, headers)
        },
    },
    validation: {
        isEmail(str) {
            const input = document.createElement('input')
            input.type = 'email'
            input.value = str
            return input.checkValidity()
        },
    },
})
export default lib
