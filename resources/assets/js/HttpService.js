const axios = require('axios');

const axiosInstance = axios.create({
  baseURL: '/lyra-api',
});

module.exports = axiosInstance;