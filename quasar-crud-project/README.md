# Quasar App (quasar-crud-project)
## cors nedir ?
CORS (Cross-Origin Resource Sharing), bir web sayfasının kendi domaini dışındaki bir kaynağa (API, resim, CSS dosyası vb.) erişim yapmasına izin vermek için kullanılan bir güvenlik mekanizmasıdır. Tarayıcılar, bir kaynağa yapılan isteğin güvenli olup olmadığını kontrol etmek için CORS politikalarını uygularlar. CORS hatası genellikle, sunucunun gelen isteklere izin vermediğinde ortaya çıkar.
## OPTIONS nedir ?
OPTIONS yöntemi, bir HTTP isteği türüdür ve istemci (örneğin bir tarayıcı), bir sunucunun belirli kaynaklar üzerinde hangi HTTP yöntemlerini desteklediğini öğrenmek için kullanır. Ayrıca, tarayıcıların CORS (Cross-Origin Resource Sharing) politikalarını uygulamak için preflight istekleri yapmak amacıyla da kullanılır. Preflight istekleri, özellikle güvenlik nedeniyle, istemcinin belirli eylemleri gerçekleştirmeden önce izinleri kontrol etmek için gönderilir.


A Quasar Project

## Install the dependencies
```bash
yarn
# or
npm install
```

### Start the app in development mode (hot-code reloading, error reporting, etc.)
```bash
quasar dev
```


### Lint the files
```bash
yarn lint
# or
npm run lint
```


### Format the files
```bash
yarn format
# or
npm run format
```



### Build the app for production
```bash
quasar build
```

### Customize the configuration
See [Configuring quasar.config.js](https://v2.quasar.dev/quasar-cli-vite/quasar-config-js).
