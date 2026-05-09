import axios from "axios"
import { useKioskStore } from "@/stores/kiosk" 

const baseURL = import.meta.env.VITE_API_URL_BACKEND;
const api = axios.create({
    baseURL: baseURL.endsWith('/') ? baseURL : baseURL + '/'
})

api.interceptors.request.use(config => {
    if (config.url && config.url.startsWith('/')) {
        config.url = config.url.substring(1);
    }
    return config;
});

api.interceptors.request.use((config) => {
    const kioskStore = useKioskStore()
    
    const tokenStorage = localStorage.getItem('token')
    
    const userToken = kioskStore.userToken

    if (userToken) {
        config.headers.Authorization = `Bearer ${userToken}`
    } else if (tokenStorage) {
        config.headers.Authorization = `Bearer ${tokenStorage}`
    }

    return config;
}, (error) => {
    return Promise.reject(error)
})

export default api