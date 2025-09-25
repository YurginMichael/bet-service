module.exports = {
  devServer: {
    host: '0.0.0.0',
    port: 8080,
    client: { overlay: false },
    proxy: {
      '^/api': {
        target: 'http://nginx',
        changeOrigin: true,
        secure: false
      }
    }
  }
}
