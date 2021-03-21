import axios from 'axios'

window.axios = axios

axios.interceptors.request.use(request => {

    if (auth.check()) {
        const token = auth.token
        request.headers.common['Authorization'] = `Bearer ${token}`
    }

    return request
})

axios.interceptors.response.use(response => response, error => {
    const { status } = error.response

    if (status >= 500) {
    }

    return Promise.reject(error)
})

