module.exports = {
  devServer: {
    host: '0.0.0.0',
    port: 8080,
    client: { overlay: false },
    proxy: {
      '^/api': {
        target: process.env.VUE_APP_API_BASE || 'http://nginx',
        changeOrigin: true
      }
    }
  }
}
