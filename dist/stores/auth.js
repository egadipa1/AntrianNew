import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";
export const useAuthStore = defineStore("auth", () => {
    const error = ref(null);
    const user = ref({});
    const isAuthenticated = ref(false);
    function setAuth(authUser, token = "") {
        isAuthenticated.value = true;
        user.value = authUser;
        error.value = null;
        if (token) {
            JwtService.saveToken(token);
        }
    }
    function purgeAuth() {
        isAuthenticated.value = false;
        user.value = {};
        error.value = null;
        JwtService.destroyToken();
    }
    async function login(credentials) {
        return ApiService.post("auth/login", credentials)
            .then(({ data }) => {
            setAuth(data.user, data.token);
        })
            .catch(({ response }) => {
            error.value = response.data.message;
        });
    }
    async function logout() {
        if (JwtService.getToken()) {
            ApiService.setHeader();
            await ApiService.delete("auth/logout");
            purgeAuth();
        }
        else {
            purgeAuth();
        }
    }
    async function register(credentials) {
        return ApiService.post("auth/register", credentials)
            .then(({ data }) => {
            setAuth(data.user, data.token);
        })
            .catch(({ response }) => {
            error.value = response.data.message;
        });
    }
    async function forgotPassword(email) {
        return ApiService.post("auth/forgot_password", email)
            .then(() => {
            error.value = null;
        })
            .catch(({ response }) => {
            error.value = response.data.message;
        });
    }
    async function verifyAuth() {
        if (JwtService.getToken()) {
            ApiService.setHeader();
            await ApiService.get("auth/me")
                .then(({ data }) => {
                setAuth(data.user);
            })
                .catch(({ response }) => {
                error.value = response.data.message;
                purgeAuth();
            });
        }
        else {
            purgeAuth();
        }
    }
    return {
        error,
        user,
        isAuthenticated,
        login,
        logout,
        register,
        forgotPassword,
        verifyAuth,
        setAuth,
        purgeAuth,
    };
});
