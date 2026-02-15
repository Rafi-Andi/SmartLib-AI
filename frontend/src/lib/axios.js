import axios from "axios"
import { useKioskStore } from "@/stores/kiosk" 

const api = axios.create({
    baseURL: 'http://127.0.0.1:8000/api'
})

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