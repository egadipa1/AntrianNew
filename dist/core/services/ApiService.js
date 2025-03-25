import axios from "axios";
import VueAxios from "vue-axios";
import JwtService from "@/core/services/JwtService";
/**
 * @description service to call HTTP request via Axios
 */
class ApiService {
    /**
     * @description property to share vue instance
     */
    static vueInstance;
    /**
     * @description ialize vue axios
     */
    static init(app) {
        ApiService.vueInstance = app;
        ApiService.vueInstance.use(VueAxios, axios);
        ApiService.vueInstance.axios.defaults.baseURL =
            import.meta.env.VITE_APP_API_URL;
    }
    /**
     * @description set the default HTTP request headers
     */
    static setHeader() {
        ApiService.vueInstance.axios.defaults.headers.common["Authorization"] = `Bearer ${JwtService.getToken()}`;
        ApiService.vueInstance.axios.defaults.headers.common["Accept"] =
            "application/json";
    }
    /**
     * @description send the GET HTTP request
     * @param resource: string
     * @param params: AxiosRequestConfig
     * @returns Promise<AxiosResponse>
     */
    static query(resource, params = {}) {
        this.setHeader();
        return ApiService.vueInstance.axios.get(resource, params);
    }
    /**
     * @description send the GET HTTP request
     * @param resource: string
     * @param slug: string
     * @returns Promise<AxiosResponse>
     */
    static get(resource, slug = "") {
        this.setHeader();
        return ApiService.vueInstance.axios.get(`${resource}/${slug}`);
    }
    /**
     * @description set the POST HTTP request
     * @param resource: string
     * @param params: AxiosRequestConfig
     * @returns Promise<AxiosResponse>
     */
    static post(resource, params) {
        return ApiService.vueInstance.axios.post(`${resource}`, params);
    }
    /**
     * @description send the UPDATE HTTP request
     * @param resource: string
     * @param slug: string
     * @param params: AxiosRequestConfig
     * @returns Promise<AxiosResponse>
     */
    static update(resource, slug, params) {
        return ApiService.vueInstance.axios.put(`${resource}/${slug}`, params);
    }
    /**
     * @description Send the PUT HTTP request
     * @param resource: string
     * @param params: AxiosRequestConfig
     * @returns Promise<AxiosResponse>
     */
    static put(resource, params) {
        return ApiService.vueInstance.axios.put(`${resource}`, params);
    }
    /**
     * @description Send the DELETE HTTP request
     * @param resource: string
     * @returns Promise<AxiosResponse>
     */
    static delete(resource) {
        return ApiService.vueInstance.axios.delete(resource);
    }
}
export default ApiService;
