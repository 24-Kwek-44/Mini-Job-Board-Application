import { defineStore } from 'pinia'
import axios from 'axios'
import router from '../router'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    error: null
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
    isEmployer: (state) => state.user?.role === 'employer',
  },
  actions: {
    async register(credentials) {
      this.error = null;
      try {
        const response = await axios.post('http://localhost:8000/api/register', credentials);
        this.setToken(response.data.access_token);
        this.user = response.data.user;
        router.push('/');
      } catch (error) {
        this.error = error.response?.data?.message || 'Registration failed';
      }
    },
    async login(credentials) {
      this.error = null;
      try {
        const response = await axios.post('http://localhost:8000/api/login', credentials);
        this.setToken(response.data.access_token);
        this.user = response.data.user;
        router.push('/');
      } catch (error) {
        this.error = error.response?.data?.message || 'Login failed';
      }
    },
    async logout() {
      try {
        await axios.post('http://localhost:8000/api/logout', {}, {
          headers: { Authorization: `Bearer ${this.token}` }
        });
      } catch (e) {
        console.error(e);
      } finally {
        this.setToken(null);
        this.user = null;
        router.push('/login');
      }
    },
    async fetchUser() {
      if (!this.token) return;
      try {
        const response = await axios.get('http://localhost:8000/api/user', {
          headers: { Authorization: `Bearer ${this.token}` }
        });
        this.user = response.data;
      } catch (error) {
        this.setToken(null);
        this.user = null;
      }
    },
    setToken(token) {
      this.token = token;
      if (token) {
        localStorage.setItem('token', token);
      } else {
        localStorage.removeItem('token');
      }
    }
  }
})
